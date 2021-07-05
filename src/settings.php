<?php
namespace calisia_ticket_system;

if ( ! defined( 'ABSPATH' ) ) {
	exit();
}

class settings{
    public static function add_settings_page() {
        add_options_page( 'Example plugin page', __( 'Ticket System', 'calisia-ticket-system' ), 'manage_options', 'calisia-ticket-system', 'calisia_ticket_system\settings::render_plugin_settings_page' );
    }

    public static function render_plugin_settings_page() {
        renderer::render('settings-form');
    }

    public static function register_settings() {
        register_setting( 'calisia-ticket-system-options', 'calisia_ticket_system_plugin_options' ); //, 'dbi_example_plugin_options_validate'

        add_settings_section( 'main_settings', __( 'Main Settings', 'calisia-ticket-system' ), 'calisia_ticket_system\settings::section_text', 'calisia-ticket-system-settings-page' );
        add_settings_field( 'calisia_ticket_system_reply_roles', __('calisia-cutomer-notes', 'calisia-ticket-system'), 'calisia_ticket_system\settings::reply_roles_input', 'calisia-ticket-system-settings-page', 'main_settings' );

        add_settings_section( 'integration_settings', __( 'Integration Settings', 'calisia-ticket-system' ), 'calisia_ticket_system\settings::section_text', 'calisia-ticket-system-settings-page' );
        add_settings_field( 'calisia_ticket_system_customer_notes_integration', __('calisia-cutomer-notes', 'calisia-ticket-system'), 'calisia_ticket_system\settings::customer_notes_integration_input', 'calisia-ticket-system-settings-page', 'integration_settings' );
        
        add_settings_section( 'email_template_settings', __( 'Email template', 'calisia-ticket-system' ), 'calisia_ticket_system\settings::section_text', 'calisia-ticket-system-settings-page' );
        add_settings_field( 'calisia_ticket_system_email_header_path', __('Header', 'calisia-ticket-system'), 'calisia_ticket_system\settings::email_header_template_input', 'calisia-ticket-system-settings-page', 'email_template_settings' );
        add_settings_field( 'calisia_ticket_system_email_footer_path', __('Footer', 'calisia-ticket-system'), 'calisia_ticket_system\settings::email_footer_template_input', 'calisia-ticket-system-settings-page', 'email_template_settings' );
        
        add_settings_section( 'support_settings', __( 'Support', 'calisia-ticket-system' ), 'calisia_ticket_system\settings::section_text', 'calisia-ticket-system-settings-page' );
        add_settings_field( 'calisia_ticket_system_support_email', __('Support e-mail address', 'calisia-ticket-system'), 'calisia_ticket_system\settings::support_email_input', 'calisia-ticket-system-settings-page', 'support_settings' );
        
        add_settings_section( 'my_acc_endpoint_settings', __( 'My Account Endpoint', 'calisia-ticket-system' ), 'calisia_ticket_system\settings::section_text', 'calisia-ticket-system-settings-page' );
        add_settings_field( 'calisia_ticket_system_my_acc_endpoint', __('My Account Page Tickets Endpoint', 'calisia-ticket-system'), 'calisia_ticket_system\settings::my_acc_endpoint_input', 'calisia-ticket-system-settings-page', 'my_acc_endpoint_settings' );
        add_settings_field( 'calisia_ticket_system_ticket_endpoint', __('My Account Page Single Ticket Endpoint', 'calisia-ticket-system'), 'calisia_ticket_system\settings::ticket_endpoint_input', 'calisia-ticket-system-settings-page', 'my_acc_endpoint_settings' );
        add_settings_field( 'calisia_ticket_system_new_ticket_endpoint', __('My Account Page New Ticket Endpoint', 'calisia-ticket-system'), 'calisia_ticket_system\settings::new_ticket_endpoint_input', 'calisia-ticket-system-settings-page', 'my_acc_endpoint_settings' );
    }

    public static function section_text() {
        renderer::render('settings-section-text');
    }
    
    public static function order_notes_input() {
        //$options = get_option( 'calisia_ticket_system_plugin_options' );
        inputs::select(
            array(
                'id' => 'calisia_ticket_system_order_notes',
                'name' => 'calisia_ticket_system_plugin_options[order_notes]',
                'class' => 'select',
                'options' => array(
                    __('On', 'calisia-ticket-system') => 1,
                    __('Off', 'calisia-ticket-system') => 0
                ),
                'value' => options::option_on('order_notes')
            ),
            true
        );
    }

