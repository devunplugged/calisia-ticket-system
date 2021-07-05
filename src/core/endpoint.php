<?php 
namespace calisia_ticket_system;

if ( ! defined( 'ABSPATH' ) ) {
	exit();
}

class endpoint{
    public static function get_tickets_endpoint_name(){
        if($endpoint = options::get_option_value('my_acc_endpoint'))
            return $endpoint;
        return 'calisia-tickets';
    }
    public static function get_ticket_endpoint_name(){
        if($endpoint = options::get_option_value('ticket_endpoint'))
            return $endpoint;
        return 'calisia-show-ticket';
    }
    public static function get_new_ticket_endpoint_name(){
        if($endpoint = options::get_option_value('new_ticket_endpoint'))
            return $endpoint;
        return 'calisia-new-ticket';
    }

    public static function menu_link( $menu_links ){
        $menu_links = array_slice( $menu_links, 0, 5, true ) 
        + array( self::get_tickets_endpoint_name() => __('Tickets','calisia-ticket-system') )
        + array_slice( $menu_links, 5, NULL, true );
     
        return $menu_links;
    }

    public static function add_endpoint() {
        add_rewrite_endpoint( self::get_tickets_endpoint_name(), EP_PAGES );
    }

    public static function endpoint_content() {
        frontend::my_tickets();
    }
}