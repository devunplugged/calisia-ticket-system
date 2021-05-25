<?php
namespace calisia_ticket_system;

require_once CALISIA_TICKET_SYSTEM_ROOT . '/src/models/message.php';

class message{
    protected $model;

    public function get_model(){
        return $this->model;
    }

    function __construct(){
        $this->model = new models\message();
    }

}