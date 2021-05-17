<?php
namespace calisia_ticket_system;

class backend{
    public static function my_admin_menu() {
        add_menu_page(
            __( 'Calisia Tickets', 'my-textdomain' ),
            __( 'Tickets', 'my-textdomain' ),
            'manage_options',
            'calisia-tickets',
            'calisia_ticket_system\backend::my_admin_page_contents',
            'dashicons-tickets-alt',
            3
        );
    }

    public static function single_ticket(){
        if(isset($_POST['calisia_ticket_reply'])){
            data::save_message($_GET['id']);
        }
        $conversation = data::get_conversation($_GET['id']);

        $messages = '';
        foreach($conversation as $message){
            $messages .= renderer::render(
                'ticket-message',
                array(
                    'message' => $message
                ),
                false
            );
        }
        
        renderer::render(
            'ticket-messages',
            array(
                'messages' => $messages
            )
        );

        renderer::render(
            'reply-form',
            array(
                'ticket_id' => $_GET['id']
            )
        );
    }

    public static function browse_tickets(){
        $browser = new browser(
            array(
                'kind' => isset($_GET['kind']) ? $_GET['kind'] : 'order',
                'order_by' => isset($_GET['order_by']) ? $_GET['order_by'] : 'date',
                'page' => isset($_GET['current_page']) ? $_GET['current_page'] : 1,
                'items_per_page' => isset($_GET['on_page']) ? $_GET['on_page'] : 25
            )
        );
        $query_result = $browser->get_results();
        print_r($query_result);

        foreach($query_result as $ticket){
            echo "<div><a href='/wp-admin/admin.php?page=calisia-tickets&id=".$ticket->id."'>".$ticket->title."</a></div>";
        }
        renderer::render(
            'ticket-browser-pagination',
            array(
                'pagination' => $browser->generate_pagination_array()
            )
        );
    }

    public static function my_admin_page_contents() {
        if(isset($_GET['id'])){
            self::single_ticket();
        }else{
            self::browse_tickets();
        }
    }
}