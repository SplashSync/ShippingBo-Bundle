<?php

/*
 *  This file is part of SplashSync Project.
 *
 *  Copyright (C) 2015-2021 Splash Sync  <www.splashsync.com>
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
use Splash\Models\Objects\ImagesTrait;
use Splash\OpenApi\Validator as SPL;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Product Image Models
 */
trait ProductImageTrait
{
    use ImagesTrait;

    /**
     * Product Picture Url.
     *
     * @var null|string
     * @Assert\Type("string")
     *
     * @JMS\SerializedName("picture_url")
     * @JMS\Type("string")
     * @JMS\Groups ({"Read", "Write"})
     *
     * @SPL\Type("url")
     * @SPL\Prefer("write")
     * @SPL\Group("Meta")
     * @SPL\Microdata({"http://schema.org/Product", "coverImageUrl"})
     */
    public ?string $pictureUrl = null;

    /**
     * Product Picture Definition.
     *
     * @var null|array
     * @Assert\Type("string")
     *
     * @JMS\Type("array")
     * @JMS\Groups ({"Read"})
     *
     * @SPL\Type("image")
     * @SPL\Prefer("write")
     * @SPL\Group("Meta")
     * @SPL\Microdata({"http://schema.org/Product", "coverImage"})
     */
    public ?array $picture = null;

    //====================================================================//
    // GENERIC GETTERS & SETTERS
    //====================================================================//

    /**
     * @return null|array
     */
    public function getPicture(): ?array
    {
        //====================================================================//
        // No Image Defined
        if (empty($this->pictureUrl)) {
            return null;
        }
        //====================================================================//
        // Touch Image with Curl (In Case first reading)
        if (!self::images()->touchRemoteFile($this->pictureUrl)) {
            return null;
        }
        //====================================================================//
        // Encode Image Infos from Url
        return self::images()->encodeFromUrl(
            "Product Picture",
            $this->pictureUrl,
            $this->pictureUrl
        ) ?: null;
    }
}
