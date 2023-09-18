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
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class representing the Product model.
 *
 * @ORM\Entity
 *
 * @ORM\HasLifecycleCallbacks()
 */
#[ApiResource()]
#[ApiFilter(SearchFilter::class, properties: array('user_ref' => 'exact'))]
class Product implements SboObjectInterface
{
    use Core\SboCoreTrait;
    use Core\SboSourceTrait;

    /**
     * Product SKU.
     *
     * @var string
     *
     * @Assert\NotNull()
     *
     * @Assert\Type("string")
     *
     * @Groups({"read"})
     *
     * @ORM\Column(type="string")
     */
    public string $user_ref;

    /**
     * Available Stock.
     *
     * @Assert\NotNull()
     *
     * @Assert\Type("int")
     *
     * @Groups({"read"})
     *
     * @ORM\Column(type="integer")
     */
    public int $stock = 0;

    /**
     * Stock Variations.
     *
     * @var StockVariation[]
     *
     * @ORM\OneToMany(targetEntity="App\Entity\StockVariation", mappedBy="product")
     */
    public $stockVariations;

    /**
     * Product EAN13.
     *
     * @var null|int
     *
     * @Assert\Type("int")
     *
     * @Groups({"read"})
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    public ?int $ean13;

    /**
     * Title / Label.
     *
     * @var null|string
     *
     * @Assert\Type("string")
     *
     * @Groups({"read"})
     *
     * @ORM\Column(type="string", nullable=true)
     */
    public ?string $title;

    /**
     * Location.
     *
     * @var null|string
     *
     * @Assert\Type("string")
     *
     * @Groups({"read"})
     *
     * @ORM\Column(type="string", nullable=true)
     */
    public ?string $location;

    /**
     * Customs HS Code.
     *
     * @var null|string
     *
     * @Assert\Type("string")
     *
     * @Groups({"read"})
     *
     * @ORM\Column(type="string", nullable=true)
     */
    public ?string $hs_code;

    /**
     * Supplier Name.
     *
     * @var null|string
     *
     * @Assert\Type("string")
     *
     * @Groups({"read"})
     *
     * @ORM\Column(type="string", nullable=true)
     */
    public ?string $supplier;

    /**
     * Product Picture Url.
     *
     * @var null|string
     *
     * @Assert\Type("string")
     *
     * @Groups({"read"})
     *
     * @ORM\Column(type="string", nullable=true)
     */
    public ?string $picture_url;

    //====================================================================//
    // DIMENSIONS
    //====================================================================//

    /**
     * Product Weight.
     *
     * @var null|int
     *
     * @Assert\Type("int")
     *
     * @Groups({"read"})
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    public ?int $weight;

    /**
     * Product Height.
     *
     * @var null|int
     *
     * @Assert\Type("int")
     *
     * @Groups({"read"})
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    public ?int $height;

    /**
     * Product Length.
     *
     * @var null|int
     *
     * @Assert\Type("int")
     *
     * @Groups({"read"})
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    public ?int $length;

    /**
     * Product Width.
     *
     * @var null|int
     *
     * @Assert\Type("int")
     *
     * @Groups({"read"})
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    public ?int $width;

    //====================================================================//
    // USER EXTRA REFS
    //====================================================================//

    /**
     * Other Ref 1.
     *
     * @var null|string
     *
     * @Assert\Type("string")
     *
     * @Groups({"read"})
     *
     * @ORM\Column(type="string", nullable=true)
     */
    public ?string $other_ref1;

    /**
     * Other Ref 2.
     *
     * @var null|string
     *
     * @Assert\Type("string")
     *
     * @Groups({"read"})
     *
     * @ORM\Column(type="string", nullable=true)
     */
    public ?string $other_ref2;

    /**
     * Other Ref 3.
     *
     * @var null|string
     *
     * @Assert\Type("string")
     *
     * @Groups({"read"})
     *
     * @ORM\Column(type="string", nullable=true)
     */
    public ?string $other_ref3;

    /**
     * Other Ref 4.
     *
     * @var null|string
     *
     * @Assert\Type("string")
     *
     * @Groups({"read"})
     *
     * @ORM\Column(type="string", nullable=true)
     */
    public ?string $other_ref4;

    /**
     * Other Ref 5.
     *
     * @var null|string
     *
     * @Assert\Type("string")
     *
     * @Groups({"read"})
     *
     * @ORM\Column(type="string", nullable=true)
     */
    public ?string $other_ref5;

    /**
     * Other Ref 6.
     *
     * @var null|string
     *
     * @Assert\Type("string")
     *
     * @Groups({"read"})
     *
     * @ORM\Column(type="string", nullable=true)
     */
    public ?string $other_ref6;

    /**
     * Other Ref 6.
     *
     * @var null|string
     *
     * @Assert\Type("string")
     *
     * @Groups({"read"})
     *
     * @ORM\Column(type="string", nullable=true)
     */
    public ?string $other_ref7;

    /**
     * Other Ref 8.
     *
     * @var null|string
     *
     * @Assert\Type("string")
     *
     * @Groups({"read"})
     *
     * @ORM\Column(type="string", nullable=true)
     */
    public ?string $other_ref8;

    /**
     * Other Ref 9.
     *
     * @var null|string
     *
     * @Assert\Type("string")
     *
     * @Groups({"read"})
     *
     * @ORM\Column(type="string", nullable=true)
     */
    public ?string $other_ref9;

    /**
     * Other Ref 10.
     *
     * @var null|string
     *
     * @Assert\Type("string")
     *
     * @Groups({"read"})
     *
     * @ORM\Column(type="string", nullable=true)
     */
    public ?string $other_ref10;

    /**
     * Other Ref 11.
     *
     * @var null|string
     *
     * @Assert\Type("string")
     *
     * @Groups({"read"})
     *
     * @ORM\Column(type="string", nullable=true)
     */
    public ?string $other_ref11;

    /**
     * Other Ref 12.
     *
     * @var null|string
     *
     * @Assert\Type("string")
     *
     * @Groups({"read"})
     *
     * @ORM\Column(type="string", nullable=true)
     */
    public ?string $other_ref12;

    /**
     * Other Ref 13.
     *
     * @var null|string
     *
     * @Assert\Type("string")
     *
     * @Groups({"read"})
     *
     * @ORM\Column(type="string", nullable=true)
     */
    public ?string $other_ref13;

    /**
     * Other Ref 14.
     *
     * @var null|string
     *
     * @Assert\Type("string")
     *
     * @Groups({"read"})
     *
     * @ORM\Column(type="string", nullable=true)
     */
    public ?string $other_ref14;

    /**
     * Other Ref 15.
     *
     * @var null|string
     *
     * @Assert\Type("string")
     *
     * @Groups({"read"})
     *
     * @ORM\Column(type="string", nullable=true)
     */
    public ?string $other_ref15;

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
