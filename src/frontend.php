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
            data::save_ticket(get_current_user_id());
        }
    
        renderer::render(
            'tickets/forms/ticket-form',
            array(
                'order_id' => $_GET['order_id'],
                'kind' => 'order'
            )
        );
    }

    

    public static function save_reply(){
        if(wp_verify_nonce( $_POST['calisia_nonce'], 'calisia-ticket-reply-' . $_POST['ticket_id'] )){
            if(Form_Token::check_token($_POST['calisia_form_token'])){
                try{
                    $uploaded_files = uploader::save_uploaded_files();
                }catch(\Exception $e) {
                    renderer::render('alerts/frontend-alert-danger', array('msg'=>$e->getMessage()));
                    return;
                }
                data::save_message($_GET['id'], $uploaded_files);
            }else{
                renderer::render('alerts/frontend-alert-danger', array('msg'=>'Ta wiadomość została już zapisana!'));
            }
            
        }else{
            renderer::render('alerts/frontend-alert-danger', array('msg'=>'Wystąpił błąd.'));
        }
    }

    public static function ticket(){
        
        if(isset($_POST['calisia_ticket_reply'])){
            self::save_reply();
        }
        $conversation = data::get_conversation($_GET['id']);
        $messages = '';
        foreach($conversation as $message){

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