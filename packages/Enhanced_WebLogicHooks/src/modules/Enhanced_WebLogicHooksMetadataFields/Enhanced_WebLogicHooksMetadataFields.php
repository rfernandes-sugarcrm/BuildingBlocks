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
class Enhanced_WebLogicHooksMetadataFields extends SugarBean {
    public $new_schema = false;
    public $module_dir = 'Enhanced_WebLogicHooksMetadataFields';
    public $object_name = 'Enhanced_WebLogicHooksMetadataFields';
    public $table_name = 'enhanced_web_logic_hooks_metadata_fields';

    public $value;
    public $text;
    public $type;

    public function bean_implements($interface)
    {
        return false;
    }
}
