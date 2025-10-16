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
use Sugarcrm\Sugarcrm\AccessControl\AccessControlManager;

class Enhanced_WebLogicHooks extends Basic {
    public $new_schema = true;
    public $module_dir = 'Enhanced_WebLogicHooks';
    public $object_name = 'Enhanced_WebLogicHooks';
    public $table_name = 'enhanced_web_logic_hooks';
    public $importable = false;

    public $assigned_user_id;
    public $assigned_user_name;
    public $assigned_user_link;
    public $tag;
    public $tag_link;
    public $id;
    public $name;
    public $date_entered;
    public $date_modified;
    public $modified_user_id;
    public $modified_by_name;
    public $created_by;
    public $created_by_name;
    public $description;
    public $deleted;
    public $created_by_link;
    public $modified_user_link;
    public $activities;
    public $following;
    public $following_link;
    public $my_favorite;
    public $favorite_link;
    public $commentlog;
    public $commentlog_link;
    public $locked_fields;
    public $locked_fields_link;
    public $sync_key;
    public $hmac_secret;
    public $disable_row_level_security = true;

    public $webhook_target_module;
    public $request_method;
    public $request_type;
    public $use_hmac;
    public $auth_method;
    public $auth_token;
    public $url;
    public $trigger_event;
    public $payload;
    public $headers;
    public $timeout;
    public function bean_implements($interface)
    {
        switch ($interface) {
            case 'ACL':
                return true;
        }

        return false;
    }

    protected function getActionArray()
    {
        return [1, $this->name, 'modules/Enhanced_WebLogicHooks/Event/Handler.php', 'Handler', 'handleEvent', $this->id];
    }

    /**
     * Override the save method to add/remove logic hooks as necessary
     * Avoids the need to run LH events unless necessary
     *
     * @param bool $check_notify
     * @throws SugarApiExceptionModuleDisabled
     */
    public function save($check_notify = false)
    {
        if (!AccessControlManager::instance()->allowModuleAccess($this->webhook_target_module)) {
            throw new SugarApiExceptionModuleDisabled();
        }
        $hook = $this->getActionArray();
        if (!empty($this->fetched_row)) {
            $oldhook = $hook;
            // since remove_logic_hook compares 1, 3 and 4
            $oldhook[3] = 'Enhanced_WebLogicHooks';
            $oldTargetModule = $this->fetched_row['webhook_target_module'] ?? '';
            if (!empty($oldTargetModule)) {
                remove_logic_hook($oldTargetModule, $this->trigger_event, $oldhook);
            }
        }
        parent::save($check_notify);
        $hook[5] = $this->id;
        check_logic_hook_file($this->webhook_target_module, $this->trigger_event, $hook);
    }

    /**
     * Get a list of available modules for the webhook target module field
     * Any module available in Studio except the ones specified in $excludedModules
     *
     * @return array
     */
    public function getAvailableModules()
    {
        $available_modules = [];
        $excludedModules = [
            'Home'
        ];

        $studio_browser = new StudioBrowser();
        $studio_browser->loadModules();
        $studio_modules = array_keys($studio_browser->modules);
        foreach ($studio_modules as $module_name) {
            if (!in_array($module_name, $excludedModules)) {
                $available_modules[$module_name] = $module_name;
            }
        }
        asort($available_modules);

        return $available_modules;
    }
}
