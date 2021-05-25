<?php
namespace calisia_ticket_system;

require_once CALISIA_TICKET_SYSTEM_ROOT . '/src/models/file.php';

class file{

    protected $model;

    public function get_model(){
        return $this->model;
    }

    function __construct(){
        $this->model = new models\file();
    }

}