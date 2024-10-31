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

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata as Meta;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class representing Product Warehouse Slot Content model.
 *
 * @ORM\Entity()
 *
 * @ORM\Table(name="slot_contents")
 *
 * @ORM\HasLifecycleCallbacks()
 */
#[ApiResource(
    operations: array(
        new Meta\GetCollection(),
        new Meta\Get(),
        new Meta\Post(),
        new Meta\Post(
            uriTemplate:    '/slot_stock_variations',
            controller:     'App\Controller\SlotStockVariationController::createAction',
        ),
        new Meta\Delete(),
    ),
    normalizationContext: array("groups" => array("read")),
    denormalizationContext: array("groups" => array("write"))
)]
class SlotContents implements SboObjectInterface
{
    /**
     * Unique Identifier.
     *
     * @var int
     *
     * @ORM\Id
     *
     * @ORM\GeneratedValue
     *
     * @ORM\Column(type="integer")
     *
     * @Assert\Type("integer")
     *
     * @Groups({"read"})
     */
    public int $id;

    /**
     * Product ID
     *
     * @Assert\NotNull()
     *
     * @Assert\Type("integer")
     *
     * @ORM\Column(type="integer")
     *
     * @Groups({"read", "write"})
     */
    public int $product_id;

    /**
     * Warehouse Slot ID
     *
     * @Assert\NotNull()
     *
     * @Assert\Type("integer")
     *
     * @ORM\Column(type="integer")
     *
     * @Groups({"read", "write"})
     */
    public int $warehouse_slot_id;

    /**
     * Quantity
     *
     * @Assert\NotNull()
     *
     * @ORM\Column(type="integer")
     *
     * @Assert\Type("integer")
     *
     * @Groups({"read", "write"})
     */
    public int $stock = 0;

    /**
     * Parent Warehouse Slot
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\WarehouseSlot", inversedBy="slot_contents")
     *
     * @Groups({"read"})
     */
    public WarehouseSlot $warehouse_slot;

    //====================================================================//
    // Warehouse Slot LINK UPDATE
    //====================================================================//

    /**
     * @ORM\PrePersist()
     *
     * @ORM\PreUpdate()
     */
    public function updateWarehouseSlot(LifecycleEventArgs $event): void
    {
        //====================================================================//
        // Check if Changed
        $current = $this->warehouse_slot->id ?? null;
        $new = $this->warehouse_slot_id ?? 1;
        if ($current && $new && ($current == $new)) {
            return;
        }
        //====================================================================//
        // Identify New
        $warehouseSlot = $event->getObjectManager()->getRepository(WarehouseSlot::class)->find($new);
        if (!$warehouseSlot) {
            throw new NotFoundHttpException(
                sprintf("Target Warehouse Slot %s not found", $new)
            );
        }
        //====================================================================//
        // Update
        $this->warehouse_slot = $warehouseSlot;
    }

    //====================================================================//
    // JSON SERIALIZER
    //====================================================================//

    /**
     * {@inheritDoc}
     */
    public static function getItemIndex(): string
    {
        return "slot_contents";
    }

    /**
     * {@inheritDoc}
     */
    public static function getCollectionIndex(): string
    {
        return "slot_contents";
    }
}
