<form method="POST">
    <input type="hidden" name="ticket_id" value="<?php echo $args['ticket_id']; ?>">
    <input type="hidden" name="calisia_nonce" value="<?php echo $args['nonce']; ?>">
    <input type="hidden" name="calisia_form_token" value="<?php echo $args['calisia_form_token']; ?>">
    <input type="hidden" name="calisia_ticket_open" value="1">
    <button type="submit" class="button mt-20" style="width:100%;"><?php _e('Open This Ticket', 'calisia-ticket-system'); ?></button>
</form>