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

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Order Controller: Custom operations to work with Users
 */
class UserController extends AbstractController
{
    /**
     * Get Me User Sample
     *
     * @return JsonResponse
     */
    public function itemAction(): JsonResponse
    {
        return new JsonResponse(array('user' => array(
            "id" => 666,
            "api_client_id" => 669,
            "email" => "sandbox@splashsync.com",
            "company_name" => "SplashSync - Sandbox"
        )));
    }
}
