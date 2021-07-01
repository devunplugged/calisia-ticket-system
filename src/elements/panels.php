<?php
namespace calisia_ticket_system\elements;

if ( ! defined( 'ABSPATH' ) ) {
	exit();
}

use \calisia_ticket_system as cts;

class panels{
    public static function user_tickets_table($user_id = 0){
        if($user_id != 0)
            $_GET['user_id'] = $user_id;

        return cts\renderer::render(
            'elements/backend-user-tickets-table',
            array(
                'tickets_table' => raw::browse_tickets(false)
            ),
            false
        );
    }

    public static function order_tickets_table($order_id = 0, $raw = false){
        if($order_id != 0)
            $_GET['element_id'] = $order_id;

        $_GET['kind'] = 'order';
        
        if(!$raw){
            return cts\renderer::render(
                'elements/backend-user-tickets-table',
                array(
                    'tickets_table' => raw::browse_tickets(false, false)
                ),
                false
            );
        }else{
            return raw::browse_tickets(false, false, false);
        }
    }

    public static function user_info($user_id){
        return cts\renderer::render(
            'elements/backend-user',
            array(
                'user_id' => $user_id,
                'wp_user' => get_user_by( 'ID', $user_id ),
                'wp_user_meta' => get_user_meta( $user_id )
            ), 
            false
        );
    }

    public static function ticket_conversation($messages, $ticket){
        return cts\renderer::render(
            'elements/backend-conversation',
            array(
                'messages' => cts\renderer::render(
                                    'tickets/messages/ticket-messages',
                                    array(
                                        'messages' => $messages
                                    ),
                                    false
                                ),
                'reply-form' => cts\renderer::render(
                                    'tickets/forms/backend-reply-form',
                                    array(
                                        'ticket_id' => $ticket->get_model()->get_id(),
                                        'nonce' => wp_create_nonce( 'calisia-ticket-reply-' . $ticket->get_model()->get_id() ),
                                        'calisia_form_token' => cts\Form_Token::create_token()
                                    ),
                                    false
                                ),
                'title' => $ticket->get_model()->get_title()
            ),
            false
        );
    }

    public static function ticket_title_bar($ticket_id, $ticket_status){
        return cts\renderer::render(
            'tickets/bars/single-ticket-backend-bar',
            array(
                'ticket_id' => $ticket_id,
                'status_form' => cts\renderer::render(
                    'tickets/forms/backend-status-form',
                    array(
                        'select' => cts\inputs::select(
                            array(
                                'id' => 'calisia_ticket_system_status',
                                'name' => 'calisia_ticket_system_status',
                                'class' => 'select',
                                'options' => array(
                                    __('Opened', 'calisia-ticket-system') => 'opened',
                                    __('Onhold', 'calisia-ticket-system') => 'onhold',
                                    __('Awaiting Reply', 'calisia-ticket-system') => 'awaitingreply',
                                    __('Completed', 'calisia-ticket-system') => 'completed'
                                ),
                                'value' => $ticket_status
                            )
                        ),
                        'nonce' => wp_create_nonce( 'calisia-ticket-status' . $ticket_id )
                    ),
                    false
                )
            )
        );
    }

    public static function ticket_order_details($order){
        return cts\renderer::render(
            'elements/backend-order',
            array(
                'order' => $order
            ),
            false
        );
    }

    public static function new_ticket(){
        return cts\renderer::render(
            'elements/backend-panel',
            array(
                'content' => raw::new_ticket()
            ),
            false
        );
    }
}