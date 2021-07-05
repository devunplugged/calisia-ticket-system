<?php
namespace calisia_ticket_system;

if ( ! defined( 'ABSPATH' ) ) {
	exit();
}

class frontend{

    public static function my_account_endpoints() {
        add_rewrite_endpoint( endpoint::get_new_ticket_endpoint_name(), EP_PAGES );
        add_rewrite_endpoint( endpoint::get_ticket_endpoint_name(), EP_PAGES );
    }

    


    public static function new_ticket() {

        events::show_events();

        $args = array();
        if(isset($_GET['element_id']) && isset($_GET['kind']) && $_GET['kind'] == 'order'){
            $args['element_id'] = $_GET['element_id'];
            $args['kind'] = 'order';
        }
        $args['nonce'] = wp_create_nonce( 'calisia-ticket-new' );
        $args['calisia_form_token'] = Form_Token::create_token();
        renderer::render('tickets/forms/new-ticket-form', $args);
    }



    public static function save_forms(){

        if(isset($_POST['calisia_ticket_reply'])){
            //self::save_reply();
            $ticket = new ticket($_GET['id']);
            $ticket->save_reply('get_frontend_ticket_url');
        }

        if(isset($_POST['calisia_ticket_new'])){
            $ticket = new ticket();
            $ticket->save_ticket('get_frontend_ticket_url');
        }

        if(isset($_POST['calisia_ticket_close'])){
            $ticket = new ticket($_GET['id']);
            $ticket->close();
        }

        if(isset($_POST['calisia_ticket_open'])){
            $ticket = new ticket($_GET['id']);
            $ticket->open();
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

        if($ticket->get_model()->get_deleted() == 1){
            renderer::render(
                'alerts/alert-danger',
                array(
                    'msg' => __('This ticket has been deleted', 'calisia-ticket-system')
                )
            );
            return;
        }

        $ticket->mark_messages_customer_seen();
        $messages = '';
        foreach($ticket->get_conversation() as $message){

            $messages .= renderer::render(
                'tickets/messages/ticket-message',
                array(
                    'message' => $message,
                    'attachments' => $message->get_attachments(),
                    'backend' => 0,
                    'owner_id' => $ticket->get_model()->get_user_id()
                ),
                false
            );
        }

        $params = array();
        if($ticket->get_model()->get_kind() == 'order'){
            $order = wc_get_order($ticket->get_model()->get_element_id());
            $params['order'] = $order;
        }
        $params['ticket'] = $ticket;
        renderer::render('tickets/bars/single-ticket-frontend-bar', $params);

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

        if($ticket->get_model()->get_status() != 'completed'){
            renderer::render(
                'tickets/forms/frontend-close-ticket-form',
                array(
                    'ticket_id' => $_GET['id'],
                    'nonce' => wp_create_nonce( 'calisia-ticket-close-ticket-' . $_GET['id'] ),
                    'calisia_form_token' => Form_Token::create_token('close-ticket')
                )
            );
        }else{
            renderer::render(
                'tickets/forms/frontend-open-ticket-form',
                array(
                    'ticket_id' => $_GET['id'],
                    'nonce' => wp_create_nonce( 'calisia-ticket-open-ticket-' . $_GET['id'] ),
                    'calisia_form_token' => Form_Token::create_token('open-ticket')
                )
            );
        }
    }

    public static function my_tickets( ){
        require_once CALISIA_TICKET_SYSTEM_ROOT . '/src/core/pagination.php';

        $pagination = new pagination();
        $pagination->set_page(isset($_GET['show-page']) ? $_GET['show-page'] : 1);
        $tickets = data::get_customer_tickets(get_current_user_id(), $pagination);
        $pagination->set_row_count($tickets['row_count']);

        $ticket_list = '';
        foreach($tickets['tickets'] as $ticket){
            if($ticket->get_model()->get_kind() == 'order'){
                $order = wc_get_order($ticket->get_model()->get_element_id());
                $ticket_list .= renderer::render('tickets/lists/tickets-list-element',array('ticket' => $ticket, 'order' => $order, 'unread' => data::get_number_of_unread_messages_customer($ticket->get_model()->get_id())), false);
            }else{
                $ticket_list .= renderer::render('tickets/lists/tickets-list-element',array('ticket' => $ticket, 'unread' => data::get_number_of_unread_messages_customer($ticket->get_model()->get_id())), false);
            }

            
        }

        renderer::render(
            'tickets/buttons/new-default-ticket-button'
        );

        renderer::render(
            'tickets/lists/ticket-list-container',
            array(
                'title_bar' => renderer::render('tickets/bars/ticket-list-title-bar', array('title' => __('Your tickets', 'calisia-ticket-system')), false),
                'ticket_list' => $ticket_list
            )
        );

        $pagination->render();
        
    }

    public static function order_tickets( $order ){

        $tickets = data::get_tickets('order', get_current_user_id(), $order->get_id());

        $ticket_list = '';
        foreach($tickets as $ticket){
            $ticket_list .= renderer::render('tickets/lists/tickets-list-element',array('ticket' => $ticket, 'order' => $order, 'unread' => data::get_number_of_unread_messages_customer($ticket->get_model()->get_id())), false);
        }

        renderer::render(
            'tickets/lists/ticket-list-container',
            array(
                'title_bar' => renderer::render('tickets/bars/ticket-list-title-bar', array('title' => __('Your tickets', 'calisia-ticket-system')), false),
                'ticket_list' => $ticket_list
            )
        );

        renderer::render(
            'tickets/buttons/new-order-ticket-button',
            array(
                'element_id' => $order->get_id()
            )
        );
        
    }
}