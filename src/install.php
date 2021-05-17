<?php
namespace calisia_ticket_system;

class install{
    private static $db_version = '1.2';
    
    public static function update_check(){
        //global $calisia_customer_notes_db_version;
        if ( get_site_option( 'calisia_ticket_system' ) != self::$db_version ) {
            self::db_install();
        }
    }

    public static function db_install() {
        global $wpdb;
       // global $calisia_customer_notes_db_version;
    
        $table_name = $wpdb->prefix . 'calisia_ticket';
        
        $charset_collate = $wpdb->get_charset_collate();
    
        $sql = "CREATE TABLE $table_name (
            id int(11) NOT NULL AUTO_INCREMENT,
            title text NOT NULL,
            kind ENUM('order', 'other') NOT NULL default 'order',
            added datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
            user_id int(11) NOT NULL,
            added_by int(11) NOT NULL,
            element_id int(11) NOT NULL,
            PRIMARY KEY  (id)
        ) $charset_collate;";
    
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );

        $table_name = $wpdb->prefix . 'calisia_ticket_conversation';
        $sql = "CREATE TABLE $table_name (
            id int(11) NOT NULL AUTO_INCREMENT,
            ticket_id int(11) NOT NULL,
            added datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
            text text NOT NULL,
            added_by int(11) NOT NULL,
            PRIMARY KEY  (id)
        ) $charset_collate;";
        
        dbDelta( $sql );

        add_option( 'calisia_ticket_system', self::$db_version );
    }
}