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

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use DateTime;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class representing the Order Shipment model.
 */
#[ORM\Entity]
#[ORM\Table(name: 'order_shipment')]
#[ORM\HasLifecycleCallbacks]
#[ApiResource(
    normalizationContext: array("groups" => array("read")),
    denormalizationContext: array("groups" => array("write"))
)]
class Shipment implements SboObjectInterface
{
    //====================================================================//
    // SBO CORE DATA
    use Core\SboCoreTrait;

    /**
     * Order ID.
     */
    #[Assert\Type('integer')]
    #[Groups(array('read'))]
    public ?int $orderId;

    /**
     * Shipped Order.
     */
    #[Assert\Type(Order::class)]
    #[ORM\ManyToOne(targetEntity: Order::class, inversedBy: 'shipments')]
    public Order $order;

    /**
     * Carrier ID.
     */
    #[Assert\Type('string')]
    #[Groups(array('read'))]
    #[ORM\Column(type: Types::STRING, nullable: true)]
    public ?string $carrierId;

    /**
     * Carrier Name.
     */
    #[Assert\Type('string')]
    #[Groups(array('read'))]
    #[ORM\Column(type: Types::STRING, nullable: true)]
    public ?string $carrierName;

    /**
     * Shipping Method ID.
     */
    #[Assert\Type('string')]
    #[Groups(array('read'))]
    #[ORM\Column(type: Types::STRING, nullable: true)]
    public ?string $shippingMethodId = null;

    /**
     * Shipping Method Name.
     */
    #[Assert\Type('string')]
    #[Groups(array('read'))]
    #[ORM\Column(type: Types::STRING, nullable: true)]
    public ?string $shippingMethodName = null;

    /**
     * Shipping Reference.
     */
    #[Assert\Type('string')]
    #[Groups(array('read'))]
    #[ORM\Column(type: Types::STRING, nullable: true)]
    public ?string $shippingRef = null;

    /**
     * Tracking Url.
     */
    #[Assert\Type('string')]
    #[Groups(array('read'))]
    #[ORM\Column(type: Types::STRING, nullable: true)]
    public ?string $trackingUrl = null;

    /**
     * Delivery Date.
     */
    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Assert\Type(DateTime::class)]
    #[Groups(array('read'))]
    public ?DateTime $deliveryAt = null;

    //====================================================================//
    // DATA FAKER
    //====================================================================//

    /**
     * Shipment Faker
     *
     * @param Order $order
     *
     * @return Shipment
     */
    public static function fake(Order $order): Shipment
    {
        $shipment = new self();
        $shipment->id = 12;
        $shipment->order = $order;
        $shipment->orderId = $order->id;
        $shipment->carrierId = rand(10, 100);
        $shipment->carrierName = "My Carrier Name";
        $shipment->shippingMethodId = rand(10, 100);
        $shipment->shippingMethodName = $order->chosenDeliveryService;
        $shipment->shippingRef = "TRACKING".rand(10000, 99999);
        $shipment->trackingUrl = "https://tracking.url?".$shipment->shippingRef;
        $shipment->deliveryAt = new DateTime("-10 days");

        return $shipment;
    }

    //====================================================================//
    // JSON SERIALIZER
    //====================================================================//

    /**
     * {@inheritDoc}
     */
    public static function getItemIndex(): ?string
    {
        return null;
    }

    /**
     * {@inheritDoc}
     */
    public static function getCollectionIndex(): ?string
    {
        return null;
    }
}
