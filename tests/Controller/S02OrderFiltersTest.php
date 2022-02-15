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

namespace Splash\Connectors\ShippingBo\Test\Controller;

use Exception;
use Splash\Connectors\ShippingBo\Services\ShippingBoConnector;
use Splash\Tests\Tools\ObjectsCase;
use Splash\Tests\Tools\Traits\ObjectsSetTestsTrait;

/**
 * Test of ShippingBo Connector Order Filters
 */
class S02OrderFiltersTest extends ObjectsCase
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
     * Verify Connector Setup
     *
     * @throws Exception
     */
    public function testCheckConnectorSetup(): void
    {
        //====================================================================//
        // Load Connector
        $connector = $this->getConnector(self::CONNECTOR);
        $this->assertInstanceOf(ShippingBoConnector::class, $connector);
        //====================================================================//
        // Setup Connector
        $minDate = new \DateTime('2000-01-01');
        $connector->setParameter("minObjectDate", $minDate);
        $connector->updateConfiguration();
        //====================================================================//
        // Verify
        $this->assertEquals($minDate, $connector->getParameter("minObjectDate"));
        $this->assertEquals(array(
            "DropShipping" => "REJECTED",
            "Colissimo" => "default"
        ), $connector->getParameter("ShippingMethods"));
        $this->assertEquals(array(
            "AllowedWebsite" => "pass",
            "RejectedWebsite" => "REJECTED"
        ), $connector->getParameter("OrderOrigins"));
    }

    /**
     * Test Order Filtered / Rejected by Fields Values
     *
     * @dataProvider orderDataProvider
     *
     * @throws Exception
     */
    public function testOrderFiltered(string $fieldId, string $value, bool $rejected): void
    {
        $objectType = "Order";
        //====================================================================//
        // Load Connector
        $connector = $this->getConnector(self::CONNECTOR);
        $this->assertInstanceOf(ShippingBoConnector::class, $connector);
        //====================================================================//
        // Get Target Field
        $fields = $connector->getObjectFields($objectType);
        $field = $this->findField($fields, array($fieldId));
        $this->assertIsArray($field);
        //====================================================================//
        // Generate Dummy Object Data
        $dummyData = $this->prepareForTesting($objectType, $field);
        if (false == $dummyData) {
            return;
        }
        //====================================================================//
        // Force Dummy Object Data
        $dummyData[$fieldId] = $value;
        if (!$rejected) {
            //====================================================================//
            //   Verify Create Works
            $this->assertIsString(
                $connector->setObject("Order", null, $dummyData)
            );

            return;
        }
        //====================================================================//
        // verify Create is Rejected
        $this->assertEquals(
            "REJECTED",
            $connector->setObject("Order", null, $dummyData)
        );
    }

    /**
     * Get Order Tested Fields.
     *
     * @return array<string, array>
     */
    public function orderDataProvider() : array
    {
        return array(
            // Filter Orders by Date
            "DateOk" => array("origin_created_at", "2001-01-01 00:00:00", false),
            "DateKo" => array("origin_created_at", "1998-01-01 00:00:00", true),
            // Filter Orders by Delivery method
            "MethodOk" => array("chosen_delivery_service", "Colissimo", false),
            "MethodOk2" => array("chosen_delivery_service", "DPD", false),
            "MethodKo" => array("chosen_delivery_service", "DropShipping", true),
            // Filter Orders by Origin
            "OriginOk" => array("origin", "AllowedWebsite", false),
            "OriginOk2" => array("origin", "AnyWebsite", false),
            "OriginKo" => array("origin", "RejectedWebsite", true),
        );
    }
}
