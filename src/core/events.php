<?php
namespace calisia_ticket_system;

if ( ! defined( 'ABSPATH' ) ) {
	exit();
}

class events{

    private static $events_array = array();

    public static function add_event($msg, $type){
        //$_SESSION['calisia-events'][] = array('msg' => $msg, 'type' => $type);
        $events = get_user_meta(get_current_user_id(), 'calisia-events', true );
      
        $current_events = array();
        if(is_array($events)){
            foreach($events as $event){
                $current_events[] = $event;
            }
        }
        $current_events[] = array('msg' => $msg, 'type' => $type);
        update_user_meta(get_current_user_id(), 'calisia-events', $current_events);
    }

    public static function get_events(){
        /*if(isset($_SESSION['calisia-events']))
            return $_SESSION['calisia-events'];
        return array();*/
        if(is_array(get_user_meta(get_current_user_id(), 'calisia-events', true )))
            return get_user_meta(get_current_user_id(), 'calisia-events', true );
        return array();
    }

    public static function clear_events(){
        //$_SESSION['calisia-events'] = array();
        delete_user_meta( get_current_user_id(), 'calisia-events');
    }

    public static function show_event($event){
        switch($event['type']){
            case 'success': renderer::render('alerts/alert-success', array('msg' => $event['msg'])); break;
            case 'warning': renderer::render('alerts/alert-warning', array('msg' => $event['msg'])); break;
            case 'danger': renderer::render('alerts/alert-danger', array('msg' => $event['msg'])); break;
            default: renderer::render('alerts/alert-info', array('msg' => $event['msg']));
        }
    }

    public static function show_events(){
        if(count(self::get_events())){
            foreach(self::get_events() as $event){
                self::show_event($event);
            }
        }
        self::clear_events();
    }
}
