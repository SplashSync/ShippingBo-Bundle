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
use Splash\Connectors\ShippingBo\Models\Metadata as ApiModels;
use Splash\Connectors\ShippingBo\Services\ShippingBoConnector;
use Splash\Models\Objects\PrimaryKeysAwareInterface;
use Splash\OpenApi\Action\Json;
use Splash\OpenApi\Models\Metadata\AbstractApiMetadataObject;

/**
 * Splash Mapper for Supplier Order
 */
class SupplierOrder extends AbstractApiMetadataObject implements PrimaryKeysAwareInterface
{
    //====================================================================//
    // ShippingBo Supply Capsule Traits
    use SupplierOrder\CoreTrait;
    use SupplierOrder\CRUDTrait;
    use SupplierOrder\PrimaryTrait;

    //====================================================================//
    // General Class Variables
    //====================================================================//

    /**
     * @var ApiModels\SupplyCapsule
     */
    protected object $object;

    /**
     * Class Constructor
     *
     * @throws Exception
     */
    public function __construct(
        protected ShippingBoConnector $connector
    ) {
        parent::__construct(
            $connector->getMetadataAdapter(),
            $connector->getConnexion(),
            $connector->getHydrator(),
            ApiModels\SupplyCapsule::class
        );
        $this->visitor->setTimezone("UTC");
        $isSandbox = $this->connector->isSandbox();
        //====================================================================//
        // Prepare Api Visitor
        $this->visitor->setModel(
            ApiModels\SupplyCapsule::class,
            "/supply_capsules",
            "/supply_capsules/{id}",
            array("id")
        );
        $this->visitor->setListAction(
            Json\ListAction::class,
            array(
                "filterKey" => "search[source_ref__contains][]",
                "pageKey" => $isSandbox ? "offset" : null,
                "offsetKey" => $isSandbox ? null : "offset",
            )
        );
    }
}
