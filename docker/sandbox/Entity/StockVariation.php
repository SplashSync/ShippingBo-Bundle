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
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class representing the Product Stock Variation model.
 */
#[ApiResource(
    normalizationContext: array("groups" => array("read")),
    denormalizationContext: array("groups" => array("write")),
)]
#[ORM\Entity]
#[ORM\Table(name: 'stock_variation')]
#[ORM\HasLifecycleCallbacks]
class StockVariation implements SboObjectInterface
{
    //====================================================================//
    // SBO CORE DATA
    use Core\SboCoreTrait;

    /**
     * Stock Variation
     */
    #[Assert\NotNull]
    #[Assert\Type('integer')]
    #[ORM\Column(type: Types::INTEGER)]
    #[Groups(array('write'))]
    public int $variation;

    /**
     * New Product Stock
     */
    #[Assert\NotNull]
    #[Assert\Type('integer')]
    #[Groups(array('read'))]
    public ?int $stock = 0;

    /**
     * Received Product ID.
     */
    #[Assert\Type('integer')]
    #[Groups(array('write'))]
    public ?int $productId;

    /**
     * Impacted Product.
     */
    #[Assert\Type(Product::class)]
    #[Groups(array('read'))]
    #[ORM\ManyToOne(targetEntity: Product::class)]
    public Product $product;

    //====================================================================//
    // MAIN METHODS
    //====================================================================//

    #[ORM\PrePersist]
    public function linkToProduct(LifecycleEventArgs $event): void
    {
        //====================================================================//
        // Check Received Product ID
        if (($this->productId ?? 0) <= 0) {
            throw new NotFoundHttpException(
                sprintf('Product ID must be given')
            );
        }

        //====================================================================//
        // Check Received Variation
        if (($this->variation ?? 0) == 0) {
            throw new NotFoundHttpException(
                sprintf('Variation must be a non-zero signed int')
            );
        }

        //====================================================================//
        // Identify Product
        /** @var null|Product $product */
        $product = $event->getObjectManager()->getRepository(Product::class)->find($this->productId);
        if (!$product) {
            throw new NotFoundHttpException(
                sprintf('Target Product %s not found', $this->productId)
            );
        }

        //====================================================================//
        // Update
        $product->stockVariations[] = $this;
        $this->product = $product;
        $this->stock = $product->getStock();
    }

    //====================================================================//
    // JSON SERIALIZER
    //====================================================================//

    /**
     * {@inheritDoc}
     */
    public static function getItemIndex(): string
    {
        return 'stock_variation';
    }

    /**
     * {@inheritDoc}
     */
    public static function getCollectionIndex(): string
    {
        return 'stock_variations';
    }
}
