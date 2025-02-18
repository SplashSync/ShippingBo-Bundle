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
use Doctrine\DBAL\Types\Types;
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
    #[Assert\Type(Types::INTEGER)]
    #[ORM\Column(type: Types::INTEGER)]
    #[Groups(array("read", "write"))]
    /**
     * Order Shipping Address ID.
     */
    public ?int $shippingAddressId;

    #[Assert\Type(Address::class)]
    #[Groups(array("read"))]
    #[ORM\ManyToOne(targetEntity: Address::class)]
    /**
     * Order Shipping Address.
     */
    public ?Address $shippingAddress;

    #[Assert\Type(Types::INTEGER)]
    #[ORM\Column(type: Types::INTEGER, nullable: true)]
    #[Groups(array("read", "write"))]
    /**
     * Order Billing Address ID.
     */
    public ?int $billingAddressId;

    #[Assert\Type(Address::class)]
    #[Groups(array("read"))]
    #[ORM\ManyToOne(targetEntity: Address::class)]
    /**
     * Order Billing Address.
     */
    public ?Address $billingAddress;

    //====================================================================//
    // ADDRESS LINK UPDATE
    //====================================================================//

    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    /**
     * @param LifecycleEventArgs $event
     *
     * @return void
     */
    public function updateShippingAddress(LifecycleEventArgs $event): void
    {
        //====================================================================//
        // Check if Changed
        $current = $this->shippingAddress->id ?? null;
        $new = $this->shippingAddressId ?? 1;
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
        $this->shippingAddress = $address;
    }

    //====================================================================//
    // GETTERS & SETTERS
    //====================================================================//

    /**
     * @return int
     */
    public function getShippingAddressId(): int
    {
        $this->shippingAddressId = $this->shippingAddressId ?? $this->shippingAddress->id;

        return $this->shippingAddressId;
    }

    /**
     * @param null|int $shippingAddressId
     */
    public function setShippingAddressId(?int $shippingAddressId): static
    {
        $this->shippingAddressId = $shippingAddressId;

        return $this;
    }
}
