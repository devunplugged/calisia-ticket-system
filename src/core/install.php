<?php
namespace calisia_ticket_system;

class install{
    private static $db_version = '1.11';
    
    public static function update_check(){
        //global $calisia_customer_notes_db_version;
        if ( get_site_option( 'calisia_ticket_system' ) != self::$db_version ) {
            self::db_install();
        }
        
    }
    //should run once after adding my-account endpoint
    public static function flush_permalinks(){
        if(!get_option('calisia_ticket_system_permalinks_flushed')){
            flush_rewrite_rules(false);
            update_option('calisia_ticket_system_permalinks_flushed', 1);
        }
    }

    public static function db_install() {
        global $wpdb;
       // global $calisia_customer_notes_db_version;
    
        $table_name = $wpdb->prefix . 'calisia_ticket_system_ticket';
        
        $charset_collate = $wpdb->get_charset_collate();
    
        $sql = "CREATE TABLE $table_name (
            id int(11) NOT NULL AUTO_INCREMENT,
            title text NOT NULL,
            kind ENUM('order', 'other') NOT NULL default 'order',
            added datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
            user_id int(11) NOT NULL,
            added_by int(11) NOT NULL,
            element_id int(11) NOT NULL,
            seen int(1) NOT NULL,
            status ENUM('opened', 'onhold', 'awatingreply', 'completed') NOT NULL default 'opened',
            deleted int(1) NOT NULL,
            PRIMARY KEY  (id)
        ) $charset_collate;";
    
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );

        $table_name = $wpdb->prefix . 'calisia_ticket_system_message';
        $sql = "CREATE TABLE $table_name (
            id int(11) NOT NULL AUTO_INCREMENT,
            ticket_id int(11) NOT NULL,
            added datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
            text text NOT NULL,
            added_by int(11) NOT NULL,
            seen int(1) NOT NULL,
            PRIMARY KEY  (id)
        ) $charset_collate;";
        
        dbDelta( $sql );


        $table_name = $wpdb->prefix . 'calisia_ticket_system_file';
        $sql = "CREATE TABLE $table_name (
            id int(11) NOT NULL AUTO_INCREMENT,
            message_id int(11) NOT NULL,
            file_name varchar(40) NOT NULL,
            file_path varchar(256) NOT NULL,
            added datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
            added_by int(11) NOT NULL,
            PRIMARY KEY  (id)
        ) $charset_collate;";
        
        dbDelta( $sql );

        add_option( 'calisia_ticket_system', self::$db_version );
    }
}