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

namespace Splash\Connectors\ShippingBo\Models\Api;

use DateTime;
use JMS\Serializer\Annotation as JMS;
use Splash\OpenApi\Validator as SPL;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class representing the Order Shipment model.
 */
class Shipment
{
    //====================================================================//
    // JSON PREFIXES
    const COLLECTION_PROP = "shipments";
    const ITEMS_PROP = "shipment";

    //====================================================================//
    // SPLASH EXCLUDED PROPS
    const EXCLUDED = array(
        "id",
    );

    /**
     * Unique identifier.
     *
     * @var null|string
     * @Assert\NotNull()
     * @Assert\Type("string")
     *
     * @JMS\SerializedName("id")
     * @JMS\Type("string")
     * @JMS\Groups ({"Read"})
     * @JMS\Exclude()
     */
    public ?string $id = null;

    //====================================================================//
    // CARRIER
    //====================================================================//

    /**
     * Carrier ID.
     *
     * @var null|string
     * @Assert\Type("string")
     *
     * @JMS\SerializedName("carrier_id")
     * @JMS\Groups ({"Read"})
     * @JMS\Type("string")
     */
    public ?string $carrierId;

    /**
     * Carrier Name.
     *
     * @var null|string
     * @Assert\Type("string")
     *
     * @JMS\SerializedName("carrier_name")
     * @JMS\Groups ({"Read"})
     * @JMS\Type("string")
     */
    public ?string $carrierName;

    //====================================================================//
    // SHIPPING
    //====================================================================//

    /**
     * Shipping Method ID.
     *
     * @var null|string
     * @Assert\Type("string")
     *
     * @JMS\SerializedName("shipping_method_id")
     * @JMS\Groups ({"Read"})
     * @JMS\Type("string")
     */
    public ?string $shippingMethodId = null;

    /**
     * Shipping Method NAme.
     *
     * @var null|string
     * @Assert\Type("string")
     *
     * @JMS\SerializedName("shipping_method_name")
     * @JMS\Groups ({"Read"})
     * @JMS\Type("string")
     */
    public ?string $shippingMethodName = null;

    /**
     * Shipping Reference.
     *
     * @var null|string
     * @Assert\Type("string")
     *
     * @JMS\SerializedName("shipping_ref")
     * @JMS\Groups ({"Read"})
     * @JMS\Type("string")
     */
    public ?string $shippingRef = null;

    //====================================================================//
    // TRACKING
    //====================================================================//

    /**
     * Tracking Url.
     *
     * @var null|string
     * @Assert\Type("string")
     *
     * @JMS\SerializedName("tracking_url")
     * @JMS\Groups ({"Read"})
     * @JMS\Type("string")
     *
     * @SPL\Type("url")
     */
    public ?string $trackingUrl = null;

    /**
     * Delivery Date.
     *
     * @var null|DateTime
     *
     * @JMS\SerializedName("delivery_at")
     * @JMS\Groups ({"Read"})
     * @JMS\Type("datetime")
     */
    public ?DateTime $deliveryAt = null;
}
