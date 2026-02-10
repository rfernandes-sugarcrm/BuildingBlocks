({
	newConfig: false,
	errors: [],
	/**
	 * Initialization
	 */
	initialize: function(options) {
		this._super("initialize", [options]);
		this.context.on('settings:save', this.saveConfig, this);
	},

	/**
	 * Loads config data
	 */
	loadData: function() {
		app.api.call('read', app.api.buildURL('MD_IFrameAuthDashlet', 'config'), {}, {
			success: _.bind(function(data) {
				if (!_.isEmpty(data)) {
					_.each(data, function(value, key) {
						this.model.set(key, value);
					}, this);
				}
				this.render();
			}, this),
			error: _.bind(function() {
				app.alert.show('settings:refresh', {
					level: 'error',
					messages: 'Please refresh page',
					autoClose: false
				});
			}, this)
		});
	},

	/**
	 * Saves config data
	 */
	saveConfig: function() {
		this.errors = [];
		var data = {};

		_.each(this.meta.fields, function(def) {
			value = this.model.get(def.name);

			if ((value === '' || typeof value === 'undefined' || !value)) {
				this.errors.push({
					type: 'empty',
					label: app.lang.get(def.label, 'MD_IFrameAuthDashlet')
				});
			}

			data[def.name] = value;
		}, this);

		if (this.errors.length > 0) {
			_.each(this.errors, function(error) {
				switch (error.type) {
					case 'empty':
						app.alert.show('value' + error.label, {
							level: 'error',
							messages: 'Field "' + error.label + '" can not be empty!',
							autoClose: false
						});
						break;
				}

			}, this);

			return;
		}

		app.alert.show('settings:save', {
			level: 'process',
			title: app.lang.getAppString('LBL_LOADING')
		});

		var url = app.api.buildURL('MD_IFrameAuthDashlet', 'config');

		var method = this.newConfig === true ? 'create' : 'update';

		app.api.call(method, url, data, {
			success: _.bind(function() {
				app.alert.dismiss('settings:save');
				app.alert.show('settings:success', {
					level: 'success',
					messages: 'Configuration successfully saved!',
					autoClose: true
				});
			}, this),
			error: _.bind(function() {
				app.alert.show('settings:error', {
					level: 'error',
					messages: 'Error while saving Configuration',
					autoClose: false
				});
			}, this),
		});
	}
})