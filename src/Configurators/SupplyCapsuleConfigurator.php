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

namespace Splash\Connectors\ShippingBo\Configurators;

use Splash\Client\Splash;
use Splash\Models\AbstractConfigurator;

/**
 * Main Supply Capsule Fields Configurator
 */
class SupplyCapsuleConfigurator extends AbstractConfigurator
{
    const CONFIGURATION = array(
        //====================================================================//
        // Supplier Order Items
        //====================================================================//
        "source_ref@supplyCapsuleItems" => array(
            "required" => false,
            "inlist" => false,
            "itemtype" => "http://schema.org/Product",
            "itemprop" => "mpn",
        ),
    );

    const DEBUG = array(
        // Force Testing of Order States Changes
        "state" => array('required' => false, "write" => true),
    );

    /**
     * {@inheritdoc}
     */
    public function getConfiguration(): array
    {
        return array(
            "SupplierOrder" => array("fields" => array_replace_recursive(
                self::CONFIGURATION,
                Splash::isDebugMode() ? self::DEBUG : array()
            )));
    }
}
