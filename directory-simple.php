<?php
/*
Plugin Name: Directory Simple
Plugin URI: https://github.com/Meowski-hub/directory-simple-wp
Description: A simple directory builder plugin that allows you to create and manage directories easily.
Version: 1.0.0
Author: Your Name
Author URI: https://yourwebsite.com
License: GPL v2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: directory-simple
*/

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

// Define plugin constants
define('DIRECTORY_SIMPLE_VERSION', '1.0.0');
define('DIRECTORY_SIMPLE_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('DIRECTORY_SIMPLE_PLUGIN_URL', plugin_dir_url(__FILE__));

// Include required files
require_once DIRECTORY_SIMPLE_PLUGIN_DIR . 'includes/class-directory-simple.php';
require_once DIRECTORY_SIMPLE_PLUGIN_DIR . 'includes/class-directory-simple-activator.php';
require_once DIRECTORY_SIMPLE_PLUGIN_DIR . 'includes/class-directory-simple-deactivator.php';

// Activation Hook
register_activation_hook(__FILE__, array('Directory_Simple_Activator', 'activate'));

// Deactivation Hook
register_deactivation_hook(__FILE__, array('Directory_Simple_Deactivator', 'deactivate'));

// Initialize the plugin
function run_directory_simple() {
    $plugin = new Directory_Simple();
    $plugin->run();
}
run_directory_simple();