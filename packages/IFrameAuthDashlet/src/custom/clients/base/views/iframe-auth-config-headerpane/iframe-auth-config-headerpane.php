<?php

$viewdefs['base']['view']['iframe-auth-config-headerpane'] = array (
    'template' => 'headerpane',
    'buttons' => array (
        array (
            'name' => 'close',
            'type' => 'button',
            'label' => 'LBL_CANCEL_BUTTON_LABEL',
            'events' => array (
                'click' => 'settings:close',
            ),
            'css_class' => 'btn-invisible btn-link',
        ),
        array (
            'name' => 'save_button',
            'type' => 'button',
            'label' => 'LBL_SAVE_BUTTON_LABEL',
            'primary' => true,
            'events' => array (
                'click' => 'settings:save',
            ),
        ),
    ),
);