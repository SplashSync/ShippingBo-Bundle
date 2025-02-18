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

use DateTime;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Sbo Order Dates Fields
 */
trait DatesTrait
{
    //====================================================================//
    // STATUS DATES - READ ONLY
    //====================================================================//

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Assert\Type(DateTime::class)]
    #[Groups(array("read"))]
    public ?DateTime $shippedAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Assert\Type(DateTime::class)]
    #[Groups(array("read"))]
    public ?DateTime $closedAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Assert\Type(DateTime::class)]
    #[Groups(array("read"))]
    public ?DateTime $stateChangedAt = null;

    //====================================================================//
    // SHIPPING DATES - READ ONLY
    //====================================================================//

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Assert\Type(DateTime::class)]
    #[Groups(array("read"))]
    public ?DateTime $latestShippedAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Assert\Type(DateTime::class)]
    #[Groups(array("read"))]
    public ?DateTime $earliestShippedAt = null;

    //====================================================================//
    // DELIVERY DATES - READ ONLY
    //====================================================================//

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Assert\Type(DateTime::class)]
    #[Groups(array("read"))]
    public ?DateTime $latestDeliveryAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Assert\Type(DateTime::class)]
    #[Groups(array("read"))]
    public ?DateTime $earliestDeliveryAt = null;
}
