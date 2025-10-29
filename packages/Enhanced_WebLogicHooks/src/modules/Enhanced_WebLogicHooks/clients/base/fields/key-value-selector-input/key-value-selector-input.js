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

    /**
     * Default settings used when none are supplied through metadata.
     *
     * Supported settings:
     * - {Number} max_display_chars The maximum number of characters to be
     *   displayed before truncating the field.
     * - {Boolean} collapsed Defines whether or not the textarea detail view
     *   should be collapsed on initial render.
     *
     *     // ...
     *     'settings' => array(
     *         'max_display_chars' => 50,
     *         'collapsed' => false
     *         //...
     *     ),
     *     //...
     *
     * @protected
     * @type {Object}
     */
    _defaultSettings: {
        max_display_chars: 450,
        collapsed: true
    },

    initialize: function(options) {
        this._super('initialize', [options]);
        this.plugins = _.union(this.plugins, 'Tooltip');
        this._initSettings();

        this.listenTo(this.model, 'before:save', this.setPayloadBeforeSave, this);
        this.listenTo(this.model, 'data:sync:complete', this._dataLoaded, this);
        // this.collection.on('data:sync:complete', this.loaded, this);
        this.events = {
            'click [name=toggleEditor]': this.toggleEditor,
            'click [name=addKVInputs]': this.addKVInputs,
            'click [name=removeKVRow]': this.removeKVRow,
            'click [name=openSelector]': this.openSelector,
            'click [name=addAllFields]': this.addAllFields,
            'click [name=removeAllFields]': this.removeAllFields,
            'click [data-action=toggle]': this.toggleCollapsed,
        };
    },

    /**
     * Initialize settings, default settings are used when none are supplied
     * through metadata.
     *
     * @return Instance of this field.
     * @protected
     */
    _initSettings: function() {
        this._settings = _.extend({},
            this._defaultSettings,
            this.def && this.def.settings || {}
        );
        this.collapsed = this._settings.collapsed;
        this.isTextArea = true;

        return this;
    },

    _dataLoaded: function() {
        if(this.model.get(this.name)) {
            this.kvData = JSON.parse(this.model.get(this.name));
            this.value = JSON.stringify(this.kvData, null, 2);
            this.render();
        }

        return this;
    },

    toggleEditor: function(e) {
        e.preventDefault();
        e.stopPropagation();

        if (this.isTextArea) {
            this.rebuildFromTextArea();
        } else {
            this.rebuildFromKVInputs();
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
        this.rebuildFromKVInputs();
    },

    rebuildFromKVInputs() {
        let kvObj = {};
        this.$('.kv-input').each(function() {
            let key = $(this).find('.key').val();
            let value = $(this).find('.value').val();
            if (key) {
                kvObj[key] = value;
            }
        });
        this.value = JSON.stringify(kvObj, null, 2);
    },

    rebuildFromTextArea() {
        // Parse textarea value as JSON and create kv-inputs
        let text = this.$('textarea').val() || '{}';
        let kvData = {};
        try {
            kvData = JSON.parse(text);
        } catch (err) {
            app.alert.show('invalid_json', {
                level: 'error',
                messages: 'Invalid JSON format.',
                autoClose: true
            });
            return;
        }
        this.kvData = kvData;
        this.value = text;
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
        this.value = JSON.stringify(kvObj, null, 2);
        this.render();
        this.hasChanged = () => {
            return true
        };
    },

    removeAllFields: function(e) {
        e.preventDefault();
        e.stopPropagation();

        this.kvData = {};
        this.value = '{}';
        this.render();
        this.hasChanged = () => {
            return true
        };
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

    setPayloadBeforeSave: function() {
        if (this.isTextArea) {
            this.rebuildFromTextArea();
        } else {
            this.rebuildFromKVInputs();
        }
        this.model.set(this.name, this.value);
    },

    /**
     * @inheritdoc
     *
     * Formatter that always returns the value set on the textarea field. Sets
     * a `short` value for a truncated representation, if the lenght of the
     * value on the field exceeds that of `max_display_chars`. The return value
     * can either be a string, or an object such as {long: 'abc'} or
     * {long: 'abc', short: 'ab'}, for example.
     * @param {String} value The value set on the textarea field.
     * @return {String|Object} The value set on the textarea field.
     */
    getFormattedValue: function() {
        if(!this.value) {
            return '';
        }
        const value = this.value.defaultValue || this.value;

        // no need to process further for edit template
        if (this.tplName === 'edit') {
            return value;
        }

        // If the tplName is 'edit' then value needs to be a string. Otherwise
        var max = this._settings.max_display_chars;

        var valueObj = {
            long: this.getDescription(value, false),
            defaultValue: value,
            short: '',
        };

        var longValueOverMaxChars = valueObj.long.string.length > max;

        if (valueObj.long && longValueOverMaxChars) {
            valueObj.short = this.getDescription(value, true);
        }

        return valueObj;
    },

    format: function(value) {
        // If the tplName is 'edit' then value needs to be a string. Otherwise
        // send back the object containing `value.long` and, if necessary,
        // `value.short`.
        if (this.tplName === 'edit' && !value) {
            return this.value;
        }
        var shortComment = value;
        var max = this._settings.max_display_chars;

        var valueObj = {
            long: this.getDescription(value, false),
            defaultValue: value,
            short: '',
        };

        var longValueOverMaxChars = valueObj.long.string.length > max;

        if (valueObj.long && longValueOverMaxChars) {
            valueObj.short = this.getDescription(shortComment, true);
        }

        return valueObj;
    },

    /**
     * Displaying full or short descriptions.
     *
     * @param {string} description The value set on the textarea field.
     * @param {boolean} short Need a short value of the comment.
     * @return {string} The entry with html for any links.
     */
    getDescription: function(description, short) {
        short = !!short;
        description = Handlebars.Utils.escapeExpression(description);
        description = short ? this.getShortComment(description) : description;

        return new Handlebars.SafeString(description);
    },

    /**
     * Truncate the text area entry so it is shorter than the max_display_chars
     * Only truncate on full words to prevent ellipsis in the middle of words
     *
     * @param {string} description The comment log entry to truncate
     * @return {string} the shortened version of an entry if it was originally longer than max_display_chars
     */
    getShortComment: function(description) {
        if (!description.length > this._settings.max_display_chars) {
            return description;
        }
        let shortDescription = description.substring(0, this._settings.max_display_chars);
        // let's cut at a full word by checking we are at a whitespace char
        while (!(/\s/.test(shortDescription[shortDescription.length - 1])) && shortDescription.length > 0) {
            shortDescription = shortDescription.substring(0, shortDescription.length - 1);
        }

        return shortDescription;
    },

    /**
     * Toggles the field between displaying the truncated `short` or `long`
     * value for the field, and toggles the label for the 'more/less' link.
     */
    toggleCollapsed: function() {
        this.collapsed = !this.collapsed;
        this.render();
    },
})
