<?php
// Notes: 
//  * Don't use reserved keys (they're used internally). 
//  * Reserved keys = NAME, VERSION, MAIN_FILE, ROOT_DIR, NAMESPACE_BASE
//  * The SLUG and PREFIX keys are auto-generated (based in plugin name), if you do not set them here.
//  * Don't declare any key starting with underline 
//  * all values here can be acessed with `h\config_get` helper

return [
	'ASSETS_DIR' => 'assets',
	'TEMPLATES_DIR' => 'templates',
	'LANGUAGES_DIR' => 'languages',
	'SLUG' => 'brazilian-fields-registry',
	// 'PREFIX' => 'wp_brazilian_fields_registry_',

	// disable the plugin cache while in development or debugging
	'DISABLE_CACHE' => WP_DEBUG,
];
