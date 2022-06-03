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

namespace Splash\Connectors\ShippingBo\Objects\Order;

use Exception;
use Splash\Connectors\ShippingBo\DataTransformer\TotalsTransformer;
use Splash\Connectors\ShippingBo\Hydrator\Hydrator;
use Splash\Connectors\ShippingBo\Models\Api\Order;
use Splash\Core\SplashCore as Splash;
use Splash\OpenApi\Models\Objects\CRUDTrait as OpenApiCRUDTrait;

/**
 * ShippingBo Orders CRUD Functions
 */
trait CRUDTrait
{
    use OpenApiCRUDTrait{
        OpenApiCRUDTrait::load as coreLoad;
        OpenApiCRUDTrait::create as coreCreate;
        OpenApiCRUDTrait::update as coreUpdate;
    }

    /**
     * Load Request Object
     *
     * @param string $objectId Object id
     *
     * @return null|Order
     */
    public function load(string $objectId): ?Order
    {
        //====================================================================//
        // Stack Trace
        Splash::log()->trace();
        //====================================================================//
        // Detect Rejected Order Id => Init Rejected Object
        if ($this->isRejectedId($objectId)) {
            return $this->initRejected();
        }
        //====================================================================//
        // Execute Core Action
        $order = $this->coreLoad($objectId);

        return ($order instanceof Order) ? $order : null;
    }

    /**
     * @throws Exception
     *
     * @return null|Order
     */
    public function create(): ?Order
    {
        //====================================================================//
        // Stack Trace
        Splash::log()->trace();
        //====================================================================//
        // Ensure Default Source
        $this->in['source'] = $this->in['source'] ?? "Splashsync";
        //====================================================================//
        // Ensure Default State
        $this->in['state'] = $this->in['state'] ?? "waiting_for_payment";
        //====================================================================//
        // Check if Order is Allowed for Creation
        if (!$this->isAllowedDate()) {
            // Filtered By Date
            $this->logFilteredDate();
        } elseif (!$this->isAllowedDeliveryService()) {
            // Filtered By Delivery Service Name
            $this->logFilteredMethod();
        } elseif (!$this->isAllowedOrigin()) {
            // Filtered By Origin
            $this->logFilteredOrigin();
        } else {
            //====================================================================//
            // Complete Create Request with Items Attributes
            /** @var Hydrator $hydrator */
            $hydrator = $this->getVisitor()->getHydrator();
            $hydrator->setExtractExtra(TotalsTransformer::getInitialValues($this->in));
            //====================================================================//
            // Execute Core Action
            $order = $this->coreCreate();
            //====================================================================//
            // Unset State to Prevent Already Validated/Cancelled Rollback
            unset($this->in['state']);

            return ($order instanceof Order) ? $order : null;
        }

        return $this->initRejected();
    }

    /**
     * {@inheritDoc}
     *
     * @throws Exception
     *
     * @return null|string Object ID of False if Failed to Update
     */
    public function update(bool $needed): ?string
    {
        //====================================================================//
        // Detect Rejected Order Id => Init Rejected Object
        if ($this->isRejectedId($this->object->id)) {
            return $this->getObjectIdentifier();
        }
        //====================================================================//
        // Execute Core Action
        $response = $this->coreUpdate($needed);
        if (!$response) {
            return null;
        }
        //====================================================================//
        // Update Order Shipping Address
        if ($this->isToUpdate("ShippingAddress") && !$this->updateShippingAddress()) {
            return null;
        }
        //====================================================================//
        // Update Order Items
        if ($this->isToUpdate("Items")) {
            $itemsUpdate = $this->updateOrderItems();
            //====================================================================//
            // Update Order Items Fail
            if (false === $itemsUpdate) {
                return null;
            }
            //====================================================================//
            // Update Order Items Done
            if ((true === $itemsUpdate) && (!$this->computeOrderItems())) {
                return null;
            }
        }

        return $response;
    }

    /**
     * Update Order Shipping Address after Main Update
     *
     * @throws Exception
     *
     * @return bool
     */
    protected function updateShippingAddress(): bool
    {
        $address = $this->object->shippingAddress;
        $addressId = $this->object->getShippingAddressId() ?? $address->id ?? null;
        //====================================================================//
        // Safety Check
        if (!$address || empty($addressId)) {
            return false;
        }
        //====================================================================//
        // Build Request Uri
        $uri = "/addresses/".$addressId;
        $body = $this->getVisitor()->getHydrator()->extract($address);
        //====================================================================//
        // Execute Request
        if (!$this->getVisitor()->getConnexion()->patch($uri, $body)) {
            return Splash::log()->err("An error occurred while updating Order Shipping Address");
        }
        Splash::log()->war("Order Shipping Address Updated");

        return true;
    }
}
