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
/**
 * @class View.Fields.Base.KeyValueSelectorField
 * @alias SUGAR.App.view.fields.BaseKeyValueSelectorField
 * @extends View.Fields.Base.BaseField
 */
({
    initialize: function(options) {
        this._super('initialize', [options]);
        this.plugins = _.union(this.plugins, 'Tooltip');
        this.kvData = {};
        this.events = {
            'click [name=toggleEditor]': this.toggleEditor,
            'click [name=addKVInputs]': this.addKVInputs,
            'click [name=removeKVRow]': this.removeKVRow,
            'click [name=openSelector]': this.openSelector,
            'click [name=addAllFields]': this.addAllFields,
            'click [name=removeAllFields]': this.removeAllFields
        };
    },

    toggleEditor: function(e) {
        e.preventDefault();
        e.stopPropagation();

        if (this.isTextArea) {
            // Parse textarea value as JSON and create kv-inputs
            let jsonStr = this.$('textarea').val();
            let kvData = {};
            try {
                kvData = JSON.parse(jsonStr);
            } catch (err) {
                app.alert.show('invalid_json', {
                    level: 'error',
                    messages: 'Invalid JSON format.',
                    autoClose: true
                });
                return;
            }
            this.kvData = kvData;
        } else {
            // Collect kv-inputs and generate JSON
            let kvObj = {};
            this.$('.kv-input').each(function() {
                let key = $(this).find('.key').val();
                let value = $(this).find('.value').val();
                if (key) {
                    kvObj[key] = value;
                }
            });
            let jsonStr = JSON.stringify(kvObj, null, 2);
            this.jsonStr = jsonStr;
        }
        this.isTextArea = !this.isTextArea;
        this.render();
    },

    addKVInputs: function(e) {
        e.preventDefault();
        e.stopPropagation();

        this.$('#kv-editor').append(`
            <div class="flex gap-2 kv-input">
                <input type="text" class="input key"/>
                <input type="text" class="input value"/>
                <button name="openSelector" class="btn h-8" rel="tooltip" data-bs-placement="left" aria-label="{{str "LBL_EWLH_SELECT_FIELD" ../module}}" data-original-title="{{str 'LBL_EWLH_SELECT_FIELD' ../module}}">
                   <i class="sicon sicon-add-existing"></i>
                </button>
                <button name="removeKVRow" class="btn h-8" rel="tooltip" data-bs-placement="left" aria-label="{{str "LBL_EWLH_REMOVE_FIELD" ../module}}" data-original-title="{{str 'LBL_EWLH_REMOVE_FIELD' ../module}}">
                   <i class="sicon sicon-remove"></i>
                </button>
            </div>
        `);
    },

    removeKVRow: function(e) {
        e.preventDefault();
        e.stopPropagation();

        e.currentTarget.parentElement.remove();
    },

    addAllFields: async function(e) {
        e.preventDefault();
        e.stopPropagation();

        const filterCollection = await this.fetchCollectionData(this.getFilterCollection());
        const module = this.model.get('webhook_target_module');

        let kvObj = {};
        if (Array.isArray(filterCollection)) {
            filterCollection.forEach(item => {
                const key = item.value;
                if (key) {
                    kvObj[key] = `{::${module}::${key}::}`;
                }
            });
        }
        this.kvData = kvObj;
        this.jsonStr = JSON.stringify(kvObj, null, 2);
        this.render();
    },

    removeAllFields: function(e) {
        e.preventDefault();
        e.stopPropagation();

        this.kvData = {};
        this.jsonStr = '{}';
        this.render();
    },

    getFilterCollection: function() {
        let filterCollection = app.data.createBeanCollection('Enhanced_WebLogicHooksMetadataFields', null, {});
        filterCollection.setOption('webhook_target_module', this.model.get('webhook_target_module'));
        filterCollection.setOption('endpoint', this.metadataFieldsCollectionEndpoint);

        return filterCollection;
    },

    fetchCollectionData: function(filterCollection) {
        return new Promise((resolve, reject) => {
            filterCollection.fetch({
                success: function(col, resp) {
                    resolve(resp);
                },
                error: function(err) {
                    reject(err);
                }
            });
        });
    },

    openSelector: async function(e) {
        e.preventDefault();
        e.stopPropagation();
        this.currentTarget = e.currentTarget;

        //open drawer with module fields
        app.drawer.open(
            {
                layout: 'selection-list',
                context: {
                    module: 'Enhanced_WebLogicHooksMetadataFields',
                    collection: this.getFilterCollection(),
                    model: app.data.createBean('Enhanced_WebLogicHooksMetadataFields'),
                    limit: 500
                }
            },
            _.bind(this.setValue, this)
        );

        return this;
    },

    metadataFieldsCollectionEndpoint: function(method, collection, options, callbacks) {
        var url = app.api.buildURL(`Enhanced_WebLogicHooksMetadataFields/${options.webhook_target_module}`);
        return app.api.call('read', url, null, callbacks);
    },

    setValue(selection) {
        const variable = `{::${this.model.get('webhook_target_module')}::${selection.value}::}`;
        $(this.currentTarget.parentElement).find('.value').val(variable);
    },
})
