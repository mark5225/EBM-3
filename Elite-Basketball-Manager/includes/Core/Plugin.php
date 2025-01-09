<?php
namespace EBM\Core;

class Plugin {
    private static $instance = null;

    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        $this->load_dependencies();
    }

    private function load_dependencies() {
        if (is_admin()) {
            // Add admin menu
            add_action('admin_menu', [$this, 'register_admin_menus']);
        }
        return true;
    }

    public function register_admin_menus() {
        // Add main menu
        add_menu_page(
            'Basketball Manager',     // Page title
            'Basketball Manager',     // Menu title
            'manage_options',        // Capability required
            'basketball-manager',    // Menu slug
            [$this, 'render_dashboard'], // Callback function
            'dashicons-groups',      // Icon
            30                       // Position
        );

        // Add submenus
        add_submenu_page(
            'basketball-manager',    // Parent slug
            'Dashboard',            // Page title
            'Dashboard',            // Menu title
            'manage_options',       // Capability required
            'basketball-manager',   // Menu slug (same as parent for main submenu)
            [$this, 'render_dashboard'] // Callback function
        );

        add_submenu_page(
            'basketball-manager',
            'Teams',
            'Teams',
            'manage_options',
            'basketball-manager-teams',
            [$this, 'render_teams']
        );

        add_submenu_page(
            'basketball-manager',
            'Players',
            'Players',
            'manage_options',
            'basketball-manager-players',
            [$this, 'render_players']
        );

        add_submenu_page(
            'basketball-manager',
            'Games/Events',
            'Games/Events',
            'manage_options',
            'basketball-manager-games',
            [$this, 'render_games']
        );

        add_submenu_page(
            'basketball-manager',
            'Stats',
            'Stats',
            'manage_options',
            'basketball-manager-stats',
            [$this, 'render_stats']
        );

        add_submenu_page(
            'basketball-manager',
            'College Exposure',
            'College Exposure',
            'manage_options',
            'basketball-manager-college',
            [$this, 'render_college']
        );
    }

    public function render_dashboard() {
        echo '<div class="wrap">';
        echo '<h1>Basketball Manager Dashboard</h1>';
        echo '<div class="dashboard-widgets">';
        echo '<p>Welcome to Basketball Manager! More features coming soon.</p>';
        echo '</div>';
        echo '</div>';
    }

    public function render_teams() {
        echo '<div class="wrap">';
        echo '<h1>Teams Management</h1>';
        $team_list = new \EBM\Admin\TeamList();
        $team_list->prepare_items();
        $team_list->display();
        echo '</div>';
    }

    public function render_players() {
        echo '<div class="wrap">';
        echo '<h1>Players Management</h1>';
        echo '<p>Player management interface coming soon.</p>';
        echo '</div>';
    }

    public function render_games() {
        echo '<div class="wrap">';
        echo '<h1>Games and Events</h1>';
        echo '<p>Games and events management coming soon.</p>';
        echo '</div>';
    }

    public function render_stats() {
        echo '<div class="wrap">';
        echo '<h1>Statistics</h1>';
        echo '<p>Statistical analysis interface coming soon.</p>';
        echo '</div>';
    }

    public function render_college() {
        echo '<div class="wrap">';
        echo '<h1>College Exposure</h1>';
        echo '<p>College exposure tools coming soon.</p>';
        echo '</div>';
    }
}