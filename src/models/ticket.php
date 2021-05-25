<?php
namespace calisia_ticket_system\models;

require_once CALISIA_TICKET_SYSTEM_ROOT . '/src/core/model.php';

class ticket extends model{
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
}