<div class="mt-20 mb-20">
<a class="button" href="<?php echo get_permalink( get_option('woocommerce_myaccount_page_id') ) . calisia_ticket_system\endpoint::get_new_ticket_endpoint_name(); ?>/?kind=order&element_id=<?php echo $args['element_id']; ?>"><?php _e('New Ticket', 'calisia-ticket-system'); ?></a>
</div>