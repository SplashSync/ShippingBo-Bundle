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

namespace Splash\Connectors\ShippingBo\Objects\Product;

/**
 * Product Stocks on Warehouse Slots Trait
 */
trait WarehouseSlotsStockTrait
{
    /**
     * Build Fields
     */
    protected function buildWarehouseSlotsStockFields(): void
    {
        $whSlotsManager = $this->connector->getWarehouseSlotsManager();
        //====================================================================//
        // Walk on Active Warehouse Slots
        foreach ($whSlotsManager->getActiveSlots() as $slotId => $activeSlot) {
            //====================================================================//
            // Build Warehouse Slot Name
            $slotName = !empty($activeSlot['name']) ? $activeSlot['name'] : sprintf("Slot%d", $slotId);
            //====================================================================//
            // Register Slot Inventory Level
            $this->fieldsFactory()->create(SPL_T_INT)
                ->identifier(sprintf("stock_available_%d", $slotId))
                ->name(sprintf("Stock on %s", $slotName))
                ->group("Warehouse Slots")
                ->microData("http://schema.org/Offer", sprintf("inventoryLevel%d", ucfirst($slotName)))
                ->isReadOnly(!$whSlotsManager->isWritableSlots($slotId))
            ;
        }
    }

    /**
     * Read requested Field
     *
     * @param string $key       Input List Key
     * @param string $fieldName Field Identifier / Name
     */
    protected function getWarehouseSlotsStockFields(string $key, string $fieldName): void
    {
        //====================================================================//
        // Filter on Warehouse Slot Field Name
        if (!$slotId = $this->getWarehouseSlotId($fieldName)) {
            return;
        }
        //====================================================================//
        // READ Slot Stock
        $this->out[$fieldName] = $this->getWarehouseSlotStocks($slotId);

        unset($this->in[$key]);
    }

    /**
     * Write Given Fields
     */
    protected function setWarehouseSlotsStockFields(string $fieldName, int $fieldData): void
    {
        //====================================================================//
        // Filter on Warehouse Slot Field Name
        if (!$slotId = $this->getWarehouseSlotId($fieldName)) {
            return;
        }
        $whSlotsManager = $this->connector->getWarehouseSlotsManager();
        //====================================================================//
        // READ Current Slot Stock
        $current = $this->getWarehouseSlotStocks($slotId);
        unset($this->in[$fieldName]);

        //====================================================================//
        // Slot Stock is NOT Empty, but Product is NOT Registered
        if (!empty($fieldData) && is_null($current)) {
            $whSlotsManager->createSlotContentForProduct($slotId, (int) $this->object->id, $fieldData);

            return;
        }
        //====================================================================//
        // Stock was Updated
        if ($delta = ($fieldData - $current)) {
            $whSlotsManager->updateSlotContentForProduct($slotId, (int) $this->object->id, $delta);
        }
        //====================================================================//
        // Slot Stock is Empty, but Product is Registered
        if (empty($fieldData) && !is_null($current)) {
            $whSlotsManager->removeSlotContentForProduct($slotId, (int) $this->object->id);

            return;
        }

        unset($this->in[$fieldName]);
    }

    /**
     * Extract Slot ID from FieldName
     */
    private function getWarehouseSlotStocks(int $slotId): ?int
    {
        //====================================================================//
        // Ensure Loading only Once by Product
        $this->object->warehouseStocks ??= $this->connector
            ->getWarehouseSlotsManager()
            ->getSlotsForProduct((int) $this->object->id)
        ;

        return $this->object->warehouseStocks[$slotId] ?? null;
    }

    /**
     * Extract Slot ID from FieldName
     */
    private function getWarehouseSlotId(string $fieldName): ?int
    {
        //====================================================================//
        // READ Fields
        $slotId = null;
        sscanf($fieldName, "stock_available_%d", $slotId);

        return $slotId ? (int) $slotId : null;
    }
}
