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

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class representing the Product Stock Variation model.
 *
 * @ApiResource(
 *     collectionOperations={
 *          "get_user":      {
 *              "method": "GET",
 *              "path": "/users/me",
 *              "controller": {"App\Controller\UserController", "itemAction"}
 *          },
 *     },
 *     itemOperations={
 *     },
 * )
 */
class User
{
    /**
     * Unique identifier.
     *
     * @var string
     *
     * @Assert\Type("string")
     *
     * @Groups({"read"})
     */
    public int $id;
}
