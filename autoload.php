<?php
$lib_path = dirname(__FILE__) . "/";

/**
 * Loads the `Standard_Libraries_Integration_Settings` class
 */
require_once $lib_path . 'controllers/Standard_Libraries_Integration_Settings.php';

/**
 * Adds an action hook by using the main object as the holder of the callable
 */
$stdlib_integration_settings = new Standard_Libraries_Integration_Settings( $lib_path );
add_action( 'admin_menu', array( $stdlib_integration_settings, 'embed_submenu_page' ) );