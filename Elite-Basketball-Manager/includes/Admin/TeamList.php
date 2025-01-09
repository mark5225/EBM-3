<?php
namespace EBM\Admin;


if (!class_exists('WP_List_Table')) {
    require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
}
if (!class_exists('WP_List_Table')) {
    require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
}

class TeamList extends \WP_List_Table {
    public function __construct() {
        parent::__construct(array(
            'singular' => 'team',
            'plural' => 'teams',
            'ajax' => false
        ));
    }

    public function get_columns() {
        return array(
            'cb' => '<input type="checkbox" />',
            'title' => __('Team Name', 'elite-basketball-manager'),
            'category' => __('Category', 'elite-basketball-manager'),
            'coach' => __('Head Coach', 'elite-basketball-manager'),
            'players' => __('Players', 'elite-basketball-manager'),
            'record' => __('Record', 'elite-basketball-manager'),
            'season' => __('Season', 'elite-basketball-manager')
        );
    }

    public function prepare_items() {
        $per_page = 20;
        $current_page = $this->get_pagenum();
        $total_items = wp_count_posts('ebm_team')->publish;

        $this->set_pagination_args(array(
            'total_items' => $total_items,
            'per_page' => $per_page,
            'total_pages' => ceil($total_items / $per_page)
        ));

        $this->items = get_posts(array(
            'post_type' => 'ebm_team',
            'posts_per_page' => $per_page,
            'paged' => $current_page,
            'orderby' => 'title',
            'order' => 'ASC'
        ));
    }

    public function column_default($item, $column_name) {
        switch ($column_name) {
            case 'category':
                $terms = get_the_terms($item->ID, 'ebm_team_category');
                return $terms ? implode(', ', wp_list_pluck($terms, 'name')) : '—';
            
            case 'coach':
                return get_post_meta($item->ID, '_ebm_coach', true) ?: '—';
            
            case 'players':
                $player_count = get_posts(array(
                    'post_type' => 'ebm_player',
                    'meta_key' => '_ebm_team_id',
                    'meta_value' => $item->ID,
                    'posts_per_page' => -1,
                    'fields' => 'ids'
                ));
                return count($player_count);
            
            case 'record':
                return get_post_meta($item->ID, '_ebm_record', true) ?: '—';
            
            case 'season':
                return get_post_meta($item->ID, '_ebm_season', true) ?: '—';
            
            default:
                return print_r($item, true);
        }
    }

    public function column_title($item) {
        $actions = array(
            'edit' => sprintf(
                '<a href="%s">%s</a>',
                get_edit_post_link($item->ID),
                __('Edit', 'elite-basketball-manager')
            ),
            'view' => sprintf(
                '<a href="%s">%s</a>',
                get_permalink($item->ID),
                __('View', 'elite-basketball-manager')
            ),
            'delete' => sprintf(
                '<a href="%s">%s</a>',
                get_delete_post_link($item->ID),
                __('Delete', 'elite-basketball-manager')
            )
        );

        return sprintf(
            '%1$s %2$s',
            '<strong>' . $item->post_title . '</strong>',
            $this->row_actions($actions)
        );
    }

    public function column_cb($item) {
        return sprintf(
            '<input type="checkbox" name="teams[]" value="%s" />',
            $item->ID
        );
    }

    public function get_sortable_columns() {
        return array(
            'title' => array('title', true),
            'season' => array('season', false)
        );
    }

    public function get_bulk_actions() {
        return array(
            'delete' => __('Delete', 'elite-basketball-manager')
        );
    }
}