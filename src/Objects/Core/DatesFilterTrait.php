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

namespace Splash\Connectors\ShippingBo\Objects\Core;

use DateTime;
use Splash\Core\SplashCore      as Splash;

/**
 * Filter Sbo Object Creation by Created Dates
 */
trait DatesFilterTrait
{
    /**
     * Check if this Object is Allowed Writing
     *
     * @return bool
     */
    protected function isAllowedDate(): bool
    {
        //====================================================================//
        // Check If Min Date was Selected
        $minDate = $this->connector->getParameter("minObjectDate");
        if (!($minDate instanceof DateTime)) {
            return true;
        }
        //====================================================================//
        // Check If Received Order Date is Given
        if (!isset($this->in["origin_created_at"])
            || empty($this->in["origin_created_at"])
            || !is_scalar($this->in["origin_created_at"])) {
            return false;
        }

        //====================================================================//
        // Convert Received Order date to Datetime
        try {
            $receivedDate = new DateTime((string)$this->in["origin_created_at"]);
        } catch (\Exception $e) {
            return Splash::log()->err(sprintf(
                "Unable to parse origin_created_at: %s",
                $this->in["origin_created_at"]
            ));
        }

        //====================================================================//
        // Check if Received date is After Selected Date
        return ($receivedDate > $minDate);
    }

    /**
     * Mark Object as Filtered & Return Details in Log
     *
     * @return false
     */
    protected function logFilteredDate(): bool
    {
        Splash::log()->war("This Object is Filtered by Create Date.");

        return false;
    }
}
