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

namespace Splash\Connectors\ShippingBo\Models\Metadata;

use JMS\Serializer\Annotation as JMS;
use Splash\Metadata\Attributes as SPL;
use Splash\Models\Helpers\ObjectsHelper;

/**
 * Represents the reception details of a supply capsule.
 */
class SupplyCapsuleReception
{
    /**
     * Product ID.
     */
    #[
        JMS\Exclude,
        SPL\Field(
            type: SPL_T_ID.IDSPLIT."Product",
            name: "Product ID",
            desc: "Product Identifier",
        ),
        SPL\Microdata("http://schema.org/OrderItem", "productID"),
        SPL\IsReadOnly,
    ]
    public ?string $productId = null;

    /**
     * Product MPN.
     */
    #[
        JMS\Exclude,
        SPL\Microdata("http://schema.org/OrderItem", "mpn"),
        SPL\IsReadOnly,
    ]
    public ?string $productRef = null;

    /**
     * Product SKU.
     */
    #[
        JMS\Exclude,
        SPL\Microdata("http://schema.org/OrderItem", "sku"),
        SPL\IsReadOnly,
    ]
    public ?string $productUserRef = null;

    /**
     * Expected Quantity.
     */
    #[
        JMS\Exclude,
        SPL\Microdata("http://schema.org/OrderItem", "orderQuantity"),
        SPL\IsReadOnly,
    ]
    public int $quantity = 0;

    /**
     * Received Quantity.
     */
    #[
        JMS\Exclude,
        SPL\Microdata("http://schema.org/OrderItem", "orderItemStatus"),
        SPL\IsReadOnly,
    ]
    public int $receivedQuantity = 0;

    public function __construct(SupplyCapsuleItem $item)
    {
        $this->productId = $item->productId
            ? (string) ObjectsHelper::encode("Product", (string) $item->productId)
            : null
        ;
        $this->productRef = $item->productRef;
        $this->productUserRef = $item->productUserRef;
        $this->quantity = $item->quantity;
        $this->receivedQuantity = $item->receivedQuantity;
    }
}
