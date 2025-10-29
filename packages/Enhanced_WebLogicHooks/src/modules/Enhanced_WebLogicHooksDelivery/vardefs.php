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
        'request_data' => [
            'name' => 'request_data',
            'vname' => 'LBL_EWLH_REQUEST_DATA',
            'type' => 'text',
            'required' => true,
        ],
        'response_data' => [
            'name' => 'response_data',
            'vname' => 'LBL_EWLH_RESPONSE_DATA',
            'type' => 'text',
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
        'logs' => [
            'name' => 'logs',
            'vname' => 'LBL_EWLH_LOGS',
            'type' => 'varchar',
            'dbType' => 'text',
            'required' => true,
        ],
        'enhanced_wlhs_to_delivery' => [
            'name' => 'enhanced_wlhs_to_delivery',
            'type' => 'link',
            'relationship' => 'enhanced_wlhs_to_delivery',
            'source' => 'non-db',
            'module' => 'Enhanced_WebLogicHooks',
            'bean_name' => 'Enhanced_WebLogicHooks',
            'side' => 'right',
            'vname' => 'LBL_ENHANCED_WLHS_TO_DELIVERY_FROM_ENHANCED_WEBLOGICHOOKSDELIVERY_TITLE',
            'id_name' => 'enhanced_wc8efichooks_ida',
            'link-type' => 'one',
        ],
        'enhanced_wlhs_to_delivery_name' => [
            'name' => 'enhanced_wlhs_to_delivery_name',
            'type' => 'relate',
            'source' => 'non-db',
            'vname' => 'LBL_ENHANCED_WLHS_TO_DELIVERY_FROM_ENHANCED_WEBLOGICHOOKS_TITLE',
            'save' => true,
            'id_name' => 'enhanced_wc8efichooks_ida',
            'link' => 'enhanced_wlhs_to_delivery',
            'table' => 'enhanced_web_logic_hooks',
            'module' => 'Enhanced_WebLogicHooks',
            'rname' => 'name',
        ],
        'enhanced_wc8efichooks_ida' => [
            'name' => 'enhanced_wc8efichooks_ida',
            'type' => 'id',
            'source' => 'non-db',
            'vname' => 'LBL_ENHANCED_WLHS_TO_DELIVERY_FROM_ENHANCED_WEBLOGICHOOKSDELIVERY_TITLE_ID',
            'id_name' => 'enhanced_wc8efichooks_ida',
            'link' => 'enhanced_wlhs_to_delivery',
            'table' => 'enhanced_web_logic_hooks',
            'module' => 'Enhanced_WebLogicHooks',
            'rname' => 'id',
            'reportable' => false,
            'side' => 'right',
            'massupdate' => false,
            'duplicate_merge' => 'disabled',
            'hideacl' => true,
        ],
    ],
    'optimistic_locking' => true,
    'table' => 'enhanced_web_logic_hooks_delivery',
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
