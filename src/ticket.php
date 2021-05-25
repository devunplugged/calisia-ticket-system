<?php
namespace calisia_ticket_system;

require_once CALISIA_TICKET_SYSTEM_ROOT . '/src/models/ticket.php';

class ticket{
    protected $model;
    private $conversation;

    public function get_model(){
        return $this->model;
    }

    public function get_conversation(){
        return $this->conversation;
    }

    public function set_conversation($conversation){
        $this->conversation = $conversation;
    }

    function __construct($ticket_id = 0){

        $this->model = new models\ticket();

        if($ticket_id === 0)
            return;

        if(!is_numeric($ticket_id))
            return;

        $ticket_id = (int)$ticket_id;

        if(!is_int($ticket_id))
            return;


        
        $this->model->set_id($ticket_id);
        $this->load_class_values();
    }

    public function load_class_values(){
        $this->db_get_ticket();  
    }
    
    public function db_get_ticket(){
        global $wpdb;
        $result = $wpdb->get_results(
            $wpdb->prepare(
            "SELECT * FROM ".$wpdb->prefix."calisia_ticket_system_ticket WHERE id = %d",
            array(
                $this->model->get_id()
               )
            )
        );
        $this->model->fill($result[0]);
        $this->conversation = $this->db_get_conversation();
    }

    private function db_get_conversation(){
        global $wpdb;
        $results =  $wpdb->get_results(
            $wpdb->prepare(
            "SELECT * FROM ".$wpdb->prefix."calisia_ticket_system_message WHERE ticket_id = %d",
            array(
                $this->model->get_id()
               )
            )
        );
        $conversation = array();
        foreach($results as $result){
            $message = new message();
            $message->get_model()->fill($result);
            $conversation[] = $message;
        }

        return $conversation;
    }

    public function user_has_access($user_id){
        if($this->model->get_user_id() == $user_id)
            return true;
        return false;
    }

    public function mark_ticket_seen(){
        $this->model->set_seen(1);
        $this->model->update();
        /*global $wpdb;

        return $wpdb->update( $wpdb->prefix."calisia_ticket_system_ticket", array( 'seen' => 1 ), array( 'id' => $this->model->get_id() ), array( '%d' ), array( '%d' ));*/
    }

    public function mark_messages_seen(){
        global $wpdb;

        return $wpdb->update( $wpdb->prefix."calisia_ticket_system_message", array( 'seen' => 1 ), array( 'ticket_id' => $this->model->get_id() ), array( '%d' ), array( '%d' ));
    }

/*
    public function save(){
        global $wpdb;
        
        $wpdb->insert( 
            $wpdb->prefix . 'calisia_ticket_system_ticket', 
            array( 
                'title' => $this->model->get_title(),
                'kind' => $this->model->get_kind(),
                'added' => $this->model->get_added(), 
                'user_id' => $this->model->get_user_id(), 
                'added_by' => $this->model->get_added_by(),
                'element_id' => $this->model->get_element_id()
            ) 
        );
        $this->model->set_id($wpdb->insert_id);
        //self::save_message($ticket_id);
    }
*/
    /*

    public function update(){
        global $wpdb;

        return $wpdb->update( 
            $wpdb->prefix."calisia_ticket_system_ticket", 
            array( 
                'title' => $this->model->set_title(), 
                'kind' => $this->model->set_kind(), 
                'added' => $this->model->set_added(), 
                'user_id' => $this->model->set_user_id(), 
                'element_id' => $this->model->set_element_id(), 
                'seen' => $this->model->set_seen(), 
                'status' => $this->model->set_status(),
                'deleted' => $this->model->set_deleted()
            ), 
            array( 'id' => $this->model->id() ), 
            array( '%s', '%s', '%s', '%d', '%d', '%d', '%s' ), 
            array( '%d' )
        );
    }*/

    public function delete(){
        $this->model->set_deleted(1);
        $this->model->update();
    }

    public function save_ticket($redirect_url_callback = 'get_frontend_ticket_url'){
        if(wp_verify_nonce( $_POST['calisia_nonce'], 'calisia-ticket-new')){

            if(Form_Token::check_token($_POST['calisia_form_token'])){
                try{
                    $uploaded_files = uploader::save_uploaded_files();
                }catch(\Exception $e) {
                    events::add_event($e->getMessage(), 'danger');
                    $this->model->set_element_id($_GET['order_id']);
                    wp_redirect( $this->get_frontend_new_ticket_url() );
                    exit;
                    return;
                }
                $ticket = data::save_post_to_ticket();
                $message = data::save_post_to_message($ticket->model->get_id());
                data::save_uploads($message->get_model()->get_id(), $uploaded_files);
                $this->model->set_id($ticket->model->get_id());
            }else{
                events::add_event(__('This message has been saved already','calisia-ticket-system'), 'warning');
            }
            
        }else{
            events::add_event(__('Unexpected error','calisia-ticket-system'), 'danger');
        }

        wp_redirect( $ticket->$redirect_url_callback() );
        exit;
    }

    public function save_reply($redirect_url_callback = 'get_frontend_ticket_url'){
        if(wp_verify_nonce( $_POST['calisia_nonce'], 'calisia-ticket-reply-' . $this->model->get_id() )){

            if(Form_Token::check_token($_POST['calisia_form_token'])){
                try{
                    $uploaded_files = uploader::save_uploaded_files();
                }catch(\Exception $e) {
                    events::add_event($e->getMessage(), 'danger');
                    wp_redirect( $this->$redirect_url_callback() );
                    exit;
                    return;
                }
                $message = data::save_post_to_message($this->model->get_id());
                data::save_uploads($message->model->get_id(), $uploaded_files);
            }else{
                events::add_event(__('This message has been saved already','calisia-ticket-system'), 'warning');
            }
            
        }else{
            events::add_event(__('Unexpected error','calisia-ticket-system'), 'danger');
        }

        wp_redirect( $this->$redirect_url_callback() );
        exit;
    }

    public function get_backend_ticket_url(){
        return menu_page_url( 'calisia-tickets', false ).'&id='.$this->model->get_id();
    }
    
    public function get_frontend_ticket_url(){
        return get_permalink( get_option('woocommerce_myaccount_page_id') ) . 'calisia-show-ticket/?id=' . $this->model->get_id();
    }

    public function get_frontend_new_ticket_url(){
        return get_permalink( get_option('woocommerce_myaccount_page_id') ) . 'calisia-new-ticket/?order_id=' . $args['order_id'];
    }

    public function get_frontend_order_url(){
        $order = wc_get_order($this->model->get_element_id());
        return $order->get_view_order_url();
    }
}