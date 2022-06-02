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

namespace Splash\Connectors\ShippingBo\Models\Api\Order;

use JMS\Serializer\Annotation as JMS;
use Splash\OpenApi\Validator as SPL;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * ShippingBo Order Totals
 */
trait TotalsTrait
{
    //====================================================================//
    // GRAND TOTALS
    //====================================================================//

    /**
     * @var null|int
     *
     * @Assert\Type("int")
     *
     * @JMS\SerializedName("total_price_cents")
     * @JMS\Groups ({"Read", "Write"})
     * @JMS\Type("integer")
     */
    public ?int $total_price_cents = 0;

    /**
     * @var null|int
     *
     * @Assert\Type("int")
     *
     * @JMS\SerializedName("total_without_tax_cents")
     * @JMS\Groups ({"Read", "Write"})
     * @JMS\Type("integer")
     */
    public ?int $total_without_tax_cents = 0;

    /**
     * @var null|int
     *
     * @Assert\Type("int")
     *
     * @JMS\SerializedName("total_tax_cents")
     * @JMS\Groups ({"Read", "Write"})
     * @JMS\Type("integer")
     */
    public ?int $total_tax_cents = 0;

    /**
     * @var null|string
     *
     * @Assert\Type("string")
     *
     * @JMS\SerializedName("total_price_currency")
     * @JMS\Groups ({"Read", "Write"})
     * @JMS\Type("string")
     */
    public ?string $total_price_currency = "EUR";

    //====================================================================//
    // SHIPPING TOTALS
    //====================================================================//

    /**
     * @var null|int
     *
     * @Assert\Type("int")
     *
     * @JMS\SerializedName("total_shipping_cents")
     * @JMS\Groups ({"Read", "Write"})
     * @JMS\Type("integer")
     */
    public ?int $total_shipping_cents = 0;

    /**
     * @var null|int
     *
     * @Assert\Type("int")
     *
     * @JMS\SerializedName("total_shipping_tax_included_cents")
     * @JMS\Groups ({"Read", "Write"})
     * @JMS\Type("integer")
     */
    public ?int $total_shipping_tax_included_cents = 0;

    /**
     * @var null|int
     *
     * @Assert\Type("int")
     *
     * @JMS\SerializedName("total_shipping_tax_cents")
     * @JMS\Groups ({"Read", "Write"})
     * @JMS\Type("integer")
     */
    public ?int $total_shipping_tax_cents = 0;

    /**
     * @var null|string
     *
     * @Assert\Type("string")
     *
     * @JMS\SerializedName("total_shipping_tax_included_currency")
     * @JMS\Groups ({"Read", "Write"})
     * @JMS\Type("string")
     */
    public ?string $total_shipping_tax_included_currency = "EUR";

    //====================================================================//
    // DISCOUNT TOTALS
    //====================================================================//

    /**
     * @var null|int
     *
     * @Assert\Type("int")
     *
     * @JMS\SerializedName("total_discount_tax_included_cents")
     * @JMS\Groups ({"Read", "Write"})
     * @JMS\Type("integer")
     */
    public ?int $total_discount_tax_included_cents = 0;

    /**
     * @var null|int
     *
     * @Assert\Type("int")
     *
     * @JMS\SerializedName("total_discount_cents")
     * @JMS\Groups ({"Read", "Write"})
     * @JMS\Type("integer")
     */
    public ?int $total_discount_cents = 0;

    /**
     * @var null|string
     *
     * @Assert\Type("string")
     *
     * @JMS\SerializedName("total_discount_tax_included_currency")
     * @JMS\Groups ({"Read", "Write"})
     * @JMS\Type("string")
     */
    public ?string $total_discount_tax_included_currency = "EUR";

    //====================================================================//
    // WEIGHT TOTALS
    //====================================================================//

    /**
     * @var null|int
     *
     * @Assert\Type("int")
     *
     * @JMS\SerializedName("total_weight")
     * @JMS\Groups ({"Read"})
     * @JMS\Type("integer")
     *
     * @SPL\Type("double")
     */
    public ?int $totalWeight = 0;

    /**
     * Order Total Price.
     *
     * @var null|array
     *
     * @Assert\Type("array")
     *
     * @JMS\SerializedName("total_price")
     * @JMS\Groups ({"Read", "Write"})
     * @JMS\Type("array")
     *
     * @SPL\Type("price")
     * @SPL\Microdata({"http://schema.org/Invoice", "total"})
     */
    private ?array $totalPrice = null;

