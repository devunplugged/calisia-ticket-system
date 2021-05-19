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
            try{
                $uploaded_files = uploader::save_uploaded_files();
            }catch(\Exception $e) {
                renderer::render('alerts/frontend-alert-danger', array('msg'=>$e->getMessage()));
                return;
            }
            data::save_message($_GET['id'], $uploaded_files);
        }

        data::ticket_seen($_GET['id']);
        $ticket = new ticket((int)$_GET['id']);




/*
echo "<pre>";
print_r($ticket);
echo "</pre>";
*/
        $messages = '';
        foreach($ticket->get_conversation() as $message){
            $messages .= renderer::render(
                'tickets/messages/ticket-message',
                array(
                    'message' => $message,
                    'attachments' => data::get_message_attachments($message->id)
                ),
                false
            );
        }
        



        renderer::render(
            'containers/backend-settings-container',
            array(
                'title-bar' => renderer::render(
                                    'tickets/bars/single-ticket-backend-bar',
                                    array(
                                        'ticket_id' => $ticket->get_id()
                                    )
                                ),
                'conversation' => renderer::render(
                                    'elements/backend-conversation',
                                    array(
                                        'messages' => renderer::render(
                                                            'tickets/messages/ticket-messages',
                                                            array(
                                                                'messages' => $messages
                                                            ),
                                                            false
                                                        ),
                                        'reply-form' => renderer::render(
                                                            'tickets/forms/backend-reply-form',
                                                            array(
                                                                'ticket_id' => $ticket->get_id()
                                                            ),
                                                            false
                                                        )
                                    ),
                                    false
                                ),
                'user' => renderer::render('elements/backend-user',array(), false)
            )
        );

        

        
    }

    public static function browse_tickets(){
        /*$browser = new browser(
            array(
                'kind' => isset($_GET['kind']) ? $_GET['kind'] : 'order',
                'order_by' => isset($_GET['order_by']) ? $_GET['order_by'] : 'date',
                'page' => isset($_GET['current_page']) ? $_GET['current_page'] : 1,
                'items_per_page' => isset($_GET['on_page']) ? $_GET['on_page'] : 25
            )
        );
        $query_result = $browser->get_results();
       // print_r($query_result);
        $ticket_list = '';
        foreach($query_result as $ticket){
            $ticket_list .= renderer::render(
                'tickets-list-element-backend',
                array(
                    'ticket' => $ticket
                ),
                false
            );
           // echo "<div><a href='/wp-admin/admin.php?page=calisia-tickets&id=".$ticket->id."'>".$ticket->title."</a></div>";
        }

        renderer::render(
            'ticket-list-backend',
            array(
                'list' => $ticket_list
            )
        );

        renderer::render(
            'ticket-browser-pagination',
            array(
                'pagination' => $browser->generate_pagination_array()
            )
        );*/

        $tickets_list = new Ticket_List();
        $tickets_list->prepare_items();
        $tickets_list->display();
    }

    public static function my_admin_page_contents() {
        if(isset($_GET['id'])){
            self::single_ticket();
        }else{
            self::browse_tickets();
        }
    }
}