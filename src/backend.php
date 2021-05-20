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

    public static function save_reply(){
        $ticket = new ticket((int)$_GET['id']);

            if(isset($_POST['calisia_ticket_reply'])){
                try{
                    $uploaded_files = uploader::save_uploaded_files();
                }catch(\Exception $e) {
                    //renderer::render('alerts/alert-danger', array('msg'=>$e->getMessage()));
                    events::add_event(__($e->getMessage(),'calisia-ticket-system'), 'danger');
                    wp_redirect( $ticket->get_backend_ticket_url() );
                    exit;
                    return;
                }
                data::save_message($_GET['id'], $uploaded_files);
                
            }

        wp_redirect( $ticket->get_backend_ticket_url() );
        exit;
    }

    public static function save_reply2(){
        $ticket = new ticket((int)$_GET['id']);
        
        if(wp_verify_nonce( $_POST['calisia_nonce'], 'calisia-ticket-reply-' . $_POST['ticket_id'] )){
           
            if(Form_Token::check_token($_POST['calisia_form_token'])){
                try{
                    $uploaded_files = uploader::save_uploaded_files();
                }catch(\Exception $e) {
                    events::add_event($e->getMessage(), 'danger');
                    wp_redirect( $ticket->get_backend_ticket_url() );
                    exit;
                    return;
                }
                data::save_message($_GET['id'], $uploaded_files);
                
                
            }else{
                events::add_event(__('This message has been saved already','calisia-ticket-system'), 'warning'); echo "vvv"; die;
            }
            
        }else{
            events::add_event(__('Unexpected error','calisia-ticket-system'), 'danger');
        }

        wp_redirect( $ticket->get_backend_ticket_url() );
        exit;
    }

    public static function save_forms(){
        if(isset($_POST['calisia_ticket_reply'])){
            self::save_reply2();
        }
    }

    public static function single_ticket(){
        events::show_events();

        $ticket = new ticket((int)$_GET['id']);
        $ticket->mark_ticket_seen();
        $ticket->mark_messages_seen();
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
        
        $title_bar = renderer::render(
            'tickets/bars/single-ticket-backend-bar',
            array(
                'ticket_id' => $ticket->get_id(),
                'status_form' => renderer::render(
                    'tickets/forms/backend-status-form',
                    array(
                        'select' => inputs::select(
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
                                'value' => $ticket->get_status()
                            )
                        )
                    ),
                    false
                )
            )
        );


        renderer::render(
            'containers/backend-settings-container',
            array(
                'title-bar' => $title_bar,
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
                                                                'ticket_id' => $ticket->get_id(),
                                                                'nonce' => wp_create_nonce( 'calisia-ticket-reply-' . $_GET['id'] ),
                                                                'calisia_form_token' => Form_Token::create_token()
                                                            ),
                                                            false
                                                        )
                                    ),
                                    false
                                ),
                'user' => renderer::render(
                            'elements/backend-user',
                            array(
                                'user_id' => $ticket->get_user_id(),
                                'wp_user' => get_user_by( 'ID', $ticket->get_user_id() ),
                                'wp_user_meta' => get_user_meta( $ticket->get_user_id() )
                            ), 
                            false
                        )
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