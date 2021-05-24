<form method="POST">
    <?php echo $args['select']; ?>
    <input type="hidden" name="calisia_ticket_status" value="1">
    <input type="hidden" name="calisia_ticket_status_nonce" value="<?php echo $args['nonce'];?>">
    <button class="button" type="submit"><?php _e('Save','calisia-ticket-system'); ?></button>
</form>