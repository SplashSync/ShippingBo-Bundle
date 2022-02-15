<?php

/*
 *  This file is part of SplashSync Project.
 *
 *  Copyright (C) 2015-2021 Splash Sync  <www.splashsync.com>
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
     * @JMS\Groups ({"Read"})
     * @JMS\Type("integer")
     */
    public ?int $total_price_cents = 0;

    /**
     * @var null|int
     *
     * @Assert\Type("int")
     *
     * @JMS\SerializedName("total_without_tax_cents")
     * @JMS\Groups ({"Read"})
     * @JMS\Type("integer")
     */
    public ?int $total_without_tax_cents = 0;

    /**
     * @var null|int
     *
     * @Assert\Type("int")
     *
     * @JMS\SerializedName("total_tax_cents")
     * @JMS\Groups ({"Read"})
     * @JMS\Type("integer")
     */
    public ?int $total_tax_cents = 0;

    /**
     * @var null|string
     *
     * @Assert\Type("string")
     *
     * @JMS\SerializedName("total_price_currency")
     * @JMS\Groups ({"Read"})
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
     * @JMS\Groups ({"Read"})
     * @JMS\Type("integer")
     */
    public ?int $total_shipping_cents = 0;

    /**
     * @var null|int
     *
     * @Assert\Type("int")
     *
     * @JMS\SerializedName("total_shipping_tax_included_cents")
     * @JMS\Groups ({"Read"})
     * @JMS\Type("integer")
     */
    public ?int $total_shipping_tax_included_cents = 0;

    /**
     * @var null|int
     *
     * @Assert\Type("int")
     *
     * @JMS\SerializedName("total_shipping_tax_cents")
     * @JMS\Groups ({"Read"})
     * @JMS\Type("integer")
     */
    public ?int $total_shipping_tax_cents = 0;

    /**
     * @var null|string
     *
     * @Assert\Type("string")
     *
     * @JMS\SerializedName("total_shipping_tax_included_currency")
     * @JMS\Groups ({"Read"})
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
     * @JMS\Groups ({"Read"})
     * @JMS\Type("integer")
     */
    public ?int $total_discount_tax_included_cents = 0;

    /**
     * @var null|string
     *
     * @Assert\Type("string")
     *
     * @JMS\SerializedName("total_discount_tax_included_currency")
     * @JMS\Groups ({"Read"})
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
     * @JMS\Groups ({"Read"})
     * @JMS\Type("array")
     *
     * @SPL\Type("price")
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
     * @JMS\Groups ({"Read"})
     * @JMS\Type("array")
     *
     * @SPL\Type("price")
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
     * @JMS\Groups ({"Read"})
     * @JMS\Type("array")
     *
     * @SPL\Type("price")
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
     * Get Order Discount Total Price
     *
     * @return null|array
     */
    public function getTotalDiscountPrice(): ?array
    {
        $price = self::prices()->encode(
            (float) ($this->total_discount_tax_included_cents ?? 0) / 100,
            0.0,
            null,
            $this->total_discount_tax_included_currency ?? "EUR",
        );

        return is_array($price) ? $price : null;
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
