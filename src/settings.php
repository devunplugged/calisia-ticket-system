<?php
namespace calisia_ticket_system;

class settings{
    public static function add_settings_page() {
        add_options_page( 'Example plugin page', __( 'Ticket System', 'calisia-ticket-system' ), 'manage_options', 'calisia-ticket-system', 'calisia_ticket_system\settings::render_plugin_settings_page' );
    }

    public static function render_plugin_settings_page() {
        renderer::render('settings-form');
    }

    public static function register_settings() {
        register_setting( 'calisia-ticket-system-options', 'calisia_ticket_system_plugin_options' ); //, 'dbi_example_plugin_options_validate'
        add_settings_section( 'integration_settings', __( 'Integration Settings', 'calisia-ticket-system' ), 'calisia_ticket_system\settings::section_text', 'calisia-ticket-system-settings-page' );
    
        add_settings_field( 'calisia_ticket_system_customer_notes_integration', __('calisia-ticket-system', 'calisia-ticket-system'), 'calisia_ticket_system\settings::customer_notes_integration_input', 'calisia-ticket-system-settings-page', 'integration_settings' );
        //add_settings_field( 'calisia_ticket_system_order_notes', __('Edit order page', 'calisia-ticket-system'), 'calisia_ticket_system\settings::order_notes_input', 'calisia-ticket-system-settings-page', 'integration_settings' );      
    }

    public static function section_text() {
        renderer::render('settings-section-text');
    }
    
    public static function order_notes_input() {
        $options = get_option( 'calisia_ticket_system_plugin_options' );
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
        $options = get_option( 'calisia_ticket_system_plugin_options' );
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
    
}