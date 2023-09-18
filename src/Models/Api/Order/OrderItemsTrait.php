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
        foreach ($this->items as $index => $item) {
            if ($item->isDeleted()) {
                unset($this->items[$index]);

                continue;
            }
            $this->oldItems[(string) $item->getId()] = clone $item;
        }
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
            $upItem->source_ref = $this->source_ref."-".($index + 1);
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
            $existing[] = $item->getId();
        }
        //====================================================================//
        // Walk on All Original Order Items
        foreach ($this->oldItems as $oldItem) {
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
