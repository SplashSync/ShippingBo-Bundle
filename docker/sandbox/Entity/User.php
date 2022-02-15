<?php

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