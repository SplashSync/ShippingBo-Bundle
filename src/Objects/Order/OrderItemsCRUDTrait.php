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
     * @return bool
     */
    protected function updateOrderItems(): bool
    {
        //====================================================================//
        // Check if Order Items Updates are Allowed
        if (!StatusTransformer::isAllowedUpdates($this->object->state)) {
            if (!$this->connector->isSandbox()) {
                return Splash::log()->err("Order Items are not allowed");
            }
        }
        $result = true;
        //====================================================================//
        // Update All Order Items Sources
        $this->object->updateItemsSources();
        //====================================================================//
        // Create All Inserted Order Items
        foreach ($this->object->getInsertedItems() as $newItem) {
            $result = $result && $this->createOrderItem($newItem);
        }
        //====================================================================//
        // Update All Modified Order Items
        foreach ($this->object->getUpdatedItems() as $upItem) {
            $result = $result && $this->updateOrderItem($upItem);
        }
        //====================================================================//
        // Delete All Removed Order Items
        foreach ($this->object->getDeletedItems() as $delItem) {
            $result = $result && $this->updateOrderItem($delItem->setDeleted());
        }

        return $result;
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
        Splash::log()->war("Order Items Computed");

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
        if (!$item->isNew() || !$item->isValid()) {
            return Splash::log()->err(
                sprintf("Invalid New %s", $item)
            );
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
            return Splash::log()->err(
                sprintf("Invalid Order Item %s", $item->getId())
            );
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
                sprintf("Unable to update Order Item %s", $item->getId())
            );
        }

        return Splash::log()->msg(sprintf("Order Item %s Updated", $item->getId()));
    }
}
