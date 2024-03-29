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
     * @var null|string
     *
     * @Assert\Type("string")
     *
     * @JMS\SerializedName("shipping_address_id")
     *
     * @JMS\Type("integer")
     *
     * @JMS\Groups ({"Read", "Write", "Required"})
     *
     * @JMS\Accessor(getter="getShippingAddressId",setter="setShippingAddressId")
     *
     * @SPL\Type ("objectid::Address")
     *
     * @SPL\Microdata({"http://schema.org/Order", "orderDelivery"})
     */
    public ?string $shipping_address_id;

    /**
     * Order Billing Address ID.
     *
     * @var null|string
     *
     * @Assert\Type("string")
     *
     * @JMS\SerializedName("billing_address_id")
     *
     * @JMS\Type("integer")
     *
     * @JMS\Groups ({"Read", "Write"})
     *
     * @JMS\Accessor(getter="getBillingAddressId",setter="setBillingAddressId")
     *
     * @SPL\Type ("objectid::Address")
     *
     * @SPL\Microdata({"http://schema.org/Order", "billingAddress"})
     */
    public ?string $billing_address_id;

    /**
     * Order Delivery Relay Code.
     *
     * @var null|string
     *
     * @Assert\Type("string")
     *
     * @JMS\SerializedName("relay_ref")
     *
     * @JMS\Type("string")
     *
     * @JMS\Groups ({"Read", "Write"})
     *
     * @SPL\Prefer("write")
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
     *
     * @JMS\Type("Splash\Connectors\ShippingBo\Models\Api\Address")
     *
     * @JMS\Groups ({"Read", "Write"})
     *
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
     *
     * @JMS\Type("Splash\Connectors\ShippingBo\Models\Api\Address")
     *
     * @JMS\Groups ({"Read"})
     *
     * @JMS\Accessor(setter="setBillingAddress")
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
     * @return null|string
     */
    public function getNullAddress(): ?string
    {
        return null;
    }

    /**
     * @return null|int
     */
    public function getShippingAddressId(): ?int
    {
        if ($this->shipping_address_id ?? null) {
            return (int) self::objects()->id((string) $this->shipping_address_id);
        }

        return null;
    }

    /**
     * @param null|int $shippingAddressId
     *
     * @return self
     */
    public function setShippingAddressId(?int $shippingAddressId): self
    {
        $this->shipping_address_id = $shippingAddressId
            ? (string) self::objects()->encode("Address", (string) $shippingAddressId)
            : null
        ;

        return $this;
    }

    /**
     * @return null|int
     */
    public function getBillingAddressId(): ?int
    {
        if ($this->billing_address_id ?? null) {
            return (int) self::objects()->id((string) $this->billing_address_id);
        }

        return null;
    }

    /**
     * @param null|int $billingAddressId
     *
     * @return self
     */
    public function setBillingAddressId(?int $billingAddressId): self
    {
        $this->billing_address_id = $billingAddressId
            ? (string) self::objects()->encode("Address", (string) $billingAddressId)
            : null
        ;

        return $this;
    }

    /**
     * @param null|Address $billingAddress
     *
     * @return self
     */
    public function setBillingAddress(?Address $billingAddress): self
    {
        $this->billingAddress = $billingAddress;
        $this->setBillingAddressId(!empty($billingAddress->id) ? (int) $billingAddress->id : null);

        return $this;
    }
}
