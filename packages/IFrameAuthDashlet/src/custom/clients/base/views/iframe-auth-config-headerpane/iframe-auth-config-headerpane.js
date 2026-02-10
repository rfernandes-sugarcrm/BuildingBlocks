({
    extendsFrom: 'HeaderpaneView',

    initialize: function (options) {
        this._super('initialize', [options]);
        this.title = app.lang.get('LBL_CONFIG_TITLE', this.module);
        if (this.context.get('layout') && this.context.get('layout') == 'iframe-auth-config') {
            this.context.on('settings:close', function () {
                app.router.goBack();
            });
        }
    }
})