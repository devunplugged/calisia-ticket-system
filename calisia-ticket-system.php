<?php
/**
 * Plugin Name: calisia-ticket-system
 */
define('CALISIA_TICKET_SYSTEM_ROOT', __DIR__);
define('CALISIA_TICKET_SYSTEM_URL', plugin_dir_url( __FILE__ ));

require_once CALISIA_TICKET_SYSTEM_ROOT . '/src/core/renderer.php';
require_once CALISIA_TICKET_SYSTEM_ROOT . '/src/core/install.php';
require_once CALISIA_TICKET_SYSTEM_ROOT . '/src/core/loader.php';
require_once CALISIA_TICKET_SYSTEM_ROOT . '/src/core/ticket-table.php';
require_once CALISIA_TICKET_SYSTEM_ROOT . '/src/core/form-token.php';

require_once CALISIA_TICKET_SYSTEM_ROOT . '/src/data.php';
require_once CALISIA_TICKET_SYSTEM_ROOT . '/src/backend.php';
require_once CALISIA_TICKET_SYSTEM_ROOT . '/src/frontend.php';






//$calisia_ticket = new calisia_ticket_system\ticket();
//check if update is necessary
add_action( 'plugins_loaded', 'calisia_ticket_system\install::update_check' );

//add my account endpoints and its content
add_action( 'init', 'calisia_ticket_system\frontend::my_account_endpoints' );
add_action( 'woocommerce_account_calisia-new-ticket_endpoint', 'calisia_ticket_system\frontend::new_ticket' );
add_action( 'woocommerce_account_calisia-show-ticket_endpoint', 'calisia_ticket_system\frontend::ticket' );

//show order tickets
add_action( 'woocommerce_order_details_after_customer_details', 'calisia_ticket_system\frontend::order_tickets', 20, 1);

//add admin menu page
add_action( 'admin_menu', 'calisia_ticket_system\backend::my_admin_menu' );

//load css and js files in backend (admin)
add_action('admin_enqueue_scripts', 'calisia_ticket_system\loader::load_css');