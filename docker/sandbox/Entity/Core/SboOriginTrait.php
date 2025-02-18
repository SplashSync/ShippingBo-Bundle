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

namespace App\Entity\Core;

use DateTime;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Object Origin Trait
 */
trait SboOriginTrait
{
    #[Assert\NotNull]
    #[Assert\Type('string')]
    #[ORM\Column(type: Types::STRING)]
    #[Groups(array('read', 'write'))]
    public string $origin;

    #[Assert\NotNull]
    #[Assert\Type('string')]
    #[ORM\Column(type: Types::STRING)]
    #[Groups(array('read', 'write'))]
    public string $originRef;

    #[Assert\NotNull]
    #[Assert\Type(DateTime::class)]
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(array('read', 'write'))]
    public DateTime $originCreatedAt;
}
