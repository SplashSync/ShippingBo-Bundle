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

namespace Splash\Connectors\ShippingBo\Services;

use Splash\Client\Splash;
use Splash\OpenApi\Models\Connexion\ConnexionInterface;
use Symfony\Component\HttpFoundation\Response;

/**
 *
 */
class WarehouseSlotsManager
{
    /**
     * Parameters Storage Key
     */
    const STORAGE = "WarehouseSlots";

    /**
     * Default Slot Storage Key
     */
    const DEFAULT = "DefaultWarehouseSlot";

    /**
     * Write Slots Storage Key
     */
    const WRITE = "WriteWarehouseSlots";

    /**
     * List of Slot Data Key to Store
     */
    const SLOT_KEYS = array(
        "id" => true,
        "updated_at" => true,
        "name" => true,
        "priority" => true,
        "picking_disabled" => true,
        "warehouse_zone_name" => true,
        "stock_disabled" => true,
    );

    /**
     * Search by Slot ID
     */
    const BY_SLOT_ID = "search[id__eq]";

    /**
     * Search by Product ID
     */
    const BY_PRODUCT_ID = "search[product_id__eq]";

    /**
     * Current Connexion
     */
    private ConnexionInterface $connexion;

    /**
     * List of Writable Slots IDs
     *
     * @var string[]
     */
    private array $writeSlots = array();

    /**
     * List of Warehouse Slots Definitions
     *
     * @var array[]
     */
    private array $whSlots = array();

    /**
     * Temporary Storage for Last Fetched Warehouse Slots Content IDs
     *
     * @var array<string, int>
     */
    private array $lastSlotsContentsIds = array();

    /**
     * Configure with Current API Connexion Settings
     */
    public function configure(ShippingBoConnector $connector): static
    {
        $warehouseSlots = $connector->getParameter(self::STORAGE, array());
        $this->whSlots = is_array($warehouseSlots) ? $warehouseSlots : array();

        $writableSlots = $connector->getParameter(self::WRITE, array());
        $this->writeSlots = is_array($writableSlots) ? $writableSlots : array();

        $this->connexion = $connector->getConnexion();

        return $this;
    }

    /**
     * Fetch List of Customer Warehouse Slots from API
     */
    public function fetchWarehouseSlots(ShippingBoConnector $connector): bool
    {
        //====================================================================//
        // Get Lists of Available Slots from Api
        try {
            $response = $this->connexion->get("/warehouse_slots");
        } catch (\Exception $e) {
            return Splash::log()->report($e);
        }
        if (!is_array($response)) {
            return (Response::HTTP_FORBIDDEN == $this->connexion->getLastResponse()?->code);
        }
        if (!is_array($whSlots = $response['warehouse_slots'] ?? null)) {
            return false;
        }
        //====================================================================//
        // Reformat results
        $slots = array_combine(
            array_map(fn (array $slot) => $slot["id"], $whSlots),
            array_map(fn (array $slot) => array_intersect_key($slot, self::SLOT_KEYS), $whSlots)
        );
        //====================================================================//
        // Store in Connector Settings
        $connector->setParameter(self::STORAGE, $slots);

        return true;
    }

    /**
     * Get List of Customer Active Warehouse Slots from Connector Configuration
     */
    public function getActiveSlots(): array
    {
        $slots = array();
        //====================================================================//
        // Walk on Defined Slots
        foreach ($this->whSlots as $warehouseSlot) {
            if (empty($warehouseSlot["stock_disabled"])) {
                $slots[$warehouseSlot["id"]] = $warehouseSlot;
            }
        }

        return $slots;
    }

    /**
     * Check if a Warehouse Slot can be Updated
     */
    public function isWritableSlots(int $slotId): bool
    {
        return in_array($slotId, $this->writeSlots, false);
    }

    /**
     * Get Products Stocks on a Given Warehouse Slot
     *
     * @return array<int|string, int>
     */
    public function getSlotProducts(int $slotId): array
    {
        $productStocks = array();
        //====================================================================//
        // Fetch a Slots by ID
        $rawSlots = $this->connexion->get("/warehouse_slots", array(self::BY_SLOT_ID => (string) $slotId));
        if (!$rawSlots) {
            return array();
        }
        //====================================================================//
        // Extract First Slot
        $whSlots = $rawSlots["warehouse_slots"] ?? array();
        if (!is_array($whSlots) || (1 != count($whSlots))) {
            return array();
        }
        $whSlot = array_shift($whSlots);
        //====================================================================//
        // Safety Check
        if (!$whSlot || !is_array($whSlot["slot_contents"])) {
            return array();
        }
        //====================================================================//
        // Walk on Warehouse Slot Contents
        foreach ($whSlot["slot_contents"] as $whSlotContent) {
            $productRef = (string) ($whSlotContent["product_ref"] ?? null);
            $productStock = (int) ($whSlotContent["stock"] ?? null);
            //====================================================================//
            // Register Product Stock
            if ($productRef && $productStock > 0) {
                $productStocks[$productRef] = $productStock;
            }
        }

        return $productStocks;
    }

