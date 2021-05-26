<?php 
namespace calisia_ticket_system;

class endpoint{
    public static function menu_link( $menu_links ){
        $menu_links = array_slice( $menu_links, 0, 5, true ) 
        + array( 'calisia-tickets' => __('Tickets','calisia-ticket-system') )
        + array_slice( $menu_links, 5, NULL, true );
     
        return $menu_links;
    }

    public static function add_endpoint() {
        add_rewrite_endpoint( 'calisia-tickets', EP_PAGES );
    }

    public static function endpoint_content() {
        frontend::my_tickets();
    }
}