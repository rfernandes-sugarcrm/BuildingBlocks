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


class Enhanced_WebLogicHooksMetadataFieldsApi extends SugarApi
{
    public function registerApiRest()
    {
        return [
            'opportunity_stats' => [
                'reqType' => 'GET',
                'path' => ['Enhanced_WebLogicHooksMetadataFields', '<module>'],
                'pathVars' => ['me', 'module'],
                'method' => 'getModuleMetadataFields',
                'shortHelp' => 'Get metadata fields for a given module',
                'longHelp' => '',
            ],
        ];
    }

    public function getModuleMetadataFields(ServiceBase $api, array $args)
    {
        $module = $args['module'];
        // Check for permissions on module.
        $bean = BeanFactory::newBean($module);
        if (!$bean || !$bean->ACLAccess('view')) {
            return array();
        }

        $data = [];
        $mm = $this->getMetaDataManager();
        $vardefs = $mm->getVarDef($module);
        $fields = $vardefs['fields'];
        foreach ($fields as $field) {
            if (in_array($field['type'], ['id', 'relate', 'parent', 'assigned_user_name', 'team_name', 'email', 'text'])) {
                $data[] = [
                    'id' => $field['name'],
                    'value' => $field['name'],
                    'text' => get_label($field['vname'], $module),
                    'type' => $field['type']
                ];
            }
        }
        $ret = [
            'next_offset' => -1,
            'records' => $data
        ];

        return $ret;
    }
}
