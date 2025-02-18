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

namespace Splash\Connectors\ShippingBo\Services;

use Splash\Connectors\ShippingBo\Models\Connector\ShippingBoConnectorAwareTrait;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;
use Webmozart\Assert\Assert;

class WebhooksManager
{
    use ShippingBoConnectorAwareTrait;

    public function __construct(
        private readonly RouterInterface $router
    ) {
    }

    /**
     * Check API WebHooks Configuration.
     */
    public function verifyWebHooks() : bool
    {
        //====================================================================//
        // Connector SelfTest
        if (!$this->connector->selfTest()) {
            return false;
        }
        //====================================================================//
        // Generate WebHook Url
        $webHookUrl = $this->getWebHooksUrl();
        //====================================================================//
        // Get WebHooks List
        $webHooks = $this->getSplashWebHooks($webHookUrl);
        //====================================================================//
        // Update WebHooks Configurations
        $success = $this->checkWebHookConfig($webHooks, $webHookUrl, "Order");
        $success = $success && $this->checkWebHookConfig($webHooks, $webHookUrl, "Product");

        return $success && $this->checkWebHookConfig($webHooks, $webHookUrl, "SupplyCapsule");
    }

    /**
     * Check & Update API WebHooks.
     */
    public function updateWebHooks() : bool
    {
        //====================================================================//
        // Connector SelfTest
        if (!$this->connector->selfTest()) {
            return false;
        }
        //====================================================================//
        // Generate WebHook Url
        $webHookUrl = $this->getWebHooksUrl();
        //====================================================================//
        // Get WebHooks List
        $webHooks = $this->getSplashWebHooks($webHookUrl);
        //====================================================================//
        // Update WebHooks Configurations
        $success = $this->updateWebHookConfig($webHooks, $webHookUrl, "Order");
        $success = $success && $this->updateWebHookConfig($webHooks, $webHookUrl, "Product");

        return $success && $this->updateWebHookConfig($webHooks, $webHookUrl, "SupplyCapsule");
    }

    /**
     * Check ShippingBo WebHook Configuration is Available.
     */
    private function checkWebHookConfig(
        array  $webHooks,
        string $endpointUrl,
        string $objectClass,
    ) : bool {
        //====================================================================//
        // Walk on List Of WebHooks
        foreach ($webHooks as $webHook) {
            //====================================================================//
            // This is Current WebHook ?
            if (($webHook["object_class"] != $objectClass) || ($webHook["endpoint_url"] != $endpointUrl)) {
                continue;
            }

            return true;
        }

        //====================================================================//
        // Splash WebHooks was NOT Found
        return false;
    }

    /**
     * Create & Update ShippingBo WebHook Configuration.
     */
    private function updateWebHookConfig(
        array  $webHooks,
        string $endpointUrl,
        string $objectClass,
        array  $options = array()
    ) : bool {
        //====================================================================//
        // Build Configuration
        $config = array_replace_recursive(array(
            "object_class" => $objectClass,
            "activated" => true,
            "visible" => false,
            "triggerOnDestroy" => true,
            "endpoint_url" => $endpointUrl,
        ), $options);
        //====================================================================//
        // Filter & Clean List Of WebHooks
        $foundWebHook = false;
        foreach ($webHooks as $webHook) {
            //====================================================================//
            // This is Current WebHook ?
            if (($webHook["object_class"] != $objectClass) || ($webHook["endpoint_url"] != $endpointUrl)) {
                continue;
            }
            $foundWebHook = true;
            //====================================================================//
            // Update WebHook Configuration
            Assert::stringNotEmpty($webHook["id"]);
            $this->connector->setObject("Webhook", $webHook["id"], $config);
        }
        //====================================================================//
        // Splash WebHooks was Found
        if ($foundWebHook) {
            return true;
        }

        //====================================================================//
        // Add Splash WebHooks
        return (false !== $this->connector->setObject("Webhook", null, $config));
    }

    /**
     * Fetch All Splash WebHooks by API.
     */
    private function getSplashWebHooks(string $webHookUrl) : array
    {
        $webHooks = array();

        $offset = 0;

        do {
            //====================================================================//
            // Fetch WebHooks List
            $rawWebHooks = $this->connector->getObjectList("Webhook", null, array(
                "offset" => $offset,
                "max" => 25
            ));
            $offset += 25;
            //====================================================================//
            // Safety Check
            if (!isset($rawWebHooks["meta"]) || empty($rawWebHooks["meta"]["current"])) {
                break;
            }
            unset($rawWebHooks["meta"]);
            //====================================================================//
            // Walk on WebHooks
            foreach ($rawWebHooks as $webHookConfig) {
                //====================================================================//
                // This a Splash WebHook
                $endpoint = $webHookConfig["endpoint_url"] ?? null;
                if ($endpoint == $webHookUrl) {
                    $webHooks[] = $webHookConfig;
                }
            }
        } while (empty($rawWebHooks["meta"]["current"]));

        return $webHooks;
    }

    /**
     * Check & Update API WebHooks.
     */
    private function getWebHooksUrl() : string
    {
        //====================================================================//
        // Setup Hostname for WebHooks
        $this->router->getContext()
            ->setHost($this->getHostname())
            ->setScheme("https")
        ;

        //====================================================================//
        // Generate WebHook Url
        return $this->router->generate(
            'splash_connector_action',
            array(
                'connectorName' => $this->connector->getProfile()["name"],
                'webserviceId' => $this->connector->getWebserviceId(),
            ),
            UrlGeneratorInterface::ABSOLUTE_URL
        );
    }

    /**
     * Get HostName for Webhooks
     */
    private function getHostname(): string
    {
        static $hostAliases = array(
            "localhost" => "eu-99.splashsync.com",
            "toolkit.shipping-bo.local" => "eu-99.splashsync.com",
            "eu-99.splashsync.com" => "app-99.splashsync.com",
            "www.splashsync.com" => "proxy.splashsync.com",
            "app.splashsync.com" => "proxy.splashsync.com",
            "admin.splashsync.com" => "proxy.splashsync.com"
        );
        //====================================================================//
        // Get Current Server Name
        $hostName = $this->router->getContext()->getHost();
        //====================================================================//
        // Detect Server Aliases
        foreach ($hostAliases as $source => $target) {
            if (str_contains($source, $hostName)) {
                $hostName = $target;
            }
        }

        return $hostName;
    }
}
