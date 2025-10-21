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
class Enhanced_WebLogicHooksDelivery extends SugarBean {
    public $new_schema = true;
    public $module_dir = 'Enhanced_WebLogicHooksDelivery';
    public $object_name = 'Enhanced_WebLogicHooksDelivery';
    public $table_name = 'enhanced_web_logic_hooks_delivery';

    public $request_data;
    public $response_data;
    public $status;
    public $attempt;
    public $failure;
    public $id;
    public $name;
    public $date_entered;
    public $date_modified;
    public $modified_user_id;
    public $modified_by_name;
    public $created_by;
    public $created_by_name;

    public function bean_implements($interface)
    {
        switch ($interface) {
            case 'ACL':
                return true;
        }

        return false;
    }
}
