exports.config = {
	directConnect: true,
	capabilities:{
		'browserName': 'chrome',
		args: ['--test-type=browser'],
		prefs: {
				'plugins.always_open_excel_externally': true,
				'download': {
				'prompt_for_download': false,
				'default_directory': 'C:/Users/usmatr06/Downloads',
				'directory_upgrade': true
			}
		}
	},
	framework: 'jasmine2',
	specs: ['cuic.js'],

	allScriptsTimeout: 60000,

	// Options to be passed to Jasmine.
	jasmineNodeOpts: {
		defaultTimeoutInterval: 60000
	}
};