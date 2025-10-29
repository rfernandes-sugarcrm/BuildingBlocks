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

namespace Sugarcrm\Sugarcrm\modules\Enhanced_WebLogicHooks\Event\Dispatcher;
use Sugarcrm\Sugarcrm\modules\Enhanced_WebLogicHooks\Event\Dispatcher\Sync\SyncExponentialBackoffDispatcher;
use Sugarcrm\Sugarcrm\modules\Enhanced_WebLogicHooks\Event\Dispatcher\Sync\SyncLinearBackoffDispatcher;
use Sugarcrm\Sugarcrm\modules\Enhanced_WebLogicHooks\Event\Dispatcher\Sync\SyncFixedBackoffDispatcher;
use SugarBean;

final class DispatcherFactory
{
    public static function create(SugarBean $elwb): DispatcherInterface
    {
        if ($elwb->request_type === 'sync') {
            if ($elwb->retry_strategy === 'exponential') {
                return new SyncExponentialBackoffDispatcher();
            }
            if ($elwb->retry_strategy === 'linear') {
                return new SyncLinearBackoffDispatcher();
            }
            if ($elwb->retry_strategy === 'fixed') {
                return new SyncFixedBackoffDispatcher();
            }
        }

//        if ($elwb->request_type === 'async') {
//            if ($elwb->retry_strategy === 'exponential') {
//                return new AsyncExponentialBackoffDispatcher();
//            }
//            if ($elwb->retry_strategy === 'linear') {
//                return new AsyncLinearBackoffDispatcher();
//            }
//            return new AsyncFixedTimeoutDispatcher();
//        }
        // Default dispatcher
        return new SyncExponentialBackoffDispatcher();
    }
}
