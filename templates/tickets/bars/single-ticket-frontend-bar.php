<div style="padding:20px; display:flex;justify-content: space-between;">
    <div>
        <h2><?php echo $args['ticket']->get_model()->get_title() ?></h2>
    </div>
    <div>
        <div style="padding-left:10px;"><?php printf(__( 'Ticket #%1$s', 'calisia-ticket-system' ), $args['ticket']->get_model()->get_id()); ?></div>
    </div>
    
</div>