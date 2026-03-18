({
    /**
     * Copyright 2016 SugarCRM Inc.  Licensed by SugarCRM under the Apache 2.0 license.
     */

    // Display iframe inline
    // tagName: 'span',

    initialize: function(view) {
        this._super('initialize', arguments);
        var ctx = this.context;
        this.ready = true; //do some loading or setup if necessary, then set ready to true to render the iframe
        this.url = ctx.get('url');
    }

});
