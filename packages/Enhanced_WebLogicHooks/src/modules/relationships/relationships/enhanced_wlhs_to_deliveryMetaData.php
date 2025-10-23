<?php
$dictionary["enhanced_wlhs_to_delivery"] = array (
    'true_relationship_type' => 'one-to-many',
    'from_studio' => true,
    'relationships' =>
        array (
            'enhanced_wlhs_to_delivery' =>
                array (
                    'lhs_module' => 'Enhanced_WebLogicHooks',
                    'lhs_table' => 'enhanced_web_logic_hooks',
                    'lhs_key' => 'id',
                    'rhs_module' => 'Enhanced_WebLogicHooksDelivery',
                    'rhs_table' => 'enhanced_web_logic_hooks_delivery',
                    'rhs_key' => 'id',
                    'relationship_type' => 'many-to-many',
                    'join_table' => 'enhanced_wlhs_to_delivery',
                    'join_key_lhs' => 'enhanced_wc8efichooks_ida',
                    'join_key_rhs' => 'enhanced_w7a9belivery_idb',
                ),
        ),
    'table' => 'enhanced_wlhs_to_delivery',
    'fields' =>
        array (
            'id' =>
                array (
                    'name' => 'id',
                    'type' => 'id',
                ),
            'date_modified' =>
                array (
                    'name' => 'date_modified',
                    'type' => 'datetime',
                ),
            'deleted' =>
                array (
                    'name' => 'deleted',
                    'type' => 'bool',
                    'default' => 0,
                ),
            'enhanced_wc8efichooks_ida' =>
                array (
                    'name' => 'enhanced_wc8efichooks_ida',
                    'type' => 'id',
                ),
            'enhanced_w7a9belivery_idb' =>
                array (
                    'name' => 'enhanced_w7a9belivery_idb',
                    'type' => 'id',
                ),
        ),
    'indices' =>
        array (
            0 =>
                array (
                    'name' => 'idx_enhanced_wlhs_to_delivery_pk',
                    'type' => 'primary',
                    'fields' =>
                        array (
                            0 => 'id',
                        ),
                ),
            1 =>
                array (
                    'name' => 'idx_enhanced_wlhs_to_delivery_ida1_deleted',
                    'type' => 'index',
                    'fields' =>
                        array (
                            0 => 'enhanced_wc8efichooks_ida',
                            1 => 'deleted',
                        ),
                ),
            2 =>
                array (
                    'name' => 'idx_enhanced_wlhs_to_delivery_idb2_deleted',
                    'type' => 'index',
                    'fields' =>
                        array (
                            0 => 'enhanced_w7a9belivery_idb',
                            1 => 'deleted',
                        ),
                ),
            3 =>
                array (
                    'name' => 'enhanced_wlhs_to_delivery_alt',
                    'type' => 'alternate_key',
                    'fields' =>
                        array (
                            0 => 'enhanced_w7a9belivery_idb',
                        ),
                ),
        ),
);