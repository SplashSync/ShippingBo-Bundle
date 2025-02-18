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

namespace Splash\Connectors\ShippingBo\Models\Metadata\SupplyCapsuleItem;

use JMS\Serializer\Annotation as JMS;
use Splash\Metadata\Attributes as SPL;
use Splash\Models\Helpers\ObjectsHelper;
use Symfony\Component\Validator\Constraints as Assert;

trait ProductTrait
{
    /**
     * Product ID.
     */
    #[
        Assert\Type("int"),
        JMS\SerializedName("product_id"),
        JMS\Groups(array("Read")),
        JMS\Type("integer"),
        SPL\Field(
            type: SPL_T_ID.IDSPLIT."Product",
            name: "Product ID",
            desc: "Product Identifier",
        ),
        SPL\Microdata("http://schema.org/Product", "productID"),
        SPL\IsReadOnly,
    ]
    public ?int $productId = null;

    /**
     * Product SKU.
     */
    #[
        Assert\Type("string"),
        JMS\SerializedName("product_ref"),
        JMS\Groups(array("Read", "Write")),
        JMS\Type("string"),
        SPL\Microdata("http://schema.org/Product", "sku"),
        SPL\IsRequired
    ]
    public ?string $productRef = null;

    /**
     * Product Identified SKU.
     */
    #[
        Assert\Type("string"),
        JMS\SerializedName("product_user_ref"),
        JMS\Groups(array("Read")),
        JMS\Type("string"),
        SPL\IsReadOnly
    ]
    public ?string $productUserRef = null;

    /**
     * Product EAN 13.
     */
    #[
        Assert\Type("string"),
        JMS\SerializedName("product_ean"),
        JMS\Groups(array("Read", "Write")),
        JMS\Type("string"),
        JMS\SkipWhenEmpty,
        SPL\Microdata("http://schema.org/Product", "gtin13"),
    ]
    public ?string $productEan = null;

    /**
     * Convert Product ID for Splash
     */
    public function getProductId(): ?string
    {
        return $this->productId
            ? (string) ObjectsHelper::encode("Product", (string) $this->productId)
            : null
        ;
    }

    /**
     * Update Product ID from Splash
     */
    public function setProductId(?string $productId): self
    {
        $this->productId = $productId
            ? (int) ObjectsHelper::id($productId)
            : null
        ;

        return $this;
    }
}
