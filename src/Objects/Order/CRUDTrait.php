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

namespace Splash\Connectors\ShippingBo\Objects\Order;

use Exception;
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
     * @return false|Order
     */
    public function load($objectId)
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
        return $this->coreLoad($objectId);
    }

    /**
     * @throws Exception
     *
     * @return false|Order
     */
    public function create()
    {
        //====================================================================//
        // Stack Trace
        Splash::log()->trace();
        //====================================================================//
        // Ensure Default Source
        $this->in['source'] = $this->in['source'] ?? "SplashSync";
        //====================================================================//
        // Ensure Default State
        $this->in['state'] = $this->in['state'] ?? "rejected";
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
            // Execute Core Action
            return $this->coreCreate();
        }

        return $this->initRejected();
    }

    /**
     * {@inheritDoc}
     *
     * @throws Exception
     *
     * @return false|string Object ID of False if Failed to Update
     */
    public function update(bool $needed)
    {
        //====================================================================//
        // Detect Rejected Order Id => Init Rejected Object
        if ($this->isRejectedId($this->object->id)) {
            return $this->getObjectIdentifier();
        }
        //====================================================================//
        // Execute Core Action
        $response = $this->coreUpdate($needed);
        if (false == $response) {
            return false;
        }
        //====================================================================//
        // Update Order Shipping Address
        if ($this->isToUpdate("ShippingAddress") && !$this->updateShippingAddress()) {
            return false;
        }
        //====================================================================//
        // Update Order Items
        if ($this->isToUpdate("Items")) {
            if (!$this->updateOrderItems()) {
                return false;
            }
            if (!$this->computeOrderItems()) {
                return false;
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

    /**
     * Update Order Items after Main Update
     *
     * @throws Exception
     *
     * @return bool
     */
    private function updateOrderItems(): bool
    {
        //====================================================================//
        // Serialize All Order Items
        $orderItems = array();
        foreach ($this->object->items as $index => $item) {
            $item->source = $this->object->source;
            $item->source_ref = $this->object->source_ref."-".($index + 1);
            $orderItems[$index] = $this->getVisitor()->getHydrator()->extract($item);
            unset($orderItems[$index]['price']);
        }
        //====================================================================//
        // Build Request Uri
        $uri = $this->visitor->getItemUri((string) $this->getObjectIdentifier());
        $uri .= "/update_order_items";
        $body = array(
            "order_items" => $orderItems
        );
        //====================================================================//
        // Execute Request
        if (!$this->getVisitor()->getConnexion()->post($uri, $body)) {
            return Splash::log()->err("An error occurred while updating Order Items");
        }
        Splash::log()->war("Order Items Updated");

        return true;
    }

    /**
     * Update Order Items after Main Update
     *
     * @throws Exception
     *
     * @return bool
     */
    private function computeOrderItems(): bool
    {
        //====================================================================//
        // Build Request Uri
        $uri = $this->visitor->getItemUri((string) $this->getObjectIdentifier());
        $uri .= "/recompute_mapped_products";
        //====================================================================//
        // Execute Request
        if (!$this->getVisitor()->getConnexion()->post($uri, array())) {
            return Splash::log()->err("An error occurred while computing Order Items");
        }
        Splash::log()->war("Order Items Computed");

        return true;
    }
}
