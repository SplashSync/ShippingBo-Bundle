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

namespace Splash\Connectors\ShippingBo\Objects;

use Exception;
use Splash\Client\Splash;
use Splash\Connectors\ShippingBo\Models\Metadata as ApiModels;
use Splash\Connectors\ShippingBo\Services\ShippingBoConnector;
use Splash\Models\Objects\IntelParserTrait;
use Splash\OpenApi\Action\Json;
use Splash\OpenApi\Models\Metadata\AbstractApiMetadataObject;

class Webhook extends AbstractApiMetadataObject
{
    //====================================================================//
    // Splash Php Core Traits
    use IntelParserTrait;

    //====================================================================//
    // General Class Variables
    //====================================================================//

    /**
     * @var ApiModels\Webhook
     */
    protected object $object;

    /**
     * Class Constructor
     *
     * @throws Exception
     */
    public function __construct(
        protected ShippingBoConnector $connector
    ) {
        parent::__construct(
            $connector->getMetadataAdapter(),
            $connector->getConnexion(),
            $connector->getHydrator(),
            ApiModels\Webhook::class
        );
        $this->visitor->setTimezone("UTC");
        //====================================================================//
        // Only Visible on Sandbox
        $isSandbox = $this->connector->isSandbox();
        if (!$isSandbox && !Splash::isDebugMode()) {
            self::$disabled = true;
        }
        //====================================================================//
        // Prepare Api Visitor
        $this->visitor->setModel(
            ApiModels\Webhook::class,
            "/update_hooks",
            "/update_hooks/{id}",
            array("id")
        );
        $this->visitor->setListAction(
            Json\ListAction::class,
            array(
                "filterKey" => null,
                "pageKey" => $isSandbox ? "offset" : null,
                "offsetKey" => $isSandbox ? null : "offset",
            )
        );
    }
}
