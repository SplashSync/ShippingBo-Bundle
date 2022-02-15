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
use Splash\Tests\Tools\TestCase;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Test of ShippingBo Connector WebHook Controller
 */
class S01WebHookTest extends TestCase
{
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
     * Test WebHook HTTP Methods
     *
     * @throws Exception
     */
    public function testWebhookMethods(): void
    {
        //====================================================================//
        // Load Connector
        $connector = $this->getConnector(self::CONNECTOR);
        $this->assertInstanceOf(ShippingBoConnector::class, $connector);

        //====================================================================//
        // PING -> OK
        $this->assertPublicActionWorks($connector, null, array(), "POST");
        $this->assertNotEmpty($this->getResponseContents());
        //====================================================================//
        // POST -> FORBIDDEN
        $this->assertPublicActionFail($connector, self::ACTION, array(), "POST");
        $this->assertEquals(JsonResponse::HTTP_BAD_REQUEST, $this->getResponseCode());
        //====================================================================//
        // GET -> BAD_REQUEST
        $this->assertPublicActionFail($connector, self::ACTION, array(), "GET");
        $this->assertEquals(JsonResponse::HTTP_BAD_REQUEST, $this->getResponseCode());
        //====================================================================//
        // PUT -> BAD_REQUEST
        $this->assertPublicActionFail($connector, self::ACTION, array(), "PUT");
        $this->assertEquals(JsonResponse::HTTP_BAD_REQUEST, $this->getResponseCode());
        //====================================================================//
        // PATCH -> BAD_REQUEST
        $this->assertPublicActionFail($connector, self::ACTION, array(), "PATCH");
        $this->assertEquals(JsonResponse::HTTP_BAD_REQUEST, $this->getResponseCode());
        //====================================================================//
        // DELETE -> BAD_REQUEST
        $this->assertPublicActionFail($connector, self::ACTION, array(), "DELETE");
        $this->assertEquals(JsonResponse::HTTP_BAD_REQUEST, $this->getResponseCode());
    }

    /**
     * Test WebHook with Errors
     *
     * @throws Exception
     */
    public function testWebhookErrors(): void
    {
        //====================================================================//
        // Load Connector
        $connector = $this->getConnector(self::CONNECTOR);
        $this->assertInstanceOf(ShippingBoConnector::class, $connector);

        //====================================================================//
        // Empty Contents
        //====================================================================//

        $this->assertPublicActionFail($connector, self::ACTION, array(), "POST");
        $this->assertEquals(JsonResponse::HTTP_BAD_REQUEST, $this->getResponseCode());

        //====================================================================//
        // Partial Contents
        //====================================================================//

        $partial = array("object" => array());
        $this->assertPublicActionFail($connector, self::ACTION, $partial, "POST");
        $this->assertEquals(JsonResponse::HTTP_BAD_REQUEST, $this->getResponseCode());

        //====================================================================//
        // No Type Contents
        //====================================================================//

        $noType = array("object" => array(
            "ids" => uniqid()
        ));
        $this->assertPublicActionFail($connector, self::ACTION, $noType, "POST");
        $this->assertEquals(JsonResponse::HTTP_BAD_REQUEST, $this->getResponseCode());

        //====================================================================//
        // No Id Contents
        //====================================================================//

        $noId = array(
            "object_class" => "Order",
            "object" => array("noId" => uniqid())
        );
        $this->assertPublicActionFail($connector, self::ACTION, $noId, "POST");
        $this->assertEquals(JsonResponse::HTTP_BAD_REQUEST, $this->getResponseCode());

        //====================================================================//
        // Wrong Ids Contents
        //====================================================================//

        $wrongIds = array(
            "object_class" => "Product",
            "object" => array("id" => "")
        );
        $this->assertPublicActionFail($connector, self::ACTION, $wrongIds, "POST");
        $this->assertEquals(JsonResponse::HTTP_BAD_REQUEST, $this->getResponseCode());
    }

    /**
     * Test WebHook Updates
     *
     * @dataProvider webHooksInputsProvider
     *
     * @param array  $data
     * @param string $objectType
     * @param string $action
     * @param string $objectId
     *
     * @throws Exception
     *
     * @return void
     */
    public function testWebhookOkRequest(
        array $data,
        string $objectType,
        string $action,
        string $objectId
    ): void {
        //====================================================================//
        // Load Connector
        $connector = $this->getConnector(self::CONNECTOR);
        $this->assertInstanceOf(ShippingBoConnector::class, $connector);

        //====================================================================//
        // POST MODE
        $this->assertPublicActionWorks($connector, self::ACTION, $data, "POST");
        $this->assertEquals(JsonResponse::HTTP_OK, $this->getResponseCode());
        $this->assertIsLastCommitted($action, $objectType, $objectId);

        //====================================================================//
        // JSON POST MODE
        $this->assertPublicActionWorks($connector, self::ACTION, $data, "JSON");
        $this->assertEquals(JsonResponse::HTTP_OK, $this->getResponseCode());
        $this->assertIsLastCommitted($action, $objectType, $objectId);
    }

    /**
     * Generate Fake Inputs for WebHook Requests
     *
     * @return array
     */
    public function webHooksInputsProvider(): array
    {
        $hooks = array();

        //====================================================================//
        // Add Product WebHooks
        for ($i = 0; $i < 10; $i++) {
            $uniqueId = uniqid("Product-");
            $hooks[$uniqueId] = array(
                array(
                    "object_class" => "Product",
                    "object" => array("id" => $uniqueId)
                ),
                "Product",
                SPL_A_UPDATE,
                $uniqueId
            );
        }
        //====================================================================//
        // Add Order WebHooks
        for ($i = 0; $i < 10; $i++) {
            $uniqueId = uniqid("Order-");
            $hooks[$uniqueId] = array(
                array(
                    "object_class" => "Order",
                    "object" => array("id" => $uniqueId)
                ),
                "Order",
                SPL_A_UPDATE,
                $uniqueId
            );
        }

        return $hooks;
    }

    /**
     * Get Framework Client Response Code.
     *
     * @return int
     */
    private function getResponseCode() : int
    {
        $jsonResponse = $this->getResponseContents();
        $this->assertIsString($jsonResponse);
        $response = json_decode($jsonResponse, true);
        $this->assertIsArray($response);
        $this->assertArrayHasKey("code", $response);
        $this->assertIsInt($response["code"]);

        return $response["code"];
    }
}
