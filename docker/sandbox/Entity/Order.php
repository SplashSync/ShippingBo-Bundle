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

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class representing the Product model.
 *
 * @ApiResource(
 *     attributes={
 *          "normalization_context"={"groups"={"read"}},
 *          "denormalizationContext"={"groups"={"write"}}
 *     },
 *     itemOperations={
 *          "get":      { "path": "/orders/{id}" },
 *          "put":      { "path": "/orders/{id}" },
 *          "patch":    { "path": "/orders/{id}" },
 *          "delete":   { "path": "/orders/{id}" },
 *          "compute":      {
 *              "method": "POST",
 *              "path": "/orders/{id}/recompute_mapped_products",
 *              "controller": {"App\Controller\OrderController", "computeAction"},
 *          },
 *     },
 * )
 *
 * @ORM\Entity()
 * @ORM\Table(name="`orders`")
 * @ORM\HasLifecycleCallbacks()
 */
class Order implements SboObjectInterface
{
    //====================================================================//
    // SBO CORE DATA
    use Core\SboCoreTrait;
    use Core\SboSourceTrait;
    use Core\SboOriginTrait;

    //====================================================================//
    // SBO ORDER DATA
    use Order\StatusTrait;
    use Order\DatesTrait;
    use Order\AddressTrait;
    use Order\TotalsTrait;

    /**
     * Order Delivery Service.
     *
     * @var string
     * @Assert\NotNull()
     * @Assert\Type("string")
     *
     * @ORM\Column(type="string")
     *
     * @Groups({"read"})
     */
    public string $chosen_delivery_service;

    /**
     * Order Delivery Service.
     *
     * @var null|string
     * @Assert\Type("string")
     *
     * @ORM\Column(type="string", nullable=true)
     *
     * @Groups({"read", "write"})
     */
    public ?string $relay_ref;

    /**
     * Order Items.
     *
     * @var OrderItem[]
     *
     * @Assert\All({
     *   @Assert\Type("App\Entity\OrderItem")
     * })
     * @Groups({"read"})
     *
     * @ORM\OneToMany(targetEntity="App\Entity\OrderItem", mappedBy="order", cascade={"all"})
     */
    public $order_items;

    /**
     * Order Shipments
     *
     * @var Shipment[]
     *
     * @Assert\All({
     *   @Assert\Type("App\Entity\Shipment")
     * })
     * @Groups({"read"})
     *
     * @ORM\OneToMany(targetEntity="App\Entity\Shipment", mappedBy="order", cascade={"all"})
     */
    public $shipments;

    //====================================================================//
    // MAIN METHODS
    //====================================================================//

    /**
     * Update Order Total Weight
     *
     * @return void
     */
    public function updateTotalWeight(): void
    {
        $totalWeight = 0;
        //====================================================================//
        // Walk on Products
        foreach ($this->order_items as $index => $item) {
            $totalWeight += $index * 250;
        }
        //====================================================================//
        // Update Order
        $this->total_weight = $totalWeight;
    }

    /**
     * @return array
     */
    public function getShipments()
    {
        if ($this->shipments->isEmpty()) {
            return array(
                Shipment::fake($this),
                Shipment::fake($this),
            );
        }

        return $this->shipments;
    }

    //====================================================================//
    // JSON SERIALIZER
    //====================================================================//

    /**
     * {@inheritDoc}
     */
    public static function getItemIndex(): string
    {
        return "order";
    }

    /**
     * {@inheritDoc}
     */
    public static function getCollectionIndex(): string
    {
        return "orders";
    }
}
