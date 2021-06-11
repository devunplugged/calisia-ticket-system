<div class="text-center" style="background: #f5f5f5; padding: 20px 10px;">
    <h3><?php echo calisia_ticket_system\translations::ticket_status($args['ticket']->get_model()->get_status()); ?></h3>
    <div class="text-small"><?php echo calisia_ticket_system\translations::ticket_kind($args['ticket']->get_model()->get_kind()); ?>
    <?php
        if(isset($args['order'])){
            echo '<a href="'.$args['order']->get_view_order_url().'">';
            echo '#' . $args['ticket']->get_model()->get_element_id();
            echo '</a>';
        }
    ?>
    </div>
</div>

<div style="padding:30px 20px 20px 20px; display:flex;justify-content: space-between;">
    <div>
        <h2><?php echo $args['ticket']->get_model()->get_title() ?></h2>
    </div>
    <div>
        <div style="padding-left:10px;"><?php printf(__( 'Ticket #%1$s', 'calisia-ticket-system' ), $args['ticket']->get_model()->get_id()); ?></div>
    </div>
    
</div>