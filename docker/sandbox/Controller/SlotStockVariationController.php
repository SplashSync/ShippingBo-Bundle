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

namespace App\Controller;

use App\Entity\SlotContents;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Serializer\SerializerInterface;
use Webmozart\Assert\Assert;

/**
 * Product Slots Stocks Controller: Custom operations to work with Warehouse Slots Stocks
 */
class SlotStockVariationController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly SerializerInterface $serializer
    ) {
    }

    /**
     * Create a Slot Stock Variation
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function createAction(Request $request): JsonResponse
    {
        //====================================================================//
        // Decode Received Item
        $rawData = json_decode($request->getContent(), true, 512, \JSON_BIGINT_AS_STRING);
        Assert::integer($productId = $rawData["product_id"] ?? null);
        Assert::integer($whSlotId = $rawData["warehouse_slot_id"] ?? null);
        Assert::integer($variation = $rawData["variation"] ?? null);
        //====================================================================//
        // Load Slot Contents
        /** @var null|SlotContents $order */
        $slotContents = $this->entityManager->getRepository(SlotContents::class)->findOneBy(array(
            "productId" => $productId,
            "warehouseSlotId" => $whSlotId,
        ));
        if (!$slotContents) {
            throw new NotFoundHttpException();
        }
        //====================================================================//
        // Update Slot Contents
        $slotContents->stock += $variation;
        //====================================================================//
        // Save Slot Contents
        $this->entityManager->flush();

        return new JsonResponse($this->serializer->normalize($slotContents, 'json', array(
            "resource_class" => SlotContents::class,
            "operation_type" => "item"
        )));
    }
}
