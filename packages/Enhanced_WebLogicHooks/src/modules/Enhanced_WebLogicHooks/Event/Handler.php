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

use Exception;
use Psr\Log\LoggerInterface;
use SugarBean;
use Sugarcrm\Sugarcrm\DependencyInjection\Container;
use Sugarcrm\Sugarcrm\ProcessManager;
use Sugarcrm\Sugarcrm\modules\Enhanced_WebLogicHooks\Event\Dispatcher\DispatcherFactory;
use Sugarcrm\Sugarcrm\Security\HttpClient\ExternalResourceClient;

final class Handler
{
    private const RELATIONSHIP_EVENTS = [
        'after_relationship_add',
        'after_relationship_delete',
        'after_relationship_update',
    ];

    private const MODULE_DENYLIST = [
        'Enhanced_WebLogicHooks',
    ];

    protected $beanHandler;
    protected $logger;
    protected $container;
    private array $execLogs = [];
    public function __construct()
    {
        $this->container = Container::getInstance();
        $this->beanHandler = ProcessManager\Factory::getPMSEObject('PMSEBeanHandler');
        $this->logger = $this->container->get(LoggerInterface::class);
    }

    /**
     * Dispatch request based on SugarBean event.
     *
     * @param SugarBean $bean      The bean that triggered the event.
     * @param string    $event     Event name.
     * @param array     $args      Event arguments.
     * @param string    $id        ID of the Enhanced Web Logic Hook.
     */
    public function handleEvent(SugarBean $bean, string $event, array $args, string $id): void
    {
        $ewlb = BeanFactory::getBean('Enhanced_WebLogicHooks', $id);

        if (empty($ewlb->id)) {
            return;
        }

        $recordIdentifier = '';

        try {
            $moduleName = $bean->getModuleName();
            $recordIdentifier = "{$moduleName}/{$bean->id}";

            if (!self::isModuleAllowed($moduleName)) {
                $this->addWLLog($recordIdentifier, "Skipped module event ({$event}) [reason={$moduleName} events are not allowed]");
                return;
            }

            if (in_array($event, self::RELATIONSHIP_EVENTS, true)) {
                $relatedModuleName = $args['related_module'] ?? '';

                if (!self::isModuleAllowed($relatedModuleName)) {
                    $this->addWLLog($recordIdentifier, "Skipped module event ({$event}) [reason={$relatedModuleName} events are not allowed]");
                    return;
                }

                if (SugarBean::inOperation('delete') && $bean->deleted) {
                    $this->addWLLog($recordIdentifier, "Skipped module event ({$event}) [reason=deleting record]");
                    return;
                }
            }

            $this->processTemplateFields($recordIdentifier, $ewlb, $bean);

            // Default timeout
            if (!isset($ewlb->timeout) || !is_numeric($ewlb->timeout)) {
                $ewlb->timeout = 5;
                $this->addWLLog($recordIdentifier,"Request timeout set to default 5 seconds.");
            }

            $payload = $this->preparePayload($recordIdentifier, $ewlb, $event);
            if ($payload === null) {
                return;
            }
            $this->applyAuthHeaders($recordIdentifier, $ewlb, $payload);

            $this->addWLLog($recordIdentifier,"RequestMethod ({$ewlb->request_method}) URL {$ewlb->url}");
            $this->addWLLog($recordIdentifier,"Payload [{$payload}]");

            $dispatcher = DispatcherFactory::create($ewlb);
            try {
                $client = new ExternalResourceClient($ewlb->timeout);
                $headers = array_merge(['Content-type' => 'application/x-www-form-urlencoded'], $ewlb->headers);
                $this->addWLLog($recordIdentifier, "Headers: " . json_encode($headers, JSON_PRETTY_PRINT));
                if ($payload) {
                    $this->addWLLog($recordIdentifier, "Payload RAW: " . json_encode($payload, JSON_PRETTY_PRINT));
                    $payload = is_string($payload) ? $payload : http_build_query($payload);
                    $this->addWLLog($recordIdentifier, "Payload: " . json_encode($payload, JSON_PRETTY_PRINT));
                }
                $dispatchResult = $dispatcher->dispatch($client, $ewlb, $payload, $headers);
//                $this->addWLLog($recordIdentifier, "Dispatch attempts: " . $dispatchResult['attempts']);
                foreach ($dispatchResult['logs'] as $i => $log) {
                    $msg = "Attempt " . ($i+1) . ": ";
                    if ($log['statusCode'] !== null) {
                        $msg .= "StatusCode: " . $log['statusCode'] . ". ";
                    }
                    if ($log['exception'] !== null) {
                        $msg .= "Exception: " . $log['exception'] . ". ";
                    }
//                    $this->addWLLog($recordIdentifier, $msg);

                    // Create and link Enhanced_WebLogicHooksDelivery record for this attempt
                    $delivery = BeanFactory::newBean('Enhanced_WebLogicHooksDelivery');
//                    $delivery->parent_id = $bean->id;
//                    $delivery->response_data = $bean->getModuleName();
                    $delivery->request_data = $payload;
                    $delivery->attempt_number = $i + 1;
                    $delivery->status = $log['statusCode'];
                    $delivery->failure = $log['exception'];
                    $delivery->payload = $payload;
                    $delivery->headers = json_encode($headers);
                    $delivery->logs = $this->getExecLogs(true);
                    if ($dispatchResult['response'] && method_exists($dispatchResult['response'], 'getBody')) {
                        $delivery->response_data = $dispatchResult['response']->getBody()->getContents();
                    }
                    $delivery->save();
                    // Optionally relate via a link field if available:
                    if ($ewlb->load_relationship('enhanced_wlhs_to_delivery')) {
                        $ewlb->enhanced_wlhs_to_delivery->add($delivery->id);
                    }
                }
            } catch (Exception $e) {
                $this->addWLLog($recordIdentifier, "[ERROR] Request failed: " . $e->getMessage());
            }
        } catch (Exception $e) {
            $this->addWLLog($recordIdentifier,"Error handling event ({$event}) for {$recordIdentifier}: {$e->getMessage()}: {$e->getTraceAsString()}");
        }
    }

