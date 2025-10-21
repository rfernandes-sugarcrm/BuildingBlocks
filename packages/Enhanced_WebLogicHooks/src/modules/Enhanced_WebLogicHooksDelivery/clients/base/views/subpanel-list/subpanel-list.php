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
$viewdefs['Enhanced_WebLogicHooksDelivery']['base']['view']['subpanel-list'] = [
    'panels' => [
        [
            'name' => 'panel_header',
            'label' => 'LBL_PANEL_1',
            'fields' => [
                [
                    'name' => 'date_entered',
                    'label' => 'LBL_DATE_ENTERED',
                    'enabled' => true,
                    'readonly' => true,
                    'default' => true,
                ],
                [
                    'name' => 'enhanced_wlhs_to_delivery_name',
                    'label' => 'LBL_ENHANCED_WLHS_TO_DELIVERY_FROM_ENHANCED_WEBLOGICHOOKS_TITLE',
                    'enabled' => true,
                    'id' => 'ENHANCED_WC8EFICHOOKS_IDA',
                    'link' => true,
                    'sortable' => false,
                    'default' => true,
                ],
                [
                    'name' => 'status',
                    'label' => 'LBL_EWLH_STATUS',
                    'enabled' => true,
                    'default' => true,
                ],
                [
                    'name' => 'request_data',
                    'label' => 'LBL_EWLH_REQUEST_DATA',
                    'enabled' => true,
                    'default' => true,
                ],
                [
                    'name' => 'response_data',
                    'label' => 'LBL_EWLH_RESPONSE_DATA',
                    'enabled' => true,
                    'default' => true,
                ],
                [
                    'name' => 'attempt',
                    'label' => 'LBL_EWLH_ATTEMPT',
                    'enabled' => true,
                    'default' => true,
                ],
                [
                    'name' => 'failure',
                    'label' => 'LBL_EWLH_FAILURE',
                    'enabled' => true,
                    'default' => true,
                ],
                [
                    'label' => 'LBL_DATE_MODIFIED',
                    'enabled' => true,
                    'default' => true,
                    'name' => 'date_modified',
                ],
            ],
        ],
    ],
    'orderBy' => [
        'field' => 'date_modified',
        'direction' => 'desc',
    ],
];
