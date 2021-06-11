<?php
namespace calisia_ticket_system;

class translations{
    /**
     * Load plugin textdomain.
     */
    public static function load_textdomain() {
        load_plugin_textdomain( 'calisia-ticket-system', false, 'calisia-ticket-system/languages' );
    }

    public static function ticket_kind($kind){
        $kind_name = '';
        switch($kind){
            case 'order': $kind_name = __('Order','calisia-ticket-system'); break;
            case 'other': $kind_name = __('Other','calisia-ticket-system'); break;
        }
        return $kind_name;
    }

    public static function ticket_status($status){
        $status_name = '';
        switch($status){
            case 'opened': $status_name = __('Opened','calisia-ticket-system'); break;
            case 'onhold': $status_name = __('On hold','calisia-ticket-system'); break;
            case 'awaitingreply': $status_name = __('Awaiting Reply','calisia-ticket-system'); break;
            case 'completed': $status_name = __('Completed','calisia-ticket-system'); break;
        }
        return $status_name;
    }
}