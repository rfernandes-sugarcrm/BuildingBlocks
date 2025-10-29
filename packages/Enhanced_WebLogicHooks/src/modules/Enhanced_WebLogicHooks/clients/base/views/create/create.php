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

$viewdefs['Enhanced_WebLogicHooks']['base']['view']['create'] = [
    'template' => 'record',
    'panels' => [
        [
            'name' => 'panel_header',
            'header' => true,
            'fields' => [
                [
                    'name' => 'name',
                    'required' => true,
                    'label' => 'LBL_NAME',
                ],
                [
                    'type' => 'follow',
                    'readonly' => true,
                ],
            ],
        ],
        [
            'name' => 'panel_body',
            'label' => 'LBL_RECORD_DETAILS',
            'columns' => 2,
            'placeholders' => true,
            'fields' => [
                'webhook_target_module',
                'request_type',
                'request_method',
                'trigger_event',
            ],
        ],
    ],
];
