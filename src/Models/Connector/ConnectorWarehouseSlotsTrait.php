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

use Splash\Connectors\ShippingBo\Services\WarehouseSlotsManager;

/**
 * Manage Access to Customer Warehouse Slots Stocks
 */
trait ConnectorWarehouseSlotsTrait
{
    /**
     * Get Sellsy Taxes Manager
     */
    public function getWarehouseSlotsManager(): WarehouseSlotsManager
    {
        return $this->warehouseSlotsManager->configure($this);
    }

    /**
     * Get List of SBO Warehouse Slots from API
     */
    public function fetchWarehouseSlots(): bool
    {
        return $this->getWarehouseSlotsManager()->fetchWarehouseSlots($this);
    }
}
