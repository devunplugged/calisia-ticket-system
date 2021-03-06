<?php
namespace calisia_ticket_system;

if ( ! defined( 'ABSPATH' ) ) {
	exit();
}

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
        $this->model->db_fill();  
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
        return default_object::get_models($results, 'calisia_ticket_system\message');
    }

    public function user_has_access($user_id){
        if($this->model->get_user_id() == $user_id)
            return true;
        return false;
    }

    public function mark_ticket_seen(){
        $this->model->set_seen(1);
        $this->model->update();
    }

    public function mark_messages_seen(){
        global $wpdb;
        return $wpdb->update( $wpdb->prefix."calisia_ticket_system_message", array( 'seen' => 1 ), array( 'ticket_id' => $this->model->get_id() ), array( '%d' ), array( '%d' ));
    }

    public function mark_messages_customer_seen(){
        global $wpdb;
        return $wpdb->update( $wpdb->prefix."calisia_ticket_system_message", array( 'customer_seen' => 1 ), array( 'ticket_id' => $this->model->get_id() ), array( '%d' ), array( '%d' ));
    }


    public function delete(){
        $this->model->set_deleted(1);
        $this->model->update();
    }

    private function validate_before_ticket_save($redirect_url_callback){
  
        if(!is_user_logged_in()){
            events::add_event(__('You have to be logged in','calisia-ticket-system'), 'danger');
            if($redirect_url_callback == 'get_frontend_ticket_url'){
                $this->redirect_to_new_ticket_form();
            }else{
                wp_redirect( $this->get_backend_new_ticket_url() );
            }
            exit;
        }

        //check if user is valid only when requst is comming from backend
        //every user that is logged in can open new ticket
        if($redirect_url_callback != 'get_frontend_ticket_url'){
            if(!$this->user_validation()){
                wp_redirect( $this->get_backend_new_ticket_url() );
                exit;
            }
        }

        $kind_object = kinds::get($_POST['kind']);
        if(!$kind_object){
            events::add_event(__("Ticket kind doesn't exist.",'calisia-ticket-system'), 'danger');
            if($redirect_url_callback == 'get_frontend_ticket_url'){
                $this->redirect_to_new_ticket_form();
            }else{
                wp_redirect( $this->get_backend_new_ticket_url() );
            }
            exit;
        }
        if(!$kind_object->exists($_POST['element_id'])){
            events::add_event(__("Element doesn't exist.",'calisia-ticket-system'), 'danger');
            if($redirect_url_callback == 'get_frontend_ticket_url'){
                $this->redirect_to_new_ticket_form();
            }else{
                wp_redirect( $this->get_backend_new_ticket_url() );
            }
            exit;
        }
        if(!$kind_object->can_open($_POST['user_id'], $_POST['element_id'])){
            events::add_event(__("Cannot open ticket for this user and element.",'calisia-ticket-system'), 'danger');
            if($redirect_url_callback == 'get_frontend_ticket_url'){
                $this->redirect_to_new_ticket_form();
            }else{
                wp_redirect( $this->get_backend_new_ticket_url() );
            }
            exit;
        }



        if(!isset($_POST['title']) || empty($_POST['title'])){
            events::add_event(__('Ticket title cannot be empty.','calisia-ticket-system'), 'danger');
            if($redirect_url_callback == 'get_frontend_ticket_url'){
                $this->redirect_to_new_ticket_form();
            }else{
                wp_redirect( $this->get_backend_new_ticket_url() );
            }
            exit;
        }

        if(!isset($_POST['msg']) || empty($_POST['msg'])){
            events::add_event(__('Message cannot be empty.','calisia-ticket-system'), 'danger');
            if($redirect_url_callback == 'get_frontend_ticket_url'){
                $this->redirect_to_new_ticket_form();
            }else{
                wp_redirect( $this->get_backend_new_ticket_url() );
            }
            exit;
        }

        if(!$this->basic_form_validation($_POST['calisia_nonce'], 'calisia-ticket-new', $_POST['calisia_form_token'], '')){
            if($redirect_url_callback == 'get_frontend_ticket_url'){
                $this->redirect_to_new_ticket_form();
            }else{
                wp_redirect( $this->get_backend_new_ticket_url() );
            }
            exit;
        }
    }

    public function save_ticket($redirect_url_callback = 'get_frontend_ticket_url'){
 
        $this->validate_before_ticket_save($redirect_url_callback);

        try{
            $uploaded_files = uploader::save_uploaded_files();
        }catch(\Exception $e) {
            events::add_event($e->getMessage(), 'danger');
            if($redirect_url_callback == 'get_frontend_ticket_url'){
                $this->redirect_to_new_ticket_form();
            }else{
                wp_redirect( $this->get_backend_new_ticket_url() );
            }
            exit;
        }

        $this->model->fill(data::save_post_to_ticket()->get_model());
        $message = data::save_post_to_message($this->model->get_id());
        data::save_uploads($message->get_model()->get_id(), $uploaded_files);
        $this->model->set_id($this->model->get_id());

        require_once CALISIA_TICKET_SYSTEM_ROOT . '/src/email-message.php';
        $email = new email_message();
        //require_once CALISIA_TICKET_SYSTEM_ROOT . '/src/email-schedule.php';
        //$email_schedule = new email_schedule();
        //send email when someone else than user resopnds, otherwise send notification to support
        if($this->model->get_user_id() != get_current_user_id()){
            $email->send_notification_to_client($message);
            //$email_schedule->schedule($message->get_model()->get_id(), 'customer');
            $this->save_support_last_message_date();
        }else{
            $email->send_notification_to_support($message);
            //$email_schedule->schedule($message->get_model()->get_id(), 'support');
            $this->save_customer_last_message_date();
        }

        wp_redirect( $this->$redirect_url_callback() );
        exit;
    }

    public function validate_before_reply_save($redirect_url_callback){
        if(!is_user_logged_in()){
            events::add_event(__('You have to be logged in','calisia-ticket-system'), 'danger');
            if($redirect_url_callback == 'get_frontend_ticket_url'){
                wp_redirect( $this->get_frontend_ticket_url() );
            }else{
                wp_redirect( $this->get_backend_ticket_url() );
            }
            exit;
        }

        if(!$this->user_validation()){
            wp_redirect( $this->$redirect_url_callback() );
            exit;
        }

        if($this->model->get_deleted() == 1){
            events::add_event(__('You cannot reply to deleted tickets.','calisia-ticket-system'), 'danger');
            if($redirect_url_callback == 'get_frontend_ticket_url'){
                wp_redirect( $this->get_frontend_ticket_url() );
            }else{
                wp_redirect( $this->get_backend_ticket_url() );
            }
            exit;
        }

        if(!isset($_POST['msg']) || empty($_POST['msg'])){
            events::add_event(__('Message cannot be empty.','calisia-ticket-system'), 'danger');
            if($redirect_url_callback == 'get_frontend_ticket_url'){
                wp_redirect( $this->get_frontend_ticket_url() );
            }else{
                wp_redirect( $this->get_backend_ticket_url() );
            }
            exit;
        }

        if(!$this->basic_form_validation($_POST['calisia_nonce'], 'calisia-ticket-reply-'.$this->model->get_id(), $_POST['calisia_form_token'], '')){
            wp_redirect( $this->$redirect_url_callback() );
            exit;
        }
    }

    public function save_reply($redirect_url_callback = 'get_frontend_ticket_url'){
        
        $this->validate_before_reply_save($redirect_url_callback);
        try{
            $uploaded_files = uploader::save_uploaded_files();
        }catch(\Exception $e) {
            events::add_event($e->getMessage(), 'danger');
            wp_redirect( $this->$redirect_url_callback() );
            exit;
            return;
        }
        $message = data::save_post_to_message($this->model->get_id());
        data::save_uploads($message->get_model()->get_id(), $uploaded_files);

        require_once CALISIA_TICKET_SYSTEM_ROOT . '/src/email-message.php';
        $email = new email_message();
        //require_once CALISIA_TICKET_SYSTEM_ROOT . '/src/email-schedule.php';
        //$email_schedule = new email_schedule();
        //send email when someone else than user resopnds, otherwise send notification to support
        if($this->get_model()->get_user_id() != get_current_user_id()){ 
            //support responded
            $email->send_notification_to_client($message);
            //$email_schedule->schedule($message->get_model()->get_id(), 'customer');
            $this->save_support_last_message_date($this);
        }else{
            //client responded
            $email->send_notification_to_support($message);
            //$email_schedule->schedule($message->get_model()->get_id(), 'support');
            if($this->model->get_status() == 'awaitingreply'){
                $this->model->set_status('opened');
                $this->model->update();
            }
            $this->save_customer_last_message_date($this);
        }

        wp_redirect( $this->$redirect_url_callback() );
        exit;
    }

    public function save_support_last_message_date(){
        $this->model->set_last_support_reply(current_time('mysql'));
        $this->model->update();
    }

    public function save_customer_last_message_date(){
        $this->model->set_last_customer_reply(current_time('mysql'));
        $this->model->update();
    }

    public function close($redirect_url_callback = 'get_frontend_ticket_url'){
        if(!$this->user_validation()){
            wp_redirect( $this->$redirect_url_callback() );
            exit;
        }

        if(!$this->basic_form_validation($_POST['calisia_nonce'], 'calisia-ticket-close-ticket-'.$this->model->get_id(), $_POST['calisia_form_token'], 'close-ticket')){
            wp_redirect( $this->$redirect_url_callback() );
            exit;
        }

        $this->model->set_status('completed');
        $this->model->update();

        wp_redirect( $this->$redirect_url_callback() );
        exit;
    }

    public function open($redirect_url_callback = 'get_frontend_ticket_url'){
        if(!$this->user_validation()){
            wp_redirect( $this->$redirect_url_callback() );
            exit;
        }

        if(!$this->basic_form_validation($_POST['calisia_nonce'], 'calisia-ticket-open-ticket-'.$this->model->get_id(), $_POST['calisia_form_token'], 'open-ticket')){
            wp_redirect( $this->$redirect_url_callback() );
            exit;
        }

        $this->model->set_status('opened');
        $this->model->update();

        wp_redirect( $this->$redirect_url_callback() );
        exit;
    }

    private function basic_form_validation($nonce_value, $nonce_name, $token_value, $token_name){
        $ok = true;
        if(wp_verify_nonce( $nonce_value, $nonce_name )){
            if(!Form_Token::check_token($token_value, $token_name)){
                events::add_event(__('This form has been saved already','calisia-ticket-system'), 'warning');
                $ok = false;
            }  
        }else{
            events::add_event(__('Unexpected error','calisia-ticket-system'), 'danger');
            $ok = false;
        }
        return $ok;
    }

    private function user_validation(){
        
        if($this->user_has_access(get_current_user_id())){
            return true;
        }

        $user = wp_get_current_user();
        if ( array_intersect( options::get_replay_capable_roles(), $user->roles ) ) {
            return true;
        }

        events::add_event(__('You have no access to this action','calisia-ticket-system'), 'danger');
        return false;
    }

    public function get_backend_ticket_url(){
        //return menu_page_url( 'calisia-tickets', false ).'&id='.$this->model->get_id();
        return admin_url( 'admin.php?page=calisia-tickets' ) . '&id='.$this->model->get_id();
    }

    public function get_backend_new_ticket_url(){
        $link = menu_page_url( 'calisia-tickets', false ).'&new=ticket';
        $link .= isset($_GET['user_id']) ? '&user_id=' . $_GET['user_id'] : '';
        $link .= isset($_GET['kind']) ? '&kind=' . $_GET['kind'] : '';
        $link .= isset($_GET['element_id']) ? '&element_id=' . $_GET['element_id'] : '';
        return $link;
    }
    
    public function get_frontend_ticket_url(){
        return get_permalink( get_option('woocommerce_myaccount_page_id') ) . endpoint::get_ticket_endpoint_name() . '/?id=' . $this->model->get_id();
    }

    public function get_frontend_new_ticket_url($kind = '', $element_id = ''){
        if($attr)
            return get_permalink( get_option('woocommerce_myaccount_page_id') ) . endpoint::get_new_ticket_endpoint_name() .'/?kind='.$kind.'&element_id'. $element_id;
        return get_permalink( get_option('woocommerce_myaccount_page_id') ) . endpoint::get_new_ticket_endpoint_name() .'/';
    }

    public function redirect_to_new_ticket_form(){
        if(isset($_GET['element_id'])){
            wp_redirect( $this->get_frontend_new_ticket_url($_GET['kind'], $_GET['element_id']));
        }else{
            wp_redirect( $this->get_frontend_new_ticket_url());
        }
    }

    public function get_frontend_order_url(){
        $order = wc_get_order($this->model->get_element_id());
        return $order->get_view_order_url();
    }

    
}