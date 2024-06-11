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

/**
 * API Shipment Stage
 *
 * (For Stage environment) This API manage `Shipment` (of order) and its preparation in warehouse.
 *
 * OpenAPI spec version: 2
 *
 * Generated by: https://github.com/swagger-api/swagger-codegen.git
 */

/**
 * NOTE: This class is auto generated by the swagger code generator program.
 * https://github.com/swagger-api/swagger-codegen
 * Do not edit the class manually.
 */

namespace Splash\Connectors\ShippingBo\Models\Api;

use JMS\Serializer\Annotation as JMS;
use Splash\OpenApi\Validator as SPL;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class representing the Product model.
 *
 * @SuppressWarnings(PHPMD.CamelCasePropertyName)
 */
class Product
{
    use Core\SboCoreTrait;
    use Core\SboSourceTrait;
    use Product\ProductOtherRefsTrait;
    use Product\ProductDimensionsTrait;
    use Product\ProductImageTrait;
    use Product\PackComponentsTrait;
    use Product\AdminUrlsTrait;

    const COLLECTION_PROP = "products";
    const ITEMS_PROP = "product";

    /**
     * Unique Identifier.
     *
     * @var string
     *
     * @Assert\NotNull()
     *
     * @Assert\Type("string")
     *
     * @JMS\SerializedName("id")
     *
     * @JMS\Type("string")
     *
     * @JMS\Groups ({"Read", "List"})
     */
    public string $id;

    /**
     * Product SKU.
     *
     * @var string
     *
     * @Assert\NotNull()
     *
     * @Assert\Type("string")
     *
     * @JMS\SerializedName("user_ref")
     *
     * @JMS\Type("string")
     *
     * @JMS\Groups ({"Read", "Write", "List", "Required"})
     *
     * @SPL\Microdata({"http://schema.org/Product", "model"})
     *
     * @SPL\Primary
     */
    public ?string $user_ref = null;

    /**
     * Available Stock.
     *
     * @Assert\NotNull()
     *
     * @Assert\Type("int")
     *
     * @JMS\SerializedName("stock")
     *
     * @JMS\Type("int")
     *
     * @JMS\Groups ({"Read", "Write", "List"})
     *
     * @SPL\Microdata({"http://schema.org/Offer", "inventoryLevel"})
     */
    public ?int $stock = 0;

    /**
     * Product EAN13.
     *
     * @var null|int
     *
     * @Assert\Type("int")
     *
     * @JMS\SerializedName("ean13")
     *
     * @JMS\Type("int")
     *
     * @JMS\Groups ({"Read", "Write"})
     *
     * @SPL\Microdata({"http://schema.org/Product", "gtin13"})
     */
    public ?int $ean13 = null;

    /**
     * Title / Label.
     *
     * @var null|string
     *
     * @Assert\Type("string")
     *
     * @JMS\SerializedName("title")
     *
     * @JMS\Type("string")
     *
     * @JMS\Groups ({"Read", "Write"})
     *
     * @SPL\Microdata({"http://schema.org/Product", "name"})
     */
    public ?string $title = null;

    /**
     * Location.
     *
     * @var null|string
     *
     * @Assert\Type("string")
     *
     * @JMS\SerializedName("location")
     *
     * @JMS\Type("string")
     *
     * @JMS\Groups ({"Read", "Write"})
     *
     * @SPL\Microdata({"http://schema.org/Offer", "inventoryLevel"})
     */
    public ?string $location = null;

    /**
     * Customs HS Code.
     *
     * @var null|string
     *
     * @Assert\Type("string")
     *
     * @JMS\SerializedName("hs_code")
     *
     * @JMS\Type("string")
     *
     * @JMS\Groups ({"Read", "Write"})
     *
     * @SPL\Microdata({"http://schema.org/Product", "customsHsCode"})
     */
    public ?string $hsCode = null;

    /**
     * Supplier Name.
     *
     * @var null|string
     *
     * @Assert\Type("string")
     *
     * @JMS\SerializedName("supplier")
     *
     * @JMS\Type("string")
     *
     * @JMS\Groups ({"Read", "Write"})
     *
     * @SPL\Microdata({"http://schema.org/Product", "manufacturer"})
     */
    public ?string $supplier = null;

    /**
     * Post Loading Storage for Product Warehouse Slots Stocks
     *
     * @var null|int[]
     */
    public ?array $warehouseStocks = null;

    //====================================================================//
    // MAIN METHODS
    //====================================================================//

    /**
     * Product Constructor
     */
    public function __construct()
    {
        $this->source = "Splash";
    }
}
