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
use ApiPlatform\Metadata as Meta;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class representing the Order model.
 */
#[ORM\Entity]
#[ORM\Table(name: '`orders`')]
#[ORM\HasLifecycleCallbacks]
#[ApiResource(
    operations: array(
        new Meta\GetCollection(),
        new Meta\Get(),
        new Meta\Put(),
        new Meta\Patch(),
        new Meta\Post(),
        new Meta\Delete(),
        new Meta\Post(
            uriTemplate: '/orders/{id}/recompute_mapped_products',
            controller: 'App\Controller\OrderController::computeAction',
        ),
        new Meta\Post(
            uriTemplate: '/orders/{id}/order_items',
            controller: 'App\Controller\OrderController::addItemAction',
        ),
        new Meta\Post(
            uriTemplate: '/orders/{id}/update_order_items',
            controller: 'App\Controller\OrderController::itemsAction',
        ),
    ),
    normalizationContext: array('groups' => array('read')),
    denormalizationContext: array('groups' => array('write'))
)]
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
     */
    #[Assert\NotNull]
    #[Assert\Type('string')]
    #[ORM\Column(type: Types::STRING)]
    #[Groups(array('read', 'write'))]
    public string $chosenDeliveryService;

    #[Assert\Type('string')]
    #[ORM\Column(type: Types::STRING, nullable: true)]
    #[Groups(array('read', 'write'))]
    public ?string $relayRef;

    /**
     * @var OrderItem[] List of order items.
     */
    #[Assert\All(array(new Assert\Type(OrderItem::class)))]
    #[Groups(array('read'))]
    #[ORM\OneToMany(mappedBy: 'order', targetEntity: OrderItem::class, cascade: array('all'))]
    public $orderItems;

    /**
     * @var Shipment[] List of shipments.
     */
    #[Assert\All(array(new Assert\Type(Shipment::class)))]
    #[Groups(array('read'))]
    #[ORM\OneToMany(mappedBy: 'order', targetEntity: Shipment::class, cascade: array('all'))]
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
        foreach ($this->orderItems as $index => $item) {
            $totalWeight += $index * 250;
        }
        //====================================================================//
        // Update Order
        $this->totalWeight = $totalWeight;
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
        return 'order';
    }

    /**
     * {@inheritDoc}
     */
    public static function getCollectionIndex(): string
    {
        return 'orders';
    }
}
