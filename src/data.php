<?php
namespace calisia_ticket_system;

class data{
    public static function get_user_orders($phrase, $user_id){
        global $wpdb;

        return $wpdb->get_results(
            $wpdb->prepare(
            "SELECT pm1.meta_value AS user_id, pm1.post_id AS order_id, pm2.meta_value AS order_total, p.post_date AS order_date, p.post_status AS order_status
            FROM wp_posts AS p 
            LEFT JOIN wp_postmeta AS pm1 ON pm1.post_id = p.ID 
            LEFT JOIN wp_postmeta AS pm2 ON pm2.post_id = p.ID
            WHERE 
            p.post_type = 'shop_order' AND
            pm1.meta_key = '_customer_user' AND
            pm1.meta_value = %d AND
            pm2.meta_key = '_order_total' AND
            pm1.post_id LIKE %s",
            array(
                $user_id,
                $phrase
               )
            )
        );
    }

    public static function get_users($phrase){
        global $wpdb;

        return $wpdb->get_results(
            $wpdb->prepare(
            "SELECT u.*, um1.meta_value as first_name, um2.meta_value as last_name 
            FROM wp_users as u 
            LEFT JOIN wp_usermeta as um1 ON u.ID = um1.user_id 
            LEFT JOIN wp_usermeta as um2 ON u.ID = um2.user_id 
            WHERE um1.meta_key = 'first_name' AND um2.meta_key = 'last_name' AND 
            (u.user_login LIKE %s OR u.user_email LIKE %s OR um1.meta_value LIKE %s OR um2.meta_value LIKE %s)",
            array(
                $phrase,
                $phrase,
                $phrase,
                $phrase
               )
            )
        );
    }

