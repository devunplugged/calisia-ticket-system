<?php
namespace calisia_ticket_system;

require_once CALISIA_TICKET_SYSTEM_ROOT . '/src/intarface/kind.php';

class other implements kind{
    public function exists($id){
        return true;
    }

    public function can_open($user_id, $element_id){
        return true;
    }
}