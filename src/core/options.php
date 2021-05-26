<?php
namespace calisia_ticket_system;

class options{
    public static function option_on($option_name){
        $options = get_option( 'calisia_ticket_system_plugin_options' );
        if(!isset($options[$option_name]) || !$options[$option_name])
            return 0;
        return 1;
    }
    public static function get_option_value($option_name){
        $options = get_option( 'calisia_ticket_system_plugin_options' );
        if(isset($options[$option_name]) && $options[$option_name])
            return $options[$option_name];
        return '';
    }

    public static function get_replay_capable_roles(){
        $reply_roles = explode(',', self::get_option_value('reply_roles'));

        if(count($reply_roles) == 0)
            return array('administrator','shop_manager');

        if(count($reply_roles) == 1 && $reply_roles[0] == '')
            return array('administrator','shop_manager');

        $reply_roles_clean = array();
        foreach($reply_roles as $role){
            $reply_roles_clean[] = trim($role);
        }

        return $reply_roles_clean;
    }
}