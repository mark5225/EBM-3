<?php
/**
 * Plugin Name: Elite Basketball Manager
 * Description: Complete management solution for basketball teams, rosters, alumni, and recruiting.
 * Version: 1.0.0
 * Author: ABQ Finest Web Design
 * Text Domain: elite-basketball-manager
 */

if (!defined('ABSPATH')) exit;

// Define plugin constants
define('EBM_VERSION', '1.0.0');
define('EBM_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('EBM_PLUGIN_URL', plugin_dir_url(__FILE__));
define('EBM_INCLUDES_DIR', EBM_PLUGIN_DIR . 'includes/');
define('EBM_TEMPLATES_DIR', EBM_PLUGIN_DIR . 'templates/');
define('EBM_ASSETS_URL', EBM_PLUGIN_URL . 'assets/');

// Autoloader
require_once EBM_INCLUDES_DIR . 'class-autoloader.php';

spl_autoload_register(function ($class_name) {
    // Only handle classes in our namespace
    if (strpos($class_name, 'EBM\\') !== 0) {
        return;
    }

    // Convert namespace to file path
    $class_path = str_replace('EBM\\', '', $class_name);
    
    // Convert namespace separators to directory separators
    $class_path = str_replace('\\', DIRECTORY_SEPARATOR, $class_path);
    
    // Build the full file path
    $file = dirname(__FILE__) . '/includes/' . $class_path . '.php';
    
    // For debugging
    error_log("Looking for class file: " . $file);
    
    if (file_exists($file)) {
        require_once $file;
    } else {
        error_log("File not found: " . $file);
    }
});

// Initialize the plugin
require_once EBM_INCLUDES_DIR . 'class-init.php';
EBM\Init::get_instance();


