<?php

/**
 * Plugin Name: Amadeus Plugin
 * Description: This plugin is use to show cities autocompletes on simple text-fields of any form of the website.
 * Author: Usama Khan
 * Author URI: https://github.com/usamakhan143
 * Version: 1.0.0
 * Requires PHP: 7.4
 * Text Domain: amadeus-plugin-translate
 */


if( !defined('ABSPATH') ) {
    exit;
}


if (!class_exists('AmadeusPlugin')) {


    class AmadeusPlugin {

        function __construct()
        {
            define('AMADEUS_PLUGIN_URL', plugin_dir_url(__FILE__));
            define('AMADEUS_PLUGIN_PATH', plugin_dir_path(__FILE__));
        }

        function initialize()
        {
            include_once(AMADEUS_PLUGIN_PATH . 'includes/amadeus-plugin.php');
            include_once(AMADEUS_PLUGIN_PATH . 'includes/utilities.php');
        }

    }


}


$AmadeusPlugin = new AmadeusPlugin();
$AmadeusPlugin->initialize();