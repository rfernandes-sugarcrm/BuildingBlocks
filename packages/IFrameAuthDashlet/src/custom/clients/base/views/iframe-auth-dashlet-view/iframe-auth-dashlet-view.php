<?php

$viewdefs['base']['view']['iframe-auth-dashlet-view'] = array(
    'dashlets' => array(
        array(
            'label' => 'LBL_IFRAME_AUTH_DASHLET_TITLE',
            'description' => 'LBL_IFRAME_AUTH_DASHLET_DESCRIPTION',
            'height' => 25,
            'config' => array(
            ),
            'preview' => array(
            ),
            'filter' => array(
                'module' => array(
                    'Accounts',
                    'Contacts',
                    'Leads',
                ),
                'view' => array(
                    'record',
                )
            )
        ),
    ),
    'custom_toolbar' => array(
        'buttons' => array(
            array(
                "type" => "dashletaction",
                "css_class" => "dashlet-toggle btn btn-invisible minify",
                "icon" => "sicon-chevron-up",
                "action" => "toggleMinify",
                "tooltip" => "LBL_DASHLET_MINIMIZE",
            ),
            array(
                'dropdown_buttons' => array(
                    array(
                        'type' => 'dashletaction',
                        'action' => 'editClicked',
                        'label' => 'LBL_DASHLET_CONFIG_EDIT_LABEL',
                    ),
                    array(
                        'type' => 'dashletaction',
                        'action' => 'iframeAuthConfigClicked',
                        'label' => 'LBL_IFRAME_AUTH_DASHLET_CONFIG',
                    ),
                    array(
                        'type' => 'dashletaction',
                        'action' => 'refreshClicked',
                        'label' => 'LBL_DASHLET_REFRESH_LABEL',
                    ),
                    array(
                        'type' => 'dashletaction',
                        'action' => 'removeClicked',
                        'label' => 'LBL_DASHLET_REMOVE_LABEL',
                    ),
                ),
            ),
        ),
    ),
    'panels' => array(
        array(
            'name' => 'dashlet_settings',
            'columns' => 1,
            'labelsOnTop' => true,
            'placeholders' => true,
            'fields' => array(
                array(
                    'name' => 'auto_refresh',
                    'label' => 'LBL_REPORT_AUTO_REFRESH',
                    'type' => 'enum',
                    'span' => 6,
                    'options' => 'sugar7_dashlet_auto_refresh_options',
                ),
            ),
        ),
    ),
);