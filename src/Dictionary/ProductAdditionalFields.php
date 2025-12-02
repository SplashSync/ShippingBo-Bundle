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
 * Dictionary for Product Additional Fields Configurations
 */
class ProductAdditionalFields
{
    /**
     * Storage Key on Connector Configuration
     */
    const KEY = "ProductAdditionalFields";

    /**
     * Get Configuration Form Modes Choices
     */
    public static function getChoices() : array
    {
        return array(
            self::toTransKey(strtolower(SPL_T_VARCHAR)) => SPL_T_VARCHAR,
            self::toTransKey(strtolower(SPL_T_BOOL)) => SPL_T_BOOL,
        );
    }

    /**
     * Get Translation Keys with Prefix on Connector Translation Files
     */
    public static function toTransKey(string $key) : string
    {
        return sprintf("var.products.additionalFields.%s", $key);
    }
}
