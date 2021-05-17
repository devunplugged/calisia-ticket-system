<?php
namespace calisia_ticket_system;

class data{
    public static function save_ticket($user_id){
        global $wpdb;
        
        $wpdb->insert( 
            $wpdb->prefix . 'calisia_ticket', 
            array( 
                'title' => $_POST['title'],
                'kind' => $_POST['kind'],
                'added' => current_time( 'mysql' ), 
                'user_id' => $user_id, 
                'added_by' => get_current_user_id(),
                'element_id' => $_POST['order_id']
            ) 
        );
        $ticket_id = $wpdb->insert_id;
        self::save_message($ticket_id);
    }

    public static function save_message($ticket_id){
        global $wpdb;
        $wpdb->insert( 
            $wpdb->prefix . 'calisia_ticket_conversation', 
            array( 
                'ticket_id' => $ticket_id,
                'added' => current_time( 'mysql' ), 
                'text' => $_POST['msg'], 
                'added_by' => get_current_user_id()
            ) 
        );
    }

    public static function get_tickets($kind, $user_id, $order_id){
        global $wpdb;

        return $wpdb->get_results(
            $wpdb->prepare(
            "SELECT * FROM ".$wpdb->prefix."calisia_ticket WHERE kind = %s AND user_id = %d AND element_id = %d ORDER BY id",
            array(
                $kind,
                $user_id,
                $order_id
               )
            )
        );
    }

    public static function get_all_tickets(){
        global $wpdb;

        return $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."calisia_ticket ORDER BY id");
    }

    public static function get_conversation($ticket_id){
        global $wpdb;

        return $wpdb->get_results(
            $wpdb->prepare(
            "SELECT * FROM ".$wpdb->prefix."calisia_ticket_conversation WHERE ticket_id = %d",
            array(
                $ticket_id
               )
            )
        );
    }
}