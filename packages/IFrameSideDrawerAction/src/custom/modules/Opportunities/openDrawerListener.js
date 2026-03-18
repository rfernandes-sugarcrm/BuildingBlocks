(function(app){
    /**
     * Copyright 2016 SugarCRM Inc.  Licensed by SugarCRM under the Apache 2.0 license.
     */
    //Run callback when Sidecar metadata is fully initialized
    app.events.on('app:sync:complete', function(){

        var openDrawerCallback = function(model, event){
            if (!model || !model.get('id')) {
                app.alert.show('iframe-side-drawer-error', {
                    level: 'error',
                    messages: 'Cannot open IFrame Side Drawer: missing record ID.'
                });
                return;
            }
            var module = model.module || model.get('_module') || model.get('module') || 'Opportunities';
            var objectId = model.get('id');
            var name = model.get('name') || app.lang.get('LBL_IFRAME_SIDEDRAWER_CHAT_TITLE');
            var dataTitle = app.sideDrawer.getDataTitle ? app.sideDrawer.getDataTitle(module, 'LBL_IFRAME_SIDEDRAWER_CHAT_FULL_VIEW', name) : name;
            var tabLabel = app.lang.get ? app.lang.get('LBL_IFRAME_SIDEDRAWER_CHAT_FULL_VIEW', module) : 'Full View';
            var url = '//httpbin.org/get?record=' + encodeURIComponent(objectId) + '&module=' + encodeURIComponent(module);

            var recordContext = {
                layout: 'iframe-side-drawer',
                title: tabLabel,
                dataTitle: dataTitle,
                dashboardName: name,
                context: {
                    module: module,
                    model: model,
                    modelId: objectId,
                    baseModelId: objectId,
                    dataTitle: dataTitle,
                    title: tabLabel,
                    tabLabel: tabLabel,
                    dashboardName: name,
                    url: url, // Pass the iframe URL to the side drawer context
                    isIFrameSideDrawerFullView: true,
                    evtSource: event ? $(event.currentTarget) : null
                }
            };

            // if no drawer currently open
            if(app.drawer.getActiveDrawerLayout() === app.controller.layout){
                app.sideDrawer.open(recordContext, null, true);
                app.controller.context.once('button:open_drawer:click', openDrawerCallback);
                app.controller.context.reloadData();
            }
        };

        //When a record layout is loaded...
        app.router.on('route:record', function(module){
            //AND the module is Opportunities...
            if(module === 'Opportunities') {
                //AND the 'button:open_drawer:click' event occurs on the current Context
                app.controller.context.once('button:open_drawer:click', openDrawerCallback);
            }
        });

    });
})(SUGAR.App);
