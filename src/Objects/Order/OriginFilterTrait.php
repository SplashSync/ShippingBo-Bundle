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

use DateTime;
use Splash\Core\SplashCore      as Splash;

/**
 * Filter Sbo Object Creation by Origin Name
 */
trait OriginFilterTrait
{
    /**
     * Check if this Object is Allowed Writing
     *
     * @return bool|null
     */
    protected function isAllowedOrigin(): ?bool
    {
        //====================================================================//
        // Check if Origins are Selected
        $knownOrigins = $this->connector->getParameter("OrderOrigins");
        if (!is_array($knownOrigins) || empty($knownOrigins)) {
            return true;
        }
        //====================================================================//
        // Check If received Origin is Given
        if (!isset($this->in["origin"]) || empty($this->in["origin"]) || !is_scalar($this->in["origin"])) {
            return false;
        }
        //====================================================================//
        // Identify Origin by Name
        if (isset($knownOrigins[trim($this->in["origin"])])) {
            if ("REJECTED" == $knownOrigins[trim($this->in["origin"])]) {
                return false;
            }
        }

        return true;
    }

    /**
     * Mark Object as Filtered & Return Details in Log
     *
     * @return false
     */
    protected function logFilteredOrigin(): bool
    {
        Splash::log()->war("This Object is Filtered by Origin.");

        return false;
    }
}
