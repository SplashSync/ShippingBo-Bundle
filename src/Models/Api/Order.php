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

use JMS\Serializer\Annotation as JMS;
use Splash\Models\Objects\ObjectsTrait;
use Splash\Models\Objects\PricesTrait;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class representing the Order model.
 *
 * @SuppressWarnings(PHPMD.TooManyFields)
 */
class Order
{
    //====================================================================//
    // Splash Core Traits
    use PricesTrait;
    use ObjectsTrait;
    //====================================================================//
    // SBO CORE DATA
    use Core\SboCoreTrait;
    use Core\SboSourceTrait;
    use Core\SboOriginTrait;

    //====================================================================//
    // SBO ORDER DATA
    use Order\StatusTrait;
    use Order\OrderItemsTrait;
    use Order\DatesTrait;
    use Order\DeliveryAddressTrait;
    use Order\DeliveryServiceTrait;
    use Order\ShipmentsTrait;
    use Order\TotalsTrait;
    //====================================================================//
    // JSON PREFIXES
    const COLLECTION_PROP = "orders";
    const ITEMS_PROP = "order";

    //====================================================================//
    // SPLASH EXCLUDED PROPS
    const EXCLUDED = array(
        "id",
        "oldItems",
        // Grand Total Raw
        "total_price_cents",
        "total_without_tax_cents",
        "total_tax_cents",
        "total_price_currency",
        // Shipping Total
        "total_shipping_cents",
        "total_shipping_tax_included_cents",
        "total_shipping_tax_cents",
        "total_shipping_tax_included_currency",
        // Discounts Total
        "total_discount_cents",
        "total_discount_tax_included_cents",
        "total_discount_tax_included_currency",
    );

    /**
     * Unique identifier.
     *
     * @var string
     * @Assert\NotNull()
     * @Assert\Type("string")
     *
     * @JMS\SerializedName("id")
     * @JMS\Type("string")
     * @JMS\Groups ({"Read", "List"})
     */
    public string $id;

    //====================================================================//
    // MAIN METHODS
    //====================================================================//

    /**
     * Product Constructor
     */
    public function __construct()
    {
        $this->source = "Splash";
    }
}
