<div class="calisia-tickets-row">
    <div class="p-10 calisia-tickets-row-content">
        <div class="calisia-ticket-row-date">
            <?php echo $args['ticket']->get_model()->get_added(); ?>
        </div>
        
        <div class="calisia-ticket-row-title">
            <a href="<?php echo get_permalink( get_option('woocommerce_myaccount_page_id') ); ?>calisia-show-ticket/?id=<?php echo $args['ticket']->get_model()->get_id(); ?>"><?php echo $args['ticket']->get_model()->get_title(); ?></a>
        </div>
        
        <div class="calisia-ticket-row-status">
            <?php echo $args['ticket']->get_model()->get_status(); ?>
        </div>
    </div>
</div>