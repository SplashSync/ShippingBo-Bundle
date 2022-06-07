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

namespace Splash\Connectors\ShippingBo\Models\Api;

use JMS\Serializer\Annotation as JMS;
use Splash\Models\Helpers\PricesHelper;
use Splash\Models\Objects\PricesTrait;
use Splash\OpenApi\Validator as SPL;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class representing the Order Item model.
 *
 * @SuppressWarnings(PHPMD.CamelCasePropertyName)
 */
class OrderItem
{
    //====================================================================//
    // Splash Core Traits
    use PricesTrait;

    //====================================================================//
    // SBO CORE DATA
    use Core\SboCoreTrait;
    use Core\SboSourceTrait;

    //====================================================================//
    // SBO ORDER ITEM DATA
    use OrderItem\PriceTrait;
    //====================================================================//
    // JSON PREFIXES
    const COLLECTION_PROP = "order_items";
    const ITEMS_PROP = "order_item";

    //====================================================================//
    // SPLASH EXCLUDED PROPS
    const EXCLUDED = array(
        "id",
        "price_cents",
        "tax_cents",
        "price_tax_included_cents",
        "price_tax_included_currency"
    );

    /**
     * Unique identifier.
     *
     * @var null|string
     * @Assert\NotNull()
     * @Assert\Type("string")
     *
     * @JMS\SerializedName("id")
     * @JMS\Type("string")
     * @JMS\Groups ({"Read", "Write", "List"})
     */
    public ?string $id = null;

    /**
     * @var null|PricesHelper
     *
     * @JMS\Exclude
     */
    private static ?PricesHelper $pricesHelper = null;

    //====================================================================//
    // ORDER PRODUCT INFO
    //====================================================================//

    /**
     * Product Name.
     *
     * @var string
     *
     * @Assert\NotNull()
     * @Assert\Type("string")
     *
     * @JMS\SerializedName("title")
     * @JMS\Groups ({"Read", "Write", "Required"})
     * @JMS\Type("string")
     *
     * @SPL\Microdata({"http://schema.org/partOfInvoice", "description"})
     */
    public string $title = "";

    /**
     * Product SKU.
     *
     * @var string
     *
     * @Assert\NotNull()
     * @Assert\Type("string")
     *
     * @JMS\SerializedName("product_ref")
     * @JMS\Groups ({"Read", "Write", "Required"})
     * @JMS\Type("string")
     *
     * @SPL\Microdata({"http://schema.org/Product", "sku"})
     */
    public string $product_ref;

    /**
     * Product EAN 13.
     *
     * @var null|string
     *
     * @Assert\NotNull()
     * @Assert\Type("string")
     *
     * @JMS\SerializedName("product_ean")
     * @JMS\Groups ({"Read", "Write"})
     * @JMS\Type("string")
     *
     * @SPL\Microdata({"http://schema.org/Product", "gtin13"})
     */
    public ?string $product_ean = null;

    //====================================================================//
    // ORDER ITEM QTY
    //====================================================================//

    /**
     * Ordered Quantities.
     *
     * @var int
     *
     * @Assert\NotNull()
     * @Assert\Type("integer")
     *
     * @JMS\SerializedName("quantity")
     * @JMS\Groups ({"Read", "Write", "Required"})
     * @JMS\Type("integer")
     *
     * @SPL\Microdata({"http://schema.org/QuantitativeValue", "value"})
     */
    public int $quantity;

    //====================================================================//
    // ORDER ITEM MAIN METHODS
    //====================================================================//

    /**
     * Convert to String
     *
     * @return string
     */
    public function __toString(): string
    {
        return 'Order Item '.($this->id ?? "NEW");
    }

    /**
     * Get Order Item ID
     *
     * @return null|string
     */
    public function getId(): ?string
    {
        return $this->id ?? null;
    }

    /**
     * Check if Order Item is to Create= on API
     *
     * @return bool
     */
    public function isNew(): bool
    {
        return empty($this->id ?? null);
    }

    /**
     * Check if Order Item is Valid for Create/Update
     *
     * @return bool
     */
    public function isValid(): bool
    {
        return !empty($this->title ?? null)
            && (!empty($this->product_ref ?? null) || !empty($this->product_ean ?? null))
            && (($this->quantity ?? 0) >= 0)
        ;
    }

    /**
     * Check if Order Item is Considered as Deleted
     *
     * @return bool
     */
    public function isDeleted(): bool
    {
        return empty($this->quantity ?? 0);
    }

    /**
     * Mark this Item as Deleted
     *
     * @return $this
     */
    public function setDeleted(): self
    {
        $this->title = "DELETED";
        $this->quantity = 0;

        return $this;
    }

    /**
     * Compute Order Item Md5
     *
     * @return string
     */
    public function getMd5(): string
    {
        return md5(serialize(array(
            $this->title ?? null,
            $this->product_ref ?? null,
            $this->product_ean ?? null,
            $this->quantity ?? null,
            $this->price_tax_included_cents ?? null,
            $this->price_tax_included_currency ?? null,
            $this->price_cents ?? null,
            $this->tax_cents ?? null,
        )));
    }

    //====================================================================//
    // FAKE ORDER ITEM GENERATOR
    //====================================================================//

    /**
     * @return OrderItem
     */
    public static function fake(): OrderItem
    {
        $orderItem = new self();
        $orderItem->id = (string) rand(10, 1000);
        $orderItem->title = "Fake me up";
        $orderItem->product_ref = "FakeSKU".rand(1000, 9999);
        $orderItem->quantity = rand(1, 10);
        $orderItem->source = "SplashSync";
        $orderItem->source_ref = $orderItem->product_ref."-".rand(1, 10);

        return $orderItem;
    }
}
