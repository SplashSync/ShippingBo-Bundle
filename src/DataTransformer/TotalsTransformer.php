<?php

/*
 *  This file is part of SplashSync Project.
 *
 *  Copyright (C) Splash Sync  <www.splashsync.com>
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace Splash\Connectors\ShippingBo\DataTransformer;

use ArrayObject;
use Splash\Models\Objects\PricesTrait;

/**
 * Build Initial Order Totals Array
 */
class TotalsTransformer
{
    use PricesTrait;

    /**
     * @param array|ArrayObject $inputs
     *
     * @return array
     */
    public static function getInitialValues($inputs): array
    {
        //====================================================================//
        // Ensure Default Source
        $inputs['source'] = $inputs['source'] ?? "Splashsync";

        return array_merge(
            self::getItemsAttributes($inputs),
            self::getShippingPrices($inputs['totalShippingPrice'] ?? null),
            self::getDiscountPrices($inputs['totalDiscountPrice'] ?? null),
            self::getTotalPrices($inputs['totalPrice'] ?? null),
        );
    }

    /**
     * Get Order Totals Values
     *
     * @param null|array|ArrayObject $price
     *
     * @return array
     */
    private static function getTotalPrices($price): array
    {
        $price = ($price instanceof ArrayObject) ? $price->getArrayCopy() : $price;
        if (empty($price)) {
            return array();
        }

        return array(
            "total_price_cents" => (int) (100 * self::prices()->taxIncluded($price)),
            "total_without_tax_cents" => (int) (100 * self::prices()->taxExcluded($price)),
            "total_tax_cents" => (int) (100 * self::prices()->taxAmount($price)),
            "total_price_currency" => $price['code'] ?? "EUR",
        );
    }

    /**
     * Get Order Shipping Values
     *
     * @param null|array|ArrayObject $price
     *
     * @return array
     */
    private static function getShippingPrices($price): array
    {
        $price = ($price instanceof ArrayObject) ? $price->getArrayCopy() : $price;
        if (empty($price)) {
            return array();
        }

        return array(
            "total_shipping_tax_included_cents" => (int) (100 * self::prices()->taxIncluded($price)),
            "total_shipping_cents" => (int) (100 * self::prices()->taxExcluded($price)),
            "total_shipping_tax_cents" => (int) (100 * self::prices()->taxAmount($price)),
            "total_shipping_tax_included_currency" => $price['code'] ?? "EUR",
        );
    }

    /**
     * Get Order Shipping Values
     *
     * @param null|array|ArrayObject $price
     *
     * @return array
     */
    private static function getDiscountPrices($price): array
    {
        $price = ($price instanceof ArrayObject) ? $price->getArrayCopy() : $price;
        if (empty($price)) {
            return array();
        }

        return array(
            "total_discount_tax_included_cents" => (int) (100 * self::prices()->taxIncluded($price)),
            "total_discount_cents" => (int) (100 * self::prices()->taxExcluded($price)),
            "total_discount_tax_included_currency" => $price['code'] ?? "EUR",
        );
    }

    /**
     * @param array|ArrayObject $inputs
     *
     * @return array[]
     */
    private static function getItemsAttributes($inputs): array
    {
        //====================================================================//
        // Complete Received Items
        $items = array();
        /** @var array|ArrayObject $item */
        foreach ($inputs['items'] ?? array() as $index => $item) {
            //====================================================================//
            // Safety Check => Only Products Items
            if (empty($item["product_ref"])) {
                continue;
            }
            //====================================================================//
            // Detect ArrayObject
            $item = ($item instanceof ArrayObject) ? $item->getArrayCopy() : $item;
            //====================================================================//
            // Set Item Source
            $item["source"] = $inputs['source'];
            $item["source_ref"] = sprintf("%s-%s", ($inputs['source_ref'] ?? ""), ((int) $index + 1));
            //====================================================================//
            // Set Item Price in Cent

            if (is_iterable($item["price"] ?? null)) {
                $itemPrice = self::toItemPrice(
                    ($item["price"] instanceof ArrayObject) ? $item["price"]->getArrayCopy() : $item["price"],
                    $item['quantity'] ?? 1
                );
            } else {
                /** @var array $nullPrice */
                $nullPrice = self::prices()->encode(0.0, 0.0, null, "EUR");
                $itemPrice = self::toItemPrice($nullPrice, $item['quantity'] ?? 1);
            }
            $items[$index] = array_merge($item, $itemPrice);
        }

        return array(
            "order_items_attributes" => $items
        );
    }

    /**
     * Build Order Item Price Fields
     *
     * @param array $price
     * @param int   $quantity
     *
     * @return array
     */
    private static function toItemPrice(array $price, int $quantity): array
    {
        return array(
            "price_tax_included_cents" => (int) (100 * $quantity * self::prices()->taxIncluded($price)),
            "price_cents" => (int) (100 * $quantity * self::prices()->taxExcluded($price)),
            "tax_cents" => (int) (100 * $quantity * self::prices()->taxAmount($price)),
            "price_tax_included_currency" => $price['code'] ?? "EUR",
            "tax_currency" => $price['code'] ?? "EUR",
        );
    }
}
