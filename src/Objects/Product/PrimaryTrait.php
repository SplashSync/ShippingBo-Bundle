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

namespace Splash\Connectors\ShippingBo\Objects\Product;

/**
 * Search for Products by Primary Key
 */
trait PrimaryTrait
{
    /**
     * @inheritDoc
     */
    public function getByPrimary(array $keys): ?string
    {
        //====================================================================//
        // Safety Check
        $userRef = $keys['user_ref'] ?? null;
        if (!$userRef && is_string($userRef)) {
            return null;
        }
        //====================================================================//
        // Search by User Ref
        $productsList = $this->objectsList($userRef);
        if (1 != $productsList['meta']['current']) {
            return null;
        }
        //====================================================================//
        // Search In Results
        $firstProductId = array_shift($productsList)['id'] ?? null;
        if (empty($firstProductId) || !is_string($firstProductId)) {
            return null;
        }

        return $firstProductId;
    }
}
