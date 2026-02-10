({
    plugins: ['Dashlet'],

    _defaultSettings: {
        auto_refresh: 0,
        iframe_height: 500,
    },

    ready: false,
    missconfig: false,

    initialize: function(opts) {
        this._super('initialize', [opts]);
    },

    initDashlet: function() {
        const self = this;
        if (this.meta.config) {
            this.layout.before('dashletconfig:save', _.bind(function() {
                if (this.validateFields() === false) {
                    return false;
                }
            }, this));
        } else {
            let options = {};
            options.limit = this.settings.get('limit') || this._defaultSettings.limit;
            this.settings.set('limit', options.limit);

            options.auto_refresh = this.settings.get('auto_refresh') || this._defaultSettings.auto_refresh;
            this.settings.set('auto_refresh', options.auto_refresh);

            options = _.extend({}, this.context.get('collectionOptions'), options);
            this.context.set('collectionOptions', options);

            // Set up refresh if needed
            const refreshRate = options.auto_refresh * 60000;
            if (refreshRate > 0) {
                if (this.timerId) {
                    clearInterval(this.timerId);
                }
                this.timerId = setInterval(_.bind(function() {
                    if (self.context) {
                        self.context.resetLoadFlag();
                        self.loadData();
                    }
                }, this), refreshRate);
            }
        }
    },

    loadData: function() {
        if (this.meta.config) {
            return;
        }

        this.iframe_height = this._defaultSettings.iframe_height;

        var proceedWithLoad = _.bind(function() {
            var config = (this.workData && this.workData.iframeAuthDashletConfig) ? this.workData.iframeAuthDashletConfig : {};
            var baseUrl = config.base_url || '';
            var token = config.token || '';
            var userEmail = '';
            if (App.user && Array.isArray(App.user.get('email')) && App.user.get('email').length > 0) {
                userEmail = App.user.get('email')[0];
            }

            // Defensive: Show error UI if config is missing
            if (!baseUrl || !token) {
                this.missconfig = true;
                this.ready = false;
                this.url = '';
            } else {
                // Compose the iframe URL using base_url and token
                this.url = `${baseUrl}?token=${token}&userEmail=${encodeURIComponent(userEmail.email_address)}`;
                this.ready = true;
            }
            this.render();
        }, this);

        if (this.workData && this.workData.iframeAuthDashletConfig && this.workData.iframeAuthDashletConfig.base_url) {
            proceedWithLoad();
        } else {
            this.fetchConfig(proceedWithLoad, proceedWithLoad);
        }
    },

    iframeAuthConfigClicked: function() {
        return SUGAR.App.router.navigate("Home/layout/iframe-auth-config", {trigger: true});
    },

    fetchConfig: function(resolve) {
        app.api.call('read', app.api.buildURL('MD_IFrameAuthDashlet/config'), null, {
            success: function(config) {
                this.workData = this.workData || {};
                this.workData.iframeAuthDashletConfig = config;
                resolve();
            }.bind(this),
            error: function() {
                resolve();
            }.bind(this)
        });
    },

    /**
     * Validates dashlet fields marked as required.
     *
     * @return {boolean}
     */
    validateFields: function() {
        let _validModel = true;

        _.each(this.fields, _.bind(function(field) {
            if (field.def.required === true && _.isEmptyValue(this.dashModel.get(field.name))) {
                field.model.trigger('error:validation:' + field.name, {
                    'required': true
                });
                _validModel = false;
            }
        }, this));
        return _validModel;
    }
})
