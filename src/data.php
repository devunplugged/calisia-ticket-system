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

    public static function save_message($ticket_id, $uploaded_files = array()){
        global $wpdb;
        $result = $wpdb->insert( 
            $wpdb->prefix . 'calisia_ticket_conversation', 
            array( 
                'ticket_id' => $ticket_id,
                'added' => current_time( 'mysql' ), 
                'text' => wp_kses_post( stripslashes($_POST['msg'])), 
                'added_by' => get_current_user_id()
            ) 
        );
        $message_id = $wpdb->insert_id;

        foreach($uploaded_files as $uploaded_file){

            $wpdb->insert( 
                $wpdb->prefix . 'calisia_ticket_conversation_files', 
                array( 
                    'message_id' => $message_id,
                    'file_name' => $uploaded_file['name'],
                    'file_path' => $uploaded_file['path'],
                    'added' => current_time( 'mysql' ), 
                    'added_by' => get_current_user_id()
                ) 
            );
        }
    }

    public static function get_number_of_uploads($user_id, $hours = 1){
        $since = time() - ($hours * 3600);

        global $wpdb;

        $result = $wpdb->get_results(
            $wpdb->prepare(
            "SELECT count(id) as upload_count FROM ".$wpdb->prefix."calisia_ticket_conversation_files WHERE added_by = %d AND added > %s",
            array(
                $user_id,
                date('Y-m-d H:i:s', $since)
               )
            )
        );
        
        return $result[0]->upload_count;
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

    public static function get_message_attachments($message_id){
        global $wpdb;

        return $wpdb->get_results(
            $wpdb->prepare(
            "SELECT * FROM ".$wpdb->prefix."calisia_ticket_conversation_files WHERE message_id = %d",
            array(
                $message_id
               )
            )
        );
    }
/*
    public static function validate_tickets_sort($order_by){

    }

    public static function browse_query($browser){
        global $wpdb;
        $results = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT SQL_CALC_FOUND_ROWS * FROM ".$wpdb->prefix."calisia_ticket ORDER BY %s LIMIT %d,%d",
                array(
                    $browser->get_order_by(),                    
                    $browser->get_offset(),
                    $browser->get_items_per_page()
                )
            )
        );
        $number_of_all_results = $wpdb->get_results("SELECT FOUND_ROWS() as found_rows");

        return array('results' => $results, 'number_of_all_results' => $number_of_all_results[0]->found_rows);
    }
*/
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

    

    public static function get_number_of_unread_messages($ticket_id){
        global $wpdb;

        $result = $wpdb->get_results(
            $wpdb->prepare(
            "SELECT count(id) as unread FROM ".$wpdb->prefix."calisia_ticket_conversation WHERE ticket_id = %d AND seen = 0",
            array(
                $ticket_id
               )
            )
        );
        
        return $result[0]->unread;
    }
}