    /**
     * Order Total Shipping Price.
     *
     * @var null|array
     *
     * @Assert\Type("array")
     *
     * @JMS\SerializedName("total_shipping_price")
     * @JMS\Groups ({"Read", "Write"})
     * @JMS\Type("array")
     *
     * @SPL\Type("price")
     * @SPL\Microdata({"http://schema.org/Invoice", "totalShipping"})
     */
    private ?array $totalShippingPrice = null;

    /**
     * Order Total Shipping Price.
     *
     * @var null|array
     *
     * @Assert\Type("array")
     *
     * @JMS\SerializedName("total_dicount_price")
     * @JMS\Groups ({"Read", "Write"})
     * @JMS\Type("array")
     *
     * @SPL\Type("price")
     * @SPL\Microdata({"http://schema.org/Invoice", "totalDiscount"})
     */
    private ?array $totalDiscountPrice = null;

    //====================================================================//
    // PRICE TRANSFORMERS
    //====================================================================//

    /**
     * Get Order Grand Total Price
     *
     * @return null|array
     */
    public function getTotalPrice(): ?array
    {
        $vatRate = (($this->total_without_tax_cents ?? 0) > 0)
            ? 100 * ($this->total_tax_cents / $this->total_without_tax_cents)
            : 0
        ;
        $price = self::prices()->encode(
            (float) ($this->total_without_tax_cents ?? 0) / 100,
            (float) $vatRate,
            null,
            $this->total_price_currency ?? "EUR",
        );

        return is_array($price) ? $price : null;
    }

    /**
     * Set Order Grand Total Price
     *
     * @param null|array $totalPrice
     *
     * @return void
     */
    public function setTotalPrice($totalPrice): void
    {
        if (!is_iterable($totalPrice)) {
            return;
        }

        $this->total_price_cents = (int) (100 * self::prices()->taxIncluded($totalPrice));
        $this->total_without_tax_cents = (int) (100 * self::prices()->taxExcluded($totalPrice));
        $this->total_tax_cents = (int) (100 * self::prices()->taxAmount($totalPrice));
        $this->total_price_currency = $totalPrice['code'] ?? "EUR";
    }

    /**
     * Get Order Shipping Total Price
     *
     * @return null|array
     */
    public function getTotalShippingPrice(): ?array
    {
        $vatRate = (($this->total_shipping_cents ?? 0) > 0)
            ? 100 * ($this->total_shipping_tax_cents / $this->total_shipping_cents)
            : 0
        ;
        $price = self::prices()->encode(
            (float) ($this->total_shipping_cents ?? 0) / 100,
            (float) $vatRate,
            null,
            $this->total_shipping_tax_included_currency ?? "EUR",
        );

        return is_array($price) ? $price : null;
    }

    /**
     * Set Order Shipping Total Price
     *
     * @param null|array $shippingPrice
     *
     * @return void
     */
    public function setTotalShippingPrice($shippingPrice): void
    {
        if (!is_iterable($shippingPrice)) {
            return;
        }

        $this->total_shipping_tax_included_cents = (int) (100 * self::prices()->taxIncluded($shippingPrice));
        $this->total_shipping_cents = (int) (100 * self::prices()->taxExcluded($shippingPrice));
        $this->total_shipping_tax_cents = (int) (100 * self::prices()->taxAmount($shippingPrice));
        $this->total_shipping_tax_included_currency = $shippingPrice['code'] ?? "EUR";
    }

    /**
     * Get Order Discount Total Price
     *
     * @return null|array
     */
    public function getTotalDiscountPrice(): ?array
    {
        $vatRate = (($this->total_discount_cents ?? 0) > 0)
            ? 100 * (($this->total_discount_tax_included_cents - $this->total_discount_cents)
                / $this->total_discount_cents)
            : 0.0
        ;

        $price = self::prices()->encode(
            null,
            (float) $vatRate,
            (float) ($this->total_discount_tax_included_cents ?? 0) / 100,
            $this->total_discount_tax_included_currency ?? "EUR",
        );

        return is_array($price) ? $price : null;
    }

    /**
     * Set Order Discount Total Price
     *
     * @param null|array $discountPrice
     *
     * @return void
     */
    public function setTotalDiscountPrice($discountPrice): void
    {
        if (!is_iterable($discountPrice)) {
            return;
        }

        $this->total_discount_tax_included_cents = (int) (100 * self::prices()->taxIncluded($discountPrice));
        $this->total_discount_cents = (int) (100 * self::prices()->taxExcluded($discountPrice));
        $this->total_discount_tax_included_currency = $discountPrice['code'] ?? "EUR";
    }

    //====================================================================//
    // WEIGHT TRANSFORMERS
    //====================================================================//

    /**
     * Get Order Grand Total Price
     *
     * @return float
     */
    public function getTotalWeight(): float
    {
        return ($this->totalWeight ?? 0) / 100;
    }
}
