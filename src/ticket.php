<?php
namespace calisia_ticket_system;



class ticket extends default_object{
    protected $id;
    protected $title;
    protected $kind;
    protected $added;
    protected $user_id;
    protected $added_by;
    protected $element_id;
    protected $seen;
    protected $status;
    protected $deleted;

    private $conversation;

    public function get_id(){
        return $this->id;
    }

    public function set_id($id){
        $this->id = $id;
    }

    public function get_title(){
        return $this->title;
    }

    public function set_title($title){
        $this->title = $title;
    }

    public function get_kind(){
        return $this->kind;
    }

    public function set_kind($kind){
        $this->kind = $kind;
    }

    public function get_added(){
        return $this->added;
    }

    public function set_added($added){
        $this->added = $added;
    }

    public function get_user_id(){
        return $this->user_id;
    }

    public function set_user_id($user_id){
        $this->user_id = $user_id;
    }

    public function get_added_by(){
        return $this->added_by;
    }

    public function set_added_by($added_by){
        $this->added_by = $added_by;
    }

    public function get_element_id(){
        return $this->element_id;
    }

    public function set_element_id($element_id){
        $this->element_id = $element_id;
    }

    public function get_seen(){
        return $this->seen;
    }

    public function set_seen($seen){
        $this->seen = $seen;
    }

    public function get_status(){
        return $this->status;
    }

    public function set_status($status){
        $this->status = $status;
    }

    public function get_deleted(){
        return $this->deleted;
    }

    public function set_deleted($deleted){
        $this->deleted = $deleted;
    }

    public function get_conversation(){
        return $this->conversation;
    }

    public function set_conversation($conversation){
        $this->conversation = $conversation;
    }

    function __construct($ticket_id = 0){
        if($ticket_id === 0)
            return;

        if(!is_numeric($ticket_id))
            return;

        $ticket_id = (int)$ticket_id;

        if(!is_int($ticket_id))
            return;

        $this->id = $ticket_id;
        $this->load_class_values();
    }

    public function load_class_values(){
        $this->db_get_ticket();  
    }
    
    public function db_get_ticket(){
        global $wpdb;
        $result = $wpdb->get_results(
            $wpdb->prepare(
            "SELECT * FROM ".$wpdb->prefix."calisia_ticket WHERE id = %d",
            array(
                $this->id
               )
            )
        );
        $this->fill($result[0]);
        $this->conversation = $this->db_get_conversation();
    }

    private function db_get_conversation(){
        global $wpdb;
        return $wpdb->get_results(
            $wpdb->prepare(
            "SELECT * FROM ".$wpdb->prefix."calisia_ticket_conversation WHERE ticket_id = %d",
            array(
                $this->id
               )
            )
        );
    }

    public function user_has_access($user_id){
        if($this->user_id == $user_id)
            return true;
        return false;
    }

    public function mark_ticket_seen(){
        global $wpdb;

        return $wpdb->update( $wpdb->prefix."calisia_ticket", array( 'seen' => 1 ), array( 'id' => $this->id ), array( '%d' ), array( '%d' ));
    }

    public function mark_messages_seen(){
        global $wpdb;

        return $wpdb->update( $wpdb->prefix."calisia_ticket_conversation", array( 'seen' => 1 ), array( 'ticket_id' => $this->id ), array( '%d' ), array( '%d' ));
    }


    public function save(){
        global $wpdb;
        
        $wpdb->insert( 
            $wpdb->prefix . 'calisia_ticket', 
            array( 
                'title' => $this->title,
                'kind' => $this->kind,
                'added' => $this->added, 
                'user_id' => $this->user_id, 
                'added_by' => $this->added_by,
                'element_id' => $this->element_id
            ) 
        );
        $this->id = $wpdb->insert_id;
        //self::save_message($ticket_id);
    }

    

    public function update(){
        global $wpdb;

        return $wpdb->update( 
            $wpdb->prefix."calisia_ticket", 
            array( 
                'title' => $this->title, 
                'kind' => $this->kind, 
                'added' => $this->added, 
                'user_id' => $this->user_id, 
                'element_id' => $this->element_id, 
                'seen' => $this->seen, 
                'status' => $this->status,
                'deleted' => $this->deleted
            ), 
            array( 'id' => $this->id ), 
            array( '%s', '%s', '%s', '%d', '%d', '%d', '%s' ), 
            array( '%d' )
        );
    }

    public function delete(){
        $this->deleted = 1;
        $this->update();
    }

    public function save_reply($redirect_url_callback = 'get_frontend_ticket_url'){
        if(wp_verify_nonce( $_POST['calisia_nonce'], 'calisia-ticket-reply-' . $this->id )){

            if(Form_Token::check_token($_POST['calisia_form_token'])){
                try{
                    $uploaded_files = uploader::save_uploaded_files();
                }catch(\Exception $e) {
                    events::add_event($e->getMessage(), 'danger');
                    wp_redirect( $this->$redirect_url_callback() );
                    exit;
                    return;
                }
                $message = data::save_post_to_message($this->id);
                data::save_uploads($message->get_id(), $uploaded_files);
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
        return menu_page_url( 'calisia-tickets', false ).'&id='.$this->id;
    }
    
    public function get_frontend_ticket_url(){
        return get_permalink( get_option('woocommerce_myaccount_page_id') ) . 'calisia-show-ticket/?id=' . $this->id;
    }
}