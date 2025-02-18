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
    name: "SupplierOrder",
    description: "ShippingBo Supply Capsule Object",
    ico: "fa fa-truck",
)]
class SupplyCapsule
{
    //====================================================================//
    // SBO CORE DATA
    use Core\SboCoreTrait;
    use Core\SboSourceTrait;
    //====================================================================//
    // Supply Capsule Data
    use SupplyCapsule\SupplierTrait;
    use SupplyCapsule\DatesTrait;
    use SupplyCapsule\ItemsTrait;
    use SupplyCapsule\ReceptionsTrait;
    use SupplyCapsule\StatusTrait;

    const COLLECTION_PROP = "supply_capsules";
    const ITEMS_PROP = "supply_capsule";

    #[
        Assert\NotNull,
        Assert\Type("string"),
        JMS\Groups(array("Read", "Write", "List")),
        JMS\Type("string"),
    ]
    public string $id;

    #[
        Assert\NotNull,
        Assert\Type("string"),
        JMS\SerializedName("source_ref"),
        JMS\Groups(array("Read", "Write", "List", "Required")),
        JMS\Accessor(
            getter: "getSourceRef",
        ),
        SPL\Flags(
            required: true,
            listed: true,
            primary: true,
            searchable: true
        ),
        SPL\Microdata("http://schema.org/Order", "name"),
        SPL\Accessor(
            setter: "setSourceRef"
        ),
    ]
    public string $source_ref;
}
