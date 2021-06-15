<?php
namespace calisia_ticket_system\models;

require_once CALISIA_TICKET_SYSTEM_ROOT . '/src/core/model.php';

class email_schedule extends model{
    protected $id;
    protected $message_id;
    protected $date_added = '0000-00-00 00:00:00';
    protected $date_sent = '0000-00-00 00:00:00';
    protected $send_to;


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
    public function set_date_added($date_added){
        $this->date_added = $date_added;
    }
    public function get_date_added(){
        return $this->date_added;
    }
    public function set_date_sent($date_sent){
        $this->date_sent = $date_sent;
    }
    public function get_date_sent(){
        return $this->date_sent;
    }
    public function set_send_to($send_to){
        $this->send_to = $send_to;
    }
    public function get_send_to(){
        return $this->send_to;
    }
}