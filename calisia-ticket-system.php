<?php
/**
 * Plugin Name: calisia-ticket-system
 * Author: Tomasz Boroń
 * Text Domain: calisia-ticket-system
 * Domain Path: /languages
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit();
}

define('CALISIA_TICKET_SYSTEM_ROOT', __DIR__);
define('CALISIA_TICKET_SYSTEM_URL', plugin_dir_url( __FILE__ ));

require_once CALISIA_TICKET_SYSTEM_ROOT . '/src/core/default-object.php';
require_once CALISIA_TICKET_SYSTEM_ROOT . '/src/core/renderer.php';
require_once CALISIA_TICKET_SYSTEM_ROOT . '/src/core/install.php';
require_once CALISIA_TICKET_SYSTEM_ROOT . '/src/core/loader.php';
require_once CALISIA_TICKET_SYSTEM_ROOT . '/src/core/form-token.php';
require_once CALISIA_TICKET_SYSTEM_ROOT . '/src/core/uploader.php';
require_once CALISIA_TICKET_SYSTEM_ROOT . '/src/core/inputs.php';
require_once CALISIA_TICKET_SYSTEM_ROOT . '/src/core/events.php';
require_once CALISIA_TICKET_SYSTEM_ROOT . '/src/core/options.php';
require_once CALISIA_TICKET_SYSTEM_ROOT . '/src/core/endpoint.php';
require_once CALISIA_TICKET_SYSTEM_ROOT . '/src/core/translations.php';

require_once CALISIA_TICKET_SYSTEM_ROOT . '/src/data.php';
require_once CALISIA_TICKET_SYSTEM_ROOT . '/src/backend.php';
require_once CALISIA_TICKET_SYSTEM_ROOT . '/src/frontend.php';
require_once CALISIA_TICKET_SYSTEM_ROOT . '/src/ticket-table.php';
require_once CALISIA_TICKET_SYSTEM_ROOT . '/src/settings.php';
require_once CALISIA_TICKET_SYSTEM_ROOT . '/src/ajax.php';
require_once CALISIA_TICKET_SYSTEM_ROOT . '/src/kinds/kinds.php';

require_once CALISIA_TICKET_SYSTEM_ROOT . '/src/ticket.php';
require_once CALISIA_TICKET_SYSTEM_ROOT . '/src/message.php';
require_once CALISIA_TICKET_SYSTEM_ROOT . '/src/file.php';






//$calisia_ticket = new calisia_ticket_system\ticket();
//check if update is necessary
add_action( 'plugins_loaded', 'calisia_ticket_system\install::update_check' );

//add my account endpoints and its content
add_action( 'init', 'calisia_ticket_system\frontend::my_account_endpoints' );
add_action( 'woocommerce_account_'.calisia_ticket_system\endpoint::get_new_ticket_endpoint_name().'_endpoint', 'calisia_ticket_system\frontend::new_ticket' );
add_action( 'woocommerce_account_'.calisia_ticket_system\endpoint::get_ticket_endpoint_name().'_endpoint', 'calisia_ticket_system\frontend::ticket' );

//show order tickets
add_action( 'woocommerce_order_details_after_customer_details', 'calisia_ticket_system\frontend::order_tickets', 20, 1);

//add admin menu page
add_action( 'admin_menu', 'calisia_ticket_system\backend::my_admin_menu', 1 );

//load css and js files in backend (admin)
add_action('admin_enqueue_scripts', 'calisia_ticket_system\loader::load_css', 20);
add_action('admin_enqueue_scripts', 'calisia_ticket_system\loader::load_js', 20);

//load css and js files in frontend
add_action('wp_enqueue_scripts', 'calisia_ticket_system\loader::load_frontend_css', 19);


//save forms and redirect
add_action( 'template_redirect', 'calisia_ticket_system\frontend::save_forms' );
add_action( 'admin_init', 'calisia_ticket_system\backend::save_forms' );

//settings page 
add_action( 'admin_menu', 'calisia_ticket_system\settings::add_settings_page' );
add_action( 'admin_init', 'calisia_ticket_system\settings::register_settings' );

//my-account endpoint
add_filter ( 'woocommerce_account_menu_items', 'calisia_ticket_system\endpoint::menu_link', 40 );
add_action( 'init', 'calisia_ticket_system\endpoint::add_endpoint' );
add_action( 'woocommerce_account_'.calisia_ticket_system\endpoint::get_tickets_endpoint_name().'_endpoint', 'calisia_ticket_system\endpoint::endpoint_content' );
add_action( 'init', 'calisia_ticket_system\install::flush_permalinks', 20);

//ajax
add_action( "wp_ajax_calisia_ticket_system_search", 'calisia_ticket_system\ajax::search' ); //ajax call endpoint
add_action( "wp_ajax_calisia_ticket_system_unread_count", 'calisia_ticket_system\ajax::count_unread_messages' ); //ajax call endpoint

//load plugin textdomain
add_action( 'init', 'calisia_ticket_system\translations::load_textdomain' );

//add metabox to order edit page; display order tickets table
add_action( 'add_meta_boxes_shop_order', 'calisia_ticket_system\backend::add_order_meta_boxes' );

//detect change in my account endpoint setting na schedule permalinks flush
add_filter( 'pre_update_option_calisia_ticket_system_plugin_options', 'calisia_ticket_system\options::detect_change_in_my_acc_endpoint', 10, 2 );
/*
add_action( 'wp_footer', 'xzc231' );
function xzc231(){
            echo plugin_dir_path( dirname( __FILE__ ) ) . 'woocommerce/includes/emails/class-wc-email.php';
            require_once plugin_dir_path( dirname( __FILE__ ) ) . 'woocommerce/includes/emails/class-wc-email.php';
            require_once CALISIA_TICKET_SYSTEM_ROOT . '/src/email-schedule.php';
            $email_schedule = new calisia_ticket_system\email_schedule();
            $WC_Email = new WC_Email();
            echo $email_schedule->test();
            echo "//////////////////////////////////////////////////////////////////////////";
            echo $WC_Email->format_string($email_schedule->test());

    
}*/
