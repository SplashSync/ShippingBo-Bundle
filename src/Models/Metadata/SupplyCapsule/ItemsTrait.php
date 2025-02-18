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

namespace Splash\Connectors\ShippingBo\Models\Metadata\SupplyCapsule;

use JMS\Serializer\Annotation as JMS;
use Splash\Connectors\ShippingBo\Models\Metadata\SupplyCapsuleItem;
use Splash\Metadata\Attributes as SPL;
use Symfony\Component\Validator\Constraints as Assert;

trait ItemsTrait
{
    /**
     * Supplier Order Items Lines.
     *
     * @var SupplyCapsuleItem[]
     */
    #[
        Assert\All(array(
            new Assert\Type(SupplyCapsuleItem::class)
        )),
        JMS\SerializedName("supply_capsule_items"),
        JMS\Groups(array("Read", "Write", "Required")),
        JMS\Accessor(getter: "getValidCapsuleItem"),
        SPL\ListResource(targetClass: SupplyCapsuleItem::class),
        SPL\Accessor(factory: "createCapsuleItem"),
        SPL\IsRequired,
    ]
    public array $supplyCapsuleItems = array();

    /**
     * Original Supplier Order Items Lines.
     *
     * @var SupplyCapsuleItem[]
     */
    #[JMS\Exclude]
    public array $oldSupplyCapsuleItems = array();

    /**
     * On Post Deserialize, Archive Original Items for Update Comparaisons
     */
    #[JMS\PostDeserialize]
    public function archiveOriginalItems(): void
    {
        $this->oldSupplyCapsuleItems = array();
        foreach ($this->supplyCapsuleItems as $item) {
            if (empty($item->id)) {
                continue;
            }
            $this->oldSupplyCapsuleItems[(string) $item->id] = clone $item;
        }
    }

    /**
     * Update Order Items Source & Source Ref.
     *
     * @return void
     */
    public function updateItemsSources(): void
    {
        foreach ($this->supplyCapsuleItems as &$upItem) {
            $upItem->source = $this->source;
        }
    }

    /**
     * Get Original Supply Capsule Item Md5
     */
    public function getOriginalItemMd5(string $itemId): ?string
    {
        $originalItem = $this->getOriginalItem($itemId);

        return $originalItem?->getMd5();
    }

    /**
     * Delete Original Order Item
     */
    public function deleteOriginalItem(string $itemId): void
    {
        if ($this->getOriginalItem($itemId)) {
            unset($this->oldSupplyCapsuleItems[$itemId]);
        }
    }

    /**
     * Create a New Supply Capsule Item
     */
    public function createCapsuleItem(): SupplyCapsuleItem
    {
        return  new SupplyCapsuleItem();
    }

    /**
     * Only Validated Supply Capsule Items
     *
     * @return SupplyCapsuleItem[]
     */
    public function getValidCapsuleItem(): array
    {
        $capsuleItems = array();
        foreach ($this->supplyCapsuleItems as $capsuleItem) {
            //====================================================================//
            // Validate Class
            if (!($capsuleItem instanceof SupplyCapsuleItem)) {
                continue;
            }
            //====================================================================//
            // Product
            if (empty($capsuleItem->productRef) || empty($capsuleItem->quantity)) {
                continue;
            }
            $capsuleItems[] = $capsuleItem;
        }

        return $capsuleItems;
    }

    /**
     * Get Original Order Item
     */
    private function getOriginalItem(string $itemId): ?SupplyCapsuleItem
    {
        return $this->oldSupplyCapsuleItems[$itemId] ?? null;
    }
}
