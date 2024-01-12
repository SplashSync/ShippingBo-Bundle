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

namespace Splash\Connectors\ShippingBo\Models\Api;

use JMS\Serializer\Annotation as JMS;
use Splash\Models\Objects\ObjectsTrait;
use Splash\OpenApi\Validator as SPL;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class representing the Order Item model.
 *
 * @SuppressWarnings(PHPMD.CamelCasePropertyName)
 */
class PackComponent
{
    //====================================================================//
    // Splash Core Traits
    use ObjectsTrait;
    //====================================================================//
    // SBO CORE DATA
    use Core\SboCoreTrait;

    /**
     * Unique Identifier.
     *
     * @var string
     *
     * @Assert\NotNull()
     *
     * @Assert\Type("string")
     *
     * @JMS\SerializedName("id")
     *
     * @JMS\Type("string")
     *
     * @JMS\Groups({"Read"})
     */
    public string $id;

    /**
     * Products Quantities.
     *
     * @var int
     *
     * @Assert\NotNull()
     *
     * @Assert\Type("integer")
     *
     * @JMS\SerializedName("quantity")
     *
     * @JMS\Groups({"Read"})
     *
     * @JMS\Type("integer")
     *
     * @SPL\Microdata({"https://schema.org/ProductCollection", "size"})
     */
    public int $quantity;

    /**
     * Child Product Unique Identifier.
     *
     * @var string
     *
     * @Assert\NotNull()
     *
     * @Assert\Type("string")
     *
     * @JMS\SerializedName("component_product_id")
     *
     * @JMS\Type("integer")
     *
     * @JMS\Groups({"Read"})
     *
     * @JMS\Accessor(getter="getProductId",setter="setProductId")
     *
     * @SPL\Type("objectid::Product")
     *
     * @SPL\Microdata({"https://schema.org/ProductCollection", "identifier"})
     */
    public string $component_product_id;

    /**
     * Decode Product ID for Serialize
     */
    public function getProductId(): ?int
    {
        if ($this->productId ?? null) {
            return (int) self::objects()->id((string) $this->component_product_id);
        }

        return null;
    }

    /**
     * Encode Product ID on Object Hydratation
     */
    public function setProductId(?int $productId): self
    {
        $this->component_product_id = (string) self::objects()
            ->encode("Product", (string) $productId)
        ;

        return $this;
    }
}
