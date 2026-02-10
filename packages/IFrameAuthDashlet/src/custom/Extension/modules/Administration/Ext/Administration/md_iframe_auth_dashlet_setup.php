<?php
$admin_option_defs = array();

$admin_option_defs['Administration']['md_iframe_auth_dashlet_setup'] = array(
    'Administration',
    'LBL_MD_IFRAME_AUTH_DASHLET_SETUP',
    'LBL_MD_IFRAME_AUTH_DASHLET_SETUP_DESCRIPTION',
    'javascript:parent.SUGAR.App.router.navigate("Home/layout/iframe-auth-config", {trigger: true});',
);

$admin_group_header[] = array(
    'LBL_MD_IFRAME_AUTH_DASHLET_SECTION_HEADER',
    '',
    false,
    $admin_option_defs,
    'LBL_MD_IFRAME_AUTH_DASHLET_SECTION_DESCRIPTION'
);
