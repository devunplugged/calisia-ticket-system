<form method="POST" enctype="multipart/form-data">
    <input type="text" name="title"><br>
    

    <?php if(isset($args['order_id'])){ ?>
        <input type="text" value="<?php printf(__( 'Order: #%1$s', 'calisia-ticket-system' ), $args['order_id']); ?>" disabled>
        <input type="hidden" name="kind" value="<?php echo $args['kind']; ?>">
        <input type="hidden" name="element_id" value="<?php echo $args['order_id']; ?>">
    <?php }else{ ?>
        <select name="kind">
            <option value="other"><?php _e('Other','calisia-ticket-system'); ?></option>
        </select>
        <input type="hidden" name="element_id" value="0">
    <?php } ?>

    <textarea name="msg"></textarea>

    <input type="hidden" name="calisia_ticket_new" value="1">
    <input type="hidden" name="calisia_nonce" value="<?php echo $args['nonce']; ?>">
    <input type="hidden" name="calisia_form_token" value="<?php echo $args['calisia_form_token']; ?>">
    <input type="hidden" name="max_file_size" value="2000000">
    <input type="file" name="calisia_file_upload[]" multiple="multiple"  style="margin-top:10px; margin-bottom:10px;"/>
    <button type="submit" class="button" style="width:100%;">Wyślij</button>
</form>