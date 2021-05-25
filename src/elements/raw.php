<?php
namespace calisia_ticket_system\elements;

use \calisia_ticket_system as cts;

class raw{
    public static function ticket_message($message){
        return cts\renderer::render(
            'tickets/messages/ticket-message',
            array(
                'message' => $message,
                'attachments' => cts\data::get_message_attachments($message->get_model()->get_id())
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
}