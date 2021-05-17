<?php
/**
 * Plugin Name: calisia-ticket-system
 */

require_once __DIR__ . '/src/renderer.php';
require_once __DIR__ . '/src/install.php';
require_once __DIR__ . '/src/data.php';
require_once __DIR__ . '/src/ticket.php';
require_once __DIR__ . '/src/backend.php';
require_once __DIR__ . '/src/frontend.php';

//$calisia_ticket = new calisia_ticket_system\ticket();
//check if update is necessary
add_action( 'plugins_loaded', 'calisia_ticket_system\install::update_check' );

//add my account endpoints and thair content
add_action( 'init', 'calisia_ticket_system\frontend::my_account_endpoints' );
add_action( 'woocommerce_account_calisia-new-ticket_endpoint', 'calisia_ticket_system\frontend::new_ticket' );
add_action( 'woocommerce_account_calisia-show-ticket_endpoint', 'calisia_ticket_system\frontend::ticket' );

//show order tickets
add_action( 'woocommerce_order_details_after_customer_details', 'calisia_ticket_system\frontend::order_tickets', 20, 1);

//add admin menu page
add_action( 'admin_menu', 'calisia_ticket_system\backend::my_admin_menu' );

