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

namespace App\Entity\SupplyCapsule;

use DateTime;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Provides date-related properties and functionality.
 */
trait DatesTrait
{
    /**
     * The Expected Delivery Date.
     */
    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Assert\Type(DateTime::class)]
    #[Serializer\Groups(array("read", "write"))]
    #[Serializer\Context(array(
        "datetime_format" => 'Y-m-d\TH:i:s.v\Z'
    ))]
    public ?DateTime $expectedDeliveryDate = null;
}
