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

namespace Splash\Connectors\ShippingBo\Models\Api\Product;

use JMS\Serializer\Annotation as JMS;
use Splash\OpenApi\Validator as SPL;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Product Dimensions Models
 */
trait ProductDimensionsTrait
{
    /**
     * Product Weight.
     *
     * @var null|float
     *
     * @Assert\Type("float")
     *
     * @JMS\SerializedName("weight")
     *
     * @JMS\Type("int")
     *
     * @JMS\Groups ({"Read", "Write"})
     *
     * @JMS\Accessor(getter="getApiWeight",setter="setApiWeight")
     *
     * @SPL\Type("double")
     *
     * @SPL\Prefer("write")
     *
     * @SPL\Microdata({"http://schema.org/Product", "weight"})
     */
    public ?float $weight = null;

    /**
     * Product Height.
     *
     * @var null|float
     *
     * @Assert\Type("float")
     *
     * @JMS\SerializedName("height")
     *
     * @JMS\Type("int")
     *
     * @JMS\Groups ({"Read", "Write"})
     *
     * @JMS\Accessor(getter="getApiHeight",setter="setApiHeight")
     *
     * @SPL\Type("double")
     *
     * @SPL\Prefer("write")
     *
     * @SPL\Microdata({"http://schema.org/Product", "height"})
     */
    public ?float $height = null;

    /**
     * Product Length.
     *
     * @var null|float
     *
     * @Assert\Type("float")
     *
     * @JMS\SerializedName("length")
     *
     * @JMS\Type("int")
     *
     * @JMS\Groups ({"Read", "Write"})
     *
     * @JMS\Accessor(getter="getApiLength",setter="setApiLength")
     *
     * @SPL\Type("double")
     *
     * @SPL\Prefer("write")
     *
     * @SPL\Microdata({"http://schema.org/Product", "depth"})
     */
    public ?float $length = null;

    /**
     * Product Width.
     *
     * @var null|float
     *
     * @Assert\Type("float")
     *
     * @JMS\SerializedName("width")
     *
     * @JMS\Type("int")
     *
     * @JMS\Groups ({"Read", "Write"})
     *
     * @JMS\Accessor(getter="getApiWidth",setter="setApiWidth")
     *
     * @SPL\Type("double")
     *
     * @SPL\Prefer("write")
     *
     * @SPL\Microdata({"http://schema.org/Product", "width"})
     */
    public ?float $width = null;

    //====================================================================//
    // GENERIC GETTERS & SETTERS
    //====================================================================//

    /**
     * Get Product Weight
     */
    public function getApiWeight(): ?int
    {
        return ((int) (1000 * $this->weight)) ?: null;
    }

    /**
     * Set Product Weight
     */
    public function setApiWeight(?int $weight): static
    {
        $this->weight = ((float) $weight) / 1000;

        return $this;
    }

    /**
     * Get Product Height
     */
    public function getApiHeight(): ?int
    {
        return ((int) (100 * $this->height)) ?: null;
    }

    /**
     * Set Product Height
     */
    public function setApiHeight(?int $height): self
    {
        $this->height = ((float) $height) / 100;

        return $this;
    }

    /**
     * Get Product Length
     */
    public function getApiLength(): ?int
    {
        return ((int) (100 * $this->length)) ?: null;
    }

    /**
     * Set Product Length
     */
    public function setApiLength(?int $length): static
    {
        $this->length = ((float) $length) / 100;

        return $this;
    }

    /**
     * Get Product Width
     */
    public function getApiWidth(): ?int
    {
        return ((int) (100 * $this->width)) ?: null;
    }

    /**
     * Set Product Width
     */
    public function setApiWidth(?int $width): static
    {
        $this->width = ((float) $width) / 100;

        return $this;
    }
}
