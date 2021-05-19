<form method="POST" enctype="multipart/form-data">
    <h3 class='pt-20'><?php _e('Your reply:', 'calisia-ticket-system'); ?></h3>
    <?php wp_editor( '', 'msg', array( 'textarea_name' => 'msg', 'textarea_rows' => 10 ) ); ?>
    <input type="hidden" name="ticket_id" value="<?php echo $args['ticket_id']; ?>">
    <input type="hidden" name="calisia_ticket_reply" value="1">
    <input type="hidden" name="calisia_nonce" value="<?php echo $args['nonce']; ?>">
    <input type="hidden" name="calisia_form_token" value="<?php echo $args['calisia_form_token']; ?>">
    <input type="hidden" name="max_file_size" value="2000000">
    <h4 class='pt-20'><?php _e('Attachments:', 'calisia-ticket-system'); ?></h4>
    <input class="calisia-ticket-attachement-button" type="file" name="calisia_file_upload[]" multiple="multiple" />
    <button type="submit" class="button w-100 mt-20">Wyślij</button>
</form>