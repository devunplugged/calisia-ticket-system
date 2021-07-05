<?php
namespace calisia_ticket_system;

if ( ! defined( 'ABSPATH' ) ) {
	exit();
}

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

    public static function detect_change_in_my_acc_endpoint($new_value, $old_value){
        if(!isset($new_value['my_acc_endpoint']) && !isset($new_value['ticket_endpoint']) && !isset($new_value['new_ticket_endpoint']))
            return $new_value;

        if(!isset($old_value['my_acc_endpoint'])){
            delete_option('calisia_ticket_system_permalinks_flushed');
        }elseif($new_value['my_acc_endpoint'] != $old_value['my_acc_endpoint']){
            delete_option('calisia_ticket_system_permalinks_flushed');
        }

        if(!isset($old_value['ticket_endpoint'])){
            delete_option('calisia_ticket_system_permalinks_flushed');
        }elseif($new_value['ticket_endpoint'] != $old_value['ticket_endpoint']){
            delete_option('calisia_ticket_system_permalinks_flushed');
        }

        if(!isset($old_value['new_ticket_endpoint'])){
            delete_option('calisia_ticket_system_permalinks_flushed');
        }elseif($new_value['new_ticket_endpoint'] != $old_value['new_ticket_endpoint']){
            delete_option('calisia_ticket_system_permalinks_flushed');
        }

        return $new_value;
    }
}