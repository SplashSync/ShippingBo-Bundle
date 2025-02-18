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

use JMS\Serializer\Annotation as JMS;
use Splash\Metadata\Attributes as SPL;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Object Origin Trait
 */
trait SboOriginTrait
{
    /**
     * The Entity Origin Name.
     */
    #[
        Assert\NotNull,
        Assert\Type("string"),
        JMS\SerializedName("origin"),
        JMS\Groups(array("Read", "Write", "Required")),
        SPL\Microdata("http://splashync.com/schemas", "SourceNodeName"),
        SPL\Field(
            name: "Origin",
            desc: "The Entity Origin Name.",
        )
    ]
    public string $origin;

    /**
     * The Entity Origin Reference.
     */
    #[
        Assert\NotNull,
        Assert\Type("string"),
        JMS\SerializedName("origin_ref"),
        JMS\Groups(array("Read", "Write", "List", "Required")),
        SPL\Microdata("http://schema.org/Order", "orderNumber"),
        SPL\Field(
            name: "Origin Ref",
            desc: "The Entity Origin Name.",
        )
    ]
    public string $origin_ref;

    #[
        Assert\NotNull,
        Assert\Type("DateTime"),
        JMS\SerializedName("origin_created_at"),
        JMS\Groups(array("Read", "Write", "Required")),
        JMS\Type("DateTime<'Y-m-d\\TH:i:sP','',['Y-m-d\\TH:i:sP', 'Y-m-d H:i:s']>"),
        SPL\Microdata("http://schema.org/DataFeedItem", "dateCreated"),
        SPL\Field(
            name: "Origin Created",
            desc: "The Entity Origin Creation Date.",
        )
    ]
    public $origin_created_at;
}
