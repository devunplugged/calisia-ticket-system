<?php
namespace calisia_ticket_system\models;

if ( ! defined( 'ABSPATH' ) ) {
	exit();
}

require_once CALISIA_TICKET_SYSTEM_ROOT . '/src/core/model.php';

class message extends model{
    protected $id;
    protected $ticket_id;
    protected $added;
    protected $text;
    protected $added_by;
    protected $seen = 0;
    protected $customer_seen = 0;

    public function set_id($id){
        $this->id = $id;
    }
    public function get_id(){
        return $this->id;
    }
    public function set_ticket_id($ticket_id){
        $this->ticket_id = $ticket_id;
    }
    public function get_ticket_id(){
        return $this->ticket_id;
    }
    public function set_added($added){
        $this->added = $added;
    }
    public function get_added(){
        return $this->added;
    }
    public function set_text($text){
        $this->text = wp_kses_post( stripslashes($text));
    }
    public function get_text(){
        return $this->text;
    }
    public function set_added_by($added_by){
        $this->added_by = $added_by;
    }
    public function get_added_by(){
        return $this->added_by;
    }
    public function set_seen($seen){
        $this->seen = $seen;
    }
    public function get_seen(){
        return $this->seen;
    }
    public function set_customer_seen($customer_seen){
        $this->customer_seen = $customer_seen;
    }
    public function get_customer_seen(){
        return $this->customer_seen;
    }
}