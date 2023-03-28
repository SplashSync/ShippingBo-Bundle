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

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class representing the Order Shipment model.
 *
 * @ORM\Entity()
 * @ORM\Table(name="`order_shipment`")
 * @ORM\HasLifecycleCallbacks()
 */
#[ApiResource(
    normalizationContext: array("groups" => array("read")),
    denormalizationContext: array("groups" => array("write")),
)]
class Shipment implements SboObjectInterface
{
    //====================================================================//
    // SBO CORE DATA
    use Core\SboCoreTrait;

    /**
     * Order ID.
     *
     * @var null|int
     *
     * @Assert\Type("integer")
     *
     * @Groups({"read"})
     */
    public ?int $order_id;

    /**
     * Shipped Order.
     *
     * @var Order
     *
     * @Assert\Type("App\Entity\Order")
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Order", inversedBy="shipments")
     *
     * @ApiProperty(identifier=true)
     */
    public Order $order;

    /**
     * Carrier ID.
     *
     * @var null|string
     * @Assert\Type("string")
     *
     * @Groups({"read"})
     *
     * @ORM\Column(type="string", nullable=true)
     */
    public ?string $carrier_id;

    /**
     * Carrier Name.
     *
     * @var null|string
     * @Assert\Type("string")
     *
     * @Groups({"read"})
     *
     * @ORM\Column(type="string", nullable=true)
     */
    public ?string $carrier_name;

    /**
     * Shipping Method ID.
     *
     * @var null|string
     * @Assert\Type("string")
     *
     * @Groups({"read"})
     *
     * @ORM\Column(type="string", nullable=true)
     */
    public ?string $shipping_method_id = null;

    /**
     * Shipping Method NAme.
     *
     * @var null|string
     * @Assert\Type("string")
     *
     * @Groups({"read"})
     *
     * @ORM\Column(type="string", nullable=true)
     */
    public ?string $shipping_method_name = null;

    /**
     * Shipping Reference.
     *
     * @var null|string
     * @Assert\Type("string")
     *
     * @Groups({"read"})
     *
     * @ORM\Column(type="string", nullable=true)
     */
    public ?string $shipping_ref = null;

    /**
     * Tracking Url.
     *
     * @var null|string
     * @Assert\Type("string")
     *
     * @Groups({"read"})
     *
     * @ORM\Column(type="string", nullable=true)
     */
    public ?string $tracking_url = null;

    /**
     * Delivery Date.
     *
     * @var null|DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
     *
     * @Assert\Type("DateTime")
     *
     * @Groups({"read"})
     */
    public ?DateTime $delivery_at = null;

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
        $shipment->order_id = $order->id;
        $shipment->carrier_id = rand(10, 100);
        $shipment->carrier_name = "My Carrier Name";
        $shipment->shipping_method_id = rand(10, 100);
        $shipment->shipping_method_name = $order->chosen_delivery_service;
        $shipment->shipping_ref = "TRACKING".rand(10000, 99999);
        $shipment->tracking_url = "https://tracking.url?".$shipment->shipping_ref;
        $shipment->delivery_at = new DateTime("-10 days");

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
