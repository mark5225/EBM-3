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
    $action = isset($_GET['action']) ? $_GET['action'] : 'list';
    
    echo '<div class="wrap">';
    
    switch($action) {
        case 'add':
            $this->render_team_form('add');
            break;
            
        case 'edit':
            $team_id = isset($_GET['team']) ? intval($_GET['team']) : 0;
            $this->render_team_form('edit', $team_id);
            break;
            
        case 'roster':
            $team_id = isset($_GET['team']) ? intval($_GET['team']) : 0;
            $this->render_team_roster($team_id);
            break;
            
        default:
            echo '<h1 class="wp-heading-inline">Teams Management</h1>';
            echo '<a href="?page=basketball-manager-teams&action=add" class="page-title-action">Add New Team</a>';
            
            echo '<div class="tablenav top">';
            echo '<div class="alignleft actions">';
            echo '<select name="team_type">';
            echo '<option value="">All Team Types</option>';
            echo '<option value="club">Club</option>';
            echo '<option value="school">School</option>';
            echo '<option value="league">League</option>';
            echo '</select>';
            echo '<input type="submit" class="button" value="Filter">';
            echo '</div>';
            echo '</div>';

            $team_list = new \EBM\Admin\TeamList();
            $team_list->prepare_items();
            $team_list->display();
    }
    
    echo '</div>';
}

private function render_team_form($mode = 'add', $team_id = 0) {
    $team = null;
    if ($mode === 'edit' && $team_id > 0) {
        // TODO: Fetch team data
        $team = [/* fetch team data */];
    }

    $title = ($mode === 'add') ? 'Add New Team' : 'Edit Team';
    
    echo '<h1>' . esc_html($title) . '</h1>';
    echo '<form method="post" action="" class="ebm-form">';
    wp_nonce_field('ebm_team_' . $mode);
    
    echo '<table class="form-table" role="presentation">';
    
    // Team Name
    echo '<tr>';
    echo '<th scope="row"><label for="team_name">Team Name</label></th>';
    echo '<td><input name="team_name" type="text" id="team_name" value="' . esc_attr($team['team_name'] ?? '') . '" class="regular-text"></td>';
    echo '</tr>';
    
    // Team Type
    echo '<tr>';
    echo '<th scope="row"><label for="team_type">Team Type</label></th>';
    echo '<td>';
    echo '<select name="team_type" id="team_type">';
    $types = ['club' => 'Club', 'school' => 'School', 'league' => 'League'];
    foreach ($types as $value => $label) {
        $selected = ($team['team_type'] ?? '') === $value ? 'selected' : '';
        echo "<option value='" . esc_attr($value) . "' $selected>" . esc_html($label) . "</option>";
    }
    echo '</select>';
    echo '</td>';
    echo '</tr>';
    
    // Location
    echo '<tr>';
    echo '<th scope="row"><label for="location">Location</label></th>';
    echo '<td><input name="location" type="text" id="location" value="' . esc_attr($team['location'] ?? '') . '" class="regular-text"></td>';
    echo '</tr>';
    
    // Head Coach
    echo '<tr>';
    echo '<th scope="row"><label for="head_coach">Head Coach</label></th>';
    echo '<td><input name="head_coach" type="text" id="head_coach" value="' . esc_attr($team['head_coach'] ?? '') . '" class="regular-text"></td>';
    echo '</tr>';
    
    // Season
    echo '<tr>';
    echo '<th scope="row"><label for="season">Season</label></th>';
    echo '<td><input name="season" type="text" id="season" value="' . esc_attr($team['season'] ?? '') . '" class="regular-text"></td>';
    echo '</tr>';
    
    echo '</table>';
    
    echo '<p class="submit">';
    echo '<input type="submit" name="submit" id="submit" class="button button-primary" value="' . esc_attr($mode === 'add' ? 'Add Team' : 'Update Team') . '">';
    echo ' <a href="?page=basketball-manager-teams" class="button">Cancel</a>';
    echo '</p>';
    
    echo '</form>';
}

private function render_team_roster($team_id) {
    echo '<h1>Team Roster</h1>';
    echo '<p>Roster view coming soon...</p>';
    echo '<p><a href="?page=basketball-manager-teams" class="button">Back to Teams</a></p>';
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
