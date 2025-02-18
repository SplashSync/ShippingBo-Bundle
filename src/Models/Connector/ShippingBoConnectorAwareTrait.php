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

namespace Splash\Connectors\ShippingBo\Models\Connector;

use Splash\Connectors\ShippingBo\Services\ShippingBoConnector;

/**
 * Makes a Service Aware of ShippingBo Connector
 */
trait ShippingBoConnectorAwareTrait
{
    /**
     * Currently Used Connector
     */
    private ShippingBoConnector $connector;

    /**
     * Configure with Current API Connexion Settings
     */
    public function configure(ShippingBoConnector $connector): static
    {
        $this->connector = $connector;

        return $this;
    }
}
