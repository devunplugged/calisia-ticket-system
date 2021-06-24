<div class="calisia-ticket-system-backend-panel w-50">
    <div class="calisia-ticket-system-backend-panel-content">
        <h3><?php _e('User panel', 'calisia-ticket-system'); ?></h3>
        <div class="text-center">
            <?php echo get_avatar($args['wp_user']->data->user_email); ?>
        </div>
        <div class="calisia-ticket-system-user-details-element calisia-ticket-system-user-details-name">
            <?php echo $args['wp_user_meta']['first_name'][0]; ?> <?php echo $args['wp_user_meta']['last_name'][0]; ?>
        </div>
        <div class="calisia-ticket-system-user-details-element text-center">
            <?php _e('Registered:', 'calisia-ticket-system'); ?>
            <span class="text-size-1-2 text-bold"><?php echo $args['wp_user']->data->user_registered; ?></span>
        </div>
        <div class="calisia-ticket-system-user-details-element text-center">
            <?php _e('Username:', 'calisia-ticket-system'); ?>
            <span class="text-size-1-2 text-bold"><?php echo $args['wp_user']->data->user_login; ?></span>
        </div>
        <div class="calisia-ticket-system-user-details-element text-center">
            <?php _e('E-mail:', 'calisia-ticket-system'); ?>
            <span class="text-size-1-2 text-bold"><?php echo $args['wp_user']->data->user_email; ?></span>
        </div>
    
        <?php if(calisia_ticket_system\options::option_on('customer_notes_integration')){ ?>
            <div class="calisia-ticket-system-user-details-element text-center">
                <?php _e('Customer trait:', 'calisia-ticket-system'); ?>
                <span class="text-size-1-2 text-bold"><?php echo do_shortcode('[calisia_customer_trait user_id="'.$args['user_id'].'"]'); ?></span>
            </div>
        <?php } ?>

        <?php if(calisia_ticket_system\options::option_on('customer_notes_integration')){ ?>
            <?php echo do_shortcode('[calisia_customer_notes user_id="'.$args['user_id'].'"]'); ?>
        <?php } ?>

        <?php //print_r($args['wp_user']); ?><!--<br><br>-->
        <?php //print_r($args['wp_user_meta']); ?>
    </div>
</div>