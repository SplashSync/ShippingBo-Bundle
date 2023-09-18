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

namespace App\Entity\Order;

use App\Entity\Address;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Sbo Order Address Fields
 */
trait AddressTrait
{
    /**
     * Order Shipping Address ID.
     *
     * @var null|int
     *
     * @Assert\Type("integer")
     *
     * @ORM\Column()
     *
     * @Groups({"read", "write"})
     */
    public ?int $shipping_address_id;

    /**
     * Order Shipping Address.
     *
     * @var null|Address
     *
     * @Assert\Type("App\Entity\Address")
     *
     * @Groups({"read"})
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Address")
     */
    public ?Address $shipping_address;

    /**
     * Order Billing Address ID.
     *
     * @var null|int
     *
     * @Assert\Type("integer")
     *
     * @ORM\Column(nullable=true)
     *
     * @Groups({"read", "write"})
     */
    public ?int $billing_address_id;

    /**
     * Order Billing Address.
     *
     * @var null|Address
     *
     * @Assert\Type("App\Entity\Address")
     *
     * @Groups({"read"})
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Address")
     */
    public ?Address $billing_address;

    //====================================================================//
    // ADDRESS LINK UPDATE
    //====================================================================//

    /**
     * @param LifecycleEventArgs $event
     *
     * @return void
     *
     * @ORM\PrePersist()
     *
     * @ORM\PreUpdate()
     */
    public function updateShippingAddress(LifecycleEventArgs $event): void
    {
        //====================================================================//
        // Check if Changed
        $current = $this->shipping_address->id ?? null;
        $new = $this->shipping_address_id ?? 1;
        if ($current && $new && ($current == $new)) {
            return;
        }
        //====================================================================//
        // Identify New
        $address = $event->getObjectManager()->getRepository(Address::class)->find($new);
        if (!$address) {
            throw new NotFoundHttpException(
                sprintf("Target Address %s not found", $new)
            );
        }
        //====================================================================//
        // Update
        $this->shipping_address = $address;
    }

    //====================================================================//
    // GETTERS & SETTERS
    //====================================================================//

    /**
     * @return int
     */
    public function getShippingAddressId(): int
    {
        $this->shipping_address_id = $this->shipping_address_id ?? $this->shipping_address->id;

        return $this->shipping_address_id;
    }

    /**
     * @param null|int $shipping_address_id
     *
     * @return self
     */
    public function setShippingAddressId(?int $shipping_address_id): self
    {
        $this->shipping_address_id = $shipping_address_id;

        return $this;
    }
}
