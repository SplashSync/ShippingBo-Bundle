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
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Represents a Supply Capsule Item with its related data and utility methods.
 */
class SupplyCapsuleItem
{
    //====================================================================//
    // SBO CORE DATA
    use Core\SboCoreTrait;
    use Core\SboSourceTrait;

    //====================================================================//
    // Supply Capsule Item DATA
    use SupplyCapsuleItem\ProductTrait;

    /**
     * Unique ID.
     */
    #[
        Assert\NotNull(),
        Assert\Type("int"),
        JMS\Groups(array("Read", "Write")),
        JMS\Type("int"),
    ]
    public ?int $id = null;

    #[SPL\IsReadOnly]
    public string $source = "Splashsync";

    /**
     * Expected Quantity.
     */
    #[
        Assert\NotNull(),
        Assert\Type("int"),
        JMS\SerializedName("quantity"),
        JMS\Groups(array("Read", "Write", "Required")),
        JMS\Type("integer"),
        SPL\Microdata("http://schema.org/QuantitativeValue", "value"),
        SPL\IsRequired,
    ]
    public int $quantity = 0;

    /**
     * Received Quantity.
     */
    #[
        Assert\NotNull(),
        Assert\Type("int"),
        JMS\SerializedName("received_quantity"),
        JMS\Groups(array("Read")),
        JMS\Type("integer"),
        SPL\Microdata("http://schema.org/QuantitativeValue", "value"),
        SPL\IsReadOnly,
    ]
    public int $receivedQuantity = 0;

    /**
     * Convert to String
     *
     * @return string
     */
    public function __toString(): string
    {
        return 'Capsule Item '.($this->id ?? "NEW");
    }

    /**
     * Ensure Source ref is Defined (MPN or SKU))
     */
    public function setSourceRef(?string $sourceRef): static
    {
        if (!empty($sourceRef)) {
            $this->source_ref = $sourceRef;
        }

        return $this;
    }

    /**
     * Ensure Source ref is Defined (MPN or SKU))
     */
    public function getSourceRef(): string
    {
        return $this->source_ref ?? $this->productRef ?? "";
    }

    /**
     * Check if Item is to Create on API
     */
    public function isNew(): bool
    {
        return empty($this->id ?? null);
    }

    /**
     * Check if Supply Capsule Item is Valid for Create/Update
     */
    public function isValid(): bool
    {
        return !empty($this->productRef)
            && !empty($this->quantity)
            && ($this->quantity >= 0)
        ;
    }

    /**
     * Compute Item Md5
     */
    public function getMd5(): string
    {
        return md5(serialize(array(
            $this->sourceRef ?? null,
            $this->productRef ?? null,
            $this->productUserRef ?? null,
            $this->productEan ?? null,
            $this->quantity ?? null,
        )));
    }
}
