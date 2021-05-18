<form method="POST" enctype="multipart/form-data">
    <textarea name="msg" style="width:100%;" class="mce-tinymce mce-container mce-panel" ></textarea>
    <input type="hidden" name="ticket_id" value="<?php echo $args['ticket_id']; ?>">
    <input type="hidden" name="calisia_ticket_reply" value="1">
    <input type="hidden" name="calisia_nonce" value="<?php echo $args['nonce']; ?>">
    <input type="hidden" name="calisia_form_token" value="<?php echo $args['calisia_form_token']; ?>">
    <button type="submit" class="button">Wyślij</button>
</form>