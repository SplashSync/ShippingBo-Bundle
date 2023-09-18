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

namespace Splash\Connectors\ShippingBo\Models\Api\Order;

use JMS\Serializer\Annotation as JMS;
use Splash\OpenApi\Validator as SPL;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Sbo Order Delivery Service Management
 */
trait DeliveryServiceTrait
{
    /**
     * Order Delivery Service.
     *
     * @var null|string
     *
     * @Assert\Type("string")
     *
     * @JMS\SerializedName("chosen_delivery_service")
     *
     * @JMS\Type("string")
     *
     * @JMS\Groups ({"Read", "Write", "Required"})
     *
     * @SPL\Microdata({"http://schema.org/ParcelDelivery", "identifier"})
     */
    public ?string $chosen_delivery_service = null;
}
