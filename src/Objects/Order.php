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
use Splash\Models\Objects\SimpleFieldsTrait;
use Splash\OpenApi\Action\Json;
use Splash\OpenApi\Models\Objects as ApiModels;
use Splash\OpenApi\Visitor\AbstractVisitor as Visitor;
use Splash\OpenApi\Visitor\JsonVisitor;

/**
 * ShippingBo Implementation of Customers Orders
 */
class Order extends AbstractStandaloneObject
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

    //====================================================================//
    // ShippingBo Core Traits
    use Core\DatesFilterTrait;

    //====================================================================//
    // ShippingBo Order Traits
    use Order\CRUDTrait;
    use Order\CoreTrait;
    use Order\RejectedTrait;
    use Order\DeliveryServiceTrait;
    use Order\OriginFilterTrait;
    use Order\OrderItemsCRUDTrait;
    use Order\StatusTrait;
    use Order\StatusForcedTrait;
    use Order\TrackingTrait;

    //====================================================================//
    // Object Definition Parameters
    //====================================================================//

    /**
     * {@inheritdoc}
     */
    protected static string $name = "Customer Order";

    /**
     * {@inheritdoc}
     */
    protected static string $description = "ShippingBo Order Object";

    /**
     * {@inheritdoc}
     */
    protected static string $ico = "fa fa-shopping-cart";

    //====================================================================//
    // General Class Variables
    //====================================================================//

    /**
     * @phpstan-var  Api\Order
     */
    protected object $object;

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
        self::$enablePullCreated = false;
        self::$enablePullDeleted = false;
        self::$enablePushDeleted = false;
        //====================================================================//
        // Production Configuration
        if (!$this->connector->isSandbox()) {
            self::$allowPushDeleted = false;
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
                Api\Order::class
            );
            $this->visitor->setModel(
                Api\Order::class,
                "/orders",
                "/orders/{id}",
                Api\Order::EXCLUDED
            );
            $this->visitor->setListAction(
                Json\ListAction::class,
                array(
                    "filterKey" => "search[origin_ref__contains][]",
                    "pageKey" => null,
                    "offsetKey" => "offset"
                )
            );
            //====================================================================//
            // Force Loading of Order Items Metadata
            \Splash\OpenApi\Fields\Descriptor::load(
                $this->connector->getHydrator(),
                Api\OrderItem::class,
                Api\OrderItem::EXCLUDED
            );
            //====================================================================//
            // Force Dates Timezone
            /** @var string $connectorTimezone */
            $connectorTimezone = $this->connector->getParameter("timezone", "Europe/Paris");
            $this->visitor->setTimezone($connectorTimezone);
        }

        return $this->visitor;
    }
}
