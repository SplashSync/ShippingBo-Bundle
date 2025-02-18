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

namespace Splash\Connectors\ShippingBo\Test\Controller;

use Exception;
use Splash\Connectors\ShippingBo\Services\ShippingBoConnector;
use Splash\Connectors\ShippingBo\Services\WarehouseSlotsManager;
use Splash\Tests\Tools\ObjectsCase;
use Splash\Tests\Tools\Traits\ObjectsSetTestsTrait;

/**
 * Add Minimal Number of Objects
 */
class S00MinimalObjectsTest extends ObjectsCase
{
    use ObjectsSetTestsTrait;

    /**
     * Connector Server ID
     */
    const CONNECTOR = 'ThisIsSandBoxWsId';

    /**
     * Connector Webhook Action
     */
    const ACTION = 'webhook';

    /**
     * Test Connector Loading
     *
     * @throws Exception
     */
    public function testConnectorLoading(): void
    {
        //====================================================================//
        // Load Connector
        $connector = $this->getConnector(self::CONNECTOR);
        $this->assertInstanceOf(ShippingBoConnector::class, $connector);
    }

    /**
     * Ensure at least Two Addresses are Created
     *
     * @throws Exception
     */
    public function testAtLeastTwoAddress(): void
    {
        $objectType = "Address";
        //====================================================================//
        // Load Connector
        $connector = $this->getConnector(self::CONNECTOR);
        $this->assertInstanceOf(ShippingBoConnector::class, $connector);
        //====================================================================//
        // Load Objects List
        $rawList = $connector->getObjectList($objectType);
        $this->assertIsArray($rawList);
        $count = $rawList['meta']['current'] ?? 0;
        //====================================================================//
        // Check Counter
        if ($count >= 5) {
            return;
        }
        //====================================================================//
        // Get Target Field
        $fields = $connector->getObjectFields($objectType);
        //====================================================================//
        // Generate Dummy Object Data
        $dummyData = $this->prepareForTesting($objectType, $fields[0]);
        $this->assertIsArray($dummyData);
        $this->assertNotEmpty($dummyData);
        //====================================================================//
        // Add Addresses
        while ($count < 5) {
            //====================================================================//
            //   Verify Create Works
            $this->assertIsString(
                $connector->setObject($objectType, null, $dummyData)
            );
            $count++;
        }
    }

    /**
     * Ensure at least Two Warehouse Slots are Created
     *
     * @throws Exception
     */
    public function testAtLeastTwoWarehouseSlots(): void
    {
        //====================================================================//
        // Load Connector
        $connector = $this->getConnector(self::CONNECTOR);
        $this->assertInstanceOf(ShippingBoConnector::class, $connector);
        $warehouseSlotManager = $connector->getLocator()->getWarehouseSlotsManager();
        //====================================================================//
        // Count Number of Warehouses SLots
        $connector->connect();
        $this->assertTrue($warehouseSlotManager->fetchWarehouseSlots());
        $activeSlots = $warehouseSlotManager->getActiveSlots();
        $count = count($activeSlots);
        //====================================================================//
        // Add Warehouses SLot
        while ($count < 2) {
            $count++;
            $response = $connector->getConnexion()->post("/warehouse_slots", array(
                "name" => sprintf("TestSlot%d", $count)
            ));
            $this->assertIsArray($response);
        }
        //====================================================================//
        // Register All Stocks as Writable
        $this->assertTrue($connector->connect());
        $this->assertTrue($warehouseSlotManager->fetchWarehouseSlots());
        $activeSlots = $warehouseSlotManager->getActiveSlots();
        $this->assertNotEmpty($activeSlots);
        $connector->setParameter(
            WarehouseSlotsManager::WRITE,
            array_map(fn (array $whSlot) => $whSlot["id"], $activeSlots)
        );
        $connector->updateConfiguration();
    }
}
