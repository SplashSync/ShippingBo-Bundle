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

namespace App\Entity;

use Symfony\Component\Serializer\Annotation\Ignore;

/**
 * Sandbox Api Interface for All ShippingBo Objects
 */
interface SboObjectInterface
{
    /**
     * Get Index Value for Serialized Items Data
     *
     * @return null|string
     *
     * @Ignore
     */
    public static function getItemIndex(): ?string;

    /**
     * Get Index Value for Serialized Collections Data
     *
     * @return null|string
     *
     * @Ignore
     */
    public static function getCollectionIndex(): ?string;
}
