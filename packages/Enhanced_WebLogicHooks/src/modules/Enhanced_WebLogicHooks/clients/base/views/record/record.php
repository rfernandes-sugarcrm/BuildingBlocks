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

$viewdefs['Enhanced_WebLogicHooks']['base']['view']['record'] = [
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
                'retry_strategy',
                'retry',
                'timeout',
                [
                    'name' => 'url',
                    'span' => 12,
                ],
                [
                    'name' => 'payload',
                    'span' => 12,
                ],
            ],
        ],
        [
            'name' => 'panel_security',
            'label' => 'LBL_RECORD_SECURITY',
            'columns' => 2,
            'newTab' => false,
            'placeholders' => true,
            'panelDefault' => 'expanded',
            'fields' => [
                'use_hmac',
                'hmac_secret',
                'auth_method',
                'auth_token',
            ],
        ],
        [
            'newTab' => false,
            'panelDefault' => 'expanded',
            'name' => 'hidden_panel',
            'columns' => 2,
            'labelsOnTop' => 1,
            'placeholders' => 1,
            'fields' => [
                [],
            ],
        ],
    ],
    'dependencies' => [
        [
            'hooks' => ['all'],
            'trigger' => 'true',
            'triggerFields' => ['trigger_event'],
            'onload' => true,
            'actions' => [
                [
                    'action' => 'SetVisibility',
                    'params' => [
                        'target' => 'webhook_target_module',
                        'value' => 'not(isInList($trigger_event, createList("after_login", "after_logout", "login_failed")))',
                    ],
                ],
                [
                    'action' => 'SetValue',
                    'params' => [
                        'target' => 'webhook_target_module',
                        'value' => 'ifElse(isInList($trigger_event, createList("after_login", "after_logout", "login_failed")), "Users", $webhook_target_module)',
                    ],
                ],
            ],
        ],
        [
            'hooks' => ['all'],
            'trigger' => 'true',
            'triggerFields' => ['use_hmac'],
            'onload' => true,
            'actions' => [
                [
                    'action' => 'SetVisibility',
                    'params' => [
                        'target' => 'hmac_secret',
                        'value' => 'equal($use_hmac, 1)',
                    ],
                ],
            ],
        ],
        [
            'hooks' => ['all'],
            'trigger' => 'true',
            'triggerFields' => ['auth_method'],
            'onload' => true,
            'actions' => [
                [
                    'action' => 'SetVisibility',
                    'params' => [
                        'target' => 'auth_token',
                        'value' => 'not(equal($auth_method, "none"))',
                    ],
                ],
            ],
        ],
    ],
];
