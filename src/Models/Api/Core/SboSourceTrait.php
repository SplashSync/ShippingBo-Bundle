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

namespace Splash\Connectors\ShippingBo\Models\Api\Core;

use JMS\Serializer\Annotation as JMS;
use Splash\OpenApi\Validator as SPL;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Object Source Trait
 */
trait SboSourceTrait
{
    /**
     * The order ID this shipment belongs to.
     *
     * @var string
     * @Assert\NotNull()
     * @Assert\Type("string")
     *
     * @JMS\SerializedName("source")
     * @JMS\Type("string")
     * @JMS\Groups ({"Read", "Write", "List"})
     */
    public string $source = "Splashsync";

    /**
     * The order ID this shipment belongs to.
     *
     * @var null|string
     * @Assert\Type("string")
     *
     * @JMS\SerializedName("source_ref")
     * @JMS\Type("string")
     * @JMS\Groups ({"Read", "Write", "List", "Required"})
     *
     * @SPL\Microdata({"http://splashync.com/schemas", "ObjectId"})
     */
    public ?string $source_ref;
}
