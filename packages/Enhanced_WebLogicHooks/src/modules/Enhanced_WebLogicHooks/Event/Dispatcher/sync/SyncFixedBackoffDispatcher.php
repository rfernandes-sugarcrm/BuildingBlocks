<?php
/*
 * Your installation or use of this SugarCRM file is subject to the applicable
 * terms available at
 * http://support.sugarcrm.com/Resources/Master_Subscription_Agreements/.
 * If you do not agree to all of the applicable terms or do not have the
 * authority to bind the entity as an authorized representative, then do not
 * install or use this SugarCRM file.
 *
 * Copyright (C) SugarCRM Inc. All rights reserved.
 */

namespace Sugarcrm\Sugarcrm\modules\Enhanced_WebLogicHooks\Event\Dispatcher\Sync;
use Psr\Http\Message\ResponseInterface;
use Sugarcrm\Sugarcrm\Security\HttpClient\ExternalResourceClient;
use Sugarcrm\Sugarcrm\modules\Enhanced_WebLogicHooks\Event\Dispatcher\DispatcherInterface;

final class SyncFixedBackoffDispatcher implements DispatcherInterface {

    /**
     * Dispatches an event with fixed backoff retry logic.
     *
     * @param ExternalResourceClient $client
     * @param SugarBean $ewlb
     * @param string $payload
     * @param array $headers
     * @return array{
     *     attempts: int,
     *     logs: array<int, array{statusCode: int|null, exception: string|null}>,
     *     response: mixed|null
     * }
     */
    public function dispatch(ExternalResourceClient $client, $ewlb, string $payload, array $headers): array
    {
        $maxRetries = 5;
        $fixedDelay = 2; // seconds
        $logs = [];
        $response = null;
        for ($attempt = 0; $attempt < $maxRetries; $attempt++) {
            try {
                $response = $client->request($ewlb->request_method, $ewlb->url, $payload, $headers);
                $statusCode = method_exists($response, 'getStatusCode') ? $response->getStatusCode() : null;
                $logs[] = [
                    'statusCode' => $statusCode,
                    'exception' => null
                ];
                if ($statusCode !== null && $statusCode >= 200 && $statusCode < 300) {
                    return [
                        'attempts' => $attempt + 1,
                        'logs' => $logs,
                        'response' => $response
                    ]; // Successful response
                }
            } catch (\Exception $e) {
                $logs[] = [
                    'statusCode' => null,
                    'exception' => $e->getMessage()
                ];
            }
            sleep($fixedDelay); // Fixed backoff: always 2s between attempts
        }
        return [
            'attempts' => $maxRetries,
            'logs' => $logs,
            'response' => $response
        ];
    }
}