    public static function customer_notes_integration_input() {
        //$options = get_option( 'calisia_ticket_system_plugin_options' );
        inputs::select(
            array(
                'id' => 'calisia_ticket_system_customer_notes_integration',
                'name' => 'calisia_ticket_system_plugin_options[customer_notes_integration]',
                'class' => 'select',
                'options' => array(
                    __('On', 'calisia-ticket-system') => 1,
                    __('Off', 'calisia-ticket-system') => 0
                ),
                'value' => options::option_on('customer_notes_integration')
            ),
            true
        );
    }
    
    public static function reply_roles_input(){
        inputs::input(
            array(
                'id' => 'calisia_ticket_system_reply_roles',
                'name' => 'calisia_ticket_system_plugin_options[reply_roles]',
                'class' => 'select',
                'value' => options::get_option_value('reply_roles'),
                'type' => 'text',
                'label' => __('Role slugs capable of replaying to tickets. If left blank roles "administrator", "shop_manager" are used.', 'calisia-ticket-system')
            ),
            true
        );

        //print_r(options::get_replay_capable_roles());
    }

    public static function email_header_template_input(){
        inputs::input(
            array(
                'id' => 'calisia_ticket_system_email_header_path',
                'name' => 'calisia_ticket_system_plugin_options[email_header_path]',
                'class' => 'select',
                'value' => options::get_option_value('email_header_path'),
                'type' => 'text',
                'label' => __('Path to email header template. If left blank "/wp-content/plugins/woocommerce/templates/emails/email-header.php" is used.', 'calisia-ticket-system')
            ),
            true
        );

        //print_r(options::get_replay_capable_roles());
    }

    public static function email_footer_template_input(){
        inputs::input(
            array(
                'id' => 'calisia_ticket_system_email_footer_path',
                'name' => 'calisia_ticket_system_plugin_options[email_footer_path]',
                'class' => 'select',
                'value' => options::get_option_value('email_footer_path'),
                'type' => 'text',
                'label' => __('Path to email footer template. If left blank "/wp-content/plugins/woocommerce/templates/emails/email-footer.php" is used.', 'calisia-ticket-system')
            ),
            true
        );

        //print_r(options::get_replay_capable_roles());
    }

    public static function support_email_input(){
        inputs::input(
            array(
                'id' => 'calisia_ticket_system_support_email',
                'name' => 'calisia_ticket_system_plugin_options[support_email]',
                'class' => 'select',
                'value' => options::get_option_value('support_email'),
                'type' => 'text',
                'label' => __('Support email address. Used to send notifications about new tickets and replys.', 'calisia-ticket-system')
            ),
            true
        );

        //print_r(options::get_replay_capable_roles());
    }

    public static function my_acc_endpoint_input(){
        inputs::input(
            array(
                'id' => 'calisia_ticket_system_my_acc_endpoint',
                'name' => 'calisia_ticket_system_plugin_options[my_acc_endpoint]',
                'class' => 'select',
                'value' => options::get_option_value('my_acc_endpoint'),
                'type' => 'text',
                'label' => __('Insert slug of my account page endpoint.', 'calisia-ticket-system')
            ),
            true
        );

        //print_r(options::get_replay_capable_roles());
    }
    public static function new_ticket_endpoint_input(){
        inputs::input(
            array(
                'id' => 'calisia_ticket_system_new_ticket_endpoint',
                'name' => 'calisia_ticket_system_plugin_options[new_ticket_endpoint]',
                'class' => 'select',
                'value' => options::get_option_value('new_ticket_endpoint'),
                'type' => 'text',
                'label' => __('Insert slug of my account page endpoint.', 'calisia-ticket-system')
            ),
            true
        );

        //print_r(options::get_replay_capable_roles());
    }
    public static function ticket_endpoint_input(){
        inputs::input(
            array(
                'id' => 'calisia_ticket_system_ticket_endpoint',
                'name' => 'calisia_ticket_system_plugin_options[ticket_endpoint]',
                'class' => 'select',
                'value' => options::get_option_value('ticket_endpoint'),
                'type' => 'text',
                'label' => __('Insert slug of my account page endpoint.', 'calisia-ticket-system')
            ),
            true
        );

        //print_r(options::get_replay_capable_roles());
    }
}