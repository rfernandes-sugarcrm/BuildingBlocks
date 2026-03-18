({
    plugins: ['Dashlet'],

    _defaultSettings: {
        auto_refresh: 0,
        iframe_height: 500,
        full_view_iframe_height: 800
    },

    ready: false,

    defaultUrl: 'https://httpbin.org/get?record=',

    initialize: function(opts) {
        this._super('initialize', [opts]);
        this.on('dispose', this._disposeTimers, this);

        // Keep full-view iframe height in sync with drawer/content resizing.
        this._boundUpdateIframeHeight = _.bind(this._updateIframeHeight, this);
        $(window).on('resize.iframe-side-drawer', this._boundUpdateIframeHeight);
        this.context.on('layout:resize', this._boundUpdateIframeHeight, this);
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
            options.auto_refresh = this.settings.get('auto_refresh') || this._defaultSettings.auto_refresh;
            this.settings.set('auto_refresh', options.auto_refresh);

            const collectionOptions = this.context.get('collectionOptions') || {};
            options = Object.assign({}, collectionOptions, options);
            this.context.set('collectionOptions', options);

            const refreshRate = options.auto_refresh * 60000;
            if (refreshRate > 0) {
                this._disposeTimers();
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

        this._updateIframeHeight();

        // Build URL directly, similar to openDrawerListener.js
        var module = this.module || this.context.get('module') || (this.model ? this.model.get('_module') : '') || 'Home';
        var objectId = this.model && this.model.get('id') ? this.model.get('id') : this.context.get('modelId');
        var url = this.defaultUrl;
        if (objectId) {
            url += encodeURIComponent(objectId);
        }
        url += '&module=' + encodeURIComponent(module);
        this.url = url;
        this.ready = true;
        this.render();
    },

    fullViewClicked: function(event) {
        if (event) {
            event.preventDefault();
            event.stopPropagation();
        }

        var module = this.module || this.context.get('module') || this.model.get('_module') || 'Home';
        var objectId = this.model && this.model.get('id') ? this.model.get('id') : this.context.get('modelId');
        var name = (this.model && this.model.get('name')) || this.context.get('name') || app.lang.get('LBL_IFRAME_SIDEDRAWER_CHAT_TITLE');
        var dataTitle = app.sideDrawer.getDataTitle(module, 'LBL_IFRAME_SIDEDRAWER_CHAT_FULL_VIEW', name);
        var tabLabel = app.lang.get('LBL_IFRAME_SIDEDRAWER_CHAT_FULL_VIEW', module);

        var recordContext = {
            layout: 'iframe-side-drawer',
            title: tabLabel,
            dataTitle: dataTitle,
            dashboardName: name,
            context: {
                module: module,
                model: this.model,
                modelId: objectId,
                baseModelId: objectId,
                dataTitle: dataTitle,
                title: tabLabel,
                tabLabel: tabLabel,
                dashboardName: name,
                url: this.url, // Pass the iframe URL to the side drawer context
                isIFrameSideDrawerFullView: true,
                evtSource: event ? $(event.currentTarget) : null
            }
        };
        this.ready = true;

        app.sideDrawer.open(recordContext, null, true);
    },

    _updateIframeHeight: function() {
        if (this.context.get('isIFrameSideDrawerFullView')) {
            this.iframe_height = this._calculateFullViewHeight();
        } else {
            this.iframe_height = this._defaultSettings.iframe_height;
        }

        if (this.ready) {
            this.render();
        }
    },

    _calculateFullViewHeight: function() {
        var fallbackHeight = this._defaultSettings.iframe_height;
        var minHeight = 400;

        if (!this.$el || this.$el.length === 0) {
            return fallbackHeight;
        }

        var $viewport = this.$el.closest('.drawer, .content, .main-pane').first();
        var viewportHeight = $viewport.length ? $viewport.innerHeight() : $(window).height();

        if (!viewportHeight || viewportHeight <= 0) {
            return fallbackHeight;
        }

        var topOffset = this.$el.offset() ? this.$el.offset().top : 0;
        var viewportTop = $viewport.length && $viewport.offset() ? $viewport.offset().top : 0;
        var usedSpace = Math.max(0, topOffset - viewportTop);

        // Reserve a small buffer so the iframe never overflows the drawer/container.
        var calculatedHeight = viewportHeight - usedSpace - 24;

        return Math.max(minHeight, calculatedHeight);
    },

    _disposeTimers: function() {
        if (this.timerId) {
            clearInterval(this.timerId);
            this.timerId = null;
        }

        $(window).off('resize.iframe-side-drawer', this._boundUpdateIframeHeight);
        this.context.off('layout:resize', this._boundUpdateIframeHeight, this);
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
