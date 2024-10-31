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

use App\Entity\Order;
use App\Entity\OrderItem;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Order Controller: Custom operations to work with Orders
 */
class OrderController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly SerializerInterface $serializer
    ) {
    }

    /**
     * Update Order Items by ID for an Order
     *
     * @param Request $request
     * @param int     $id
     *
     * @return JsonResponse
     */
    public function addItemAction(Request $request, int $id): JsonResponse
    {
        //====================================================================//
        // Load Parent Order
        /** @var null|Order $order */
        $order = $this - $this->entityManager->getRepository(Order::class)->find($id);
        if (!$order) {
            throw new NotFoundHttpException();
        }
        //====================================================================//
        // Decode Received Item
        $rawData = json_decode($request->getContent(), true, 512, \JSON_BIGINT_AS_STRING);
        $orderItem = $this->serializer->denormalize($rawData, OrderItem::class, "json");
        $orderItem->order = $order;
        $order->order_items[] = $orderItem;
        //====================================================================//
        // Persist Item
        $this->entityManager->persist($order);
        //====================================================================//
        // FIX - Revert Source_Ref Changes
        $uow = $this->entityManager->getUnitOfWork();
        $uow->computeChangeSets();
        $orderChangeSet = $uow->getEntityChangeSet($order);
        if (!empty($orderChangeSet["source_ref"]["0"])) {
            $order->source_ref = $orderChangeSet["source_ref"]["0"];
        }
        //====================================================================//
        // Save Item
        $this->entityManager->flush();

        return new JsonResponse($this->serializer->normalize($order, 'json', array(
            "resource_class" => Order::class,
            "operation_type" => "item"
        )));
    }

    /**
     * Update Order Items by ID for an Order
     *
     * @param Request $request
     * @param int     $id
     *
     * @return JsonResponse
     */
    public function itemsAction(Request $request, int $id): JsonResponse
    {
        //====================================================================//
        // Load Parent Order
        /** @var null|Order $order */
        $order = $this->entityManager->getRepository(Order::class)->find($id);
        if (!$order) {
            throw new NotFoundHttpException();
        }
        //====================================================================//
        // Clear Order Items
        $order->order_items = $order->order_items ?? new ArrayCollection();
        foreach ($order->order_items as $orderItem) {
            $this->entityManager->remove($orderItem);
        }
        $order->order_items->clear();
        //====================================================================//
        // Decode Received Items
        $rawData = json_decode($request->getContent(), true, 512, \JSON_BIGINT_AS_STRING);
        foreach ($rawData['order_items'] ?? array() as $index => $rawItem) {
            $orderItem = $this->serializer
                ->denormalize($rawItem, OrderItem::class, "json")
            ;
            $orderItem->order = $order;
            $order->order_items[] = $orderItem;
        }
        $this->entityManager->flush();

        return new JsonResponse($this->serializer->normalize($order, 'json', array(
            "resource_class" => Order::class,
            "operation_type" => "item"
        )));
    }

    /**
     * Compute Order Items Totals by ID for an Order
     *
     * @param int $id
     *
     * @return JsonResponse
     */
    public function computeAction(int $id): JsonResponse
    {
        //====================================================================//
        // Load Order
        /** @var null|Order $order */
        $order = $this->entityManager->getRepository(Order::class)->find($id);
        if (!$order) {
            throw new NotFoundHttpException();
        }
        //====================================================================//
        // Update Order Total Weight
        $order->updateTotalWeight();

        $this->entityManager->flush();

        return new JsonResponse($this->serializer->normalize($order, 'json', array(
            "resource_class" => Order::class,
            "operation_type" => "item"
        )));
    }
}
