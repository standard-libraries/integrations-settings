<?php

class Standard_Libraries_Integration_Settings {

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
     * Adds the `Integrations` submenu under the `Settings` menu
     */
    public function embed_submenu_page() {
        add_submenu_page( 'options-general.php','Integrations Settings', 'Integrations', 'manage_options', 'integrations', array($this,'display_settings_for_integrations'), 'dashicons-admin-tools', 3 );
    }

    /**
     * User interface of the `Settings` > `Integrations` submenu
     */
    public function display_settings_for_integrations() {
        /**
         * This site
         *
         * The succeeding code sections below this are all about this site
         */

        $http_host = $_SERVER['HTTP_HOST'];
        $ip = gethostbyname($http_host);
        if( $http_host==$ip || in_array($ip, array('localhost','127.0.0.1') ) || in_array($http_host, array('localhost','127.0.0.1') ) ) {
            $hostname = $http_host;
            $is_localhost = true;
        } else {
            $hostname = $http_host;
            $is_localhost = false;
        }

        $lib_path_url = $this->lib_path_url;

        /**
         * FACEBOOK
         *
         * The succeeding code sections below this are all about facebook
         */

        /**
         * Gets the stored data from the database
         */
        $stored = array(
            'facebook_app_id' => get_option('facebook_app_id'),
            'facebook_app_secret' => get_option('facebook_app_secret')
        );

        /**
         * Gets the post data from the HTML <form>
         */
        $post = array(
            'facebook_app_id' => ( isset($_POST['facebook_app_id']) ? $_POST['facebook_app_id'] : '' ),
            'facebook_app_secret' => ( isset($_POST['facebook_app_secret']) ? $_POST['facebook_app_secret'] : '' )
        );

        /**
         * Prepares the `$facebook_app_id` variable
         */
        $facebook_app_id = "";
        if( isset($_POST['facebook_app_id']) ) {
            $facebook_app_id = $post['facebook_app_id'];
            update_option('facebook_app_id',$facebook_app_id,'no');
            update_option('facebook_app_domains',$hostname,'no');
        }
        elseif( !empty($stored['facebook_app_id']) ) {
            $facebook_app_id = $stored['facebook_app_id'];
        }

        /**
         * Prepares the `$facebook_app_secret` variable
         */
        $facebook_app_secret = "";
        if( isset($_POST['facebook_app_secret']) ) {
            $facebook_app_secret = $post['facebook_app_secret'];
            update_option('facebook_app_secret',$facebook_app_secret,'no');
        }
        elseif( !empty($stored['facebook_app_secret']) ) {
            $facebook_app_secret = $stored['facebook_app_secret'];
        }


        $plugin = $this->string_helper->get_plugin_file();

        /**
         * Consumes the prepared variables
         * by displaying (echoing) the `integrations.php` HTML template
         */
        require_once $this->lib_path . 'views/integrations.php';
    }

    /**
     * Adds the screen options for the `Integrations` submenu
     */
    public function register_submenu_screen() {
        global $pagenow;

        if( $pagenow == 'options-general.php' && isset($_GET['page']) && $_GET['page']=='integrations' ) {
            $screen = get_current_screen();
            add_filter( 'screen_layout_columns', array($this,'embed_submenu_screen') );
            $screen->add_option('submenu_screen', '');
        }
    }

    /**
     * User interface of the `Settings` > `Integrations` screen options
     */
    public function embed_submenu_screen() {
        $integrations_list_json_object = get_option('integration_settings_list','{}');
        $integrations_list_php_object = json_decode($integrations_list_json_object);

        if( isset($integrations_list_php_object) ) {
            $key_pairs = get_object_vars($integrations_list_php_object);

            if( count($key_pairs)>0 ) {
                foreach ($key_pairs as $key => $placeholder ) {
                    $candidates = array();
                    foreach( $integrations_list_php_object->{$key} as $prospect ) {
                        $candidates[] = $prospect->value;
                    }

                    if( in_array('required',$candidates) ) {
                        ${$key} = 'required';
                    }
                    elseif( in_array('optional',$candidates) ) {
                        ${$key} = 'optional';
                    }
                }
            }
        }

        require_once $this->lib_path . "views/screen.php";
    }

