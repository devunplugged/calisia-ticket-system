<?php
namespace calisia_ticket_system;

class translations{
    /**
     * Load plugin textdomain.
     */
    public static function load_textdomain() {
        load_plugin_textdomain( 'calisia-ticket-system', false, 'calisia-ticket-system/languages' );
    }
}