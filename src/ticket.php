<?php
namespace calisia_ticket_system;

require_once CALISIA_TICKET_SYSTEM_ROOT . '/src/core/default-object.php';

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

    public function get_kind(){
        return $this->kind;
    }

    public function get_added(){
        return $this->added;
    }

    public function get_user_id(){
        return $this->user_id;
    }

    public function get_added_by(){
        return $this->added_by;
    }

    public function get_element_id(){
        return $this->element_id;
    }

    public function get_seen(){
        return $this->seen;
    }

    public function get_status(){
        return $this->status;
    }

    public function get_conversation(){
        return $this->conversation;
    }

    function __construct($ticket_id = 0){
        if($ticket_id === 0)
            return;

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
}