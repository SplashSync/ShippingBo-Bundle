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

use JMS\Serializer\Annotation as JMS;
use Splash\Connectors\ShippingBo\Models\Api\OrderItem;
use Splash\OpenApi\Validator as SPL;
use Symfony\Component\Validator\Constraints as Assert;

trait OrderItemsTrait
{
    /**
     * Order Items List.
     *
     * @var OrderItem[]
     *
     * @JMS\SerializedName("order_items")
     * @JMS\Type("array<Splash\Connectors\ShippingBo\Models\Api\OrderItem>")
     * @JMS\Groups ({"Read"})
     *
     * @Assert\All({
     *   @Assert\Type("Splash\Connectors\ShippingBo\Models\Api\OrderItem")
     * })
     *
     * @SPL\Group("Items")
     */
    public array $items = array();
}
