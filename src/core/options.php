<?php
namespace calisia_ticket_system;

class options{
    public static function option_on($option_name){
        $options = get_option( 'calisia_ticket_system_plugin_options' );
        if(!isset($options[$option_name]) || !$options[$option_name])
            return 0;
        return 1;
    }
}