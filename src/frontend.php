<?php
namespace calisia_ticket_system;

class frontend{

    public static function my_account_endpoints() {
        add_rewrite_endpoint( 'calisia-new-ticket', EP_PAGES );
        add_rewrite_endpoint( 'calisia-show-ticket', EP_PAGES );
    }

    public static function order_tickets( $order ){

        $tickets = data::get_tickets('order', get_current_user_id(), $order->get_id());

        foreach($tickets as $ticket){
            renderer::render('tickets-list-element',array('ticket' => $ticket));
        }

        renderer::render(
            'new-ticket-button',
            array(
                'order_id' => $order->get_id()
            )
        );
        
    }

    public static function new_ticket() {
        if(isset($_POST['calisia_ticket'])){
            data::save_ticket(get_current_user_id());
        }
    
        renderer::render(
            'ticket-form',
            array(
                'order_id' => $_GET['order_id'],
                'kind' => 'order'
            )
        );
    }

    public static function ticket(){
        if(isset($_POST['calisia_ticket'])){
            data::save_ticket(get_current_user_id());
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
}