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
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class representing the Product Barcode model.
 */
#[ORM\Entity]
#[ORM\Table(name: "product_additional_fields")]
#[ORM\UniqueConstraint(name: "unique_product_key", columns: array("product_id", "key"))]
#[ORM\HasLifecycleCallbacks]
#[ApiResource(
    operations: array(
        new Meta\GetCollection(),
        new Meta\Post(),
        new Meta\Patch(),
        new Meta\Delete(),
    ),
    normalizationContext: array("groups" => array("read")),
    denormalizationContext: array("groups" => array("write"))
)]
class ProductAdditionalField implements SboObjectInterface
{
    use Core\SboCoreTrait;

    /**
     * Unique Identifier.
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    #[Assert\Type("integer")]
    #[Groups(array("read"))]
    public int $id;

    /**
     * Parent Product ID
     */
    #[ORM\Column]
    #[Assert\NotNull]
    #[Assert\Type("string")]
    #[Groups(array("read", "write"))]
    public string $productId;

    /**
     * Parent Product
     */
    #[Assert\Type(Product::class)]
    #[ORM\ManyToOne(targetEntity: Product::class, inversedBy: 'productAddtionalFields')]
    public Product $product;

    //====================================================================//
    // PRODUCT ADDITIONAL FIELD
    //====================================================================//

    /**
     * Attribute Key.
     */
    #[ORM\Column]
    #[Assert\NotNull]
    #[Assert\Type("string")]
    #[Groups(array("read", "write"))]
    public string $key;

    /**
     * Attribute Value.
     */
    #[ORM\Column]
    #[Assert\NotNull]
    #[Assert\Type("string")]
    #[Groups(array("read", "write"))]
    public string $value;

    //====================================================================//
    // Product LINK UPDATE
    //====================================================================//

    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function updateProduct(LifecycleEventArgs $event): void
    {
        //====================================================================//
        // Check if Changed
        $current = $this->product->id ?? null;
        $new = (int) ($this->productId ?? 1);
        if ($current && $new && ($current == $new)) {
            return;
        }
        //====================================================================//
        // Identify New
        $product = $event->getObjectManager()->getRepository(Product::class)->find($new);
        if (!$product) {
            throw new NotFoundHttpException(
                sprintf("Target Product %s not found", $new)
            );
        }
        //====================================================================//
        // Update
        $this->product = $product;
    }

    //====================================================================//
    // JSON SERIALIZER
    //====================================================================//

    /**
     * {@inheritDoc}
     */
    public static function getItemIndex(): ?string
    {
        return "product_additional_fields";
    }

    /**
     * {@inheritDoc}
     */
    public static function getCollectionIndex(): ?string
    {
        return "product_additional_fields";
    }
}
