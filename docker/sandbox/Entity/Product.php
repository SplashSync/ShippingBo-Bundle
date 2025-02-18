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
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class representing the Product model.
 */
#[ApiResource(
    normalizationContext: array("groups" => array("read")),
    denormalizationContext: array("groups" => array("write"))
)]
#[ApiFilter(SearchFilter::class, properties: array('userRef' => 'exact'))]
#[ORM\Entity]
#[ORM\HasLifecycleCallbacks]
class Product implements SboObjectInterface
{
    use Core\SboCoreTrait;
    use Core\SboSourceTrait;

    //====================================================================//
    // CORE ATTRIBUTES
    //====================================================================//

    #[Assert\Type('string')]
    #[Groups(array('read', 'write'))]
    #[ORM\Column(type: Types::STRING)]
    public ?string $userRef;

    #[Assert\NotNull]
    #[Assert\Type('int')]
    #[Groups(array('read'))]
    #[ORM\Column(type: Types::INTEGER)]
    public int $stock = 0;

    /**
     * Stock Variations.
     *
     * @var StockVariation[]
     */
    #[ORM\OneToMany(mappedBy: 'product', targetEntity: StockVariation::class)]
    public $stockVariations;

    #[Assert\Type('int')]
    #[Groups(array('read', 'write'))]
    #[ORM\Column(type: Types::INTEGER, nullable: true)]
    public ?int $ean13;

    #[Assert\Type('string')]
    #[Groups(array('read', 'write'))]
    #[ORM\Column(type: Types::STRING, nullable: true)]
    public ?string $title;

    #[Assert\Type('string')]
    #[Groups(array('read', 'write'))]
    #[ORM\Column(type: Types::STRING, nullable: true)]
    public ?string $location;

    #[Assert\Type('string')]
    #[Groups(array('read', 'write'))]
    #[ORM\Column(type: Types::STRING, nullable: true)]
    public ?string $hsCode;

    #[Assert\Type('string')]
    #[Groups(array('read', 'write'))]
    #[ORM\Column(type: Types::STRING, nullable: true)]
    public ?string $supplier;

    #[Assert\Type('string')]
    #[Groups(array('read', 'write'))]
    #[ORM\Column(type: Types::STRING, nullable: true)]
    public ?string $pictureUrl;

    //====================================================================//
    // DIMENSIONS
    //====================================================================//

    #[Assert\Type('int')]
    #[Groups(array('read', 'write'))]
    #[ORM\Column(type: Types::INTEGER, nullable: true)]
    public ?int $weight;

    #[Assert\Type('int')]
    #[Groups(array('read', 'write'))]
    #[ORM\Column(type: Types::INTEGER, nullable: true)]
    public ?int $height;

    #[Assert\Type('int')]
    #[Groups(array('read', 'write'))]
    #[ORM\Column(type: Types::INTEGER, nullable: true)]
    public ?int $length;

    #[Assert\Type('int')]
    #[Groups(array('read', 'write'))]
    #[ORM\Column(type: Types::INTEGER, nullable: true)]
    public ?int $width;

    //====================================================================//
    // USER EXTRA REFS
    //====================================================================//

    #[Assert\Type('string')]
    #[Groups(array('read', 'write'))]
    #[ORM\Column(type: Types::STRING, nullable: true)]
    public ?string $otherRef1;

    #[Assert\Type('string')]
    #[Groups(array('read', 'write'))]
    #[ORM\Column(type: Types::STRING, nullable: true)]
    public ?string $otherRef2;

    #[Assert\Type('string')]
    #[Groups(array('read', 'write'))]
    #[ORM\Column(type: Types::STRING, nullable: true)]
    public ?string $otherRef3;

    #[Assert\Type('string')]
    #[Groups(array('read', 'write'))]
    #[ORM\Column(type: Types::STRING, nullable: true)]
    public ?string $otherRef4;

    #[Assert\Type('string')]
    #[Groups(array('read', 'write'))]
    #[ORM\Column(type: Types::STRING, nullable: true)]
    public ?string $otherRef5;

    #[Assert\Type('string')]
    #[Groups(array('read', 'write'))]
    #[ORM\Column(type: Types::STRING, nullable: true)]
    public ?string $otherRef6;

    #[Assert\Type('string')]
    #[Groups(array('read', 'write'))]
    #[ORM\Column(type: Types::STRING, nullable: true)]
    public ?string $otherRef7;

    #[Assert\Type('string')]
    #[Groups(array('read', 'write'))]
    #[ORM\Column(type: Types::STRING, nullable: true)]
    public ?string $otherRef8;

    #[Assert\Type('string')]
    #[Groups(array('read', 'write'))]
    #[ORM\Column(type: Types::STRING, nullable: true)]
    public ?string $otherRef9;

    #[Assert\Type('string')]
    #[Groups(array('read', 'write'))]
    #[ORM\Column(type: Types::STRING, nullable: true)]
    public ?string $otherRef10;

    #[Assert\Type('string')]
    #[Groups(array('read', 'write'))]
    #[ORM\Column(type: Types::STRING, nullable: true)]
    public ?string $otherRef11;

    #[Assert\Type('string')]
    #[Groups(array('read', 'write'))]
    #[ORM\Column(type: Types::STRING, nullable: true)]
    public ?string $otherRef12;

    #[Assert\Type('string')]
    #[Groups(array('read', 'write'))]
    #[ORM\Column(type: Types::STRING, nullable: true)]
    public ?string $otherRef13;

    #[Assert\Type('string')]
    #[Groups(array('read', 'write'))]
    #[ORM\Column(type: Types::STRING, nullable: true)]
    public ?string $otherRef14;

    #[Assert\Type('string')]
    #[Groups(array('read', 'write'))]
    #[ORM\Column(type: Types::STRING, nullable: true)]
    public ?string $otherRef15;

    //====================================================================//
    // ASSOCIATIONS
    //====================================================================//

    /**
     * Pack Components
     *
     * @var PackComponent[]
     */
    #[Assert\All(array(
        new Assert\Type(PackComponent::class)
    ))]
    #[Groups(array('read'))]
    #[ORM\OneToMany(mappedBy: 'product', targetEntity: PackComponent::class, cascade: array('all'))]
    public $packComponents;

    public function __construct()
    {
        $this->packComponents = new ArrayCollection(array(
            PackComponent::fake($this),
            PackComponent::fake($this),
        ));
    }

    //====================================================================//
    // STOCKS READING
    //====================================================================//

    public function getStock(): int
    {
        $stock = 0;
        foreach ($this->stockVariations as $stockVariation) {
            $stock += $stockVariation->variation;
        }

        return $stock;
    }

    //====================================================================//
    // JSON SERIALIZER
    //====================================================================//

    /**
     * {@inheritDoc}
     */
    public static function getItemIndex(): string
    {
        return "product";
    }

    /**
     * {@inheritDoc}
     */
    public static function getCollectionIndex(): string
    {
        return "products";
    }
}
