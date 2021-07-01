<?php
namespace calisia_ticket_system;

if ( ! defined( 'ABSPATH' ) ) {
	exit();
}

class loader{
    public static function load_frontend_css(){
        if(is_account_page())
            wp_enqueue_style('calisia_ticket_system_frontend_css', CALISIA_TICKET_SYSTEM_URL . 'css/calisia_ticket_system_frontend.css');

    }

    public static function load_css(){
        wp_enqueue_style('calisia_ticket_system_css', CALISIA_TICKET_SYSTEM_URL . 'css/calisia_ticket_system.css');



        if ( strpos(get_current_screen()->base, 'calisia-tickets') !== false) {
            wp_enqueue_style( 'woocommerce_admin_styles' );
        }
    }

    public static function load_js(){
        //include WC()->plugin_url() . '/includes/admin/class-wc-admin-assets.php';

        //include_once WC_ABSPATH . 'includes/admin/class-wc-admin.php';

        


        if ( strpos(get_current_screen()->base, 'calisia-tickets') === false) {
            return;
        }

        wp_enqueue_script('calisia-ticket-system-js', CALISIA_TICKET_SYSTEM_URL . 'js/calisia-ticket-system-backend.js');
        
        if(!isset($_GET['id']))
            return;

        $ticket = new ticket($_GET['id']);

        wp_enqueue_script( 'iris' );
        wp_enqueue_script( 'woocommerce_admin' );
        wp_enqueue_script( 'wc-enhanced-select' );
        wp_enqueue_script( 'jquery-ui-sortable' );
        wp_enqueue_script( 'jquery-ui-autocomplete' );

        $locale  = localeconv();
        $decimal = isset( $locale['decimal_point'] ) ? $locale['decimal_point'] : '.';

        $params = array(
            /* translators: %s: decimal */
            'i18n_decimal_error'                => sprintf( __( 'Please enter with one decimal point (%s) without thousand separators.', 'woocommerce' ), $decimal ),
            /* translators: %s: price decimal separator */
            'i18n_mon_decimal_error'            => sprintf( __( 'Please enter with one monetary decimal point (%s) without thousand separators and currency symbols.', 'woocommerce' ), wc_get_price_decimal_separator() ),
            'i18n_country_iso_error'            => __( 'Please enter in country code with two capital letters.', 'woocommerce' ),
            'i18n_sale_less_than_regular_error' => __( 'Please enter in a value less than the regular price.', 'woocommerce' ),
            'i18n_delete_product_notice'        => __( 'This product has produced sales and may be linked to existing orders. Are you sure you want to delete it?', 'woocommerce' ),
            'i18n_remove_personal_data_notice'  => __( 'This action cannot be reversed. Are you sure you wish to erase personal data from the selected orders?', 'woocommerce' ),
            'decimal_point'                     => $decimal,
            'mon_decimal_point'                 => wc_get_price_decimal_separator(),
            'ajax_url'                          => admin_url( 'admin-ajax.php' ),
            'strings'                           => array(
                'import_products' => __( 'Import', 'woocommerce' ),
                'export_products' => __( 'Export', 'woocommerce' ),
            ),
            'nonces'                            => array(
                'gateway_toggle' => wp_create_nonce( 'woocommerce-toggle-payment-gateway-enabled' ),
            ),
            'urls'                              => array(
                'import_products' => current_user_can( 'import' ) ? esc_url_raw( admin_url( 'edit.php?post_type=product&page=product_importer' ) ) : null,
                'export_products' => current_user_can( 'export' ) ? esc_url_raw( admin_url( 'edit.php?post_type=product&page=product_exporter' ) ) : null,
            ),
        );

        wp_localize_script( 'woocommerce_admin', 'woocommerce_admin', $params );


        //if ( strpos(get_current_screen()->base, 'calisia-tickets') !== false) {
           // \WC_Admin_Assets::admin_scripts();
            wp_enqueue_script( 'wc-admin-order-meta-boxes', WC()->plugin_url() . '/assets/js/admin/meta-boxes-order.min.js', array( 'wc-admin-meta-boxes', 'wc-backbone-modal', 'selectWoo', 'wc-clipboard' ) );
        //}



        $post_id            = isset( $post->ID ) ? $post->ID : $ticket->get_model()->get_element_id();
        $currency           = '';
        $remove_item_notice = __( 'Are you sure you want to remove the selected items?', 'woocommerce' );

        if ( $post_id && in_array( get_post_type( $post_id ), wc_get_order_types( 'order-meta-boxes' ) ) ) {
            $order = wc_get_order( $post_id );
            if ( $order ) {
                $currency = $order->get_currency();

                if ( ! $order->has_status( array( 'pending', 'failed', 'cancelled' ) ) ) {
                    $remove_item_notice = $remove_item_notice . ' ' . __( "You may need to manually restore the item's stock.", 'woocommerce' );
                }
            }
        }

        $params = array(
            'remove_item_notice'            => $remove_item_notice,
            'i18n_select_items'             => __( 'Please select some items.', 'woocommerce' ),
            'i18n_do_refund'                => __( 'Are you sure you wish to process this refund? This action cannot be undone.', 'woocommerce' ),
            'i18n_delete_refund'            => __( 'Are you sure you wish to delete this refund? This action cannot be undone.', 'woocommerce' ),
            'i18n_delete_tax'               => __( 'Are you sure you wish to delete this tax column? This action cannot be undone.', 'woocommerce' ),
            'remove_item_meta'              => __( 'Remove this item meta?', 'woocommerce' ),
            'remove_attribute'              => __( 'Remove this attribute?', 'woocommerce' ),
            'name_label'                    => __( 'Name', 'woocommerce' ),
            'remove_label'                  => __( 'Remove', 'woocommerce' ),
            'click_to_toggle'               => __( 'Click to toggle', 'woocommerce' ),
            'values_label'                  => __( 'Value(s)', 'woocommerce' ),
            'text_attribute_tip'            => __( 'Enter some text, or some attributes by pipe (|) separating values.', 'woocommerce' ),
            'visible_label'                 => __( 'Visible on the product page', 'woocommerce' ),
            'used_for_variations_label'     => __( 'Used for variations', 'woocommerce' ),
            'new_attribute_prompt'          => __( 'Enter a name for the new attribute term:', 'woocommerce' ),
            'calc_totals'                   => __( 'Recalculate totals? This will calculate taxes based on the customers country (or the store base country) and update totals.', 'woocommerce' ),
            'copy_billing'                  => __( 'Copy billing information to shipping information? This will remove any currently entered shipping information.', 'woocommerce' ),
            'load_billing'                  => __( "Load the customer's billing information? This will remove any currently entered billing information.", 'woocommerce' ),
            'load_shipping'                 => __( "Load the customer's shipping information? This will remove any currently entered shipping information.", 'woocommerce' ),
            'featured_label'                => __( 'Featured', 'woocommerce' ),
            'prices_include_tax'            => esc_attr( get_option( 'woocommerce_prices_include_tax' ) ),
            'tax_based_on'                  => esc_attr( get_option( 'woocommerce_tax_based_on' ) ),
            'round_at_subtotal'             => esc_attr( get_option( 'woocommerce_tax_round_at_subtotal' ) ),
            'no_customer_selected'          => __( 'No customer selected', 'woocommerce' ),
            'plugin_url'                    => WC()->plugin_url(),
            'ajax_url'                      => admin_url( 'admin-ajax.php' ),
            'order_item_nonce'              => wp_create_nonce( 'order-item' ),
            'add_attribute_nonce'           => wp_create_nonce( 'add-attribute' ),
            'save_attributes_nonce'         => wp_create_nonce( 'save-attributes' ),
            'calc_totals_nonce'             => wp_create_nonce( 'calc-totals' ),
            'get_customer_details_nonce'    => wp_create_nonce( 'get-customer-details' ),
            'search_products_nonce'         => wp_create_nonce( 'search-products' ),
            'grant_access_nonce'            => wp_create_nonce( 'grant-access' ),
            'revoke_access_nonce'           => wp_create_nonce( 'revoke-access' ),
            'add_order_note_nonce'          => wp_create_nonce( 'add-order-note' ),
            'delete_order_note_nonce'       => wp_create_nonce( 'delete-order-note' ),
            'calendar_image'                => WC()->plugin_url() . '/assets/images/calendar.png',
            'post_id'                       => isset( $post->ID ) ? $post->ID : $ticket->get_model()->get_element_id(),
            'base_country'                  => WC()->countries->get_base_country(),
            'currency_format_num_decimals'  => wc_get_price_decimals(),
            'currency_format_symbol'        => get_woocommerce_currency_symbol( $currency ),
            'currency_format_decimal_sep'   => esc_attr( wc_get_price_decimal_separator() ),
            'currency_format_thousand_sep'  => esc_attr( wc_get_price_thousand_separator() ),
            'currency_format'               => esc_attr( str_replace( array( '%1$s', '%2$s' ), array( '%s', '%v' ), get_woocommerce_price_format() ) ), // For accounting JS.
            'rounding_precision'            => wc_get_rounding_precision(),
            'tax_rounding_mode'             => wc_get_tax_rounding_mode(),
            'product_types'                 => array_unique( array_merge( array( 'simple', 'grouped', 'variable', 'external' ), array_keys( wc_get_product_types() ) ) ),
            'i18n_download_permission_fail' => __( 'Could not grant access - the user may already have permission for this file or billing email is not set. Ensure the billing email is set, and the order has been saved.', 'woocommerce' ),
            'i18n_permission_revoke'        => __( 'Are you sure you want to revoke access to this download?', 'woocommerce' ),
            'i18n_tax_rate_already_exists'  => __( 'You cannot add the same tax rate twice!', 'woocommerce' ),
            'i18n_delete_note'              => __( 'Are you sure you wish to delete this note? This action cannot be undone.', 'woocommerce' ),
            'i18n_apply_coupon'             => __( 'Enter a coupon code to apply. Discounts are applied to line totals, before taxes.', 'woocommerce' ),
            'i18n_add_fee'                  => __( 'Enter a fixed amount or percentage to apply as a fee.', 'woocommerce' ),
        );

        wp_localize_script( 'wc-admin-meta-boxes', 'woocommerce_admin_meta_boxes', $params );
    
    }
}