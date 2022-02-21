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

namespace Splash\Connectors\ShippingBo\Configurators;

use Splash\Models\AbstractConfigurator;

/**
 * Main Product Fields Configurator
 */
class ProductConfigurator extends AbstractConfigurator
{
    const CONFIGURATION = array(
        //====================================================================//
        // System - Read Only
        //====================================================================//

        "source" => array('read' => true, "write" => false, "required" => false),

        //====================================================================//
        // System - Excluded
        //====================================================================//
    );

    /**
     * {@inheritdoc}
     */
    public function getConfiguration(): array
    {
        return array(
            "Product" => array("fields" => self::CONFIGURATION)
        );
    }
}
