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

namespace Sugarcrm\Sugarcrm\modules\Enhanced_WebLogicHooks\Event\Dispatcher;
use Sugarcrm\Sugarcrm\Security\HttpClient\ExternalResourceClient;

interface DispatcherInterface
{
    /**
     * Dispatches a module event to its appropriate channel.
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
    public function dispatch(ExternalResourceClient $client, $ewlb, string $payload, array $headers): array;
}
