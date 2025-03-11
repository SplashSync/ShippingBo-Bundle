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
 * @SuppressWarnings(CamelCasePropertyName)
 */
#[SPL\SplashObject(
    name: "Webhook",
    description: "ShippingBo Webhook Object",
    ico: "fa fa-rss",
    allowPushDeleted: true,
)]
class Webhook
{
    const COLLECTION_PROP = "update_hooks";
    const ITEMS_PROP = "update_hook";

    #[
        Assert\NotNull,
        Assert\Type("string"),
        JMS\Groups(array("Read", "Write", "List")),
        JMS\Type("string"),
    ]
    public string $id;

    #[
        Assert\NotNull,
        Assert\Type("bool"),
        JMS\Groups(array("Read", "Write", "List", "Required")),
        JMS\Type("bool"),
        SPL\Field(
            name: "Activated",
            desc: "Is this Webhook Activated"
        ),
        SPL\Flags(listed: true)
    ]
    public bool $activated = true;

    #[
        Assert\Type("bool"),
        JMS\Groups(array("Read", "Write")),
        JMS\Type("bool"),
        SPL\Field(
            name: "Visible",
            desc: "Is this Webhook Visible"
        ),
    ]
    public ?bool $visible = true;

    #[
        Assert\NotNull,
        Assert\Type("bool"),
        JMS\SerializedName("trigger_on_destroy"),
        JMS\Groups(array("Read", "Write", "List")),
        JMS\Type("bool"),
        SPL\Field(
            name: "On destroy",
            desc: "Trigger Webhook on Destroy / Delete"
        ),
    ]
    public bool $triggerOnDestroy = true;

    #[
        Assert\Type("string"),
        JMS\SerializedName("endpoint_url"),
        JMS\Groups(array("Read", "Write", "List", "Required")),
        JMS\Type("string"),
        SPL\Field(
            type: SPL_T_URL,
            name: "Endpoint",
            desc: "Webhook Target Url"
        ),
        SPL\Flags(listed: true),
        SPL\IsRequired,
    ]
    public ?string $endpoint_url = "";

    #[
        Assert\Type("string"),
        JMS\SerializedName("object_class"),
        JMS\Groups(array("Read", "Write", "List", "Required")),
        JMS\Type("string"),
        SPL\Field(name: "Object Type", desc: "Target Object Class"),
        SPL\Flags(listed: true),
        SPL\IsRequired,
    ]
    public ?string $object_class = null;
}
