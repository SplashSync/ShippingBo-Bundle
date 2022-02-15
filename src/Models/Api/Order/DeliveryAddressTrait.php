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

namespace Splash\Connectors\ShippingBo\Models\Api\Order;

use JMS\Serializer\Annotation as JMS;
use Splash\Connectors\ShippingBo\Models\Api\Address;
use Splash\OpenApi\Validator as SPL;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Sbo Order Address
 */
trait DeliveryAddressTrait
{
    /**
     * Order Shipping Address ID.
     *
     * @var string
     *
     * @Assert\Type("string")
     *
     * @JMS\SerializedName("shipping_address_id")
     * @JMS\Type("integer")
     * @JMS\Groups ({"Read", "Write", "Required"})
     * @JMS\Accessor(getter="getShippingAddressId",setter="setShippingAddressId")
     *
     * @SPL\Type ("objectid::Address")
     * @SPL\Microdata({"http://schema.org/Order", "orderDelivery"})
     */
    public ?string $shipping_address_id;

    /**
     * Order Delivery Relay Code.
     *
     * @var null|string
     * @Assert\Type("string")
     *
     * @JMS\SerializedName("relay_ref")
     * @JMS\Type("string")
     * @JMS\Groups ({"Read", "Write"})
     * @JMS\ReadOnlyProperty()
     *
     * @SPL\Microdata({"http://schema.org/PostalAddress", "description"})
     */
    public ?string $relayRef = null;

    /**
     * Order Delivery Address.
     *
     * @var null|Address
     *
     * @JMS\SerializedName("shipping_address")
     * @JMS\Type("Splash\Connectors\ShippingBo\Models\Api\Address")
     * @JMS\Groups ({"Read", "Write"})
     * @JMS\Accessor(getter="getNullAddress")
     *
     * @Assert\Type("Splash\Connectors\ShippingBo\Models\Api\Address")
     *
     * @SPL\Group("Delivery")
     */
    public ?Address $shippingAddress;

    /**
     * Order Billing Address.
     *
     * @var null|Address
     *
     * @JMS\SerializedName("billing_address")
     * @JMS\Type("Splash\Connectors\ShippingBo\Models\Api\Address")
     * @JMS\Groups ({"Read"})
     *
     * @Assert\Type("Splash\Connectors\ShippingBo\Models\Api\Address")
     *
     * @SPL\Group("Billing")
     */
    public ?Address $billingAddress;

    //====================================================================//
    // GETTERS & SETTERS
    //====================================================================//

    /**
     * @return null
     */
    public function getNullAddress(): ?string
    {
        return null;
    }

    /**
     * @return int|null
     */
    public function getShippingAddressId(): ?int
    {
        if ($this->shipping_address_id ?? null) {
            return self::objects()->id($this->shipping_address_id);
        }

        return null;
    }

    /**
     * @param int|null $shipping_address_id
     *
     * @return self
     */
    public function setShippingAddressId(?int $shipping_address_id): self
    {
        $this->shipping_address_id = self::objects()->encode("Address", $shipping_address_id);

        return $this;
    }
}
