<?php
namespace calisia_ticket_system;

if ( ! defined( 'ABSPATH' ) ) {
	exit();
}

require_once CALISIA_TICKET_SYSTEM_ROOT . '/src/elements/controls.php';
require_once CALISIA_TICKET_SYSTEM_ROOT . '/src/elements/panels.php';
require_once CALISIA_TICKET_SYSTEM_ROOT . '/src/elements/raw.php';


class backend{
    public static function my_admin_menu() {
        
        add_menu_page(
            __( 'Calisia Tickets', 'calisia-ticket-system' ),
            __( 'Tickets', 'calisia-ticket-system' ) . ' <span class="ticket-messages-count update-plugins count-'.data::get_number_of_all_unread_messages().'"><span class="plugin-count">'.data::get_number_of_all_unread_messages().'</span></span>',
            'manage_woocommerce',
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

        $ticket->get_model()->set_status($_POST['calisia_ticket_system_status']);
        $ticket->get_model()->update();

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
        if(isset($_POST['calisia_ticket_new'])){
            $ticket = new ticket();
            $ticket->save_ticket('get_backend_ticket_url');
        }
    }

    public static function single_ticket(){
      /*  $mail_header_template_path = options::get_option_value('email_header_path') ? options::get_option_value('email_header_path') : '/wp-content/plugins/woocommerce/templates/emails/email-header.php';
        $mail_footer_template_path = options::get_option_value('email_footer_path') ? options::get_option_value('email_footer_path') : '/wp-content/plugins/woocommerce/templates/emails/email-footer.php';
        $email_heading = 'booom';

        ob_start();
        include ABSPATH . $mail_header_template_path;
        $message_header = ob_get_contents();
        ob_end_clean();

        ob_start();
        include ABSPATH . $mail_footer_template_path;
        $message_footer .= ob_get_contents();
        ob_end_clean();
        echo $message_header;
        echo "tresc";
        echo $message_footer;*/

        events::show_events();

        $ticket = new ticket((int)$_GET['id']);
        $ticket->mark_ticket_seen();
        $ticket->mark_messages_seen();

        

        $messages = '';
        foreach($ticket->get_conversation() as $message){
            $messages .= elements\raw::ticket_message($message, $ticket);
        }

        $params = array();
        $params['title-bar'] = elements\panels::ticket_title_bar($ticket->get_model()->get_id(), $ticket->get_model()->get_status());
        $params['conversation'] = elements\panels::ticket_conversation($messages, $ticket);
        $params['user'] = elements\panels::user_info($ticket->get_model()->get_user_id());
        $params['other_tickets_table'] = elements\panels::user_tickets_table($ticket->get_model()->get_user_id());

        if($ticket->get_model()->get_kind() == 'order'){
            $order = wc_get_order($ticket->get_model()->get_element_id());
            $params['order'] = elements\panels::ticket_order_details($order);
        }
        
        renderer::render('containers/backend-settings-container', $params);
    }

    public static function new_ticket(){
        renderer::render('elements/backend-title', array('title' => __('New Ticket','calisia-ticket-system')));
        events::show_events();
        echo elements\panels::new_ticket();

    }

    public static function my_admin_page_contents() {
        if(isset($_GET['id'])){
            self::single_ticket();
        }elseif(isset($_GET['new'])){
            self::new_ticket();
        }else{
            renderer::render(
                'elements/backend-title',
                array(
                    'title' => __('Tickets','calisia-ticket-system'), 
                    'button' => renderer::render('tickets/buttons/backend-new-ticket-button', array(), false)
                )
            );
            echo elements\panels::user_tickets_table();
        }
    }

    public static function add_order_meta_boxes($post) {
        add_meta_box( 'calisia-order-tickets-metabox', __('Order tickets','calisia-ticket-system'), 'calisia_ticket_system\backend::order_tickets_metabox_content', 'shop_order', 'normal', 'core' );
    }

    public static function order_tickets_metabox_content($post) {
        $tickets = data::get_order_tickets($post->ID);
        $order = wc_get_order($post->ID);

        $ticket_list = '';
        foreach($tickets as $ticket){
            $ticket_list .= renderer::render('tickets/lists/backend-tickets-list-element',array('ticket' => $ticket, 'order' => $order, 'unread' => data::get_number_of_unread_messages($ticket->get_model()->get_id())), false);
        }

        renderer::render(
            'tickets/containers/backend-new-ticket-button-container',
            array(
                'button' => renderer::render(
                    'tickets/buttons/backend-new-ticket-button',
                    array(
                        'user_id' => $order->get_user_id(),
                        'kind' => 'order',
                        'element_id' => $post->ID
                    ),
                    false
                )
            )
        );
        

        renderer::render(
            'tickets/lists/ticket-list-container',
            array(
                'title_bar' => renderer::render('tickets/bars/ticket-list-title-bar', array('title' => __('Order tickets', 'calisia-ticket-system')), false),
                'ticket_list' => $ticket_list
            )
        );
    }
}