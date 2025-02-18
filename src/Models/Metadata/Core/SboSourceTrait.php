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
 * Object Source Trait
 */
trait SboSourceTrait
{
    /**
     * The Entity Source Name.
     */
    #[
        Assert\NotNull,
        Assert\Type("string"),
        JMS\SerializedName("source"),
        JMS\Groups(array("Read", "Write", "List", "Required")),
        SPL\Field(
            name: "Source",
            desc: "The Entity Source Name.",
        ),
    ]
    public string $source = "Splashsync";

    /**
     * The Entity Source Reference.
     */
    #[
        Assert\NotNull,
        Assert\Type("string"),
        JMS\SerializedName("source_ref"),
        JMS\Groups(array("Read", "Write", "List", "Required")),
        JMS\Accessor(
            getter: "getSourceRef",
        ),
        SPL\Field(
            name: "Source ref.",
            desc: "The Entity Source Reference.",
        ),
        SPL\Flags(listed: true, searchable: true),
        SPL\Microdata("http://splashync.com/schemas", "ObjectId"),
        SPL\Accessor(
            setter: "setSourceRef"
        ),
        SPL\IsRequired,
    ]
    public string $source_ref;

    /**
     * Set Source ref
     */
    public function setSourceRef(string $sourceRef): static
    {
        $this->source_ref = $sourceRef;

        return $this;
    }

    /**
     * Get Source ref
     */
    public function getSourceRef(): string
    {
        return $this->source_ref;
    }
}
