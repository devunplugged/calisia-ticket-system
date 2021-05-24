<?php
namespace calisia_ticket_system;

class file extends default_object{
    protected $id;
    protected $message_id;
    protected $file_name;
    protected $file_path;
    protected $added;
    protected $added_by;

    public function set_id($id){
        $this->id = $id;
    }
    public function get_id(){
        return $this->id;
    }
    public function set_message_id($message_id){
        $this->message_id = $message_id;
    }
    public function set_file_name($file_name){
        $this->file_name = $file_name;
    }
    public function set_file_path($file_path){
        $this->file_path = $file_path;
    }
    public function set_added($added){
        $this->added = $added;
    }
    public function set_added_by($added_by){
        $this->added_by = $added_by;
    }

    public function save(){
        global $wpdb;

        $wpdb->insert( 
            $wpdb->prefix . 'calisia_ticket_conversation_files', 
            array( 
                'message_id' => $this->message_id,
                'file_name' => $this->file_name,
                'file_path' => $this->file_path,
                'added' => $this->added, 
                'added_by' => $this->added_by
            ) 
        );
        $this->id = $wpdb->insert_id;
    }
}