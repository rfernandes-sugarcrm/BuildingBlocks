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
$dictionary['Enhanced_WebLogicHooksDelivery'] = [
    'activity_enabled' => false,
    'audited' => false,
    'comment' => 'Enhanced Web Logic Hooks Delivery',
    'duplicate_check' => [
        'enabled' => false,
    ],
    'duplicate_merge' => false,
    'favorites' => false,
    'full_text_search' => false,
    'fields' => [
        'ewlh_id' => [
            'name' => 'ewlh_id',
            'rname' => 'id',
            'id_name' => 'ewlh_id',
            'vname' => 'LBL_ACCOUNT_ID',
            'type' => 'relate',
            'table' => 'enhanced_web_logic_hooks',
            'isnull' => 'true',
            'module' => 'Enhanced_WebLogicHooks',
            'dbType' => 'id',
            'reportable' => false,
            'source' => 'non-db',
            'massupdate' => false,
            'duplicate_merge' => 'disabled',
            'hideacl' => true,
            'link' => 'enhanced_web_logic_hooks',
        ],
        'request_data' => [
            'name' => 'request_data',
            'vname' => 'LBL_EWLH_REQUEST_DATA',
            'type' => 'varchar',
            'required' => true,
        ],
        'response_data' => [
            'name' => 'response_data',
            'vname' => 'LBL_EWLH_RESPONSE_DATA',
            'type' => 'varchar',
            'required' => true,
        ],
        'status' => [
            'name' => 'status',
            'vname' => 'LBL_EWLH_STATUS',
            'type' => 'int',
            'comment' => 'Status of the delivery',
            'default' => 0,
        ],
        'attempt' => [
            'name' => 'attempt',
            'vname' => 'LBL_EWLH_ATTEMPT',
            'type' => 'int',
            'comment' => 'Attempt retry',
            'default' => 0,
        ],
        'failure' => [
            'name' => 'failure',
            'vname' => 'LBL_EWLH_FAILURE',
            'type' => 'varchar',
            'required' => true,
        ],
    ],
    'optimistic_locking' => true,
    'table' => 'enhanced_web_logic_hooks_metadata_fields',
    'unified_search' => false,
    'unified_search_default_enabled' => false,
    'uses' => [
        'default',
        'basic',
    ],
];

if (!class_exists('VardefManager')){
}
VardefManager::createVardef('Enhanced_WebLogicHooksDelivery', 'Enhanced_WebLogicHooksDelivery', array('basic'));
