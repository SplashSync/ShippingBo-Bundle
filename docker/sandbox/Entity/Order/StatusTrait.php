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

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Sbo Order Status Data
 */
trait StatusTrait
{
    /**
     * Current Order State.
     */
    #[Assert\NotNull]
    #[Assert\Type('string')]
    #[Assert\Choice(array(
        'in_trouble',
        'waiting_for_payment',
        'waiting_for_stock',
        'merged',
        'sent_to_logistics',
        'dispatched',
        'splitted',
        'to_be_prepared',
        'in_preparation',
        'partially_shipped',
        'shipped',
        'handed_to_carrier',
        'at_pickup_location',
        'closed',
        'back_from_client',
        'rejected',
        'canceled',
    ))]
    #[ORM\Column(type: Types::STRING)]
    #[Groups(array('read', 'write'))]
    public string $state;

    /**
     * Custom Order State.
     */
    #[Assert\Type('string')]
    #[ORM\Column(type: Types::STRING, nullable: true)]
    #[Groups(array('read', 'write'))]
    public string $customState;
}
