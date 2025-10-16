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

$viewdefs['Enhanced_WebLogicHooks']['base']['view']['list'] = [
    'panels' => [
        [
            'name' => 'panel_header',
            'label' => 'LBL_PANEL_1',
            'fields' => [
                [
                    'name' => 'name',
                    'enabled' => true,
                    'sortable' => true,
                    'link' => true,
                ],
                [
                    'name' => 'url',
                    'enabled' => true,
                    'sortable' => true,
                ],
                [
                    'name' => 'webhook_target_module',
                    'enabled' => true,
                    'sortable' => true,
                ],
                [
                    'name' => 'trigger_event',
                    'enabled' => true,
                    'sortable' => true,
                ],
                [
                    'name' => 'request_method',
                    'enabled' => true,
                    'sortable' => true,
                ],
                [
                    'name' => 'request_type',
                    'enabled' => true,
                    'sortable' => true,
                ],
            ],
        ],
    ],
];
