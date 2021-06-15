<div class="calisia-tickets-row">
    <div class="p-10 calisia-tickets-row-content">
        <div class="calisia-ticket-row-date">
            <div>
                <?php _e('Created:','calisia-ticket-system'); ?>
                <?php echo $args['ticket']->get_model()->get_added(); ?>
            </div>
            <div>
                <?php _e('Last activity:','calisia-ticket-system'); ?>
                <?php 
                    echo $args['ticket']->get_model()->get_last_support_reply() > $args['ticket']->get_model()->get_last_customer_reply() ? $args['ticket']->get_model()->get_last_support_reply(): $args['ticket']->get_model()->get_last_customer_reply();
                ?>
            </div>
        </div>
        
        <div class="calisia-ticket-row-title">
            <?php if($args['unread'] != 0){ ?>
                <strong>
            <?php } ?>
            <a href="<?php echo $args['ticket']->get_backend_ticket_url(); ?>"><?php echo $args['ticket']->get_model()->get_title(); ?>
            <?php 
                if($args['unread'] != 0){
                    echo ' ('. $args['unread'] .')';
                } 
            ?>
            </a>
            <?php if($args['unread'] != 0){ ?>
                </strong>
            <?php } ?>
            <div class="text-small">
                <?php echo calisia_ticket_system\translations::ticket_kind($args['ticket']->get_model()->get_kind()); ?>
                <?php 
                    if($args['ticket']->get_model()->get_kind() != 'other'){
                        if(isset($args['order']))
                            echo '<a href="'.$args['order']->get_edit_order_url().'">';
                        echo '#' . $args['ticket']->get_model()->get_element_id();
                        if(isset($args['order']))
                            echo '</a>';
                    } 
                ?>
            </div>
        </div>
        
        <div class="calisia-ticket-row-status">
            <?php echo calisia_ticket_system\translations::ticket_status($args['ticket']->get_model()->get_status()); ?>
        </div>
    </div>
</div>