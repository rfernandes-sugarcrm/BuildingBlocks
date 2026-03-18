<?php

$viewdefs['base']['view']['iframe-side-drawer-dashlet'] = array(
    'dashlets' => array(
        array(
            'label' => 'LBL_IFRAME_SIDEDRAWER_CHAT_TITLE',
            'description' => 'LBL_IFRAME_SIDEDRAWER_CHAT_DESCRIPTION',
            'height' => 25,
            'config' => array(),
            'preview' => array(),
            'filter' => array(
                'module' => array(
                    'Accounts',
                    'Contacts',
                    'Leads',
                    'Opportunities',
                ),
                'view' => array(
                    'record',
                ),
            ),
        ),
    ),
    'custom_toolbar' => array(
        'buttons' => array(
            array(
                'type' => 'dashletaction',
                'css_class' => 'dashlet-toggle btn btn-invisible minify',
                'icon' => 'sicon-chevron-up',
                'action' => 'toggleMinify',
                'tooltip' => 'LBL_DASHLET_MINIMIZE',
            ),
            array(
                'type' => 'dashletaction',
                'css_class' => 'dashlet-toggle btn btn-invisible',
                'icon' => 'sicon-full-screen',
                'action' => 'fullViewClicked',
                'tooltip' => 'LBL_IFRAME_SIDEDRAWER_CHAT_FULL_VIEW',
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

