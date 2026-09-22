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

/**
 * Convert Shipping Bo PricesFloat Prices to Cents
 */
class PriceTransformer
{
    /**
     * Maximum value accepted by Shipping Bo for Prices in Cents (Signed 4 Bytes Integer)
     *
     * @var int
     */
    const MAX_CENTS = 2147483647;

    /**
     * Minimum value accepted by Shipping Bo for Prices in Cents (Signed 4 Bytes Integer)
     *
     * @var int
     */
    const MIN_CENTS = -self::MAX_CENTS - 1;

    /**
     * Convert a Float Price to Cents, clamped to a Signed 4 Bytes Integer
     */
    public static function toCents(float $price): int
    {
        $cents = round(100 * $price);

        //====================================================================//
        // Ensure Value Fits in a Signed 4 Bytes Integer
        if ($cents >= self::MAX_CENTS) {
            return self::MAX_CENTS;
        }
        if ($cents <= self::MIN_CENTS) {
            return self::MIN_CENTS;
        }

        return (int) $cents;
    }
}
