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

namespace Splash\Connectors\ShippingBo\Objects\SupplierOrder;

use Splash\Connectors\ShippingBo\Configurators\SupplyCapsuleConfigurator;

trait CoreTrait
{
    /**
     * Build Core Fields using FieldFactory
     */
    protected function buildCoreFields(): void
    {
        //====================================================================//
        // Register Order Configurator
        $this->fieldsFactory()->registerConfigurator(
            "SupplierOrder",
            new SupplyCapsuleConfigurator()
        );
    }
}
