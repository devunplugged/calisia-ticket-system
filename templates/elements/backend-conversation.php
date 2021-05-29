<div class="calisia-ticket-system-backend-panel w-50">
    <div class="calisia-ticket-system-backend-panel-content">
        <h2><?php _e('Conversation', 'calisia-ticket-system'); ?></h2>
        <h3><?php echo $args['title']; ?></h3>
        <?php
            echo $args['messages'];
            echo $args['reply-form'];
        ?>
    </div>
</div>