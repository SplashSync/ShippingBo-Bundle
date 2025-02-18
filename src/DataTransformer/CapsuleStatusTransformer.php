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

use Splash\Connectors\ShippingBo\Dictionary\SupplyCapsuleState;
use Splash\Models\Objects\Order\Status;

class CapsuleStatusTransformer
{
    /**
     * List of Orders Status Names for Splash
     */
    public const CHOICES = array(
        Status::CANCELED => "Canceled",
        Status::DRAFT => "Draft",
        Status::PROCESSING => "Processing",
        Status::IN_TRANSIT => "In Transit",
        Status::DELIVERED => "Delivered",
        Status::PROBLEM => "In Error",
    );

    /**
     * List of Orders Status for Splash
     *
     * @var array
     */
    const SPLASH = array(
        SupplyCapsuleState::DRAFT => Status::DRAFT,
        SupplyCapsuleState::UPLOADING => Status::DRAFT,
        SupplyCapsuleState::WAITING => Status::PROCESSING,
        SupplyCapsuleState::SENT_TO_LOGISTICS => Status::PROCESSING,
        SupplyCapsuleState::DISPATCHED => Status::IN_TRANSIT,
        SupplyCapsuleState::ON_GOING => Status::IN_TRANSIT,
        SupplyCapsuleState::RECEIVED => Status::DELIVERED,
        SupplyCapsuleState::CANCELED => Status::CANCELED,
        SupplyCapsuleState::ERROR => Status::PROBLEM,
    );

    /**
     * Convert Raw Status ID to Splash Status
     */
    public static function toSplash(string $status): string
    {
        return self::SPLASH[$status] ?? Status::UNKNOWN;
    }

    /**
     * Convert Splash Status to ShippingBo Status ID
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
        return Status::isValidated(self::toSplash($status));
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
     * Check if Supply Capsule Status Code Allow Item Updates
     */
    public static function isAllowedItemsUpdates(string $status): bool
    {
        return in_array($status, array(
            SupplyCapsuleState::UPLOADING,
            SupplyCapsuleState::DRAFT,
            SupplyCapsuleState::WAITING,
            SupplyCapsuleState::ERROR,
        ), true);
    }

    /**
     * Check if Current Order Status Code Allowed for Validation
     */
    public static function isAllowedValidate(string $status): bool
    {
        return in_array($status, array(
            SupplyCapsuleState::UPLOADING,
            SupplyCapsuleState::DRAFT,
            SupplyCapsuleState::WAITING,
            SupplyCapsuleState::CANCELED,
            SupplyCapsuleState::ERROR,
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
        return !in_array($status, array(
            SupplyCapsuleState::DISPATCHED,
            SupplyCapsuleState::ON_GOING,
            SupplyCapsuleState::RECEIVED,
        ), true);
    }
}
