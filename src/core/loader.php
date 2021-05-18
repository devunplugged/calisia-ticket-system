<?php
namespace calisia_ticket_system;

class loader{
    public static function load_css(){
        wp_enqueue_style('calisia_ticket_system_css', CALISIA_TICKET_SYSTEM_URL . 'css/calisia_ticket_system.css');
    }
/*
    public static function load_js(){
        wp_enqueue_script('calisia_product_notes_js', plugins_url('../js/calisia_product_notes.js',__FILE__ ));
    }*/
}