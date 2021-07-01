<?php
namespace calisia_ticket_system;

if ( ! defined( 'ABSPATH' ) ) {
	exit();
}

class renderer{
    public static function render($template, $args = array(), $render = true){
        if(!$render)
            ob_start();
        
        include  CALISIA_TICKET_SYSTEM_ROOT . '/templates/'.$template.'.php';
        
        if(!$render){
            $content = ob_get_contents();
            ob_end_clean();
            return $content;
        }
    }

 
}