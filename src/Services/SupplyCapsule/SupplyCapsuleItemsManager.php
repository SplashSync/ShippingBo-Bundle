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

namespace Splash\Connectors\ShippingBo\Services\SupplyCapsule;

use Splash\Connectors\ShippingBo\DataTransformer\CapsuleStatusTransformer;
use Splash\Connectors\ShippingBo\Models\Connector\ShippingBoConnectorAwareTrait;
use Splash\Connectors\ShippingBo\Models\Metadata\SupplyCapsule;
use Splash\Connectors\ShippingBo\Models\Metadata\SupplyCapsuleItem;
use Splash\Core\SplashCore as Splash;

/**
 *
 */
class SupplyCapsuleItemsManager
{
    use ShippingBoConnectorAwareTrait;

    /**
     * Update Supply Capsule Items after Main Update
     *
     * @return null|bool
     */
    public function updateItems(SupplyCapsule $capsule): ?bool
    {
        $expected = $success = 0;
        //====================================================================//
        // Update All Items Sources
        $capsule->updateItemsSources();
        //====================================================================//
        // Create All Inserted Items
        foreach ($this->getInsertedItems($capsule) as $newItem) {
            $expected++;
            $success += (int) $this->createSupplyCapsuleItem($capsule, $newItem);
        }
        //====================================================================//
        // Update All Modified Order Items
        foreach ($this->getUpdatedItems($capsule) as $upItem) {
            $expected++;
            $success += (int) $this->deleteSupplyCapsuleItem($capsule, $upItem)
                && (int) $this->createSupplyCapsuleItem($capsule, $upItem)
            ;
        }
        //====================================================================//
        // Delete All Removed Items
        foreach ($this->getDeletedItems($capsule) as $delItem) {
            $expected++;
            $success += (int)  $this->deleteSupplyCapsuleItem($capsule, $delItem);
        }

        return $expected ? ($expected == $success) : null;
    }

    /**
     * Create Item
     */
    private function createSupplyCapsuleItem(SupplyCapsule $capsule, SupplyCapsuleItem $item): bool
    {
        //====================================================================//
        // Check if Items Updates are Allowed
        if (!$this->isAllowedItemsUpdates($capsule)) {
            return false;
        }
        //====================================================================//
        // Safety Check
        if (!$item->isNew()) {
            return Splash::log()->err(sprintf("Invalid New %s", $item));
        }
        if (!$item->isValid()) {
            return Splash::log()->msg(sprintf("Skipped %s", $item));
        }
        //====================================================================//
        // Extract Item Data
        $itemData = $this->connector->getHydrator()->extract($item);
        $itemData['supply_capsule_id'] = $capsule->id;
        //====================================================================//
        // Execute Item Create Request
        $createResponse = $this->connector->getConnexion()->post("/supply_capsule_items", array(
            "supply_capsule_item" => $itemData
        ));
        if (!$createResponse) {
            return Splash::log()->err(
                sprintf("Unable to create %s", $item)
            );
        }
        $item->id = $createResponse['supply_capsule_item']['id'] ?? null;

        return Splash::log()->deb(sprintf("%s Created", $item));
    }

    /**
     * Delete an Item
     */
    private function deleteSupplyCapsuleItem(SupplyCapsule $capsule, SupplyCapsuleItem $item): bool
    {
        //====================================================================//
        // Check if Items Updates are Allowed
        if (!$this->isAllowedItemsUpdates($capsule)) {
            return false;
        }
        //====================================================================//
        // Safety Check
        if (!$item->isValid()) {
            return Splash::log()->err(sprintf("Invalid %s", $item));
        }
        //====================================================================//
        // Build Special Connexion (Without API Version Headers)
        $deleteConnexion = clone $this->connector->getConnexion();
        unset($deleteConnexion->getTemplate()->headers['X-API-VERSION']);
        $deleteConnexion->getTemplate()
            ->addHeader("x-activemodelserializers", "1")
        ;
        //====================================================================//
        // Execute Item Delete Request
        $deleteConnexion->delete(
            sprintf("/supply_capsule_items/%s", $item->id)
        );
        if ($deleteConnexion->getLastResponse()?->hasErrors()) {
            return Splash::log()->err(
                sprintf("Unable to delete %s", $item)
            );
        }
        //====================================================================//
        // Mark Item as Deleted
        $capsule->deleteOriginalItem((string) $item->id);
        Splash::log()->deb(sprintf("%s Deleted", $item));
        $item->id = null;

        return true;
    }

    /**
     * Add Extra Feature for Supply Capsule Updates
     * - Item Updates
     */
    private function isAllowedItemsUpdates(SupplyCapsule $capsule): bool
    {
        //====================================================================//
        // Check if Items Updates are Allowed
        if (!CapsuleStatusTransformer::isAllowedItemsUpdates($capsule->state)) {
            if (!$this->connector->isSandbox()) {
                return Splash::log()->err("Update of Supply Capsule Items not allowed");
            }
        }

        return true;
    }

    /**
     * Get List of Just Created Items
     *
     * @return SupplyCapsuleItem[]
     */
    private function getInsertedItems(SupplyCapsule $capsule): array
    {
        $inserted = array();
        foreach ($capsule->supplyCapsuleItems as $item) {
            if ($item->isNew() && $item->isValid()) {
                $inserted[] = $item;
            }
        }

        return $inserted;
    }

    /**
     * Get List of Updated Items
     *
     * @return SupplyCapsuleItem[]
     */
    private function getUpdatedItems(SupplyCapsule $capsule): array
    {
        $updated = array();
        //====================================================================//
        // Walk on All Order Items
        foreach ($capsule->supplyCapsuleItems as $item) {
            //====================================================================//
            // NEW or INVALID
            if ($item->isNew() || !$item->isValid()) {
                continue;
            }
            //====================================================================//
            // NO CHANGES
            $originMd5 = $capsule->getOriginalItemMd5((string) $item->id);
            if (empty($originMd5) || ($item->getMd5() == $originMd5)) {
                continue;
            }
            $updated[] = $item;
        }

        return $updated;
    }

    /**
     * Get List of Deleted Items
     *
     * @return SupplyCapsuleItem[]
     */
    private function getDeletedItems(SupplyCapsule $capsule): array
    {
        $existing = array();
        $deleted = array();
        //====================================================================//
        // Walk on All Order Items
        foreach ($capsule->supplyCapsuleItems as $item) {
            $existing[] = $item->id;
        }
        //====================================================================//
        // Walk on All Original Order Items
        foreach ($capsule->oldSupplyCapsuleItems as $oldItem) {
            if (!in_array($oldItem->id, $existing, true)) {
                $deleted[] = $oldItem;
            }
        }

        return $deleted;
    }
}
