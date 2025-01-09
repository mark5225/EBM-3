<?php
namespace EBM\Core;

class Database {
    private static $tables = array(
        'player_stats' => array(
            'height' => 'varchar(10)',
            'weight' => 'int(3)',
            'wingspan' => 'varchar(10)',
            'vertical' => 'int(3)',
            'position' => 'varchar(50)',
            'class_year' => 'varchar(20)',
            'jersey_number' => 'int(3)',
            'created_at' => 'datetime',
            'updated_at' => 'datetime'
        ),
        'game_stats' => array(
            'player_id' => 'bigint(20)',
            'game_date' => 'date',
            'opponent' => 'varchar(100)',
            'minutes_played' => 'int(3)',
            'points' => 'int(3)',
            'assists' => 'int(3)',
            'rebounds' => 'int(3)',
            'steals' => 'int(3)',
            'blocks' => 'int(3)',
            'fg_made' => 'int(3)',
            'fg_attempted' => 'int(3)',
            'three_made' => 'int(3)',
            'three_attempted' => 'int(3)',
            'ft_made' => 'int(3)',
            'ft_attempted' => 'int(3)',
            'created_at' => 'datetime'
        ),
        'recruitment' => array(
            'player_id' => 'bigint(20)',
            'college_name' => 'varchar(255)',
            'division' => 'varchar(50)',
            'status' => 'varchar(50)',
            'commitment_date' => 'date',
            'notes' => 'text',
            'created_at' => 'datetime',
            'updated_at' => 'datetime'
        )
    );

    public static function activate() {
        global $wpdb;
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

        foreach (self::$tables as $table_name => $columns) {
            $table = $wpdb->prefix . 'ebm_' . $table_name;
            $charset_collate = $wpdb->get_charset_collate();

            $sql = "CREATE TABLE IF NOT EXISTS $table (
                id bigint(20) NOT NULL AUTO_INCREMENT,";

            foreach ($columns as $column => $type) {
                $sql .= "$column $type,";
            }

            $sql .= "PRIMARY KEY  (id)
            ) $charset_collate;";

            dbDelta($sql);
        }

        add_option('ebm_db_version', EBM_VERSION);
    }

    public static function deactivate() {
        // Optional: Add cleanup code here
    }

    public static function uninstall() {
        global $wpdb;
        
        foreach (self::$tables as $table_name => $columns) {
            $table = $wpdb->prefix . 'ebm_' . $table_name;
            $wpdb->query("DROP TABLE IF EXISTS $table");
        }

        delete_option('ebm_db_version');
    }
}