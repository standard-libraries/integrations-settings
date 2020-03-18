<?php
/**
 * Specify the version of the library
 */
$version = 1.0;

/**
 * Identify the usable path for the library
 */
$lib_path = dirname(__FILE__) . "/";

/**
 * Identify the usable URL for the library
 */
$themes_search_index = strpos(__FILE__,'wp-content/themes');
$plugins_search_index = strpos(__FILE__,'wp-content/plugins');
$url_postfix_prospect = dirname(__FILE__);
$included_via = "";
if( $themes_search_index !== false ) {
    $url_postfix_index = $themes_search_index;
    $included_via = "theme";
} elseif( $plugins_search_index !== false ) {
	$url_postfix_index = $plugins_search_index;
    $included_via = "plugin";
}
$url_prefix = get_site_url();
$url_postfix = substr($url_postfix_prospect, $url_postfix_index);
$lib_path_url = $url_prefix .'/'. $url_postfix;

/**
 * The library is so awesome that it can be included multiple times
 * but ensures only loaded once.
 */
require_once $lib_path . 'controllers/Standard_Libraries_Integration_Settings_Dispatcher.php';
$stdlib_integration_settings_dispatcher = new Standard_Libraries_Integration_Settings_Dispatcher( $version, $lib_path, $lib_path_url, $included_via );
$stdlib_integration_settings_dispatcher->register();
$stdlib_integration_settings_dispatcher->spearhead();