<?php

class Standard_Libraries_Integration_Settings {
	public $lib_path;

	public function __construct( $lib_path ){
		$this->lib_path = $lib_path;
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

		/**
		 * Consumes the prepared variables
		 * by displaying (echoing) the `integrations.php` HTML template
		 */
		require_once $this->lib_path . 'views/integrations.php';
    }

}