    public static function get_number_of_uploads($user_id, $hours = 1){
        $since = time() - ($hours * 3600);

        global $wpdb;

        $result = $wpdb->get_results(
            $wpdb->prepare(
            "SELECT count(id) as upload_count FROM ".$wpdb->prefix."calisia_ticket_system_file WHERE added_by = %d AND added > %s",
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

        $results = $wpdb->get_results(
            $wpdb->prepare(
            "SELECT *, GREATEST( last_support_reply, last_customer_reply ) AS last_reply FROM ".$wpdb->prefix."calisia_ticket_system_ticket WHERE kind = %s AND user_id = %d AND element_id = %d ORDER BY last_reply DESC, id DESC",
            array(
                $kind,
                $user_id,
                $order_id
               )
            )
        );

        return default_object::get_models($results, 'calisia_ticket_system\ticket');
    }

    public static function get_customer_tickets($user_id, $pagination){
        global $wpdb;

        $results = $wpdb->get_results(
            $wpdb->prepare(
            "SELECT SQL_CALC_FOUND_ROWS  *, GREATEST( last_support_reply, last_customer_reply ) AS last_reply FROM ".$wpdb->prefix."calisia_ticket_system_ticket WHERE user_id = %d AND deleted = 0 ORDER BY last_reply DESC, id DESC LIMIT %d, %d",
            array(
                $user_id,
                $pagination->get_offset(), 
                $pagination->get_limit()
               )
            )
        );
        $found_rows_number = $wpdb->get_results('SELECT FOUND_ROWS() as found_rows');
        $tickets = default_object::get_models($results, 'calisia_ticket_system\ticket');
        return array('tickets' => $tickets, 'row_count' => $found_rows_number[0]->found_rows);
    }

    public static function get_order_tickets($order_id){
        global $wpdb;

        $results = $wpdb->get_results(
            $wpdb->prepare(
            "SELECT *, GREATEST( last_support_reply, last_customer_reply ) AS last_reply FROM ".$wpdb->prefix."calisia_ticket_system_ticket WHERE kind = 'order' AND element_id = %d ORDER BY last_reply DESC, id DESC",
            array(
                $order_id
               )
            )
        );

        return default_object::get_models($results, 'calisia_ticket_system\ticket');
    }

    
/*
    public static function get_message_attachments($message_id){
        global $wpdb;

        $results = $wpdb->get_results(
            $wpdb->prepare(
            "SELECT * FROM ".$wpdb->prefix."calisia_ticket_system_file WHERE message_id = %d",
            array(
                $message_id
               )
            )
        );

        $attachments = array();
        foreach($results as $result){
            $file = new file();
            $file->get_model()->fill($result);
            $attachments[] = $file;
        }

        return $attachments;
    }*/
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
/*
    public static function get_all_tickets(){
        global $wpdb;

        return $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."calisia_ticket_system_ticket ORDER BY id");
    }
*/
/*
    public static function get_conversation($ticket_id){
        global $wpdb;

        return $wpdb->get_results(
            $wpdb->prepare(
            "SELECT * FROM ".$wpdb->prefix."calisia_ticket_system_message WHERE ticket_id = %d",
            array(
                $ticket_id
               )
            )
        );
    }
*/
    public static function get_number_of_unread_messages_customer($ticket_id){
        global $wpdb;

        $result = $wpdb->get_results(
            $wpdb->prepare(
            "SELECT count(id) as unread FROM ".$wpdb->prefix."calisia_ticket_system_message WHERE ticket_id = %d AND customer_seen = 0",
            array(
                $ticket_id
            )
            )
        );
        
        return $result[0]->unread;
    }

    public static function get_number_of_unread_messages($ticket_id){
        global $wpdb;

        $result = $wpdb->get_results(
            $wpdb->prepare(
            "SELECT count(id) as unread FROM ".$wpdb->prefix."calisia_ticket_system_message WHERE ticket_id = %d AND seen = 0",
            array(
                $ticket_id
               )
            )
        );
        
        return $result[0]->unread;
    }

    public static function get_number_of_all_unread_messages(){
        $unread = 0;
        global $wpdb;

        $tickets = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."calisia_ticket_system_ticket WHERE deleted = 0");
        foreach($tickets as $ticket){
            $result = $wpdb->get_results(
                $wpdb->prepare(
                "SELECT count(id) as unread FROM ".$wpdb->prefix."calisia_ticket_system_message WHERE ticket_id = %d AND seen = 0",
                array(
                    $ticket->id
                   )
                )
            );
            $unread += $result[0]->unread;
        }

        return $unread;
    }

    public static function save_post_to_ticket(){
        $ticket = new ticket();
        $ticket->get_model()->set_title($_POST['title']);
        $ticket->get_model()->set_kind($_POST['kind']);
        $ticket->get_model()->set_added(current_time( 'mysql' ));
        $ticket->get_model()->set_user_id( isset($_POST['user_id']) ? $_POST['user_id'] : get_current_user_id() );
        $ticket->get_model()->set_added_by(get_current_user_id()); 
        $ticket->get_model()->set_element_id($_POST['element_id']); 
        $ticket->get_model()->set_seen(0); 
        $ticket->get_model()->set_status( isset($_POST['status']) ? $_POST['status'] : 'opened' ); 
        $ticket->get_model()->set_deleted(0); 
        $ticket->get_model()->save();
        return $ticket;
    }

    public static function save_post_to_message($ticket_id){
        $message = new message();
        $message->get_model()->set_ticket_id($ticket_id);
        $message->get_model()->set_added(current_time( 'mysql' ));
        $message->get_model()->set_text($_POST['msg']);
        $message->get_model()->set_added_by(get_current_user_id());
        $message->get_model()->save();
        return $message;
    }

    

    public static function save_uploads($message_id, $uploaded_files){
        foreach($uploaded_files as $uploaded_file){
            $file = new file();
            $file->get_model()->set_message_id($message_id);
            $file->get_model()->set_file_name($uploaded_file['name']);
            $file->get_model()->set_file_path($uploaded_file['path']);
            $file->get_model()->set_added(current_time( 'mysql' ));
            $file->get_model()->set_added_by(get_current_user_id());
            $file->get_model()->save();
        }
    }
}