    private function processTemplateFields(string $recordIdentifier, SugarBean $ewlb, SugarBean $bean): void
    {
        $variableFields = ['url', 'headers', 'payload'];
        $jsonFields = ['headers', 'payload'];

        foreach ($variableFields as $field) {
            $value = $ewlb->$field ?? null;

            if (empty($value)) {
                $this->addWLLog($recordIdentifier, "Skipped field ({$field}) [reason=empty field]");
                continue;
            }

            if (in_array($field, $jsonFields, true) && $this->isJsonString($value)) {
                $decoded = json_decode($value, true);
                if (is_array($decoded)) {
                    foreach ($decoded as $k => $v) {
                        $decoded[$k] = $this->beanHandler->mergeBeanInTemplate($bean, $v);
                    }
                    $ewlb->$field = $decoded;
                } else {
                    $ewlb->$field = $this->beanHandler->mergeBeanInTemplate($bean, $value);
                }
            } else {
                $ewlb->$field = $this->beanHandler->mergeBeanInTemplate($bean, $value);
            }
        }
    }

    private function applyAuthHeaders(string $recordIdentifier, SugarBean $ewlb, string $payload): void
    {
        if (empty($ewlb->auth_method) || empty($ewlb->auth_token)) {
            return;
        }

        $ewlb->headers = is_array($ewlb->headers) ? $ewlb->headers : [];

        $authHeader = match ($ewlb->auth_method) {
            'token' => $ewlb->auth_token,
            'basic' => 'Basic ' . base64_encode($ewlb->auth_token),
            default => null,
        };

        if ($authHeader !== null) {
            $ewlb->headers['Authorization'] = $authHeader;
        }

        if ((int) $ewlb->use_hmac === 1) {
            $hmac = hash_hmac('sha256', $payload, $ewlb->hmac_secret);
            $this->addWLLog($recordIdentifier, "Calculated HMAC [{$hmac}] and added to [X-Sugar-Signature] header.");

            $ewlb->headers = is_array($ewlb->headers) ? $ewlb->headers : [];
            $ewlb->headers['X-Sugar-Signature'] = $hmac;
        }
    }

    private function preparePayload(string $recordIdentifier, SugarBean $ewlb, string $event): ?string
    {
        if (is_array($ewlb->payload)) {
            $payload = json_encode($ewlb->payload);
            $this->addWLLog($recordIdentifier, "Final payload (array converted to JSON): {$payload}");
            return $payload;
        }
        if ($this->isJsonString($ewlb->payload)) {
            $payload = json_encode($ewlb->payload);
            $this->addWLLog($recordIdentifier, "Final payload: {$payload}");
            return $payload;
        }

        $this->addWLLog($recordIdentifier, "Skipped module event ({$event}) ({$ewlb->payload}) [reason=invalid payload]");
        return null;
    }

    public static function isModuleAllowed(string $module): bool
    {
        return !empty($module) && !in_array($module, self::MODULE_DENYLIST, true);
    }

    protected function isJsonString(string $string): bool
    {
        try {
            json_decode($string);
            return json_last_error() === JSON_ERROR_NONE;
        } catch (Exception $e) {
            return false;
        }
    }

    private function addWLLog(string $recordIdentifier, string $msg): void
    {
        $this->logger->info("{$recordIdentifier} -> {$msg}");
        $this->execLogs[] = $msg;
    }

    private function getExecLogs(bool $asString = false): array|string
    {
        $execLogs = $this->execLogs;
        if ($asString) {
            $execLogs = implode("<br>● ", $execLogs);
        }
        return $execLogs;
    }
}
