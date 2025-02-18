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

namespace Splash\Connectors\ShippingBo\Models\Metadata\Core;

use DateTime;
use JMS\Serializer\Annotation as JMS;
use Splash\Metadata\Attributes as SPL;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Object Source Trait
 */
trait SboCoreTrait
{
    /**
     * The creation timestamp of the entity.
     */
    #[
        Assert\Type("DateTime"),
        JMS\SerializedName("created_at"),
        JMS\Groups(array("Read")),
        SPL\Microdata("http://schema.org/DataFeedItem", "dateCreated"),
        SPL\Field(
            name: "Created At",
            desc: "The creation timestamp of the entity.",
            group: "Meta"
        ),
        SPL\IsReadOnly
    ]
    public ?DateTime $createdAt;

    /**
     * The update timestamp of the entity.
     */
    #[
        Assert\Type("DateTime"),
        JMS\SerializedName("updated_at"),
        JMS\Groups(array("Read")),
        SPL\Microdata("http://schema.org/DataFeedItem", "dateUpdated"),
        SPL\Field(
            name: "Updated At",
            desc: "The update timestamp of the entity.",
            group: "Meta"
        ),
        SPL\IsReadOnly
    ]
    public ?DateTime $updatedAt;
}
