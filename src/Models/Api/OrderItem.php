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

namespace Splash\Connectors\ShippingBo\Models\Api;

use JMS\Serializer\Annotation as JMS;
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
}
