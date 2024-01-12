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
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class representing the Product Pack Component model.
 *
 * @ORM\Entity()
 *
 * @ORM\Table(name="product_component")
 *
 * @ORM\HasLifecycleCallbacks()
 */
#[ApiResource(
    operations: array(),
    normalizationContext: array("groups" => array("read")),
    denormalizationContext: array("groups" => array("write")),
)]
class PackComponent implements SboObjectInterface
{
    //====================================================================//
    // SBO CORE DATA
    use Core\SboCoreTrait;

    /**
     * Parent Product
     *
     * @Assert\Type("App\Entity\Product")
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Product", inversedBy="packComponents")
     */
    public Product $product;

    /**
     * Products Quantities.
     *
     * @var int
     *
     * @Assert\NotNull()
     *
     * @Assert\Type("integer")
     *
     * @ORM\Column(type="integer")
     *
     * @Groups({"read", "write"})
     */
    public int $quantity;

    /**
     * Child Product Unique Identifier.
     *
     * @var int
     *
     * @Assert\Type("integer")
     *
     * @ORM\Column()
     *
     * @Groups({"read"})
     */
    public int $component_product_id;

    //====================================================================//
    // DATA FAKER
    //====================================================================//

    /**
     * Pack Components Faker
     */
    public static function fake(Product $product): PackComponent
    {
        $packComponent = new self();
        $packComponent->product = $product;
        $packComponent->quantity = rand(1, 10);
        $packComponent->component_product_id = rand(1000, 10000);

        return $packComponent;
    }

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
