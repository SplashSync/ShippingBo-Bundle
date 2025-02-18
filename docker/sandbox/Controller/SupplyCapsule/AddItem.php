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

namespace App\Controller\SupplyCapsule;

use App\Entity\SupplyCapsule;
use App\Entity\SupplyCapsuleItem;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Serializer\SerializerInterface;
use Webmozart\Assert\Assert;

/**
 * Supply Capsule Items Controller: Custom operations for Capsule Items
 */
class AddItem extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly SerializerInterface $serializer
    ) {
    }

    /**
     * Add a New Supply Capsule Item
     *
     * @throws \JsonException             If decoding the JSON payload fails.
     * @throws \InvalidArgumentException  If the provided data is invalid.
     * @throws \Doctrine\ORM\ORMException If persistence operations fail.
     */
    public function __invoke(Request $request): JsonResponse
    {
        //====================================================================//
        // Decode Received Item
        $rawData = json_decode($request->getContent(), true, 512, \JSON_BIGINT_AS_STRING);
        $capsuleItem = $this->serializer->denormalize($rawData['supply_capsule_item'], SupplyCapsuleItem::class, "json");
        Assert::isInstanceOf($capsuleItem, SupplyCapsuleItem::class);
        //====================================================================//
        // Load Parent Supply Capsule
        Assert::scalar(
            $supplyCapsuleId = $rawData['supply_capsule_item']["supply_capsule_id"] ?? null
        );
        $supplyCapsule = $this->getSupplyCapsule((int) $supplyCapsuleId);
        //====================================================================//
        // Setup Item
        $capsuleItem->order = $supplyCapsule;
        $supplyCapsule->supplyCapsuleItems[] = $capsuleItem;
        //====================================================================//
        // Persist Item
        $this->entityManager->persist($capsuleItem);
        //====================================================================//
        // Save Item
        $this->entityManager->flush();

        return new JsonResponse($this->serializer->normalize($capsuleItem, 'json', array(
            "resource_class" => SupplyCapsuleItem::class,
            "operation_type" => "item"
        )));
    }

    /**
     * Get Parent Supply Capsule
     */
    private function getSupplyCapsule(int $id): SupplyCapsule
    {
        //====================================================================//
        // Load Order
        /** @var null|SupplyCapsule $supplyCapsule */
        $supplyCapsule = $this->entityManager->getRepository(SupplyCapsule::class)->find($id);
        if (!$supplyCapsule) {
            throw new NotFoundHttpException();
        }

        return $supplyCapsule;
    }
}
