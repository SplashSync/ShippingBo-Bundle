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

namespace Splash\Connectors\ShippingBo\Models\Actions;

use Splash\OpenApi\ApiResponse;
use Splash\OpenApi\Models\Action\AbstractListAction;

class ProductListAction extends AbstractListAction
{
    /**
     * Execute Objects List Action.
     *
     * @param null|string $filter
     * @param null|array  $params
     *
     * @return ApiResponse
     */
    public function execute(string $filter = null, array $params = null): ApiResponse
    {
        //====================================================================//
        // Execute Get Request
        $rawResponse = $this->visitor->getConnexion()->get(
            $this->visitor->getCollectionUri(),
            $this->getQueryParameters($filter, $params)
        );
        if (null === $rawResponse) {
            return $this->getErrorResponse();
        }
        //====================================================================//
        // Add Packs Filter to Params
        $params['extraArgs']["is_pack"] = "true" ;
        //====================================================================//
        // Execute Get Request
        $rawPackResponse = $this->visitor->getConnexion()->get(
            $this->visitor->getCollectionUri(),
            $this->getQueryParameters($filter, $params)
        );
        //====================================================================//
        // Extract Results
        $results = array_merge(
            $this->extractData($rawResponse),
            $this->extractData($rawPackResponse ?? array()),
        );
        //====================================================================//
        // Compute Meta
        $meta = array(
            'current' => count($results),
            'total' => $this->extractTotal($rawResponse, $params)
        );
        if (empty($this->options['raw'])) {
            $results["meta"] = $meta;
        }

        //====================================================================//
        // Force Total Value
        if (empty($this->options['raw']) && empty($filter)) {
            if (!empty($counter = $this->options['forceCounter'] ?? null) && is_numeric($counter)) {
                $results["meta"]['total'] = $counter;
            }
        }

        dump($this);

        return new ApiResponse($this->visitor, true, $results, $meta);
    }

    /**
     * @return array
     */
    protected function getDefaultOptions(): array
    {
        return array(
            "filterKey" => "search[user_ref__contains][]",      // Query Key for Filtering Data
            "pageKey" => null,                                  // Query Filter for Page Number
            "offsetKey" => "offset",                            // Or Query key for Results Offset
            "maxKey" => "limit",                                // Query Key for Limit Max Number of Results
            "raw" => false,                                     // Return raw data
        );
    }
}
