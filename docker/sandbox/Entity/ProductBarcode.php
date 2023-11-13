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

use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata as Meta;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class representing the Product Barcode model.
 *
 * @ORM\Entity()
 *
 * @ORM\Table(name="product_barcodes")
 *
 * @ORM\HasLifecycleCallbacks()
 */
#[ApiResource(
    operations: array(
        new Meta\GetCollection(
        ),
        new Meta\Post(),
        new Meta\Patch(),
        new Meta\Delete(),
    ),
    normalizationContext: array("groups" => array("read")),
    denormalizationContext: array("groups" => array("write"))
)]
class ProductBarcode implements SboObjectInterface
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
     * Parent Product
     *
     * @ORM\Column()
     *
     * @Assert\NotNull()
     *
     * @Assert\Type("int")
     *
     * @Groups({"read", "write"})
     */
    #[ApiFilter(SearchFilter::class, strategy: 'exact')]
    public int $product_id;

    //====================================================================//
    // PRODUCT BARCODE INFO
    //====================================================================//

    /**
     * Attribute Key.
     *
     * @var string
     *
     * @ORM\Column()
     *
     * @Assert\NotNull()
     *
     * @Assert\Type("string")
     *
     * @Groups({"read", "write"})
     */
    public string $key = "ean";

    /**
     * Attribute Value.
     *
     * @var string
     *
     * @ORM\Column()
     *
     * @Assert\NotNull()
     *
     * @Assert\Type("string")
     *
     * @ORM\Column()
     *
     * @Groups({"read", "write"})
     */
    public string $value;

    //====================================================================//
    // JSON SERIALIZER
    //====================================================================//

    /**
     * {@inheritDoc}
     */
    public static function getItemIndex(): ?string
    {
        return "product_barcodes";
    }

    /**
     * {@inheritDoc}
     */
    public static function getCollectionIndex(): ?string
    {
        return "product_barcodes";
    }
}
