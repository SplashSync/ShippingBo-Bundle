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

use Splash\Client\Splash;
use Splash\Connectors\ShippingBo\DataTransformer\StatusTransformer;
use Splash\Connectors\ShippingBo\Models\Api\Order;

/**
 * Order Status Trait
 */
trait StatusTrait
{
    /**
     * Build Status Fields
     *
     * @return void
     */
    protected function buildStatusFields(): void
    {
        //====================================================================//
        // ORDER STATUS
        //====================================================================//

        //====================================================================//
        // Order Current Status
        $this->fieldsFactory()->create(SPL_T_VARCHAR)
            ->Identifier("splashStatut")
            ->Name("Order status")
            ->Description("Status of the order")
            ->MicroData("http://schema.org/Order", "orderStatus")
            ->addChoices(StatusTransformer::getAll())
            ->isReadOnly()
        ;

        //====================================================================//
        // ORDER STATUS FLAGS
        //====================================================================//

        //====================================================================//
        // Is Validated
        $this->fieldsFactory()->create(SPL_T_BOOL)
            ->Identifier("isValidated")
            ->Name("Is Valid")
            ->MicroData("http://schema.org/OrderStatus", "OrderProcessing")
            ->isNotTested()
        ;

        //====================================================================//
        // Is Canceled
        $this->fieldsFactory()->create(SPL_T_BOOL)
            ->Identifier("isCanceled")
            ->Name("Is Canceled")
            ->MicroData("http://schema.org/OrderStatus", "OrderCancelled")
            ->isNotTested()
        ;

        //====================================================================//
        // Force Order Status to Delivered
        $this->fieldsFactory()->create(SPL_T_BOOL)
            ->identifier("forceDelivered")
            ->name("Force Delivered")
            ->microData("http://schema.org/OrderStatus", "ForceDelivered")
            ->isWriteOnly()
        ;
    }

    /**
     * Read requested Field
     *
     * @param string $key       Input List Key
     * @param string $fieldName Field Identifier / Name
     */
    protected function getStatusFields(string $key, string $fieldName): void
    {
        //====================================================================//
        // READ Fields
        switch ($fieldName) {
            case 'splashStatut':
                $this->out[$fieldName] = StatusTransformer::isValidated($this->object->state)
                    ? StatusTransformer::toSplash($this->object->state)
                    : null
                ;

                break;
            case 'isValidated':
                $this->out[$fieldName] = StatusTransformer::isValidated($this->object->state);

                break;
            case 'isCanceled':
                $this->out[$fieldName] = StatusTransformer::isCanceled($this->object->state);

                break;
            default:
                return;
        }

        unset($this->in[$key]);
    }

    /**
     * Write Given Fields
     *
     * @param string $fieldName Field Identifier / Name
     * @param mixed  $fieldData Field Data
     */
    protected function setStatusFields(string $fieldName, $fieldData): void
    {
        //====================================================================//
        // WRITE Field
        switch ($fieldName) {
            case 'isValidated':
                if (empty($fieldData) || StatusTransformer::isValidated($this->object->state)) {
                    break;
                }
                //====================================================================//
                // Check if Order VALIDATE as Allowed
                if (!StatusTransformer::isAllowedValidate($this->object->state)) {
                    Splash::log()->war(sprintf(
                        "You cannot validate an order from %s status",
                        $this->object->state
                    ));

                    break;
                }
                $this->object->state = "to_be_prepared";
                $this->needUpdate();

                break;
            case 'isCanceled':
                if (empty($fieldData) || StatusTransformer::isCanceled($this->object->state)) {
                    break;
                }
                //====================================================================//
                // Check if Order CANCEL as Allowed
                if (!StatusTransformer::isAllowedCancel($this->object->state)) {
                    Splash::log()->war(sprintf(
                        "You cannot cancel an order from %s status",
                        $this->object->state
                    ));

                    break;
                }
                $this->object->state = "canceled";
                $this->needUpdate();

                break;
            default:
                return;
        }
        unset($this->in[$fieldName]);
    }

    /**
     * Write Given Fields
     *
     * @param string $fieldName Field Identifier / Name
     * @param mixed  $fieldData Field Data
     */
    protected function setStatusDeliveredFields(string $fieldName, $fieldData): void
    {
        //====================================================================//
        // WRITE Field
        switch ($fieldName) {
            case 'forceDelivered':
                if (empty($fieldData) || !StatusTransformer::isValidated($this->object->state)) {
                    break;
                }
                //====================================================================//
                // Compare Status
                if (StatusTransformer::isDelivered($this->object->state)) {
                    Splash::log()->war(sprintf(
                        "You cannot close an order from %s status",
                        $this->object->state
                    ));

                    break;
                }
                //====================================================================//
                // Register a Default Shipment
                if (empty($this->object->shipments) && !$this->addDefaultShipment($this->object)) {
                    Splash::log()->err("Unable to register default shipment");

                    break;
                }

                $this->object->state = "closed";
                $this->needUpdate();

                break;
            default:
                return;
        }
        unset($this->in[$fieldName]);
    }

    /**
     * Register a Default Shipment to this Order
     */
    private function addDefaultShipment(Order $order): bool
    {
        //====================================================================//
        // Collect Order Items to Ship
        $shipment = array(
            "order_id" => (int) $order->id,
            "order_items" => array(),
            "shipping_method_id" => (int) $this->getParameter('DefaultShippingMethod', 1),
            "shipping_ref" => "Forced Delivery",
            "tracking_url" => "none",
            "ship_order" => 1,
        );
        //====================================================================//
        // Collect Order Items to Ship
        foreach ($order->items as $item) {
            $shipment["order_items"][] = array(
                "order_item_id" => (int) $item->id,
                "item_quantity" => (int) $item->quantity,
            );
        }

        return !empty($this->visitor->getConnexion()->post("/shipments", $shipment));
    }
}
