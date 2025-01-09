<?php
namespace EBM;

class PostTypes {
    private static $instance = null;

    private function __construct() {
        add_action('init', array($this, 'register_post_types'), 0);
    }

    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function register_post_types() {
        $this->register_team_post_type();
        $this->register_player_post_type();
    }

    private function register_team_post_type() {
        $args = array(
            'labels' => array(
                'name' => __('Teams', 'elite-basketball-manager'),
                'singular_name' => __('Team', 'elite-basketball-manager'),
                // Add other labels...
            ),
            'public' => true,
            'has_archive' => true,
            'supports' => array('title', 'editor', 'thumbnail'),
            'menu_icon' => 'dashicons-groups',
            'rewrite' => array('slug' => 'teams')
        );

        register_post_type('ebm_team', $args);
    }

    private function register_player_post_type() {
        $args = array(
            'labels' => array(
                'name' => __('Players', 'elite-basketball-manager'),
                'singular_name' => __('Player', 'elite-basketball-manager'),
                // Add other labels...
            ),
            'public' => true,
            'has_archive' => true,
            'supports' => array('title', 'editor', 'thumbnail'),
            'menu_icon' => 'dashicons-businessman',
            'rewrite' => array('slug' => 'players')
        );

        register_post_type('ebm_player', $args);
    }
}