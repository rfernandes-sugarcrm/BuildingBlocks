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
$dictionary['Enhanced_WebLogicHooks'] = [
    'activity_enabled' => false,
    'audited' => false,
    'comment' => 'Enhanced Web Logic Hooks',
    'duplicate_check' => [
        'enabled' => false,
    ],
    'duplicate_merge' => false,
    'favorites' => false,
    'full_text_search' => false,
    'fields' => [
        'name' => [
            'name' => 'name',
            'vname' => 'LBL_EWLH_NAME',
            'type' => 'name',
            'dbType' => 'varchar',
            'len' => '255',
            'comment' => 'Easily identifiable name for the hook',
            'required' => true,
        ],
        'webhook_target_module' => [
            'name' => 'webhook_target_module',
            'vname' => 'LBL_EWLH_TARGET_NAME',
            'type' => 'enum',
            'required' => true,
            'function' => 'getAvailableModules',
            'function_bean' => 'Enhanced_WebLogicHooks'
        ],
        'request_method' => [
            'name' => 'request_method',
            'vname' => 'LBL_EWLH_REQUEST_METHOD',
            'type' => 'enum',
            'options' => 'enhanced_webhook_request_method_list',
            'default' => 'POST',
            'required' => true,
        ],
        'request_type' => [
            'name' => 'request_type',
            'vname' => 'LBL_EWLH_REQUEST_TYPE',
            'type' => 'enum',
            'options' => 'enhanced_webhook_request_type_list',
            'default' => 'async',
            'required' => true,
        ],
        'use_hmac' => [
            'name' => 'use_hmac',
            'vname' => 'LBL_EWLH_USE_HMAC',
            'type' => 'bool',
            'default' => '0',
            'audited' => false,
            'comment' => 'An indicator of whether this will use HMAC signing',
        ],
        'hmac_secret' => [
            'name' => 'hmac_secret',
            'vname' => 'LBL_EWLH_HMAC_SECRET',
            'type' => 'varchar',
            'comment' => 'HMAC Secret for signing the payload',
            'required' => false,
        ],
        'auth_method' => [
            'name' => 'auth_method',
            'vname' => 'LBL_EWLH_AUTH_METHOD',
            'type' => 'enum',
            'options' => 'enhanced_webhook_auth_method_list',
            'default' => 'none',
            'required' => true,
        ],
        'auth_token' => [
            'name' => 'auth_token',
            'vname' => 'LBL_EWLH_AUTH_TOKEN',
            'type' => 'varchar',
            'comment' => 'Authentication Token to be sent in the header',
            'required' => false,
        ],
        'url' => [
            'name' => 'url',
            'vname' => 'LBL_EWLH_URL',
            'type' => 'field-selector-input',
            'dbType' => 'varchar',
            'comment' => 'URL of website for the company',
            'required' => true,
        ],
        'trigger_event' => [
            'name' => 'trigger_event',
            'vname' => 'LBL_EWLH_TRIGGER_EVENT',
            'type' => 'enum',
            'options' => 'webLogicHookList',
            'required' => true,
        ],
        'payload' => [
            'name' => 'payload',
            'vname' => 'LBL_EWLH_PAYLOAD',
            'type' => 'key-value-selector-input',
            'dbType' => 'text',
            'comment' => 'Payload to send in the webhook with variables',
            'required' => false,
        ],
        'headers' => [
            'name' => 'headers',
            'vname' => 'LBL_EWLH_HEADERS',
            'type' => 'key-value-selector-input',
            'dbType' => 'text',
            'comment' => 'Headers to send in the webhook with variables',
        ],
        'timeout' => [
            'name' => 'timeout',
            'vname' => 'LBL_EWLH_TIMEOUT',
            'type' => 'int',
            'comment' => 'Timeout for the webhook in seconds',
            'default' => 5,
        ],
        'retry' => [
            'name' => 'retry',
            'vname' => 'LBL_EWLH_RETRY',
            'type' => 'int',
            'comment' => 'How many retries should be attempted if the webhook fails',
            'default' => 3,
        ],
        'retry_strategy' => [
            'name' => 'retry_strategy',
            'vname' => 'LBL_EWLH_RETRY_STRATEGY',
            'type' => 'enum',
            'options' => 'retry_strategy_list',
            'default' => 'exponential_backoff',
            'required' => true,
        ],
        'enhanced_wlhs_to_delivery' => [
            'name' => 'enhanced_wlhs_to_delivery',
            'type' => 'link',
            'relationship' => 'enhanced_wlhs_to_delivery',
            'source' => 'non-db',
            'module' => 'Enhanced_WebLogicHooksDelivery',
            'bean_name' => 'Enhanced_WebLogicHooksDelivery',
            'vname' => 'LBL_ENHANCED_WLHS_TO_DELIVERYDELIVERY_TITLE',
            'id_name' => 'enhanced_wc8efichooks_ida',
            'link-type' => 'many',
            'side' => 'left',
        ],
    ],
    'optimistic_locking' => true,
    'table' => 'enhanced_web_logic_hooks',
    'unified_search' => false,
    'unified_search_default_enabled' => false,
    'uses' => [
        'default',
        'basic',
    ],
];

if (!class_exists('VardefManager')){
}
VardefManager::createVardef('Enhanced_WebLogicHooks', 'Enhanced_WebLogicHooks', array('basic','assignable','taggable'));
