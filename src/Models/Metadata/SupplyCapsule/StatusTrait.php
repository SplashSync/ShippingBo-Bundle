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

namespace Splash\Connectors\ShippingBo\Models\Metadata\SupplyCapsule;

use JMS\Serializer\Annotation as JMS;
use Splash\Client\Splash;
use Splash\Connectors\ShippingBo\DataTransformer\CapsuleStatusTransformer;
use Splash\Connectors\ShippingBo\Dictionary\SupplyCapsuleState;
use Splash\Metadata\Attributes as SPL;
use Splash\Models\Objects\Order\Status;
use Symfony\Component\Validator\Constraints as Assert;

trait StatusTrait
{
    /**
     * The raw status of the supply capsule.
     */
    #[
        Assert\NotNull,
        Assert\Type("string"),
        Assert\Choice(SupplyCapsuleState::ALL),
        JMS\SerializedName("state"),
        JMS\Groups(array("Read", "Write", "List")),
        JMS\Type("string"),
        SPL\Field(
            name: "Raw Status",
            desc: "Supply Capsule Raw Status.",
        ),
        SPL\Flags(listed: true),
        SPL\Choices(SupplyCapsuleState::CHOICES),
        SPL\PreferRead,
    ]
    public string $state = SupplyCapsuleState::DRAFT;

    /**
     * Splash status of the supply capsule.
     */
    #[
        JMS\Exclude,
        SPL\Field(
            name: "Status",
            desc: "Supply Capsule Splash Status.",
        ),
        SPL\Choices(CapsuleStatusTransformer::CHOICES),
        SPL\Microdata("http://schema.org/Order", "orderStatus"),
        SPL\IsReadOnly,
    ]
    public string $splashState = Status::DRAFT;

    /**
     * Is this Order Validated ?
     */
    #[
        JMS\Exclude,
        SPL\Field(
            name: "Is Valid",
            desc: "Set this flag to Validate Supply Capsule",
            group: "Meta"
        ),
        SPL\Microdata("http://schema.org/OrderStatus", "OrderProcessing"),
        SPL\PreferWrite,
        SPL\IsNotTested,
    ]
    protected bool $validated = false;

    /**
     * Is this Order Canceled ?
     */
    #[
        JMS\Exclude,
        SPL\Field(
            name: "Is Canceled",
            desc: "Set this flag to Cancel Supply Capsule",
            group: "Meta"
        ),
        SPL\Microdata("http://schema.org/OrderStatus", "OrderCancelled"),
        SPL\PreferWrite,
        SPL\IsNotTested,
    ]
    protected bool $canceled = false;

    /**
     * Get the splash state of the supply capsule.
     */
    public function getSplashState(): ?string
    {
        return CapsuleStatusTransformer::isValidated($this->state)
            ? CapsuleStatusTransformer::toSplash($this->state)
            : null
        ;
    }

    /**
     * Check if the supply capsule is validated.
     */
    public function isValidated(): bool
    {
        return CapsuleStatusTransformer::isValidated($this->state);
    }

    /**
     * Set the validated status of the supply capsule.
     */
    public function setValidated(bool $validated): self
    {
        //====================================================================//
        // Supply Capsule Already Validated
        if (empty($validated) || CapsuleStatusTransformer::isValidated($this->state)) {
            return $this;
        }
        //====================================================================//
        // Check if Supply Capsule VALIDATE as Allowed
        if (!CapsuleStatusTransformer::isAllowedValidate($this->state)) {
            Splash::log()->war(sprintf(
                "You cannot validate an supply capsule from %s status",
                $this->state
            ));

            return $this;
        }
        $this->state = SupplyCapsuleState::WAITING;

        return $this;
    }

    /**
     * Check if the supply capsule is canceled.
     */
    public function isCanceled(): bool
    {
        return CapsuleStatusTransformer::isCanceled($this->state);
    }

    /**
     * Set the canceled status of the supply capsule.
     */
    public function setCanceled(bool $canceled): self
    {
        //====================================================================//
        // Supply Capsule Already Canceled
        if (empty($canceled) || CapsuleStatusTransformer::isCanceled($this->state)) {
            return $this;
        }
        //====================================================================//
        // Check if Supply Capsule CANCEL as Allowed
        if (!CapsuleStatusTransformer::isAllowedCancel($this->state)) {
            Splash::log()->war(sprintf(
                "You cannot cancel an supply capsule from %s status",
                $this->state
            ));

            return $this;
        }
        $this->state = SupplyCapsuleState::CANCELED;

        return $this;
    }
}