    /**
     * Get List of Warehouse Slots Stocks for a Given Product
     *
     * @return array<int|string, int>
     */
    public function getSlotsForProduct(int $productId): array
    {
        $this->lastSlotsContentsIds = $stocks = array();
        //====================================================================//
        // Fetch List of Slots with this ID
        $whSlots = $this->connexion->get("/warehouse_slots", array(self::BY_PRODUCT_ID => (string) $productId));
        if (empty($whSlots["warehouse_slots"]) || !is_array($whSlots["warehouse_slots"])) {
            return $stocks;
        }
        //====================================================================//
        // Walk on Warehouse Slots
        foreach ($whSlots["warehouse_slots"] as $whSlot) {
            //====================================================================//
            // Safety Check
            if (empty($whSlot["slot_contents"]) || !is_array($whSlot["slot_contents"])) {
                continue;
            }
            //====================================================================//
            // Walk on Warehouse Slot Contents
            foreach ($whSlot["slot_contents"] as $whSlotContent) {
                //====================================================================//
                // This is Expected Product
                if ($productId == ($whSlotContent["product_id"] ?? null)) {
                    /** @var int $stock */
                    $stock = $whSlotContent['stock'] ?? 0;
                    $stocks[$whSlot["id"]] = $stock;
                    $this->lastSlotsContentsIds[$this->getCacheKey($whSlot["id"], $productId)] = $whSlotContent['id'];
                }
            }
        }

        return $stocks;
    }

    /**
     * Create Warehouse Slot Content for Product
     */
    public function createSlotContentForProduct(int $slotId, int $productId, int $stock): bool
    {
        //====================================================================//
        // Prepare Request Parameters
        $request = array(
            "product_id" => $productId,
            "warehouse_slot_id" => $slotId,
            "stock" => $stock,
        );
        //====================================================================//
        // Create Stock Slot Content
        if (null === $this->connexion->post("/slot_contents", $request)) {
            Splash::log()->err("Is this warehouse slot a multi-product slot?");

            return Splash::log()->err(sprintf("Unable to create stock on Slot %s", $this->getSlotName($slotId)));
        }

        return true;
    }

    /**
     * Update Warehouse Slots Stocks for a Given Product
     */
    public function updateSlotContentForProduct(int $slotId, int $productId, int $variation): bool
    {
        $slotName = $this->getSlotName($slotId);
        //====================================================================//
        // Prepare Request Parameters
        $request = array(
            "product_id" => $productId,
            "warehouse_slot_id" => $slotId,
            "warehouse_slot_name" => (string) $slotName,
            "variation" => $variation,
        );
        //====================================================================//
        // Request Stock Variation on this Slot Content
        if (null === $this->connexion->post("/slot_stock_variations", $request)) {
            return Splash::log()->err(sprintf("Unable to update stock on Slot %s", $slotName));
        }

        return true;
    }

    /**
     * Update Warehouse Slots Stocks for a Given Product by Reference
     */
    public function updateSlotContentForProductRef(
        int $slotId,
        string $productRef,
        int $variation,
        string $reason = null
    ): bool {
        $slotName = $this->getSlotName($slotId);
        //====================================================================//
        // Prepare Request Parameters
        $request = array(
            "product_ref" => $productRef,
            "warehouse_slot_id" => $slotId,
            "warehouse_slot_name" => (string) $slotName,
            "variation" => $variation,
            "reason" => $reason ?: "Updated by Splash",
        );
        //====================================================================//
        // Request Stock Variation on this Slot Content
        if (null === $this->connexion->post("/slot_stock_variations", $request)) {
            return Splash::log()->err(sprintf("Unable to update stock on Slot %s", $slotName));
        }

        return true;
    }

    public function removeSlotContentForProduct(int $slotId, int $productId): bool
    {
        $cacheKey = $this->getCacheKey($slotId, $productId);
        //====================================================================//
        // Search for Slot Content ID
        if (!$slotContentId = $this->lastSlotsContentsIds[$cacheKey]) {
            return true;
        }
        //====================================================================//
        // Delete Stock Slot Content
        if (null === $this->connexion->delete(sprintf("/slot_contents/%d", $slotContentId))) {
            return Splash::log()->err(sprintf("Unable to delete stock on Slot %s", $this->getSlotName($slotId)));
        }

        return true;
    }

    /**
     * Get Warehouse Slot
     */
    private function getSlotById(int $slotId): ?array
    {
        return $this->whSlots[$slotId] ?? null;
    }

    /**
     * Get Warehouse Slot Name
     */
    private function getSlotName(int $slotId): ?string
    {
        if ($slot = $this->getSlotById($slotId)) {
            return $slot["name"] ?? null;
        }

        return null;
    }

    /**
     * Get Warehouse Slot Name
     */
    private function getCacheKey(int $slotId, int $productId): string
    {
        return sprintf("%s-%s", $slotId, $productId);
    }
}
