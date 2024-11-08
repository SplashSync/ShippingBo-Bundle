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

namespace Splash\Connectors\ShippingBo\DataTransformer;

use Splash\Client\Splash;
use Splash\Models\Objects\Order\Status;

class StatusTransformer
{
    /**
     * List of Orders Status for Splash
     *
     * @var array
     */
    const SPLASH = array(
        //====================================================================//
        // Real ReCommerce Statuses
        "in_trouble" => Status::PROBLEM,                // In Error
        "waiting_for_payment" => Status::PAYMENT_DUE,   // Draft | Waiting for Payment
        "waiting_for_stock" => Status::OUT_OF_STOCK,    // Waiting for Stock

        "to_be_prepared" => Status::PROCESSING,         // Processing.
        "merged" => Status::PROCESSING,                 // Processing.
        "sent_to_logistics" => Status::PROCESSING,      // Processing.
        "dispatched" => Status::PROCESSING,             // Processing.
        "splitted" => Status::PROCESSING,               // Processing.
        "in_preparation" => Status::PROCESSING,         // Processing.

        "partially_shipped" => Status::IN_TRANSIT,      // Shipped Partially.
        "shipped" => Status::IN_TRANSIT,                // Shipped Totally.
        "handed_to_carrier" => Status::IN_TRANSIT,      // Send to Carrier.
        "at_pickup_location" => Status::PICKUP,         // Waiting at Relay Point.
        "closed" => Status::DELIVERED,                  // Closed, Order is Delivered.
        "back_from_client" => Status::RETURNED,         // Canceled after Shipment | Returned.
        "rejected" => Status::CANCELED,                 // Rejected.
        "canceled" => Status::CANCELED,                 // Cancelled.
    );

    /**
     * Get All Available Splash Status
     *
     * @return array
     */
    public static function getAll(): array
    {
        return Status::getAllChoices(true);
    }

    /**
     * Convert Raw Status ID to Splash Status
     *
     * @param string $status
     *
     * @return string
     */
    public static function toSplash(string $status): string
    {
        return self::SPLASH[$status] ?? "Unknown";
    }

    /**
     * Convert Splash Status to ShippingBo Status ID
     *
     * @param string $status
     *
     * @return null|string
     */
    public static function toShippingBo(string $status): ?string
    {
        $index = array_search($status, self::SPLASH, true);
        if (false === $index) {
            return null;
        }

        return (string) $index;
    }

    /**
     * Check if Order Status Code is Validated
     *
     * @param string $status ShippingBo Order Status Code
     *
     * @return bool
     */
    public static function isValidated(string $status): bool
    {
        return ("waiting_for_payment" != $status) && Status::isValidated(self::toSplash($status));
    }

    /**
     * Check if Order Status Code is Canceled
     *
     * @param string $status Order Status Code
     *
     * @return bool
     */
    public static function isCanceled(string $status): bool
    {
        return Status::isCanceled(self::toSplash($status));
    }

    /**
     * Check if Order Status Code is Delivered
     *
     * @param string $status Order Status Code
     *
     * @return bool
     */
    public static function isDelivered(string $status): bool
    {
        return Status::isDelivered(self::toSplash($status));
    }

    /**
     * Check if Order Status Code is Updated by Splash
     *
     * @param string $status Order Status Code
     *
     * @return bool
     */
    public static function isAllowedUpdates(string $status): bool
    {
        return in_array($status, array(
            "in_trouble",           // In Error
            "waiting_for_payment",  // Order waiting for Payment.
            "waiting_for_stock",    // Order waiting for Stocks.
            "to_be_prepared",       // Order is to be Prepared.
            "rejected",             // Order was Rejected.
            "canceled",             // Cancelled.
        ), true);
    }

    /**
     * Check if Current Order Status Code Allowed for Validation
     *
     * @param string $status Order Status Code
     *
     * @return bool
     */
    public static function isAllowedValidate(string $status): bool
    {
        return in_array($status, array(
            "in_trouble",           // In Error
            "waiting_for_payment",  // Order waiting for Payment.
            "waiting_for_stock",    // Order waiting for Stocks.
            "rejected",             // Order was Rejected.
            "canceled",             // Cancelled.
        ), true);
    }

    /**
     * Check if Current Order Status Code Allowed for Cancel
     *
     * @param string $status Order Status Code
     *
     * @return bool
     */
    public static function isAllowedCancel(string $status): bool
    {
        return in_array($status, array(
            "in_trouble",           // In Error
            "waiting_for_payment",  // Order waiting for Payment.
            "waiting_for_stock",    // Order waiting for Stocks.
            "to_be_prepared",       // Order is to be Prepared.
            "rejected",             // Order was Rejected.
        ), true);
    }
}
