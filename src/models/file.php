<?php
namespace calisia_ticket_system\models;

if ( ! defined( 'ABSPATH' ) ) {
	exit();
}

require_once CALISIA_TICKET_SYSTEM_ROOT . '/src/core/model.php';

class file extends model{
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
    public function get_message_id(){
        return $this->message_id;
    }
    public function set_file_name($file_name){
        $this->file_name = $file_name;
    }
    public function get_file_name(){
        return $this->file_name;
    }
    public function set_file_path($file_path){
        $this->file_path = $file_path;
    }
    public function get_file_path(){
        return $this->file_path;
    }
    public function set_added($added){
        $this->added = $added;
    }
    public function get_added(){
        return $this->added;
    }
    public function set_added_by($added_by){
        $this->added_by = $added_by;
    }
    public function get_added_by(){
        return $this->added_by;
    }
}