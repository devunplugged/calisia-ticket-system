<?php
namespace calisia_ticket_system;

class message extends default_object{
    protected $id;
    protected $ticket_id;
    protected $added;
    protected $text;
    protected $added_by;

    public function set_id($id){
        $this->id = $id;
    }
    public function get_id(){
        return $this->id;
    }
    public function set_ticket_id($ticket_id){
        $this->ticket_id = $ticket_id;
    }
    public function set_added($added){
        $this->added = $added;
    }
    public function set_text($text){
        $this->text = wp_kses_post( stripslashes($text));
    }
    public function set_added_by($added_by){
        $this->added_by = $added_by;
    }


    public function save(){
        global $wpdb;
        $result = $wpdb->insert( 
            $wpdb->prefix . 'calisia_ticket_conversation', 
            array( 
                'ticket_id' => $this->ticket_id,
                'added' => $this->added, 
                'text' => $this->text, 
                'added_by' => $this->added_by
            ) 
        );
        $this->id = $wpdb->insert_id;
    }
}