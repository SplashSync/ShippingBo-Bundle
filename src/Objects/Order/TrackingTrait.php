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

namespace   Splash\Connectors\ShippingBo\Objects\Order;

use Splash\Connectors\ShippingBo\Models\Api\Shipment;

/**
 * ShippingBo Orders Tracking Fields Access
 */
trait TrackingTrait
{
    /**
     * Build Fields using FieldFactory
     */
    protected function buildTrackingFields(): void
    {
        //====================================================================//
        // Order First Shipping Carrier Name
        $this->fieldsFactory()->create(SPL_T_VARCHAR)
            ->identifier("carrierName")
            ->name("Carrier name")
            ->microData("http://schema.org/ParcelDelivery", "name")
            ->group("Tracking")
            ->isReadOnly()
        ;
        //====================================================================//
        // Order First Shipping Tracking Number
        $this->fieldsFactory()->create(SPL_T_VARCHAR)
            ->identifier("shippingRef")
            ->name("Tracking Number")
            ->microData("http://schema.org/ParcelDelivery", "trackingNumber")
            ->group("Tracking")
            ->isReadOnly()
        ;
        //====================================================================//
        // Order First Shipping Tracking Url
        $this->fieldsFactory()->create(SPL_T_URL)
            ->identifier("trackingUrl")
            ->name("Tracking Url")
            ->microData("http://schema.org/ParcelDelivery", "trackingUrl")
            ->group("Tracking")
            ->isReadOnly()
        ;
    }

    /**
     * Read requested Field
     *
     * @param string $key       Input List Key
     * @param string $fieldName Field Identifier / Name
     */
    protected function getTrackingFields(string $key, string $fieldName): void
    {
        //====================================================================//
        // READ Fields
        switch ($fieldName) {
            case 'carrierName':
            case 'shippingRef':
            case 'trackingUrl':
                $shipment = $this->getFirstShipment();
                $this->out[$fieldName] = $shipment ? ($shipment->{$fieldName} ?? null) : null;

                break;
            default:
                return;
        }
        unset($this->in[$key]);
    }

    /**
     * Get Order First Shipment
     *
     * @return null|Shipment
     */
    private function getFirstShipment(): ?Shipment
    {
        //====================================================================//
        // Safety Check
        if (!isset($this->object->shipments) || !is_iterable($this->object->shipments)) {
            return null;
        }
        //====================================================================//
        // Get First Shipment
        foreach ($this->object->shipments as $shipment) {
            if ($shipment instanceof Shipment) {
                return $shipment;
            }
        }

        return null;
    }
}
