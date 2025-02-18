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

namespace Splash\Connectors\ShippingBo\Models\Api\OrderItem;

use ArrayObject;
use JMS\Serializer\Annotation as JMS;
use Splash\Connectors\ShippingBo\DataTransformer\PriceTransformer as PT;
use Splash\OpenApi\Validator as SPL;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Order Item Price Parsing
 */
trait PriceTrait
{
    /**
     * Order Line Discount Percent.
     *
     * @var null|float
     *
     * @Assert\Type("float")
     *
     * @JMS\SerializedName("discount")
     *
     * @JMS\Groups ({"Write"})
     *
     * @JMS\Type("float")
     *
     * @SPL\Prefer ("export")
     *
     * @SPL\Microdata({"http://schema.org/Order", "discount"})
     *
     * @SPL\Type("double")
     */
    protected ?float $discount = 0.0;

    //====================================================================//
    // SBO PRICE PROPERTIES
    //====================================================================//

    /**
     * Order Line Items Price Tax Included.
     *
     * @var null|int
     *
     * @Assert\Type("integer")
     *
     * @JMS\SerializedName("price_tax_included_cents")
     *
     * @JMS\Groups ({"Read", "Write"})
     *
     * @JMS\Type("integer")
     */
    protected ?int $price_tax_included_cents = 0;

    /**
     * Order Line Items Price Currency.
     *
     * @var string
     *
     * @Assert\Type("string")
     *
     * @JMS\SerializedName("price_tax_included_currency")
     *
     * @JMS\Groups ({"Read", "Write"})
     *
     * @JMS\Type("string")
     */
    protected ?string $price_tax_included_currency = "EUR";

    /**
     * Order Line Items Price Tax Excluded.
     *
     * @var null|int
     *
     * @Assert\Type("integer")
     *
     * @JMS\SerializedName("price_cents")
     *
     * @JMS\Groups ({"Read", "Write"})
     *
     * @JMS\Type("integer")
     */
    protected ?int $price_cents = null;

    /**
     * Order Line Items Price Tax Amount.
     *
     * @var null|int
     *
     * @Assert\Type("integer")
     *
     * @JMS\SerializedName("tax_cents")
     *
     * @JMS\Groups ({"Read", "Write"})
     *
     * @JMS\Type("integer")
     */
    protected ?int $tax_cents = null;

    //====================================================================//
    // SPLASH PRICE FIELDS
    //====================================================================//

    /**
     * Order Item Unit Price.
     *
     * @var null|array
     *
     * @Assert\Type("array")
     *
     * @JMS\SerializedName("price")
     *
     * @JMS\Groups ({"Read", "Write", "Required"})
     *
     * @JMS\Type("array")
     *
     * @SPL\Microdata({"http://schema.org/PriceSpecification", "price"})
     *
     * @SPL\Type("price")
     *
     * @SPL\Prefer ("import")
     */
    private ?array $price = null;

    //====================================================================//
    // PRICE TRANSFORMERS
    //====================================================================//

    /**
     * Get Order Item Unit Price
     *
     * @return null|array
     */
    public function getPrice(): ?array
    {
        //====================================================================//
        // Detect Line Unit Price
        $price = self::prices()->encode(
            $this->getUnitPriceWithoutTax(),
            $this->getTaxPercent(),
            null,
            $this->price_tax_included_currency ?? "EUR",
        );

        return is_array($price) ? $price : null;
    }

    /**
     * Set Order Item Unit Price
     *
     * @param null|array|ArrayObject $unitPrice
     *
     * @return void
     */
    public function setPrice($unitPrice): void
    {
        //====================================================================//
        // Store Unit Price
        $this->price = $unitPrice ? (array) $unitPrice : null;
        //====================================================================//
        // Update Order Item Prices
        $this->updateItemPrices();
    }

    /**
     * Set Order Item Price Discount
     *
     * @param null|float $discount
     *
     * @return void
     */
    public function setDiscount(?float $discount): void
    {
        //====================================================================//
        // Store Price Discount
        $this->discount = $discount;
        //====================================================================//
        // Update Order Item Prices
        $this->updateItemPrices();
    }

    /**
     * Update Order Item Prices Properties
     *
     * @return void
     */
    private function updateItemPrices(): void
    {
        if (!is_array($this->price)) {
            return;
        }

        $this->price_tax_included_cents = $this->toPricesInCents((float) self::prices()->taxIncluded($this->price));
        $this->price_cents = $this->toPricesInCents((float) self::prices()->taxExcluded($this->price));
        $this->tax_cents = $this->toPricesInCents((float) self::prices()->taxAmount($this->price));
        $this->price_tax_included_currency = $this->price['code'] ?? "EUR";
    }

    /**
     * Convert Float Prices to Cents with Discount
     *
     * @param float $amount
     *
     * @return int
     */
    private function toPricesInCents(float $amount): int
    {
        //====================================================================//
        // Convert Unit to Total Price
        $price = ($this->quantity ?? 1) * $amount;
        //====================================================================//
        // Apply Discount
        if ($this->discount > 0) {
            $price -= $this->discount * $price / 100;
        }

        return PT::toCents($price);
    }

    /**
     * Get Order Item Unit Price without Tax
     *
     * @return float
     */
    private function getUnitPriceWithoutTax(): float
    {
        //====================================================================//
        // Detect Line Total Price without Tax
        $total = $this->price_cents ?? $this->price_tax_included_cents ?? 0;
        //====================================================================//
        // Convert to Unit Price
        if (($this->quantity ?? 0) > 0) {
            return round((float) ($total / $this->quantity) / 100, 3);
        }

        return 0.0;
    }

    /**
     * Get Order Item Price Tax Percentile
     *
     * @return float
     */
    private function getTaxPercent(): float
    {
        //====================================================================//
        // Both Price without Tax & Tax are defined
        if (($this->price_cents > 0) && ($this->tax_cents > 0)) {
            return round((float) ($this->tax_cents / $this->price_cents) * 100, 3);
        }

        return 0.0;
    }
}
