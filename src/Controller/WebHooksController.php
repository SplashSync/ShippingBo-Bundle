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

namespace Splash\Connectors\ShippingBo\Controller;

use Splash\Bundle\Models\AbstractConnector;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * Splash ShippingBo Connector WebHooks Controller
 */
class WebHooksController extends AbstractController
{
    /**
     * @var string|null
     */
    private ?string $objectType = null;

    /**
     * @var string|null
     */
    private ?string $objectId = null;

    /**
     * Execute WebHook Public Action
     *
     * @param Request           $request
     * @param AbstractConnector $connector
     *
     * @throws BadRequestHttpException
     *
     * @return JsonResponse
     */
    public function indexAction(Request $request, AbstractConnector $connector): JsonResponse
    {
        //==============================================================================
        // Safety Check
        $error = $this->verify($request, $connector);
        if ($error) {
            return $error;
        }
        //====================================================================//
        // Extract Data from Request
        $error = $this->extractData($request);
        if ($error) {
            return $error;
        }
        //==============================================================================
        // Commit Changes
        $error = $this->executeCommits($connector);
        if ($error) {
            return $error;
        }

        return $this->getResponse(JsonResponse::HTTP_OK,'Changes notified');
    }

    /**
     * Verify Request is Valid
     *
     * @param Request           $request
     * @param AbstractConnector $connector
     *
     * @return null|JsonResponse
     */
    private function verify(Request $request, AbstractConnector $connector) : ?JsonResponse
    {
        //====================================================================//
        // Verify Request is POST
        if (!$request->isMethod('POST')) {
            return $this->getResponse(JsonResponse::HTTP_BAD_REQUEST, 'Only POST method is supported');
        }

        return null;
    }

    /**
     * Extract Data from Request
     *
     * @param Request $request
     *
     * @return null|JsonResponse
     */
    private function extractData(Request $request): ?JsonResponse
    {
        $this->objectType = $this->objectId = null;
        //====================================================================//
        // Detect Posted Contents
        $rawData = $request->getContent()
            ? json_decode((string) $request->getContent(), true)
            : $request->request->all()
        ;
        //====================================================================//
        // Contents Include Object Class
        if (empty($rawData) || !isset($rawData["object_class"]) || !is_scalar($rawData["object_class"])) {
            return $this->getResponse(JsonResponse::HTTP_BAD_REQUEST, 'Malformed or missing data...');
        }
        $this->objectType = (string) $rawData["object_class"];
        //====================================================================//
        // Contents Include Objects Infos
        if (!isset($rawData["object"]) || !is_array($rawData["object"]) || empty($rawData["object"]['id'])) {
            return $this->getResponse(JsonResponse::HTTP_BAD_REQUEST, 'Malformed or missing data...');
        }
        $this->objectId = (string) $rawData["object"]['id'];

        return null;
    }

    /**
     * Execute Changes Commits
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     *
     * @param AbstractConnector $connector
     *
     * @return null|JsonResponse
     */
    private function executeCommits(AbstractConnector $connector) : ?JsonResponse
    {
        //====================================================================//
        // Validate Object Data Type
        if (!in_array($this->objectType, array("Order", "Product"), true)) {
            return $this->getResponse(JsonResponse::HTTP_BAD_REQUEST, 'Wrong object type');
        }
        //====================================================================//
        // Validate Object ID
        if (empty($this->objectId)) {
            return $this->getResponse(JsonResponse::HTTP_BAD_REQUEST, 'Wrong object id');
        }
        //==============================================================================
        // Commit Change for Object
        $connector->commit(
            $this->objectType,
            $this->objectId,
            SPL_A_UPDATE,
            'ShippingBo API',
            sprintf("%s modified on ShippingBo", $this->objectType)
        );

        return null;
    }



    /**
     * @param int   $code
     * @param mixed $message
     *
     * @return JsonResponse
     */
    private function getResponse($code, $message): JsonResponse
    {
        return new JsonResponse(
            array(
                'code' => $code,
                'type' => JsonResponse::$statusTexts[$code],
                'message' => $message,
            ),
            $code
        );
    }
}
