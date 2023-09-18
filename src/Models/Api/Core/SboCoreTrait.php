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
 * Object Source Trait
 */
trait SboCoreTrait
{
    /**
     * @var DateTime
     *
     * @Assert\Type("DateTime")
     *
     * @JMS\SerializedName("created_at")
     *
     * @JMS\Groups ({"Read"})
     *
     * @SPL\Microdata({"http://schema.org/DataFeedItem", "dateCreated"})
     *
     * @SPL\Group("Meta")
     */
    public DateTime $createdAt;

    /**
     * @var DateTime
     *
     * @Assert\Type("DateTime")
     *
     * @JMS\SerializedName("created_at")
     *
     * @JMS\Groups ({"Read"})
     *
     * @SPL\Microdata({"http://schema.org/DataFeedItem", "dateUpdated"})
     *
     * @SPL\Group("Meta")
     */
    public DateTime $updatedAt;
}
