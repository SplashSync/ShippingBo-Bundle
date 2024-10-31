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
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class representing Product Warehouse Slot model.
 *
 * @ORM\Entity()
 *
 * @ORM\Table(name="warehouse_slot")
 *
 * @ORM\HasLifecycleCallbacks()
 */
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
     *
     * @Assert\NotNull()
     *
     * @Assert\Type("string")
     *
     * @ORM\Column(type="string")
     *
     * @Groups({"read", "write"})
     */
    public string $name;

    /**
     * Picking Disabled Flag
     *
     * @var bool
     *
     * @ORM\Column(type="boolean")
     *
     * @Assert\Type("boolean")
     *
     * @Groups({"read", "write"})
     */
    public bool $picking_disabled = false;

    /**
     * Slot Priority
     *
     * @var null|int
     *
     * @Assert\NotNull()
     *
     * @ORM\Column(type="integer")
     *
     * @Assert\Type("integer")
     *
     * @Groups({"read", "write"})
     */
    public ?int $priority = 0;

    /**
     * Stock Disabled Flag
     *
     * @var bool
     *
     * @ORM\Column(type="boolean")
     *
     * @Assert\Type("boolean")
     *
     * @Groups({"read", "write"})
     */
    public bool $stock_disabled = false;

    /**
     * Zone Name
     *
     * @Assert\Type("string")
     *
     * @ORM\Column(type="string", nullable=true)
     *
     * @Groups({"read", "write"})
     */
    public ?string $warehouse_zone_name;

    /**
     * Warehouse Slot Contents
     *
     * @var SlotContents[]
     *
     * @Assert\All({
     *
     *    @Assert\Type("App\Entity\SlotContents")
     * })
     *
     * @ORM\OneToMany(targetEntity="App\Entity\SlotContents", mappedBy="warehouse_slot", cascade={"all"})
     *
     * @Groups({"read"})
     */
    public $slot_contents;

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
