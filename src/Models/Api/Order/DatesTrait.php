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

namespace Splash\Connectors\ShippingBo\Models\Api\Order;

use DateTime;
use JMS\Serializer\Annotation as JMS;
use Splash\OpenApi\Validator as SPL;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Sbo Order Dates Fields
 */
trait DatesTrait
{
    //====================================================================//
    // STATUS DATES - READ ONLY
    //====================================================================//

    /**
     * @var null|DateTime
     *
     * @Assert\Type("DateTime")
     *
     * @JMS\SerializedName("shipped_at")
     * @JMS\Type("DateTime")
     * @JMS\Groups ({"Read"})
     *
     * @SPL\Group("Meta")
     */
    public ?DateTime $shippedAt;

    /**
     * @var null|DateTime
     *
     * @Assert\Type("DateTime")
     *
     * @JMS\SerializedName("closed_at")
     * @JMS\Type("DateTime")
     * @JMS\Groups ({"Read"})
     *
     * @SPL\Group("Meta")
     */
    public ?DateTime $closedAt;

    /**
     * @var null|DateTime
     *
     * @Assert\Type("DateTime")
     *
     * @JMS\SerializedName("state_changed_at")
     * @JMS\Type("DateTime")
     * @JMS\Groups ({"Read"})
     *
     * @SPL\Group("Meta")
     */
    public ?DateTime $stateChangedAt;

    //====================================================================//
    // SHIPPING DATES - READ ONLY
    //====================================================================//

    /**
     * @var null|DateTime
     *
     * @Assert\Type("DateTime")
     *
     * @JMS\SerializedName("latest_shipped_at")
     * @JMS\Type("DateTime")
     * @JMS\Groups ({"Read"})
     *
     * @SPL\Group("Meta")
     */
    public ?DateTime $latestShippedAt;

    /**
     * @var null|DateTime
     *
     * @Assert\Type("DateTime")
     *
     * @JMS\SerializedName("earliest_shipped_at")
     * @JMS\Type("DateTime")
     * @JMS\Groups ({"Read"})
     *
     * @SPL\Group("Meta")
     */
    public ?DateTime $earliestShippedAt;

    //====================================================================//
    // DELIVERY DATES - READ ONLY
    //====================================================================//

    /**
     * @var null|DateTime
     *
     * @Assert\Type("DateTime")
     *
     * @JMS\SerializedName("latest_delivery_at")
     * @JMS\Type("DateTime")
     * @JMS\Groups ({"Read"})
     *
     * @SPL\Group("Meta")
     */
    public ?DateTime $latestDeliveryAt;

    /**
     * @var null|DateTime
     *
     * @Assert\Type("DateTime")
     *
     * @JMS\SerializedName("earliest_delivery_at")
     * @JMS\Type("DateTime")
     * @JMS\Groups ({"Read"})
     *
     * @SPL\Group("Meta")
     */
    public ?DateTime $earliestDeliveryAt;
}
