<?php
namespace calisia_ticket_system;


class kinds{
    public static function get($kind){
        switch($kind){
            case 'other': 
                require_once CALISIA_TICKET_SYSTEM_ROOT . '/src/kinds/other.php';
                return new other(); 
                break;
            case 'order': 
                require_once CALISIA_TICKET_SYSTEM_ROOT . '/src/kinds/order.php';
                return new order(); 
                break;
            default: return false;
        }
    }
}