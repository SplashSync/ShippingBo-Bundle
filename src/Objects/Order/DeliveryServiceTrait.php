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

namespace Splash\Connectors\ShippingBo\Objects\Order;

use Splash\Core\SplashCore      as Splash;

/**
 * Manage Order Delivery Services
 */
trait DeliveryServiceTrait
{
    /**
     * Check if this Order Delivery Services is Allowed
     *
     * @return bool
     */
    protected function isAllowedDeliveryService(): ?bool
    {
        //====================================================================//
        // Check If Received
        if (empty($this->in["chosen_delivery_service"])
            || !is_scalar($this->in["chosen_delivery_service"])) {
            return true;
        }
        //====================================================================//
        // Convert Delivery Services Name
        $serviceName = $this->toDeliveryServiceName((string) $this->in["chosen_delivery_service"]);
        if (null === $serviceName) {
            Splash::log()->war("Rejected Delivery Services Detected...");

            return false;
        }

        return true;
    }

    /**
     * Write Given Fields
     *
     * @param string $fieldName Field Identifier / Name
     * @param string|null $fieldData Field Data
     */
    protected function setDeliveryServiceFields(string $fieldName, ?string $fieldData): void
    {
        //====================================================================//
        // WRITE Field
        switch ($fieldName) {
            case "chosen_delivery_service":
                $this->setSimple(
                    $fieldName,
                    $this->toDeliveryServiceName((string) $fieldData)
                );

                break;
            default:
                return;
        }
        if (isset($this->in[$fieldName])) {
            unset($this->in[$fieldName]);
        }
    }

    /**
     * Mark Object as Filtered & Return Details in Log
     *
     * @return false
     */
    protected function logFilteredMethod(): bool
    {
        Splash::log()->war("This Object is Filtered by Delivery Method.");

        return false;
    }

    /**
     * Get Delivery Service
     *
     * @param string $serviceName
     *
     * @return null|string
     */
    private function toDeliveryServiceName(string $serviceName): ?string
    {
        //====================================================================//
        // Check Delivery Service is Not Empty
        if (empty($serviceName)) {
            return null;
        }
        //====================================================================//
        // Load List from Connector Parameters
        $carriers = $this->getParameter("ShippingMethods", array());
        //====================================================================//
        // Identify Service from Name
        if (is_array($carriers) && isset($carriers[trim($serviceName)])) {
            $serviceName = $carriers[trim($serviceName)];
            //====================================================================//
            // Service is Now Rejected
            if ("REJECTED" == $serviceName) {
                return null;
            }
            Splash::log()->war("Delivery Service changed to : ".$serviceName);
        }
        //====================================================================//
        // Return Service Name
        return $serviceName;
    }
}
