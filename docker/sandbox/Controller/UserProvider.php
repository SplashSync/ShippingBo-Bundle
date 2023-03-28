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

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;

class UserProvider implements ProviderInterface
{
    /**
     * {@inheritDoc}
     */
    public function provide(Operation $operation, array $uriVariables = array(), array $context = array())
    {
        return (object) array('user' => array(
            "id" => $uriVariables['id'],
            "api_client_id" => 669,
            "email" => "sandbox@splashsync.com",
            "company_name" => "SplashSync - Sandbox"
        ));
    }
}
