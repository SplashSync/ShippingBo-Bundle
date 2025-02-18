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
use App\Controller\SupplyCapsule as Controllers;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Represents the SupplyCapsule Item entity.
 */
#[ORM\Entity()]
#[ORM\Table("supply_capsules_items")]
#[ORM\HasLifecycleCallbacks]
#[ApiResource(
    operations: array(
        new Meta\GetCollection(),
        new Meta\Post(
            controller: Controllers\AddItem::class,
        ),
        new Meta\Delete(),
    ),
    normalizationContext: array('groups' => array('read')),
    denormalizationContext: array('groups' => array('write'))
)]
class SupplyCapsuleItem implements SboObjectInterface
{
    use Core\SboCoreTrait;
    use Core\SboSourceTrait;

    /**
     * Parent Supply Capsule
     */
    #[ORM\ManyToOne(targetEntity: SupplyCapsule::class, inversedBy: "supplyCapsuleItems")]
    public SupplyCapsule $order;

    /**
     * Product ID.
     */
    #[Assert\Type("integer")]
    #[ORM\Column(type: Types::INTEGER, nullable: true)]
    #[Groups(array('read'))]
    public ?int $productId = null;

    /**
     * Product SKU.
     */
    #[Assert\Type("string")]
    #[ORM\Column(type: Types::STRING)]
    #[Groups(array('read', 'write'))]
    #[Meta\ApiProperty(default: "sku1234")]
    public ?string $productRef = null;

    /**
     * Product Identified SKU.
     */
    #[Assert\Type("string")]
    #[ORM\Column(type: Types::STRING, nullable: true)]
    #[Groups(array('read'))]
    public ?string $productUserRef = null;

    /**
     * Product EAN 13.
     */
    #[Assert\Type("string")]
    #[ORM\Column(type: Types::STRING, nullable: true)]
    #[Groups(array('read', 'write'))]
    public ?string $productEan = null;

    /**
     * Expected Quantity.
     */
    #[Assert\NotNull]
    #[Assert\Type("integer")]
    #[ORM\Column(type: Types::INTEGER)]
    #[Groups(array('read', 'write'))]
    public int $quantity = 0;

    /**
     * Received Quantity.
     */
    #[Assert\NotNull]
    #[Assert\Type("integer")]
    #[ORM\Column(type: Types::INTEGER)]
    #[Groups(array('read', 'write'))]
    public int $receivedQuantity = 0;

    /**
     * @inheritDoc
     */
    public static function getItemIndex(): ?string
    {
        return "supply_capsule_item";
    }

    /**
     * @inheritDoc
     */
    public static function getCollectionIndex(): ?string
    {
        return "supply_capsule_items";
    }
}
