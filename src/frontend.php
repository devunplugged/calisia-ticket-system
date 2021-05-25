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
            renderer::render('tickets/lists/tickets-list-element',array('ticket' => $ticket));
        }

        renderer::render(
            'tickets/buttons/new-ticket-button',
            array(
                'order_id' => $order->get_id()
            )
        );
        
    }


    public static function new_ticket() {
        if(isset($_POST['calisia_ticket'])){
            $ticket = data::save_post_to_ticket();
            data::save_post_to_message($ticket->get_model()->get_id());
        }
    
        renderer::render(
            'tickets/forms/ticket-form',
            array(
                'order_id' => $_GET['order_id'],
                'kind' => 'order'
            )
        );
    }

    public static function save_forms(){
        if(isset($_POST['calisia_ticket_reply'])){
            //self::save_reply();
            $ticket = new ticket($_GET['id']);
            $ticket->save_reply('get_frontend_ticket_url');
        }
    }

    public static function ticket(){
        
        events::show_events();
  
        $ticket = new ticket((int)$_GET['id']);
        if(!$ticket->user_has_access(get_current_user_id())){
            renderer::render(
                'alerts/alert-danger',
                array(
                    'msg' => __('You are not allowed to view this content', 'calisia-ticket-system')
                )
            );
            return;
        }

        $messages = '';
        foreach($ticket->get_conversation() as $message){

            $messages .= renderer::render(
                'tickets/messages/ticket-message',
                array(
                    'message' => $message,
                    'attachments' => data::get_message_attachments($message->get_model()->get_id())
                ),
                false
            );
        }

        renderer::render(
            'tickets/messages/ticket-messages',
            array(
                'messages' => $messages
            )
        );

        renderer::render(
            'tickets/forms/frontend-reply-form',
            array(
                'ticket_id' => $_GET['id'],
                'nonce' => wp_create_nonce( 'calisia-ticket-reply-' . $_GET['id'] ),
                'calisia_form_token' => Form_Token::create_token()
            )
        );
    }
}