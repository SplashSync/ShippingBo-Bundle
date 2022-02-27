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

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class representing the Product model.
 *
 * @ApiResource(
 *     attributes={
 *          "normalization_context"={"groups"={"read"}},
 *          "denormalizationContext"={"groups"={"write"}}
 *     },
 *     itemOperations={
 *          "get":          {},
 *          "patch":        {},
 *          "delete":       {},
 *     },
 *     subresourceOperations={
 *     },
 *     collectionOperations={
 *          "create":      {
 *              "method": "POST",
 *              "path": "/orders/{id}/order_items",
 *              "controller": {"App\Controller\OrderController", "addItemAction"}
 *          },
 *          "post":      {
 *              "method": "POST",
 *              "path": "/orders/{id}/update_order_items",
 *              "controller": {"App\Controller\OrderController", "itemsAction"},
 *              "validate": false
 *          },
 *     },
 * )
 *
 * @ORM\Entity()
 * @ORM\Table(name="order_items")
 * @ORM\HasLifecycleCallbacks()
 */
class OrderItem implements SboObjectInterface
{
    //====================================================================//
    // SBO CORE DATA
    use Core\SboCoreTrait;
    use Core\SboSourceTrait;

    /**
     * Parent Order
     *
     * @var Order
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Order", inversedBy="order_items")
     */
    public Order $order;

    //====================================================================//
    // ORDER PRODUCT INFO
    //====================================================================//

    /**
     * Product Name.
     *
     * @var string
     *
     * @Assert\NotNull()
     * @Assert\Type("string")
     *
     * @ORM\Column()
     *
     * @Groups({"read", "write"})
     */
    public string $title;

    /**
     * Product SKU.
     *
     * @var string
     *
     * @Assert\NotNull()
     * @Assert\Type("string")
     *
     * @ORM\Column()
     *
     * @Groups({"read", "write"})
     */
    public string $product_ref;

    /**
     * Product EAN 13.
     *
     * @var null|string
     *
     * @Assert\Type("string")
     *
     * @ORM\Column(nullable=true)
     *
     * @Groups({"read", "write"})
     */
    public ?string $product_ean = null;

    //====================================================================//
    // ORDER ITEM QTY
    //====================================================================//

    /**
     * Ordered Quantities.
     *
     * @var int
     *
     * @Assert\NotNull()
     * @Assert\Type("integer")
     *
     * @ORM\Column(type="integer")
     *
     * @Groups({"read", "write"})
     */
    public int $quantity;

    //====================================================================//
    // SBO PRICE PROPERTIES
    //====================================================================//

    /**
     * Order Line Items Price Tax Included.
     *
     * @var null|int
     *
     * @Assert\Type("integer")
     *
     * @ORM\Column(type="integer", nullable=true)
     *
     * @Groups({"read", "write"})
     */
    public ?int $price_tax_included_cents;

    /**
     * Order Line Items Price Currency.
     *
     * @var string
     *
     * @Assert\NotNull()
     * @Assert\Type("string")
     *
     * @ORM\Column(type="string", nullable=true)
     *
     * @Groups({"read", "write"})
     */
    public string $price_tax_included_currency;

    /**
     * Order Line Items Price Tax Excluded.
     *
     * @var null|int
     *
     * @Assert\Type("integer")
     *
     * @ORM\Column(type="integer", nullable=true)
     *
     * @Groups({"read", "write"})
     */
    public ?int $price_cents = null;

    /**
     * Order Line Items Price Tax Amount.
     *
     * @var null|int
     *
     * @Assert\Type("integer")
     *
     * @ORM\Column(type="integer", nullable=true)
     *
     * @Groups({"read", "write"})
     */
    public ?int $tax_cents = null;

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
