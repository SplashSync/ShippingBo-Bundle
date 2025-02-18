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

namespace Splash\Connectors\ShippingBo\Actions\Webhooks;

use Splash\Bundle\Models\AbstractConnector;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * Splash ShippingBo Connector WebHooks Controller
 */
class Receive extends AbstractController
{
    /**
     * Splash Object Type
     */
    private ?string $objectType = null;

    /**
     * ShippingBo Object ID
     */
    private ?string $objectId = null;

    /**
     * Splash Action
     */
    private string $objectAction = SPL_A_UPDATE;

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
    public function __invoke(Request $request, AbstractConnector $connector): JsonResponse
    {
        //==============================================================================
        // Safety Check
        $error = $this->verify($request);
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

        return $this->getResponse(Response::HTTP_OK, 'Changes notified');
    }

    /**
     * Verify Request is Valid
     *
     * @param Request $request
     *
     * @return null|JsonResponse
     */
    private function verify(Request $request) : ?JsonResponse
    {
        //====================================================================//
        // Verify Request is GET => PING
        if ($request->isMethod('GET')) {
            return $this->getResponse(Response::HTTP_OK, 'Pong');
        }
        //====================================================================//
        // Verify Request is POST
        if (!$request->isMethod('POST')) {
            return $this->getResponse(Response::HTTP_BAD_REQUEST, 'Only POST method is supported');
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
        /** @var array $rawData */
        $rawData = $request->getContent()
            ? json_decode((string) $request->getContent(), true)
            : $request->request->all()
        ;
        //====================================================================//
        // Contents Include Object Class
        if (empty($rawData) || !isset($rawData["object_class"]) || !is_scalar($rawData["object_class"])) {
            return $this->getResponse(Response::HTTP_BAD_REQUEST, 'Malformed or missing data...');
        }
        $this->objectType = (string) $rawData["object_class"];
        //====================================================================//
        // Contents Include Objects Infos
        if (!isset($rawData["object"]) || !is_array($rawData["object"]) || empty($rawData["object"]['id'])) {
            return $this->getResponse(Response::HTTP_BAD_REQUEST, 'Malformed or missing data...');
        }
        $this->objectId = (string) $rawData["object"]['id'];
        //====================================================================//
        // Detect Objects Event Type
        $this->objectAction = SPL_A_UPDATE;
        if (!empty($rawData["additional_data"]['deleted'])) {
            $this->objectAction = SPL_A_DELETE;
        }

        return null;
    }

    /**
     * Execute Changes Commits
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
            return $this->getResponse(Response::HTTP_BAD_REQUEST, 'Wrong object type');
        }
        //====================================================================//
        // Validate Object ID
        if (empty($this->objectId)) {
            return $this->getResponse(Response::HTTP_BAD_REQUEST, 'Wrong object id');
        }
        //====================================================================//
        // Detect Object Delete
        if (empty($this->objectAction)) {
            return $this->getResponse(Response::HTTP_BAD_REQUEST, 'Wrong object action');
        }
        //==============================================================================
        // Commit Change for Object
        $connector->commit(
            $this->objectType,
            $this->objectId,
            $this->objectAction,
            'ShippingBo API',
            sprintf(
                (SPL_A_DELETE == $this->objectAction)
                    ? "%s deleted on ShippingBo"
                    : "%s modified on ShippingBo",
                $this->objectType
            )
        );

        return null;
    }

    /**
     * @param int    $code
     * @param string $message
     *
     * @return JsonResponse
     */
    private function getResponse(int $code, string $message): JsonResponse
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
