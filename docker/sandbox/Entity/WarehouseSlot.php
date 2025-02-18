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
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class representing Product Warehouse Slot model.
 */
#[ORM\Entity]
#[ORM\Table(name: "warehouse_slot")]
#[ORM\HasLifecycleCallbacks]
#[ApiResource(
    operations: array(
        new Meta\GetCollection(),
        new Meta\Post(),
        new Meta\Delete(),
    ),
    normalizationContext: array("groups" => array("read")),
    denormalizationContext: array("groups" => array("write")),
)]
class WarehouseSlot implements SboObjectInterface
{
    //====================================================================//
    // SBO CORE DATA
    use Core\SboCoreTrait;

    /**
     * Slot Name
     */
    #[Assert\NotNull]
    #[Assert\Type("string")]
    #[ORM\Column(type: Types::STRING, length: 255, nullable: false)]
    #[Groups(array("read", "write"))]
    public string $name;

    /**
     * Picking Disabled Flag
     */
    #[ORM\Column(type: Types::BOOLEAN)]
    #[Assert\Type("boolean")]
    #[Groups(array("read", "write"))]
    public bool $pickingDisabled = false;

    /**
     * Slot Priority
     */
    #[Assert\NotNull]
    #[ORM\Column(type: Types::INTEGER)]
    #[Assert\Type("integer")]
    #[Groups(array("read", "write"))]
    public ?int $priority = 0;

    /**
     * Stock Disabled Flag
     */
    #[ORM\Column(type: Types::BOOLEAN)]
    #[Assert\Type("boolean")]
    #[Groups(array("read", "write"))]
    public bool $stockDisabled = false;

    /**
     * Zone Name
     */
    #[Assert\Type("string")]
    #[ORM\Column(type: Types::STRING, nullable: true)]
    #[Groups(array("read", "write"))]
    public ?string $warehouseZoneName;

    /**
     * Warehouse Slot Contents
     *
     * @var SlotContents[]
     */
    #[Assert\All(array(
        new Assert\Type(SlotContents::class),
    ))]
    #[ORM\OneToMany(mappedBy: "warehouseSlot", targetEntity: SlotContents::class, cascade: array("all"))]
    #[Groups(array("read"))]
    public $slotContents;

    //====================================================================//
    // JSON SERIALIZER
    //====================================================================//

    /**
     * {@inheritDoc}
     */
    public static function getItemIndex(): string
    {
        return "warehouse_slots";
    }

    /**
     * {@inheritDoc}
     */
    public static function getCollectionIndex(): string
    {
        return "warehouse_slots";
    }
}
