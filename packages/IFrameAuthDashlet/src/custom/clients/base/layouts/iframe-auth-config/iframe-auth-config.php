<?php

$viewdefs['base']['layout']['iframe-auth-config'] = array(
    'components' => array(
        array(
            'layout' => array(
                'type' => 'default',
                'name' => 'sidebar',
                'components' => array(
                    array(
                        'layout' => array(
                            'type' => 'base',
                            'name' => 'main-pane',
                            'css_class' => 'main-pane span12',
                            'components' => array(
                                array(
                                    'view' => 'iframe-auth-config-headerpane',
                                ),
                                array(
                                    'view' => 'iframe-auth-config-content',
                                ),
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
);