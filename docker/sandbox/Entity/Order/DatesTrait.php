<?php

/*
 *  This file is part of SplashSync Project.
 *
 *  Copyright (C) 2015-2021 Splash Sync  <www.splashsync.com>
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

    /**
     * @var null|DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
     *
     * @Assert\Type("DateTime")
     *
     * @Groups({"read"})
     */
    public ?DateTime $shipped_at = null;

    /**
     * @var null|DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
     *
     * @Assert\Type("DateTime")
     *
     * @Groups({"read"})
     */
    public ?DateTime $closed_at = null;

    /**
     * @var null|DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
     *
     * @Assert\Type("DateTime")
     *
     * @Groups({"read"})
     */
    public ?DateTime $state_changed_at = null;

    //====================================================================//
    // SHIPPING DATES - READ ONLY
    //====================================================================//

    /**
     * @var null|DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
     *
     * @Assert\Type("DateTime")
     *
     * @Groups({"read"})
     */
    public ?DateTime $latest_shipped_at = null;

    /**
     * @var null|DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
     *
     * @Assert\Type("DateTime")
     *
     * @Groups({"read"})
     */
    public ?DateTime $earliest_shipped_at = null;

    //====================================================================//
    // DELIVERY DATES - READ ONLY
    //====================================================================//

    /**
     * @var null|DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
     *
     * @Assert\Type("DateTime")
     *
     * @Groups({"read"})
     */
    public ?DateTime $latest_delivery_at = null;

    /**
     * @var null|DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
     *
     * @Assert\Type("DateTime")
     *
     * @Groups({"read"})
     */
    public ?DateTime $earliest_delivery_at = null;
}
