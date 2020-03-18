<?php

class Standard_Libraries_Integration_Settings_String_Helper {

    public function get_full_path_plugin_file( $file=__FILE__ ) {
        $rel_file_path = plugin_basename( $file );
        $rel_file_path_parts = explode("/", $rel_file_path);
        $plugin_dir = $rel_file_path_parts[0];

        $plugin_dir_path_length = strpos($file,$plugin_dir) + strlen($plugin_dir);
        $plugin_full_dir = substr( $file, 0, $plugin_dir_path_length );

        $prospect_plugin_file = $plugin_full_dir . '/' . $plugin_dir . '.php';
        if( file_exists( $prospect_plugin_file ) ){
            $file_contents = file_get_contents($prospect_plugin_file);
            if( strpos($file_contents,'Plugin Name')!==false && strpos($file_contents,'Author')!==false && strpos($file_contents,'Version')!==false ){
                return $prospect_plugin_file;
            }
        }

    return "";
    }

    public function get_plugin_file( $file=__FILE__ ) {
        $rel_file_path = plugin_basename( $file );
        $rel_file_path_parts = explode("/", $rel_file_path);
        $plugin_dir = $rel_file_path_parts[0];

        $plugin_dir_path_length = strpos($file,$plugin_dir) + strlen($plugin_dir);
        $plugin_full_dir = substr( $file, 0, $plugin_dir_path_length );

        $prospect_plugin_file = $plugin_full_dir . '/' . $plugin_dir . '.php';
        if( file_exists( $prospect_plugin_file ) ){
            $file_contents = file_get_contents($prospect_plugin_file);
            if( strpos($file_contents,'Plugin Name')!==false && strpos($file_contents,'Author')!==false && strpos($file_contents,'Version')!==false ){
                return $plugin_dir . '/' . $plugin_dir . '.php';
            }
        }

    return "";
    }



}