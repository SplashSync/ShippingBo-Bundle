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

namespace Splash\Connectors\ShippingBo\Models\Api\Order;

use JMS\Serializer\Annotation as JMS;
use Splash\Connectors\ShippingBo\Models\Api\OrderItem;
use Splash\OpenApi\Validator as SPL;
use Symfony\Component\Validator\Constraints as Assert;

trait OrderItemsTrait
{
    /**
     * Order Items List.
     *
     * @var OrderItem[]
     *
     * @JMS\SerializedName("order_items")
     *
     * @JMS\Type("array<Splash\Connectors\ShippingBo\Models\Api\OrderItem>")
     *
     * @JMS\Groups ({"Read"})
     *
     * @Assert\All({
     *
     *   @Assert\Type("Splash\Connectors\ShippingBo\Models\Api\OrderItem")
     * })
     *
     * @SPL\Group("Items")
     */
    public array $items = array();

    /**
     * Order Original Items List.
     *
     * @var OrderItem[]
     */
    private array $oldItems = array();

    /**
     * @JMS\PostDeserialize
     *
     * @return void
     */
    public function archiveOriginalItems(): void
    {
        $this->oldItems = array();
        foreach ($this->items as $item) {
            $this->oldItems[(string) $item->getId()] = clone $item;
        }
        $this->fillGhostItems();
    }

    /**
     * Fill gaps in items positions with ghost placeholders so that
     * setApiListFields's array_shift naturally aligns with Splash positions.
     * A gap = a position that existed on a previous push but was not created
     * on the API side (e.g. a filtered empty line).
     */
    public function fillGhostItems(): void
    {
        $byPosition = array();
        $orphans = array();
        $maxPosition = 0;
        //====================================================================//
        // Index items by the position encoded in source_ref.
        // Items with a non-conforming source_ref are kept aside as orphans so
        // they never collide with the position grid nor get silently dropped.
        foreach ($this->items as $item) {
            if (!preg_match('/-(\d+)$/', (string) $item->source_ref, $matches)) {
                $orphans[] = $item;

                continue;
            }
            $position = (int) $matches[1];
            $byPosition[$position] = $item;
            $maxPosition = max($maxPosition, $position);
        }
        //====================================================================//
        // Rebuild items list — insert a ghost at every missing position
        $rebuilt = array();
        for ($pos = 1; $pos <= $maxPosition; $pos++) {
            if (isset($byPosition[$pos])) {
                $rebuilt[] = $byPosition[$pos];

                continue;
            }
            $ghost = new OrderItem();
            $ghost->quantity = 0;
            $ghost->source_ref = $this->source_ref."-".$pos;
            $rebuilt[] = $ghost;
        }
        //====================================================================//
        // Orphans are appended at the end so they never shift the slot grid.
        $this->items = array_merge($rebuilt, $orphans);
    }

    /**
     * Update Order Items Source & Source Ref.
     *
     * @return void
     */
    public function updateItemsSources(): void
    {
        foreach ($this->items as $index => &$upItem) {
            $upItem->source = $this->source;
            //====================================================================//
            // source_ref is frozen by ShippingBo after item creation.
            // Only assign it to brand new items; existing items keep their
            // API-stored value.
            if ($upItem->isNew()) {
                $upItem->source_ref = $this->source_ref."-".($index + 1);
            }
        }
    }

    /**
     * Get List of Just Created Order Items
     *
     * @return OrderItem[]
     */
    public function getInsertedItems(): array
    {
        $inserted = array();
        foreach ($this->items as $item) {
            if ($item->isNew() && $item->isValid()) {
                $inserted[] = $item;
            }
        }

        return $inserted;
    }

    /**
     * Get List of Updated Order Items
     *
     * @return OrderItem[]
     */
    public function getUpdatedItems(): array
    {
        $updated = array();
        //====================================================================//
        // Walk on All Order Items
        foreach ($this->items as $item) {
            //====================================================================//
            // NEW or INVALID
            if ($item->isNew() || !$item->isValid()) {
                continue;
            }
            //====================================================================//
            // NO CHANGES
            if ($item->getMd5() == $this->getOriginalItemMd5((string) $item->getId())) {
                continue;
            }
            $updated[] = $item;
        }

        return $updated;
    }

    /**
     * Get List of Deleted Order Items
     *
     * @return OrderItem[]
     */
    public function getDeletedItems(): array
    {
        $existing = array();
        $deleted = array();
        //====================================================================//
        // Walk on All Order Items
        foreach ($this->items as $item) {
            if ($item->isValid()) {
                $existing[] = $item->getId();
            }
        }
        //====================================================================//
        // Walk on All Original Order Items
        foreach ($this->oldItems as $oldItem) {
            //====================================================================//
            // Already-deleted tombstones stay as-is, no re-PATCH qty=0 needed.
            if ($oldItem->isDeleted()) {
                continue;
            }
            //====================================================================//
            // Orphans (source_ref off-pattern) never belong to our slot grid:
            // they must never be deleted by this connector.
            if (!preg_match('/-(\d+)$/', (string) $oldItem->source_ref)) {
                continue;
            }
            if (!in_array($oldItem->getId(), $existing, true)) {
                $deleted[] = $oldItem;
            }
        }

        return $deleted;
    }

    /**
     * Get Original Order Item
     *
     * @param string $itemId
     *
     * @return null|OrderItem
     */
    private function getOriginalItem(string $itemId): ?OrderItem
    {
        return $this->oldItems[$itemId] ?? null;
    }

    /**
     * Get Original Order Item Md5
     *
     * @param string $itemId
     *
     * @return null|string
     */
    private function getOriginalItemMd5(string $itemId): ?string
    {
        $orderItem = $this->getOriginalItem($itemId);

        return $orderItem ? $orderItem->getMd5() : null;
    }
}
