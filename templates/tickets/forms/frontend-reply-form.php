<form method="POST" enctype="multipart/form-data">
    <textarea name="msg" style="width:100%;" class="mce-tinymce mce-container mce-panel" ></textarea>
    <input type="hidden" name="ticket_id" value="<?php echo $args['ticket_id']; ?>">
    <input type="hidden" name="calisia_ticket_reply" value="1">
    <input type="hidden" name="calisia_nonce" value="<?php echo $args['nonce']; ?>">
    <input type="hidden" name="calisia_form_token" value="<?php echo $args['calisia_form_token']; ?>">
    <input type="hidden" name="max_file_size" value="2000000">
    <input type="file" name="calisia_file_upload[]" multiple="multiple"  style="margin-top:10px; margin-bottom:10px;"/>
    <button type="submit" class="button" style="width:100%;">Wyślij</button>
</form>