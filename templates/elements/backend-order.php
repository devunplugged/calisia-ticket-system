<div class="calisia-ticket-system-backend-panel w-100">
    <div class="calisia-ticket-system-backend-panel-content">
        <h3>
            <?php printf(__( 'Order #%1$s', 'calisia-ticket-system' ), $args['order']->get_id()); ?>
        </h3>
        <div class="d-flex flex-space-between flex-wrap">
            <div class="my-20 p-10">
                <?php printf(__( 'Payment via: %1$s (%2$s)', 'calisia-ticket-system' ), $args['order']->get_data()['payment_method_title'],$args['order']->get_data()['transaction_id'] ); ?>

                <?php
                    if($args['order']->is_paid()){
                        printf(__( 'Paid: %1$s', 'calisia-ticket-system' ), date('Y-m-d H:i', $args['order']->get_data()['date_paid']->getTimestamp() ) );
                    }
                ?>

                <?php
                    if(!empty($args['order']->get_data()['customer_ip_address'])){
                        printf(__( 'Client IP Address: %1$s', 'calisia-ticket-system' ), $args['order']->get_data()['customer_ip_address'] );
                    }
                ?>

                <div>
                <?php
                    printf(__( 'Date Created: %1$s', 'calisia-ticket-system' ), date('Y-m-d H:i', $args['order']->get_data()['date_created']->getTimestamp() ) );
                ?>
                </div>

                <div>
                <?php
                    $order_status_translation = __(wc_get_order_status_name($args['order']->get_data()['status']),'woocommerce');
                    printf(__( 'Order Status: %1$s', 'calisia-ticket-system' ), $order_status_translation );
                ?>
                </div>
            </div>
            
            <div class='my-20 p-10'>
                <?php _e('Billing Address:','calisia-ticket-system'); ?>
                <div><?php echo $args['order']->get_data()['billing']['first_name'] . ' ' . $args['order']->get_data()['billing']['last_name']; ?></div>
                <div><?php echo $args['order']->get_data()['billing']['address_1']; ?></div>
                <div><?php echo $args['order']->get_data()['billing']['address_2']; ?></div>
                <div><?php echo $args['order']->get_data()['billing']['postcode'] . ' ' . $args['order']->get_data()['billing']['city']; ?></div>
                <div><?php echo $args['order']->get_data()['billing']['email']; ?></div>
                <div><?php echo $args['order']->get_data()['billing']['phone']; ?></div>
            </div>

            <div class='my-20 p-10'>
                <?php _e('Shipping Address:','calisia-ticket-system'); ?>
                <div><?php echo $args['order']->get_data()['shipping']['first_name'] . ' ' . $args['order']->get_data()['billing']['last_name']; ?></div>
                <div><?php echo $args['order']->get_data()['shipping']['address_1']; ?></div>
                <div><?php echo $args['order']->get_data()['shipping']['address_2']; ?></div>
                <div><?php echo $args['order']->get_data()['shipping']['postcode'] . ' ' . $args['order']->get_data()['billing']['city']; ?></div>
            </div>
        </div>

        <div class='my-20'>
            <div id="woocommerce-order-items">
                <?php $order = $args['order']; include ABSPATH . 'wp-content/plugins/woocommerce/includes/admin/meta-boxes/views/html-order-items.php';?>
                <?php /*
                <div class="woocommerce_order_items_wrapper wc-order-items-editable">
                    <table class="woocommerce_order_items calisia-ticket-system-order-products-table" cellpadding="0" cellspacing="0">
                        <thead>
                            <tr>
                                <th colspan="2" class="item"><?php _e('Item','calisia-ticket-system'); ?></th>
                                <th class="item_cost"><?php _e('Cost','calisia-ticket-system'); ?></th>
                                <th class="quantity"><?php _e('Quantity','calisia-ticket-system'); ?></th>
                                <th class="line_cost"><?php _e('Amount','calisia-ticket-system'); ?></th>
                                <th class="line_tax"><?php _e('Tax','calisia-ticket-system'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                                $price_total = 0;
                                $tax_total = 0;

                                foreach($args['order']->get_items() as $key => $item){
                                    $item_data = $item->get_data();
                                    $price_total += $item_data['subtotal'];
                                    $tax_total += $item_data['total_tax'];
                            ?>
                    
                                    <tr class="item">
                                        <td class="thumb"><a href="<?php echo get_permalink( $item_data['product_id'] ); ?>"><img class="r-img" src="<?php echo get_the_post_thumbnail_url($item->get_data()['product_id']); ?>"></a></td>
                                        <td class="name"><a href="<?php echo get_edit_post_link($item_data['product_id']); ?>"><?php echo $item_data['name']; ?></a></td>
                                        <td class="item_cost"><?php echo wc_price($item_data['subtotal'] / $item_data['quantity'], array( 'currency' => $args['order']->get_currency())); ?></td>
                                        <td class="quantity"><small class="times">x</small> <?php echo $item_data['quantity']; ?></td>
                                        <td class="line_cost"><?php echo wc_price($item_data['subtotal'], array( 'currency' => $args['order']->get_currency())); ?></td>
                                        <td class="line_tax"><?php echo wc_price($item_data['total_tax'], array( 'currency' => $args['order']->get_currency())); ?></td>
                                    </tr>
                                    
                            <?php   
                                }
                            ?>
                        </tbody>
                        <tbody id="order_shipping_line_items">
                            <tr class="shipping">
                                <td class="thumb"><div></div></td>
                                <td class="name"><?php echo $args['order']->get_shipping_method(); ?></td>
                                <td class="item_cost">&nbsp;</td>
                                <td class="quantity">&nbsp;</td>
                                <td class="line_cost"><?php echo wc_price($args['order']->get_shipping_total(), array( 'currency' => $args['order']->get_currency())); ?></td>
                                <td class="line_tax"><?php echo wc_price($args['order']->get_shipping_tax(), array( 'currency' => $args['order']->get_currency())); ?></td>
                            </tr>
                        </tbody>

                    </table>
                </div>


                <div class="wc-order-data-row wc-order-totals-items wc-order-items-editable">
                    <table class="wc-order-totals">
                        <tbody>
                            <tr>
                                <td><?php _e('Items Subtotal:', 'woocommerce'); ?></td>
                                <td><?php echo wc_price($price_total, array( 'currency' => $args['order']->get_currency())); ?></td>
                            </tr>
                            <tr>
                                <td><?php _e('Shipping:', 'woocommerce'); ?></td>
                                <td><?php echo wc_price($args['order']->get_shipping_total(), array( 'currency' => $args['order']->get_currency())); ?></td>
                            </tr>
                            <tr>
                                <td><?php _e('Tax', 'woocommerce'); ?>:</td>
                                <td><?php echo wc_price($tax_total + $args['order']->get_shipping_tax(), array( 'currency' => $args['order']->get_currency())); ?></td>
                            </tr>
                            <tr>
                                <td><?php _e('Order Total', 'woocommerce'); ?>:</td>
                                <td><?php echo wc_price($price_total + $args['order']->get_shipping_total() + $tax_total + $args['order']->get_shipping_tax(), array( 'currency' => $args['order']->get_currency())); ?></td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="clear"></div>

                    <?php if ( in_array( $args['order']->get_status(), array( 'processing', 'completed', 'refunded' ), true ) && ! empty( $args['order']->get_date_paid() ) ){ ?>

                        <table class="wc-order-totals" style="border-top: 1px solid #999; margin-top:12px; padding-top:12px">
                            <tr>
                                <td class="<?php echo $args['order']->get_total_refunded() ? 'label' : 'label label-highlight'; ?>"><?php esc_html_e( 'Paid', 'woocommerce' ); ?>: <br /></td>
                                <td width="1%"></td>
                                <td class="total">
                                    <?php echo wc_price( $args['order']->get_total(), array( 'currency' => $args['order']->get_currency() ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <span class="description">
                                    <?php
                                    if ( $args['order']->get_payment_method_title() ) {

                                        echo esc_html( sprintf( __( '%1$s via %2$s', 'woocommerce' ), $args['order']->get_date_paid()->date_i18n( get_option( 'date_format' ) ), $args['order']->get_payment_method_title() ) );
                                    } else {
                                        echo esc_html( $args['order']->get_date_paid()->date_i18n( get_option( 'date_format' ) ) );
                                    }
                                    ?>
                                    </span>
                                </td>
                                <td colspan="2"></td>
                            </tr>
                        </table>

                        <div class="clear"></div>

                    <?php } ?>


                </div>
                */ ?>
            </div> 
        </div>

    </div>
</div>