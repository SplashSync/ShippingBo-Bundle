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
 * Class representing the Product model.
 *
 * @ORM\Entity()
 *
 * @ORM\Table(name="order_items")
 *
 * @ORM\HasLifecycleCallbacks()
 */
#[ApiResource(
    operations: array(
        new Meta\Get(),
        new Meta\Patch(),
        new Meta\Delete(),
    ),
    normalizationContext: array("groups" => array("read")),
    denormalizationContext: array("groups" => array("write"))
)]
#[ORM\Entity]
#[ORM\Table(name: "order_items")]
#[ORM\HasLifecycleCallbacks]
class OrderItem implements SboObjectInterface
{
    use Core\SboCoreTrait;
    use Core\SboSourceTrait;

    /**
     * Parent Order
     */
    #[ORM\ManyToOne(targetEntity: Order::class, inversedBy: "orderItems")]
    public Order $order;

    //====================================================================//
    // ORDER PRODUCT INFO
    //====================================================================//

    /**
     * Product Name.
     */
    #[Assert\NotNull]
    #[Assert\Type("string")]
    #[ORM\Column]
    #[Groups(array("read", "write"))]
    public string $title;

    /**
     * Product SKU.
     */
    #[Assert\NotNull]
    #[Assert\Type("string")]
    #[ORM\Column]
    #[Groups(array("read", "write"))]
    public string $productRef;

    /**
     * Product EAN 13.
     */
    #[Assert\Type("string")]
    #[ORM\Column(nullable: true)]
    #[Groups(array("read", "write"))]
    public ?string $productEan = null;

    //====================================================================//
    // ORDER ITEM QTY
    //====================================================================//

    /**
     * Ordered Quantities.
     */
    #[Assert\NotNull]
    #[Assert\Type("integer")]
    #[ORM\Column(type: Types::INTEGER)]
    #[Groups(array("read", "write"))]
    public int $quantity;

    //====================================================================//
    // SBO PRICE PROPERTIES
    //====================================================================//

    /**
     * Order Line Items Price Tax Included.
     */
    #[Assert\Type("integer")]
    #[ORM\Column(type: Types::INTEGER, nullable: true)]
    #[Groups(array("read", "write"))]
    public ?int $priceTaxIncludedCents = null;

    /**
     * Order Line Items Price Currency.
     */
    #[Assert\NotNull]
    #[Assert\Type("string")]
    #[ORM\Column(type: Types::STRING, nullable: true)]
    #[Groups(array("read", "write"))]
    public string $priceTaxIncludedCurrency;

    /**
     * Order Line Items Price Tax Excluded.
     */
    #[Assert\Type("integer")]
    #[ORM\Column(type: Types::INTEGER, nullable: true)]
    #[Groups(array("read", "write"))]
    public ?int $priceCents = null;

    /**
     * Order Line Items Price Tax Amount.
     */
    #[Assert\Type("integer")]
    #[ORM\Column(type: Types::INTEGER, nullable: true)]
    #[Groups(array("read", "write"))]
    public ?int $taxCents = null;

    //====================================================================//
    // JSON SERIALIZER
    //====================================================================//

    /**
     * {@inheritDoc}
     */
    public static function getItemIndex(): ?string
    {
        return null;
    }

    /**
     * {@inheritDoc}
     */
    public static function getCollectionIndex(): ?string
    {
        return null;
    }
}
