<?php
namespace calisia_ticket_system\elements;

use \calisia_ticket_system as cts;

class raw{
    public static function ticket_message($message, $ticket){
        return cts\renderer::render(
            'tickets/messages/ticket-message',
            array(
                'message' => $message,
                'attachments' => $message->get_attachments(),
                'backend' => 1,
                'owner_id' => $ticket->get_model()->get_user_id()
            ),
            false
        );
    }

    public static function browse_tickets($render = true){
        ob_start();
            $tickets_list = new cts\Ticket_List();
            $tickets_list->prepare_items();
            $tickets_list->display();
        $tickets_table = ob_get_contents();
        ob_end_clean();
        
        if(!$render){
            ob_start();
        }

        echo controls::ticket_table_controls();

        
        cts\renderer::render(
            'tickets/forms/default',
            array(
                'method' => 'POST',
                'content' => $tickets_table
            )
        );

        if(!$render){
            $output = ob_get_contents();
            ob_end_clean();
            return $output;
        }
    }

    public static function new_ticket(){
        return cts\renderer::render(
            'tickets/forms/backend-new-ticket-form',
            array(
                'status-select' => cts\inputs::select(
                                                array(
                                                    'id' => 'status-select',
                                                    'name' => 'status',
                                                    'class' => 'select',
                                                    'options' => array(
                                                        __('Opened', 'calisia-ticket-system') => 'opened',
                                                        __('On-hold', 'calisia-ticket-system') => 'onhold',
                                                        __('Awaiting Reply', 'calisia-ticket-system') => 'awaitingreply',
                                                        __('Completed', 'calisia-ticket-system') => 'competed'
                                                    ),
                                                    'value' => ''
                                                )
                                            ),
                'kind-select' => cts\inputs::select(
                                                array(
                                                    'id' => 'kind-select',
                                                    'name' => 'kind',
                                                    'class' => 'select',
                                                    'options' => array(
                                                        __('Other', 'calisia-ticket-system') => 'other',
                                                        __('Order', 'calisia-ticket-system') => 'order'
                                                    ),
                                                    'value' => ''
                                                )
                                            ),
                'nonce' => wp_create_nonce( 'calisia-ticket-new' ),
                'token' => cts\Form_Token::create_token()
            ),
            false
        );
    }
}