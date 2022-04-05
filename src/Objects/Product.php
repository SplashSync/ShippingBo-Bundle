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

namespace Splash\Connectors\ShippingBo\Objects;

use Exception;
use Splash\Bundle\Models\AbstractStandaloneObject;
use Splash\Client\Splash;
use Splash\Connectors\ShippingBo\Models\Api;
use Splash\Connectors\ShippingBo\Services\ShippingBoConnector;
use Splash\Models\Objects\IntelParserTrait;
use Splash\Models\Objects\ListsTrait;
use Splash\Models\Objects\ObjectsTrait;
use Splash\Models\Objects\SimpleFieldsTrait;
use Splash\OpenApi\Action\Json;
use Splash\OpenApi\Models\Objects as ApiModels;
use Splash\OpenApi\Visitor\AbstractVisitor as Visitor;
use Splash\OpenApi\Visitor\JsonVisitor;

/**
 * ShippingBo Implementation of Products
 *
 * @SuppressWarnings(PHPMD.CamelCasePropertyName)
 */
class Product extends AbstractStandaloneObject
{
    //====================================================================//
    // Splash Php Core Traits
    use IntelParserTrait;
    use SimpleFieldsTrait;
    use ObjectsTrait;
    use ListsTrait;

    //====================================================================//
    // OpenApi Traits
    use ApiModels\SimpleFieldsTrait;
    use ApiModels\ListFieldsGetTrait;
    use ApiModels\ObjectsListTrait;

    //====================================================================//
    // Products Traits
    use Product\CRUDTrait;
    use Product\CoreTrait;
    use Product\StockTrait;

    //====================================================================//
    // Object Definition Parameters
    //====================================================================//

    /**
     * {@inheritdoc}
     */
    protected static $NAME = "Product";

    /**
     * {@inheritdoc}
     */
    protected static $DESCRIPTION = "ShippingBo Product Object";

    /**
     * {@inheritdoc}
     */
    protected static $ICO = "fa fa-product-hunt";

    //====================================================================//
    // General Class Variables
    //====================================================================//

    /**
     * @var Api\Product
     */
    protected $object;

    /**
     * Open Api Shipment Visitor
     *
     * @var Visitor
     */
    protected Visitor $visitor;

    /**
     * @var ShippingBoConnector
     */
    protected ShippingBoConnector $connector;

    /**
     * Class Constructor
     *
     * @param ShippingBoConnector $parentConnector
     *
     * @throws Exception
     */
    public function __construct(ShippingBoConnector $parentConnector)
    {
        $this->connector = $parentConnector;
        //====================================================================//
        //  Load Translation File
        Splash::translator()->load('local');
        //====================================================================//
        // Prepare Api Visitor
        $this->getVisitor();
    }

    /**
     * {@inheritdoc}
     */
    public function description(): array
    {
        //====================================================================//
        // Default Configuration
        self::$ENABLE_PUSH_DELETED = false;
        self::$ENABLE_PULL_CREATED = false;
        self::$ENABLE_PULL_DELETED = false;
        //====================================================================//
        // Production Configuration
        if (!$this->connector->isSandbox()) {
            self::$ALLOW_PUSH_DELETED = false;
        }

        return parent::description();
    }

    /**
     * Get Shipment API Visitor
     *
     * @throws Exception
     */
    public function getVisitor(): Visitor
    {
        if (!isset($this->visitor)) {
            $this->visitor = new JsonVisitor(
                $this->connector->getConnexion(),
                $this->connector->getHydrator(),
                Api\Product::class
            );
            $this->visitor->setModel(
                Api\Product::class,
                "/products",
                "/products/{id}",
                array("id", "stock")
            );
            $this->visitor->setListAction(
                Json\ListAction::class,
                array(
                    "filterKey" => "search[user_ref__contains][]",
                    "pageKey" => null,
                    "offsetKey" => "offset"
                )
            );
        }

        return $this->visitor;
    }
}
