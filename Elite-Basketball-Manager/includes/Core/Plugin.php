<?php
namespace EBM\Core;

class Plugin {
    private static $instance = null;
    private $admin;
    private $models;
    private $frontend;

    private function __construct() {
        // Register activation/deactivation hooks
        register_activation_hook(EBM_PLUGIN_DIR . 'elite-basketball-manager.php', array($this, 'activate'));
        register_deactivation_hook(EBM_PLUGIN_DIR . 'elite-basketball-manager.php', array($this, 'deactivate'));

        // Initialize components
        $this->init_hooks();
        $this->load_dependencies();
    }

    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function init_hooks() {
        add_action('init', array($this, 'register_post_types'));
        add_action('init', array($this, 'register_taxonomies'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_frontend_assets'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_assets'));
        add_action('plugins_loaded', array($this, 'load_textdomain'));
    }

private function load_dependencies() {
    // Load admin functionality if in admin area
    if (is_admin()) {
        // Admin classes will be autoloaded through namespace
    }

    // Add any other dependencies here
    return true;
}

        // Load frontend components
        require_once EBM_INCLUDES_DIR . 'Frontend/Widgets.php';
        require_once EBM_INCLUDES_DIR . 'Frontend/Templates.php';

        // Load models
        require_once EBM_INCLUDES_DIR . 'Models/Player.php';
        require_once EBM_INCLUDES_DIR . 'Models/Team.php';
        require_once EBM_INCLUDES_DIR . 'Models/Stats.php';
    }

    public function register_post_types() {
        // Teams Post Type
        register_post_type('ebm_team', array(
            'labels' => array(
                'name' => __('Teams', 'elite-basketball-manager'),
                'singular_name' => __('Team', 'elite-basketball-manager'),
                'add_new' => __('Add New Team', 'elite-basketball-manager'),
                'add_new_item' => __('Add New Team', 'elite-basketball-manager'),
                'edit_item' => __('Edit Team', 'elite-basketball-manager'),
                'new_item' => __('New Team', 'elite-basketball-manager'),
                'view_item' => __('View Team', 'elite-basketball-manager'),
                'search_items' => __('Search Teams', 'elite-basketball-manager'),
                'not_found' => __('No teams found', 'elite-basketball-manager'),
                'not_found_in_trash' => __('No teams found in trash', 'elite-basketball-manager'),
            ),
            'public' => true,
            'has_archive' => true,
            'supports' => array('title', 'editor', 'thumbnail', 'excerpt'),
            'menu_icon' => 'dashicons-groups',
            'show_in_menu' => 'ebm-dashboard',
            'rewrite' => array('slug' => 'teams')
        ));

        // Players Post Type
        register_post_type('ebm_player', array(
            'labels' => array(
                'name' => __('Players', 'elite-basketball-manager'),
                'singular_name' => __('Player', 'elite-basketball-manager'),
                'add_new' => __('Add New Player', 'elite-basketball-manager'),
                'add_new_item' => __('Add New Player', 'elite-basketball-manager'),
                'edit_item' => __('Edit Player', 'elite-basketball-manager'),
                'new_item' => __('New Player', 'elite-basketball-manager'),
                'view_item' => __('View Player', 'elite-basketball-manager'),
                'search_items' => __('Search Players', 'elite-basketball-manager'),
                'not_found' => __('No players found', 'elite-basketball-manager'),
                'not_found_in_trash' => __('No players found in trash', 'elite-basketball-manager'),
            ),
            'public' => true,
            'has_archive' => true,
            'supports' => array('title', 'editor', 'thumbnail', 'excerpt'),
            'menu_icon' => 'dashicons-businessman',
            'show_in_menu' => 'ebm-dashboard',
            'rewrite' => array('slug' => 'players')
        ));

        // Games Post Type
        register_post_type('ebm_game', array(
            'labels' => array(
                'name' => __('Games', 'elite-basketball-manager'),
                'singular_name' => __('Game', 'elite-basketball-manager'),
                'add_new' => __('Add New Game', 'elite-basketball-manager'),
                'add_new_item' => __('Add New Game', 'elite-basketball-manager'),
                'edit_item' => __('Edit Game', 'elite-basketball-manager'),
                'new_item' => __('New Game', 'elite-basketball-manager'),
                'view_item' => __('View Game', 'elite-basketball-manager'),
                'search_items' => __('Search Games', 'elite-basketball-manager'),
                'not_found' => __('No games found', 'elite-basketball-manager'),
                'not_found_in_trash' => __('No games found in trash', 'elite-basketball-manager'),
            ),
            'public' => true,
            'has_archive' => true,
            'supports' => array('title', 'editor', 'thumbnail'),
            'menu_icon' => 'dashicons-calendar',
            'show_in_menu' => 'ebm-dashboard',
            'rewrite' => array('slug' => 'games')
        ));
    }

    public function register_taxonomies() {
        // Position Taxonomy
        register_taxonomy('ebm_position', array('ebm_player'), array(
            'labels' => array(
                'name' => __('Positions', 'elite-basketball-manager'),
                'singular_name' => __('Position', 'elite-basketball-manager'),
                'search_items' => __('Search Positions', 'elite-basketball-manager'),
                'all_items' => __('All Positions', 'elite-basketball-manager'),
                'edit_item' => __('Edit Position', 'elite-basketball-manager'),
                'update_item' => __('Update Position', 'elite-basketball-manager'),
                'add_new_item' => __('Add New Position', 'elite-basketball-manager'),
                'new_item_name' => __('New Position Name', 'elite-basketball-manager'),
                'menu_name' => __('Positions', 'elite-basketball-manager'),
            ),
            'hierarchical' => true,
            'show_ui' => true,
            'show_admin_column' => true,
            'query_var' => true,
            'rewrite' => array('slug' => 'position'),
        ));

        // Team Category Taxonomy
        register_taxonomy('ebm_team_category', array('ebm_team'), array(
            'labels' => array(
                'name' => __('Team Categories', 'elite-basketball-manager'),
                'singular_name' => __('Team Category', 'elite-basketball-manager'),
                'search_items' => __('Search Team Categories', 'elite-basketball-manager'),
                'all_items' => __('All Team Categories', 'elite-basketball-manager'),
                'edit_item' => __('Edit Team Category', 'elite-basketball-manager'),
                'update_item' => __('Update Team Category', 'elite-basketball-manager'),
                'add_new_item' => __('Add New Team Category', 'elite-basketball-manager'),
                'new_item_name' => __('New Team Category Name', 'elite-basketball-manager'),
                'menu_name' => __('Team Categories', 'elite-basketball-manager'),
            ),
            'hierarchical' => true,
            'show_ui' => true,
            'show_admin_column' => true,
            'query_var' => true,
            'rewrite' => array('slug' => 'team-category'),
        ));
    }

    public function enqueue_frontend_assets() {
        wp_enqueue_style('ebm-frontend', EBM_PLUGIN_URL . 'assets/css/frontend.css', array(), EBM_VERSION);
        wp_enqueue_script('ebm-frontend', EBM_PLUGIN_URL . 'assets/js/frontend.js', array('jquery'), EBM_VERSION, true);
        
        wp_localize_script('ebm-frontend', 'ebmAjax', array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('ebm-frontend-nonce')
        ));
    }

    public function enqueue_admin_assets($hook) {
        if (strpos($hook, 'ebm') !== false) {
            wp_enqueue_style('ebm-admin', EBM_PLUGIN_URL . 'assets/css/admin.css', array(), EBM_VERSION);
            wp_enqueue_script('ebm-admin', EBM_PLUGIN_URL . 'assets/js/admin.js', array('jquery'), EBM_VERSION, true);
            
            wp_localize_script('ebm-admin', 'ebmAdmin', array(
                'ajaxurl' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('ebm-admin-nonce')
            ));
        }
    }

    public function load_textdomain() {
        load_plugin_textdomain('elite-basketball-manager', false, dirname(plugin_basename(EBM_PLUGIN_DIR)) . '/languages/');
    }

    public function activate() {
        // Create database tables
        require_once EBM_INCLUDES_DIR . 'Core/Database.php';
        $database = new Database();
        $database->create_tables();

        // Add default options
        $this->add_default_options();

        // Flush rewrite rules
        flush_rewrite_rules();
    }

    public function deactivate() {
        flush_rewrite_rules();
    }

    private function add_default_options() {
        add_option('ebm_version', EBM_VERSION);
        add_option('ebm_stats_display', 'detailed');
        add_option('ebm_enable_recruiting', true);
        add_option('ebm_player_positions', array('PG', 'SG', 'SF', 'PF', 'C'));
    }
}