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
 * Object Source Trait
 */
#[ORM\HasLifecycleCallbacks]
trait SboCoreTrait
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    #[Assert\Type('integer')]
    #[Groups(array('read'))]
    public int $id;

    /**
     * The creation timestamp of the entity.
     */
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Assert\Type(DateTime::class)]
    #[Groups(array('read'))]
    public DateTime $createdAt;

    /**
     * The update timestamp of the entity.
     */
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Assert\Type(DateTime::class)]
    #[Groups(array('read'))]
    public DateTime $updatedAt;

    //====================================================================//
    // ORM EVENTS
    //====================================================================//

    #[ORM\PrePersist]
    public function onPrePersist(): void
    {
        $this->createdAt = $this->updatedAt = new DateTime();
    }

    #[ORM\PreUpdate]
    public function onPreUpdate(): void
    {
        $this->updatedAt = new DateTime();
    }
}
