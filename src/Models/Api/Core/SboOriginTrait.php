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

namespace Splash\Connectors\ShippingBo\Models\Api\Core;

use DateTime;
use JMS\Serializer\Annotation as JMS;
use Splash\OpenApi\Validator as SPL;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Object Origin Trait
 */
trait SboOriginTrait
{
    /**
     * The order ID this shipment belongs to.
     *
     * @var string
     * @Assert\NotNull()
     * @Assert\Type("string")
     *
     * @JMS\SerializedName("origin")
     * @JMS\Type("string")
     * @JMS\Groups ({"Read", "Write", "Required"})
     *
     * @SPL\Microdata({"http://splashync.com/schemas", "SourceNodeName"})
     */
    public string $origin;

    /**
     * The order ID this shipment belongs to.
     *
     * @var string
     * @Assert\NotNull()
     * @Assert\Type("string")
     *
     * @JMS\SerializedName("origin_ref")
     * @JMS\Type("string")
     * @JMS\Groups ({"Read", "Write", "List", "Required"})
     *
     * @SPL\Microdata({"http://schema.org/Order", "orderNumber"})
     */
    public string $origin_ref;

    /**
     * @var DateTime
     *
     * @Assert\NotNull()
     * @Assert\Type("DateTime")
     *
     * @JMS\SerializedName("origin_created_at")
     * @JMS\Groups ({"Read", "Write", "Required"})
     * @JMS\Type("DateTime<'Y-m-d\TH:i:sP','',['Y-m-d\TH:i:sP', 'Y-m-d H:i:s']>")
     *
     * @SPL\Microdata({"http://schema.org/DataFeedItem", "dateCreated"})
     */
    public $origin_created_at;
}
