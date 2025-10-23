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
 * @class View.Fields.Base.FieldSelectorField
 * @alias SUGAR.App.view.fields.BaseFieldSelectorField
 * @extends View.Fields.Base.UrlField
 */
({
    /**
     * @inheritdoc
     *
     * The direction for this field should always be `ltr`.
     */
    direction: 'ltr',

    /**
     * @inheritdoc
     */
    initialize: function(options) {
        this._super("initialize", arguments);
        //Generated URL's should not be editable
        if (app.utils.isTruthy(this.def.gen)) {
            this.def.readonly = true;
        }
        this.events = {
            'click [name=openSelector]': this.openSelector
        };
    },

    openSelector: function(e) {
        e.preventDefault();
        e.stopPropagation();

        let filterCollection = app.data.createBeanCollection('Enhanced_WebLogicHooksMetadataFields', null, {});
        filterCollection.setOption('webhook_target_module', this.model.get('webhook_target_module'));
        filterCollection.setOption('endpoint', this.metadataFieldsCollectionEndpoint);

        //open drawer with document templates
        app.drawer.open(
            {
                layout: 'selection-list',
                context: {
                    module: 'Enhanced_WebLogicHooksMetadataFields',
                    collection: filterCollection,
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
        this.model.set(this.name, this.model.get(this.name) + variable);
    },

    format:function(value){
        if (value) {
            if (!value.match(/^([a-zA-Z]+):/)) {
                value = 'https://' + value;
            }
            let whiteList = app.config.allowedLinkSchemes;
            this.def.isClickable = true;
            if (!whiteList.filter(function(scheme) {
                return value.toLowerCase().indexOf(scheme + ':') === 0;
            }).length) {
                this.def.isClickable = false;
            }
        }
        return value;
    },
    unformat:function(value){
        value = (value!='' && value!='https://') ? value.trim() : "";
        return value;
    },
    _render: function() {
        this.def.link_target = _.isUndefined(this.def.link_target) ? '_blank' : this.def.link_target;
        app.view.Field.prototype._render.call(this);
    }
})
