<?php
namespace EBM\Admin;

if (!class_exists('WP_List_Table')) {
    require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
}

class TeamList extends \WP_List_Table {
    
    public function __construct() {
        parent::__construct([
            'singular' => 'team',
            'plural'   => 'teams',
            'ajax'     => false
        ]);
    }

    public function get_columns() {
        return [
            'cb'            => '<input type="checkbox" />', // Checkbox for bulk actions
            'team_name'     => 'Team Name',
            'team_type'     => 'Type',
            'location'      => 'Location',
            'head_coach'    => 'Head Coach',
            'roster_count'  => 'Players',
            'season'        => 'Season'
        ];
    }

    public function get_sortable_columns() {
        return [
            'team_name'    => ['team_name', true],
            'team_type'    => ['team_type', false],
            'location'     => ['location', false],
            'season'       => ['season', false]
        ];
    }

    protected function column_default($item, $column_name) {
        switch ($column_name) {
            case 'team_name':
            case 'team_type':
            case 'location':
            case 'head_coach':
            case 'roster_count':
            case 'season':
                return $item[$column_name];
            default:
                return print_r($item, true);
        }
    }

    protected function column_team_name($item) {
        $actions = [
            'edit'      => sprintf('<a href="?page=basketball-manager-teams&action=edit&team=%s">Edit</a>', $item['ID']),
            'roster'    => sprintf('<a href="?page=basketball-manager-teams&action=roster&team=%s">View Roster</a>', $item['ID']),
            'delete'    => sprintf('<a href="?page=basketball-manager-teams&action=delete&team=%s">Delete</a>', $item['ID'])
        ];

        return sprintf('%1$s %2$s',
            $item['team_name'],
            $this->row_actions($actions)
        );
    }

    protected function get_bulk_actions() {
        return [
            'delete' => 'Delete'
        ];
    }

    public function prepare_items() {
        // For now, let's use sample data. Later we'll fetch from database
        $this->_column_headers = [
            $this->get_columns(),
            [],
            $this->get_sortable_columns()
        ];

        // Sample data - we'll replace this with real database queries later
        $data = [
            [
                'ID' => 1,
                'team_name' => 'Warriors Basketball Club',
                'team_type' => 'Club',
                'location' => 'San Francisco',
                'head_coach' => 'John Smith',
                'roster_count' => '12',
                'season' => '2024-25'
            ],
            [
                'ID' => 2,
                'team_name' => 'City High School',
                'team_type' => 'School',
                'location' => 'Los Angeles',
                'head_coach' => 'Mike Johnson',
                'roster_count' => '15',
                'season' => '2024-25'
            ]
        ];

        $per_page = 10;
        $current_page = $this->get_pagenum();
        $total_items = count($data);

        $this->items = array_slice($data, (($current_page - 1) * $per_page), $per_page);

        $this->set_pagination_args([
            'total_items' => $total_items,
            'per_page'    => $per_page,
            'total_pages' => ceil($total_items / $per_page)
        ]);
    }
}
