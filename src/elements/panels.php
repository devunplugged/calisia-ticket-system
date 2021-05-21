<?php
namespace calisia_ticket_system\elements;

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

    public static function ticket_conversation($messages, $ticket_id){
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
                                        'ticket_id' => $ticket_id,
                                        'nonce' => wp_create_nonce( 'calisia-ticket-reply-' . $ticket_id ),
                                        'calisia_form_token' => cts\Form_Token::create_token()
                                    ),
                                    false
                                )
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
                                    __('Awating Reply', 'calisia-ticket-system') => 'awatingreply',
                                    __('Complete', 'calisia-ticket-system') => 'complete'
                                ),
                                'value' => $ticket_status
                            )
                        )
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
}