<?php
namespace calisia_ticket_system\elements;

if ( ! defined( 'ABSPATH' ) ) {
	exit();
}

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

    public static function browse_tickets($render = true, $controls = true, $wrap_in_form = true){
        ob_start();
            $tickets_list = new cts\Ticket_List();
            $tickets_list->prepare_items();
            $tickets_list->display();
        $tickets_table = ob_get_contents();
        ob_end_clean();
        
        if(!$render){
            ob_start();
        }

        if($controls)
            echo controls::ticket_table_controls();

        if($wrap_in_form){
            cts\renderer::render(
                'tickets/forms/default',
                array(
                    'method' => 'POST',
                    'content' => $tickets_table
                )
            );
        }else{
            echo $tickets_table;
        }

        if(!$render){
            $output = ob_get_contents();
            ob_end_clean();
            return $output;
        }
    }

    public static function new_ticket(){
        $template = 'tickets/forms/backend-new-ticket-form';

            

        $arguments = array(
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
                                                'value' => isset($_GET['kind']) ? $_GET['kind'] : ''
                                            )
                                        ),
            'nonce' => wp_create_nonce( 'calisia-ticket-new' ),
            'token' => cts\Form_Token::create_token()
        );

        if(isset($_GET['user_id'])){
            $template = 'tickets/forms/backend-new-ticket-form-predefind';
            $user = get_user_by( 'ID', $_GET['user_id'] );
            $arguments['user'] = $user->user_email . ' ' . $user->first_name . ' ' . $user->last_name;
        }
        if(isset($_GET['kind'])){
            $arguments['kind'] = $_GET['kind'];
            $arguments['kind_name'] = cts\translations::ticket_kind($_GET['kind']);
        }

        return cts\renderer::render(
            $template,
            $arguments,
            false
        );
    }
}