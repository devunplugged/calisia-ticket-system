<div class="calisia-ticket-system-backend-panel w-50">
    <div class="calisia-ticket-system-backend-panel-content">
    <h3>
        <?php printf(__( 'Order #%1$s', 'calisia-ticket-system' ), $args['order']->get_id()); ?>
    </h3>
    <?php printf(__( 'Payment via: %1$s (%2$s)', 'calisia-ticket-system' ), $args['order']->get_data()['payment_method_title'],$args['order']->get_data()['transaction_id'] ); ?>

    <?php
        if(!empty($args['order']->get_data()['date_paid'])){
            printf(__( 'Payed: %1$s', 'calisia-ticket-system' ), date('Y-m-d H:i', $args['order']->get_data()['date_paid']->getTimestamp() ) );
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

    <div class='my-20'>
        <?php _e('Billing Address:','calisia-ticket-system'); ?>
        <div><?php echo $args['order']->get_data()['billing']['first_name'] . ' ' . $args['order']->get_data()['billing']['last_name']; ?></div>
        <div><?php echo $args['order']->get_data()['billing']['address_1']; ?></div>
        <div><?php echo $args['order']->get_data()['billing']['address_2']; ?></div>
        <div><?php echo $args['order']->get_data()['billing']['postcode'] . ' ' . $args['order']->get_data()['billing']['city']; ?></div>
        <div><?php echo $args['order']->get_data()['billing']['email']; ?></div>
        <div><?php echo $args['order']->get_data()['billing']['phone']; ?></div>
    </div>

    <div class='my-20'>
        <?php _e('Shipping Address:','calisia-ticket-system'); ?>
        <div><?php echo $args['order']->get_data()['shipping']['first_name'] . ' ' . $args['order']->get_data()['billing']['last_name']; ?></div>
        <div><?php echo $args['order']->get_data()['shipping']['address_1']; ?></div>
        <div><?php echo $args['order']->get_data()['shipping']['address_2']; ?></div>
        <div><?php echo $args['order']->get_data()['shipping']['postcode'] . ' ' . $args['order']->get_data()['billing']['city']; ?></div>
    </div>

    <div class='my-20'>
        <?php 
            foreach($args['order']->get_items() as $item){
                $product_image = wp_get_attachment_image_src( get_post_thumbnail_id( $item->get_data()['product_id'] ), 'single-post-thumbnail' )[0];
                $product_url = get_permalink( $item->get_data()['product_id'] );
                echo "<a href='$product_url'><img class='r-img ticket-order-product-img' src='$product_image'></a>";
               /* $product_factory = new WC_Product_Factory();
                $product = $product_factory->get_product($item->get_data()['product_id'])
                echo "<pre>";
                print_r($product);
                echo "</pre>";*/

                echo "<pre>";
                print_r($item->get_data());
                echo "</pre>";
                
            }
        ?>
    </div>
    <pre>
        <?php print_r($args['order']); ?>
    </pre>
    </div>
</div>