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

    public static function my_admin_page_contents() {
        if(isset($_GET['id'])){
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
        }else{
            $tickets = data::get_all_tickets();
            foreach($tickets as $ticket){
                echo "<div><a href='/wp-admin/admin.php?page=calisia-tickets&id=".$ticket->id."'>".$ticket->title."</a></div>";
            }
        }
    }
}