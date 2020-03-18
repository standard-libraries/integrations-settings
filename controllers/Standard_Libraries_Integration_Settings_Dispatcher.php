<?php

class Standard_Libraries_Integration_Settings_Dispatcher {

    private $version;
    public $lib_path;
    public $lib_path_url;
    public $string_helper;
    public $included_via;

    public function __construct( $version, $lib_path, $lib_path_url, $included_via ){
        $this->version = $version;
        $this->lib_path = $lib_path;
        $this->lib_path_url = $lib_path_url;
        $this->included_via = $included_via;

        /**
         * Loads the `Standard_Libraries_Integration_Settings_String_Helper` class
         */
        require_once $lib_path . 'controllers/Standard_Libraries_Integration_Settings_String_Helper.php';

        /**
         * Awesome object that holds the helper methods for strings
         */
        $this->string_helper = new Standard_Libraries_Integration_Settings_String_Helper();
    }

    /**
     * Adapted from
     * https://stackoverflow.com/questions/4282413/sort-array-of-objects-by-object-fields#4282423
     */
    public function sort_registrations( $a, $b ) {
        return strcmp($a->version, $b->version);
    }

    private function is_registered() {
        /**
         * Load dispatcher registrations from the database
         */
        $dispatchers_json_array = get_option('integration_settings_dispatchers','[]');
        $dispatchers_php_array = json_decode($dispatchers_json_array);

        if(count($dispatchers_php_array)>0) {
            foreach ($dispatchers_php_array as $dispatcher) {
                if( $dispatcher->lib_path === $this->lib_path ) {
                    return true;
                }
            }
        }

        return false;
    }

    private function is_config_registered($key) {
        $integrations_list_json_object = get_option('integration_settings_list','{}');
        $integrations_list_php_object = json_decode($integrations_list_json_object);

        if( isset($integrations_list_php_object) && isset($integrations_list_php_object->{$key}) && count($integrations_list_php_object->{$key})>0 ) {
            foreach ($integrations_list_php_object->{$key} as $item) {
                if( $item->lib_path == $this->lib_path ) {
                    return true;
                }
            }
        }

        return false;
    }


    public function register() {
        /**
         * Load dispatcher registrations from the database
         */
        $dispatchers_json_array = get_option('integration_settings_dispatchers','[]');
        $dispatchers_php_array = json_decode($dispatchers_json_array);

        if( ! $this->is_registered() ) {
            /**
             * Register this dispatcher
             */
            $dispatcher = new stdClass();
            $dispatcher->version = $this->version;
            $dispatcher->lib_path = $this->lib_path;
            $dispatcher->lib_path_url = $this->lib_path_url;
            $dispatcher->included_via = $this->included_via;
            $dispatchers_php_array[] = $dispatcher;

            /**
             * Appoint a single dispatcher thru sorting them by version
             */
            usort($dispatchers_php_array, array($this,"sort_registrations"));

            /**
             * Update the dispatcher registrations
             */
            update_option('integration_settings_dispatchers', json_encode($dispatchers_php_array) );
        }

        /**
         * Load integrations list from the database
         */
        $integrations_list_json_object = get_option('integration_settings_list','{}');

        $integrations_list_php_object = new stdClass();
        if( $integrations_list_json_object === '{}' ) {
            $integrations_list_php_object = new stdClass();
        } else {
            $integrations_list_php_object = json_decode($integrations_list_json_object);
        }

        /**
         * Has config.php
         * `$key` refers to each item in the config file
         */
        if( file_exists( $this->lib_path . "config.php" ) ) {
            require_once  $this->lib_path . "config.php";

            foreach ($config as $key => $value) {
                $is_item_listed = isset($integrations_list_php_object) && property_exists($integrations_list_php_object,$key);
                $is_item_registered = $this->is_config_registered($key);

                /** Append if $key was not listed at all **/
                if( !$is_item_listed ) {
                    $integrations_list_php_object->{$key} = array();
                    $integrations_list_php_object->{$key}[0] = new stdClass();
                    $integrations_list_php_object->{$key}[0]->value = $value;
                    $integrations_list_php_object->{$key}[0]->lib_path = $this->lib_path;
                    $integrations_list_php_object->{$key}[0]->version = $this->version;
                }
                /** Modify if the $key was listed BUT only if this copy of library was not the registrant **/
                elseif( $is_item_listed && !$is_item_registered ) {
                    $i = count($integrations_list_php_object->{$key});
                    $integrations_list_php_object->{$key}[$i] = new stdClass();
                    $integrations_list_php_object->{$key}[$i]->value = $value;
                    $integrations_list_php_object->{$key}[$i]->lib_path = $this->lib_path;
                    $integrations_list_php_object->{$key}[$i]->version = $this->version;
                }
                /** Update if the $key was listed AND this copy of library was the registrant **/
                elseif( $is_item_listed && $is_item_registered ) {
                    foreach( $integrations_list_php_object->{$key} as $i => $prospect ) {
                        if( $prospect->lib_path == $this->lib_path ) {
                            $integrations_list_php_object->{$key}[$i]->value = $value;
                        }
                    }
                }
            }

            update_option('integration_settings_list', json_encode($integrations_list_php_object) );
        }

        /**
         * No config.php but has config.php.sample
         */
        elseif( file_exists( $this->lib_path . "config.php.sample" ) && ! file_exists( $this->lib_path . "config.php" ) ) {
            //require_once  $this->lib_path . "config.php";
        }

    }

    public function spearhead() {
        $dispatchers_json_array = get_option('integration_settings_dispatchers','[]');

        $dispatchers_php_array = json_decode($dispatchers_json_array);

        $dispatcher = $dispatchers_php_array[0];

        /**
         * Ensure only one library is included for the whole wordpress
         */
        if( $this->lib_path == $dispatcher->lib_path ) {

            /**
             * Loads the `Standard_Libraries_Integration_Settings` class
             */
            require_once $dispatcher->lib_path . 'controllers/Standard_Libraries_Integration_Settings.php';

            /**
             * Powerful object that holds the callable methods
             */
            $stdlib_integration_settings = new Standard_Libraries_Integration_Settings(
                $dispatcher->version, $dispatcher->lib_path, $dispatcher->lib_path_url, $this->included_via );

            /**
             * Conduct activation procedures when:
             * included in a theme  and the theme  has been activated; or when
             * included in a plugin and the plugin has been activated
             */
            $themes_search_index = strpos(__FILE__,'wp-content/themes');
            $plugins_search_index = strpos(__FILE__,'wp-content/plugins');

            if( $themes_search_index !== false ){
                //add_action( 'after_switch_theme', array( $stdlib_integration_settings, 'initialize_theme' ) );
            } elseif( $plugins_search_index !== false ) {
                $plugin_name = $stdlib_integration_settings->string_helper->get_plugin_file();
                add_action('activate_'.$plugin_name, array( $stdlib_integration_settings, 'activate_plugin' ) );
                add_action('deactivate_'.$plugin_name, array( $stdlib_integration_settings, 'deactivate_plugin' ) );
            }

            add_action( 'admin_menu', array( $stdlib_integration_settings, 'embed_submenu_page' ) );
            add_action( 'admin_head', array( $stdlib_integration_settings, 'register_submenu_screen' ) );
            add_action( 'admin_enqueue_scripts', array( $stdlib_integration_settings, 'enqueue_scripts' ) );
            add_action( 'wp_ajax_'.'load_integration_options', array( $stdlib_integration_settings, 'load_integration_options' ) );
            add_action( 'wp_ajax_'.'update_integration_options', array( $stdlib_integration_settings, 'update_integration_options' ) );
        }
    }


}