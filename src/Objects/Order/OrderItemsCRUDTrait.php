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
use Splash\Connectors\ShippingBo\DataTransformer\StatusTransformer;
use Splash\Connectors\ShippingBo\Models\Api\OrderItem;
use Splash\Core\SplashCore as Splash;

/**
 * Manage CRUD Operations for Order Items
 */
trait OrderItemsCRUDTrait
{
    /**
     * Update Order Items after Main Update
     *
     * @throws Exception
     *
     * @return null|bool
     */
    protected function updateOrderItems(): ?bool
    {
        //====================================================================//
        // Check if Order Items Updates are Allowed
        if (!StatusTransformer::isAllowedUpdates($this->object->state)) {
            if (!$this->connector->isSandbox()) {
                Splash::log()->err("Update of Order Items not allowed");

                return null;
            }
        }
        $expected = $success = 0;
        //====================================================================//
        // Update All Order Items Sources
        $this->object->updateItemsSources();
        //====================================================================//
        // Create All Inserted Order Items
        foreach ($this->object->getInsertedItems() as $newItem) {
            $expected++;
            $success += (int) $this->createOrderItem($newItem);
        }
        //====================================================================//
        // Update All Modified Order Items
        foreach ($this->object->getUpdatedItems() as $upItem) {
            $success += (int) $this->updateOrderItem($upItem);
        }
        //====================================================================//
        // Delete All Removed Order Items
        foreach ($this->object->getDeletedItems() as $delItem) {
            $success += (int)  $this->updateOrderItem($delItem->setDeleted());
        }

        return $expected ? ($expected == $success) : null;
    }

    /**
     * Update Order Items after Main Update
     *
     * @throws Exception
     *
     * @return bool
     */
    protected function computeOrderItems(): bool
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
        Splash::log()->msg("Order Items Computed");

        return true;
    }

    /**
     * Create Order Item
     *
     * @param OrderItem $item
     *
     * @throws Exception
     *
     * @return bool
     */
    private function createOrderItem(OrderItem $item): bool
    {
        //====================================================================//
        // Safety Check
        if (!$item->isNew()) {
            return Splash::log()->err(sprintf("Invalid New %s", $item));
        }
        if (!$item->isValid()) {
            return Splash::log()->msg(sprintf("Skipped %s", $item));
        }
        //====================================================================//
        // Build Request Uri
        $uri = $this->visitor->getItemUri((string) $this->getObjectIdentifier());
        $uri .= "/order_items";
        //====================================================================//
        // Execute Item Create Request
        $createResponse = $this->getVisitor()->getConnexion()->post(
            $uri,
            $this->getVisitor()->getHydrator()->extract($item)
        );
        if (!$createResponse) {
            return Splash::log()->err(
                sprintf("Unable to create %s", $item)
            );
        }
        $item->id = $createResponse['order_item']['id'] ?? null;

        return Splash::log()->msg(sprintf("%s Created", $item));
    }

    /**
     * Update an Order Item
     *
     * @param OrderItem $item
     *
     * @throws Exception
     *
     * @return bool
     */
    private function updateOrderItem(OrderItem $item): bool
    {
        //====================================================================//
        // Safety Check
        if (!$item->isValid()) {
            return Splash::log()->err(sprintf("Invalid %s", $item));
        }
        //====================================================================//
        // Extract Item Data
        /** @var array<string, string> $itemData */
        $itemData = $this->getVisitor()->getHydrator()->extract($item);
        $itemData['quantity'] = $itemData['quantity'] ?? 0;
        //====================================================================//
        // Execute Item Update Request
        $updateResponse = $this->getVisitor()->getConnexion()->patch(
            "/order_items/".$item->getId(),
            $itemData
        );
        if (!$updateResponse) {
            return Splash::log()->err(
                sprintf("Unable to update %s", $item)
            );
        }

        return Splash::log()->msg(sprintf("%s Updated", $item));
    }
}
