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
use Splash\Connectors\ShippingBo\Services\WarehouseSlotsManager;

/**
 * Order Forced Status Trait
 */
trait StatusForcedTrait
{
    /**
     * Build Status Fields
     *
     * @return void
     */
    protected function buildStatusForcedFields(): void
    {
        $this->fieldsFactory()->create(SPL_T_BOOL)
            ->identifier("forceDelivered")
            ->name("Force Delivered")
            ->microData("http://schema.org/OrderStatus", "ForceDelivered")
            ->isWriteOnly()
        ;
    }

    /**
     * Write Given Fields
     *
     * @param string $fieldName Field Identifier / Name
     * @param mixed  $fieldData Field Data
     */
    protected function setStatusForcedFields(string $fieldName, $fieldData): void
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
                // Try to decrease Stocks for All Items
                if (!$this->decreaseOrderItemsStocks()) {
                    Splash::log()->err("Unable to decrease stocks of all items for this order.");

                    return;
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
     * Impact as Order Item Stocks on Default Warehouse Stock
     */
    private function decreaseOrderItemsStocks(): bool
    {
        //====================================================================//
        // Safety Check => All Items are in Default Stock
        if (!$this->hasAllItemsInDefaultSlot()) {
            return false;
        }
        $whSlotsManager = $this->connector->getLocator()->getWarehouseSlotsManager();
        //====================================================================//
        // Get default Warehouse Stock Slot
        if (!$dfSlotId = $this->getDefaultSlotId()) {
            return false;
        }
        //====================================================================//
        // Decrease All Items Stocks in Default Stock
        foreach ($this->object->items as $item) {
            //====================================================================//
            // Skip non valid Items
            if (!$item->isValid()) {
                continue;
            }
            //====================================================================//
            // Update Items Stock on Warehouse Slot
            $result = $whSlotsManager->updateSlotContentForProductRef(
                $dfSlotId,
                $item->product_ref,
                (-1) * $item->quantity,
                sprintf("Forced Delivery of order %s", $this->object->id)
            );
            if (!$result) {
                return false;
            }
        }

        return true;
    }

    /**
     * Verify All Items of this Order are available in Default Warehouse Slot
     */
    private function hasAllItemsInDefaultSlot(): bool
    {
        //====================================================================//
        // Collect List of Available Product Stocks
        $whSlotStocks = $this->getDefaultSlotStocks();
        //====================================================================//
        // Walk on Order Items
        foreach ($this->object->items as $item) {
            //====================================================================//
            // Skip non valid Items
            if (!$item->isValid()) {
                continue;
            }
            //====================================================================//
            // Item is in Default Stock
            if (!array_key_exists($item->product_ref, $whSlotStocks)) {
                return Splash::log()->err(sprintf(
                    "Product %s not found on default warehouse slot",
                    $item->product_ref
                ));
            }
            //====================================================================//
            // Item Stock is sufficient in Default Stock
            $available = $whSlotStocks[$item->product_ref];
            if (empty($available) || ($available < $item->quantity)) {
                return Splash::log()->err(sprintf(
                    "Not enough stock for %s on default warehouse slot",
                    $item->product_ref
                ));
            }
        }

        return true;
    }

    /**
     * Register a Default Shipment to this Order
     */
    private function getDefaultSlotStocks(): array
    {
        //====================================================================//
        // Get default Warehouse Stock Slot
        if (!$dfSlotId = $this->getDefaultSlotId()) {
            return array();
        }
        $whSlotsManager = $this->connector->getLocator()->getWarehouseSlotsManager();

        //====================================================================//
        // Get All Product Stocks for this Slot
        return $whSlotsManager->getSlotProducts($dfSlotId);
    }

    /**
     * Get Default Warehouse Slot ID
     */
    private function getDefaultSlotId(): ?int
    {
        $defaultSlotId = $this->connector->getParameter(WarehouseSlotsManager::DEFAULT);
        if (empty($defaultSlotId) || !is_numeric($defaultSlotId)) {
            return Splash::log()->errNull("Default Warehouse Slots not set.");
        }

        return (int) $defaultSlotId;
    }
}
