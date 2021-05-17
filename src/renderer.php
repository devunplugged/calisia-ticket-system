<?php
namespace calisia_ticket_system;

class renderer{
    public static function render($template, $args = array(), $render = true){
        if(!$render)
            ob_start();
        
        include  __DIR__ . '/../templates/'.$template.'.php';
        
        if(!$render){
            $content = ob_get_contents();
            ob_end_clean();
            return $content;
        }
    }

 
}