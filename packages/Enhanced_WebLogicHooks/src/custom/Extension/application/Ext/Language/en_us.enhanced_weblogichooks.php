<?php
$app_list_strings['moduleList']['Enhanced_WebLogicHooks'] = 'Enhanced Web Logic Hooks';
$app_list_strings['moduleListSingular']['Enhanced_WebLogicHooks'] = 'Enhanced Web Logic Hook';
$app_list_strings['moduleIconList']['Enhanced_WebLogicHooks'] = 'WH';

$app_list_strings['enhanced_webhook_request_method_list'] = [
    'POST' => 'POST',
    'GET' => 'GET',
    'PUT' => 'PUT',
    'DELETE' => 'DELETE',
    'HEAD' => 'HEAD',
    'PATCH' => 'PATCH',
    'OPTIONS' => 'OPTIONS',
];

$app_list_strings['retry_strategy_list'] = [
    'fixed' => 'Fixed Delay',
    'exponential' => 'Exponential Backoff',
    'linear' => 'Linear Backoff',
];

$app_list_strings['enhanced_webhook_request_type_list'] = [
    'async' => 'Asynchronous (uses Sugar Queue)',
    'sync' => 'Synchronous (Blocks UI & execution)',
];

$app_list_strings['enhanced_webhook_auth_method_list'] = [
    'none' => 'None',
    'basic' => 'Basic (base64-encoded)',
    'bearer' => 'Bearer Token',
];
