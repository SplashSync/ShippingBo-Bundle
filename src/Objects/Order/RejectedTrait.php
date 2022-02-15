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

namespace Splash\Connectors\ShippingBo\Objects\Order;

use Splash\Connectors\ShippingBo\Models\Api\Order;

/**
 * Manage Rejected Orders Creation
 */
trait RejectedTrait
{
    /**
     * @var string
     */
    private static string $rejectedId = "REJECTED";

    /**
     * Check if Order ID is a Rejected ID
     *
     * @param string $objectId
     *
     * @return bool
     */
    protected function isRejectedId(string $objectId): bool
    {
        return str_contains($objectId, self::$rejectedId);
    }

    /**
     * Init Order Object as Rejected
     *
     * @return Order
     */
    protected function initRejected(): Order
    {
        $this->object = new Order();
        $this->object->id = self::$rejectedId;

        return $this->object;
    }
}