    /**
     * Adds JavaScript scripts
     */
    public function enqueue_scripts() {
        global $pagenow;

        if( $pagenow == 'options-general.php' && isset($_GET['page']) && $_GET['page']=='integrations' ) {
            wp_register_style('stdlib_main_style',$this->lib_path_url . "/assets/css/style.css",array(), 'v'.uniqid() );

            wp_register_script('stdlib_main_script',$this->lib_path_url . "/assets/js/script.js",array('jquery'), 'v'.uniqid() );

            wp_enqueue_style('stdlib_main_style' );

            wp_enqueue_script('stdlib_main_script');
        }
    }

    public function activate_plugin() {
        if( file_exists( $this->lib_path . "config.php" ) ) {
            include  $this->lib_path . "config.php";

            foreach( $config as $key => $value ) {
                $boolean_value = 'no';
                if( $value == 'required' ) {
                    $boolean_value = 'yes';
                }

                if( get_option( $key, false ) === false ) {
                    add_option($key,$boolean_value,'no');
                }

                if( $boolean_value=='yes' ) {
                    update_option($key,$boolean_value,'no');
                }
            }

        } else {
            //wp_die( 'File: ' . $this->lib_path . "/config.php does not exist!" );
        }

    }

    public function deactivate_plugin() {
        /**
         * Conduct update to the list of dispatchers
         */
        $dispatchers_json_array = get_option('integration_settings_dispatchers','[]');
        $dispatchers_php_array = json_decode($dispatchers_json_array);
        if( count($dispatchers_php_array)>0 ) {
            for( $i=0; $i<count($dispatchers_php_array); $i++ ) {
                $dispatcher = $dispatchers_php_array[$i];

                if( $dispatcher->lib_path == $this->lib_path ) {
                    unset($dispatchers_php_array[$i]);
                }
            }

            update_option('integration_settings_dispatchers', json_encode($dispatchers_php_array) );
        }

        /**
         * Conduct update to the list of integrations
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
            include  $this->lib_path . "config.php";

            foreach ($config as $key => $value) {
                $is_item_listed = isset($integrations_list_php_object) && property_exists($integrations_list_php_object,$key);
                $is_item_has_elements = count($integrations_list_php_object->{$key}) > 0;

                if( $is_item_listed && $is_item_has_elements ) {

                    foreach ($integrations_list_php_object->{$key} as $i => $prospect) {
                        if( $prospect->lib_path == $this->lib_path ) {
                            unset($integrations_list_php_object->{$key}[$i]);
                        }
                    }
                }
            }

            update_option('integration_settings_list', json_encode($integrations_list_php_object) );
        }

    }

    public function load_integration_options() {
        $object = new stdClass();

        $object->facebook = ( get_option('facebook_integration','no')=='yes' ? true : false );
        $object->google = ( get_option('google_integration','no')=='yes' ? true : false );
        $object->twitter = ( get_option('twitter_integration','no')=='yes' ? true : false );
        $object->show_basic_helpers = ( get_user_meta( get_current_user_id(), 'show_basic_helpers', true )=='yes' ? true : false );
        $object->show_developer_helpers = ( get_user_meta( get_current_user_id(), 'show_developer_helpers', true )=='yes' ? true : false );
        echo json_encode($object,JSON_PRETTY_PRINT);
        die('');
    }

    public function update_integration_options() {

        if( isset($_POST['facebook_integration']) ) {
            update_option('facebook_integration',$_POST['facebook_integration'],'no');
        }

        if( isset($_POST['show_basic_helpers']) ) {
            update_user_meta( get_current_user_id(), 'show_basic_helpers', $_POST['show_basic_helpers'] );
        }

        if( isset($_POST['show_developer_helpers']) ) {
            update_user_meta( get_current_user_id(), 'show_developer_helpers', $_POST['show_developer_helpers'] );
        }

        die('');
    }


}