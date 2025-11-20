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

namespace Splash\Connectors\ShippingBo\Dictionary;

/**
 * Dictionary for Supplier Filters Modes
 */
class SupplierFilterModes
{
    /**
     * Storage Key on Connector Configuration
     */
    const KEY = "ProductSupplierFilters";

    /**
     * Synchronize Products from this Supplier
     * - This is the Default Mode
     * - Product is Create & Updated Normally
     */
    const SYNC = "sync";

    /**
     * Block Products from this Supplier
     * - Products are not Created or Updated or Deleted
     * - Trying to Create / Delete this Product will result in an Error
     * - Trying to Update this Products will be Skipped without any Warning
     */
    const BLOCK = "block";

    /**
     * Get Configuration Form Modes Choices
     */
    public static function getChoices() : array
    {
        return array(
            self::toTransKey(strtolower(self::SYNC)) => self::SYNC,
            self::toTransKey(strtolower(self::BLOCK)) => self::BLOCK,
        );
    }

    /**
     * Get Translation Keys with Prefix on Connector Translation Files
     */
    public static function toTransKey(string $key) : string
    {
        return sprintf("var.products.supplierFilter.%s", $key);
    }
}
