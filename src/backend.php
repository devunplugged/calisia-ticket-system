<?php
namespace calisia_ticket_system;

require_once CALISIA_TICKET_SYSTEM_ROOT . '/src/elements/controls.php';
require_once CALISIA_TICKET_SYSTEM_ROOT . '/src/elements/panels.php';
require_once CALISIA_TICKET_SYSTEM_ROOT . '/src/elements/raw.php';

class backend{
    public static function my_admin_menu() {
        add_menu_page(
            __( 'Calisia Tickets', 'my-textdomain' ),
            __( 'Tickets', 'my-textdomain' ),
            'manage_options',
            'calisia-tickets',
            'calisia_ticket_system\backend::my_admin_page_contents',
            'dashicons-tickets-alt',
            3
        );
    }

    public static function update_ticket_status(){
        $ticket = new ticket($_GET['id']);
   /*     if(!wp_verify_nonce( $_POST['calisia_nonce'], 'calisia-ticket-status' . $_POST['ticket_id'] )){
            events::add_event(__('Unexpected error','calisia-ticket-system'), 'danger');
        }*/

        $ticket->set_status($_POST['calisia_ticket_system_status']);
        $ticket->update();

        wp_redirect( $ticket->get_backend_ticket_url() );
        exit;
    }

    public static function save_forms(){
        if(isset($_POST['calisia_ticket_reply'])){
            //self::save_reply2();
            $ticket = new ticket($_GET['id']);
            $ticket->save_reply('get_backend_ticket_url');
        }
        if(isset($_POST['calisia_ticket_status'])){
            self::update_ticket_status();
        }
    }

    public static function single_ticket(){
        events::show_events();

        $ticket = new ticket((int)$_GET['id']);
        $ticket->mark_ticket_seen();
        $ticket->mark_messages_seen();

        $order = wc_get_order($ticket->get_element_id());
        
        //$products = array();
        //$product_factory = new \WC_Product_Factory();
        //$items = $order->get_items();
        //foreach($order->get_items() as $key => $item){
        //    $products[$key] = $product_factory->get_product($item->get_data()['product_id']);
        //}

        $messages = '';
        foreach($ticket->get_conversation() as $message){
            $messages .= elements\raw::ticket_message($message);
        }
        
        renderer::render(
            'containers/backend-settings-container',
            array(
                'title-bar' => elements\panels::ticket_title_bar($ticket->get_id(), $ticket->get_status()),
                'conversation' => elements\panels::ticket_conversation($messages, $ticket->get_id()),
                'user' => elements\panels::user_info($ticket->get_user_id()),
                'order' => elements\panels::ticket_order_details($order),
                //'products' => $products,
                'other_tickets_table' => elements\panels::user_tickets_table($ticket->get_user_id())
            )
        );
    }



    public static function my_admin_page_contents() {
        if(isset($_GET['id'])){
            self::single_ticket();
        }else{
            echo elements\panels::user_tickets_table();
        }
    }
}