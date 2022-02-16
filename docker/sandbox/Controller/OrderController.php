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

namespace App\Controller;

use App\Entity\Order;
use App\Entity\OrderItem;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Order Controller: Custom operations to work with Orders
 */
class OrderController extends AbstractController
{
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
        $order = $this->getDoctrine()->getManager()->getRepository(Order::class)->find($id);
        if (!$order) {
            throw new NotFoundHttpException();
        }
        //====================================================================//
        // Decode Received Item
        $rawData = json_decode($request->getContent(), true, 512, \JSON_BIGINT_AS_STRING);
        $orderItem = $this->get('serializer')->denormalize($rawData, OrderItem::class, "json");
        $orderItem->order = $order;
        $order->order_items[] = $orderItem;
        //====================================================================//
        // Persist Item
        $this->getDoctrine()->getManager()->persist($order);
        $this->getDoctrine()->getManager()->flush();

        return new JsonResponse($this->get('serializer')->normalize($order, 'json', array(
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
        $order = $this->getDoctrine()->getManager()->getRepository(Order::class)->find($id);
        if (!$order) {
            throw new NotFoundHttpException();
        }
        //====================================================================//
        // Clear Order Items
        $order->order_items = $order->order_items ?? new ArrayCollection();
        foreach ($order->order_items as $orderItem) {
            $this->getDoctrine()->getManager()->remove($orderItem);
        }
        $order->order_items->clear();
        //====================================================================//
        // Decode Received Items
        $rawData = json_decode($request->getContent(), true, 512, \JSON_BIGINT_AS_STRING);
        foreach ($rawData['order_items'] ?? array() as $index => $rawItem) {
            $orderItem = $this->get('serializer')
                ->denormalize($rawItem, OrderItem::class, "json")
            ;
            $orderItem->order = $order;
            $order->order_items[] = $orderItem;
        }
        $this->getDoctrine()->getManager()->flush();

        return new JsonResponse($this->get('serializer')->normalize($order, 'json', array(
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
        $order = $this->getDoctrine()->getManager()->getRepository(Order::class)->find($id);
        if (!$order) {
            throw new NotFoundHttpException();
        }
        //====================================================================//
        // Update Order Totals
        $order->updateTotals();

        $this->getDoctrine()->getManager()->flush();

        return new JsonResponse($this->get('serializer')->normalize($order, 'json', array(
            "resource_class" => Order::class,
            "operation_type" => "item"
        )));
    }
}
