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

namespace Splash\Connectors\ShippingBo\Objects;

use Exception;
use Splash\Bundle\Models\AbstractStandaloneObject;
use Splash\Client\Splash;
use Splash\Connectors\ShippingBo\Models\Api;
use Splash\Connectors\ShippingBo\Services\ShippingBoConnector;
use Splash\Models\Objects\IntelParserTrait;
use Splash\Models\Objects\ListsTrait;
use Splash\Models\Objects\ObjectsTrait;
use Splash\Models\Objects\PrimaryKeysAwareInterface;
use Splash\Models\Objects\SimpleFieldsTrait;
use Splash\OpenApi\Action\Json;
use Splash\OpenApi\Models\Objects as ApiModels;
use Splash\OpenApi\Visitor\AbstractVisitor as Visitor;
use Splash\OpenApi\Visitor\JsonVisitor;

/**
 * ShippingBo Implementation of Products
 */
class Product extends AbstractStandaloneObject implements PrimaryKeysAwareInterface
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
    use ApiModels\ListFieldsSetTrait;
    use ApiModels\ObjectsListTrait;
    //    use ApiModels\ObjectsListTrait {
    //        objectsList as public coreObjectsList;
    //    }

    //====================================================================//
    // Products Traits
    use Product\CRUDTrait;
    use Product\CoreTrait;
    use Product\PrimaryTrait;
    use Product\StockTrait;
    use Product\BarcodesTrait;

    //====================================================================//
    // Object Definition Parameters
    //====================================================================//

    /**
     * {@inheritdoc}
     */
    protected static string $name = "Product";

    /**
     * {@inheritdoc}
     */
    protected static string $description = "ShippingBo Product Object";

    /**
     * {@inheritdoc}
     */
    protected static string $ico = "fa fa-product-hunt";

    //====================================================================//
    // Object Synchronization Recommended Configuration
    //====================================================================//

    /**
     * {@inheritdoc}
     */
    protected static bool $enablePullCreated = false;

    /**
     * {@inheritdoc}
     */
    protected static bool $enablePullDeleted = false;

    //====================================================================//
    // General Class Variables
    //====================================================================//

    /**
     * @phpstan-var  Api\Product
     */
    protected object $object;

    /**
     * Open Api Shipment Visitor
     *
     * @var Visitor
     */
    protected Visitor $visitor;

    /**
     * Class Constructor
     *
     * @throws Exception
     */
    public function __construct(
        protected ShippingBoConnector $connector
    ) {
        //====================================================================//
        //  Load Translation File
        Splash::translator()->load('local');
        //====================================================================//
        // Prepare Api Visitor
        $this->getVisitor();
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

    //    /**
    //     * {@inheritdoc}
    //     */
    //    public function objectsList(?string $filter = null, array $params = array()): array
    //    {
    //        return array_replace_recursive($this->coreObjectsList($filter, $params), array(
    //            "meta" => array(
    //                "total" => 100000
    //            )
    //        ));
    //    }
}
