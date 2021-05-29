<form method="POST" enctype="multipart/form-data">
    <label for="ticket-title"><?php _e('Ticket title', 'calisia-ticket-system'); ?></label>
    <div><input id="ticket-title" type="text" name="title"></div>
    

    <?php if(isset($args['kind']) && $args['kind'] == 'order'){ ?>
        <label for="ticket-element"><?php _e('Element ID', 'calisia-ticket-system'); ?></label>
        <div><input id="ticket-element" type="text" value="<?php printf(__( 'Order: #%1$s', 'calisia-ticket-system' ), $args['element_id']); ?>" disabled></div>
        <input type="hidden" name="kind" value="<?php echo $args['kind']; ?>">
        <input type="hidden" name="element_id" value="<?php echo $args['element_id']; ?>">
    <?php }else{ ?>
        <label for="ticket-kind"><?php _e('Ticket type', 'calisia-ticket-system'); ?></label>
        <div>
            <select name="kind" id="ticket-kind">
                <option value="other"><?php _e('Other','calisia-ticket-system'); ?></option>
            </select>
        </div>
        <input type="hidden" name="element_id" value="0">
    <?php } ?>
    <label for="ticket-msg"><?php _e('Message', 'calisia-ticket-system'); ?></label>
    <div>
        <textarea id="ticket-msg" name="msg"></textarea>
    </div>

    <input type="hidden" name="calisia_ticket_new" value="1">
    <input type="hidden" name="calisia_nonce" value="<?php echo $args['nonce']; ?>">
    <input type="hidden" name="calisia_form_token" value="<?php echo $args['calisia_form_token']; ?>">
    <input type="hidden" name="max_file_size" value="2000000">
    <input type="file" name="calisia_file_upload[]" multiple="multiple"  style="margin-top:10px; margin-bottom:10px;"/>
    <button type="submit" class="button" style="width:100%;">Wyślij</button>
</form>