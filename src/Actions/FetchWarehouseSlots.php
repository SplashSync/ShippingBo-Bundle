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

namespace Splash\Connectors\ShippingBo\Actions;

use Exception;
use Splash\Bundle\Models\Local\ActionsTrait;
use Splash\Connectors\ShippingBo\Services\ShippingBoConnector;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Refresh Warehouse Slots List
 */
class FetchWarehouseSlots extends AbstractController
{
    use ActionsTrait;

    /**
     * @throws Exception
     */
    public function __invoke(
        Request $request,
        TranslatorInterface $translator,
        ShippingBoConnector $connector
    ): Response {
        $result = false;
        //====================================================================//
        // Connector SelfTest
        if ($connector->selfTest()) {
            //====================================================================//
            // Update WebHooks Config
            $result = $connector->getLocator()->getWarehouseSlotsManager()->fetchWarehouseSlots();
        }
        //====================================================================//
        // Inform User
        $this->addFlash(
            $result ? "success" : "danger",
            $translator->trans(
                $result ? "admin.warehouse.msg" : "admin.warehouse.err",
                array(),
                "ShippingBoBundle"
            )
        );
        //====================================================================//
        // Redirect Response
        /** @var string $referer */
        $referer = $request->headers->get('referer');
        if (empty($referer)) {
            return self::getDefaultResponse();
        }

        return new RedirectResponse($referer);
    }
}
