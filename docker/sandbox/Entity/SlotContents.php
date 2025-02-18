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
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class representing Product Warehouse Slot Content model.
 */
#[ORM\Entity]
#[ORM\Table(name: "slot_contents")]
#[ORM\HasLifecycleCallbacks]
#[ApiResource(
    operations: array(
        new Meta\GetCollection(),
        new Meta\Get(),
        new Meta\Post(),
        new Meta\Post(
            uriTemplate: '/slot_stock_variations',
            controller: 'App\Controller\SlotStockVariationController::createAction',
        ),
        new Meta\Delete()
    ),
    normalizationContext: array("groups" => array("read")),
    denormalizationContext: array("groups" => array("write"))
)]
class SlotContents implements SboObjectInterface
{
    /**
     * Unique Identifier.
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    #[Assert\Type("integer")]
    #[Groups(array("read"))]
    public int $id;

    /**
     * Product ID
     */
    #[Assert\NotNull]
    #[Assert\Type("integer")]
    #[ORM\Column(type: Types::INTEGER)]
    #[Groups(array("read", "write"))]
    public int $productId;

    /**
     * Warehouse Slot ID
     */
    #[Assert\NotNull]
    #[Assert\Type("integer")]
    #[ORM\Column(type: Types::INTEGER)]
    #[Groups(array("read", "write"))]
    public int $warehouseSlotId;

    /**
     * Quantity
     */
    #[Assert\NotNull]
    #[ORM\Column(type: Types::INTEGER)]
    #[Assert\Type("integer")]
    #[Groups(array("read", "write"))]
    public int $stock = 0;

    /**
     * Parent Warehouse Slot
     */
    #[ORM\ManyToOne(targetEntity: WarehouseSlot::class, inversedBy: "slotContents")]
    #[Groups(array("read"))]
    public WarehouseSlot $warehouseSlot;

    //====================================================================//
    // Warehouse Slot LINK UPDATE
    //====================================================================//

    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function updateWarehouseSlot(LifecycleEventArgs $event): void
    {
        //====================================================================//
        // Check if Changed
        $current = $this->warehouseSlot->id ?? null;
        $new = $this->warehouseSlotId ?? 1;
        if ($current && $new && ($current == $new)) {
            return;
        }

        // Identify New
        $warehouseSlot = $event->getObjectManager()->getRepository(WarehouseSlot::class)->find($new);
        if (!$warehouseSlot) {
            throw new NotFoundHttpException(
                sprintf("Target Warehouse Slot %s not found", $new)
            );
        }
        //====================================================================//
        // Update
        $this->warehouseSlot = $warehouseSlot;
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
