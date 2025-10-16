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
$dictionary['Enhanced_WebLogicHooksMetadataFields'] = [
    'activity_enabled' => false,
    'audited' => false,
    'comment' => 'Enhanced Web Logic Hooks Metadata Fields',
    'duplicate_check' => [
        'enabled' => false,
    ],
    'duplicate_merge' => false,
    'favorites' => false,
    'full_text_search' => false,
    'fields' => [
        'value' => [
            'name' => 'value',
            'vname' => 'LBL_EWLH_MF_NAME',
            'type' => 'name',
            'dbType' => 'varchar',
            'len' => '255',
            'required' => true,
        ],
        'text' => [
            'name' => 'text',
            'vname' => 'LBL_EWLH_MF_TEXT',
            'type' => 'name',
            'dbType' => 'varchar',
            'len' => '255',
            'required' => true,
        ],
        'type' => [
            'name' => 'type',
            'vname' => 'LBL_EWLH_MF_TYPE',
            'type' => 'name',
            'dbType' => 'varchar',
            'len' => '255',
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
