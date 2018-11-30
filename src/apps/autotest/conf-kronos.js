exports.config = {
   directConnect: true,
   capabilities:{
		'browserName': 'chrome',
		'chromeOptions': {
			args: ['--test-type=browser'],
			prefs: {
					'plugins.always_open_excel_externally': true,
					'download': {
						'prompt_for_download': false,
						'default_directory': 'C:/Users/usmatr06/Downloads',
						'directory_upgrade': true,
					},
					"RunAllFlashInAllowMode":"true",
			        "profile.default_content_settings.state.flash":1,
			        "profile.plugins.flashBlock.enabled": 0,
			        "profile.default_content_setting_values.plugins": 1,
			        "DefaultPluginsSetting": 1,
			        "AllowOutdatedPlugins":1,
			        "profile.content_settings.plugin_whitelist.adobe-flash-player": 1,
			        "profile.content_settings.exceptions.plugins.*,*.per_resource.adobe-flash-player": 1,
			        "PluginsAllowedForUrls": "https://timeentry.corporate.ingrammicro.com",
			        "plugins.state.flash":1
			}
		}
	},
   framework: 'jasmine2',
   specs: ['kronos.js']
};