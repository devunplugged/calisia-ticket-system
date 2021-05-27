<form method="POST" enctype="multipart/form-data">
    <input type="text" name="title"><br>
    <?php echo $args['status-select']; ?><br>
    <div id="selected-user-info"></div>
    <div><a id="select-other-user-button" style="display:none;">Select Someone Else</a></div>
    <input type="text" name="user-search" id="ticket-user-search"><br>
    <div id="user-suggestion"></div>
    <?php echo $args['kind-select']; ?><br>
    <?php wp_editor( '', 'msg', array( 'textarea_name' => 'msg', 'textarea_rows' => 10 ) ); ?><br>
    <input type="file" name="calisia_file_upload[]" multiple="multiple"  style="margin-top:10px; margin-bottom:10px;"/><br>

    <input type="hidden" name="max_file_size" value="2000000">
    <input type="hidden" name="element_id" value="0">
    <input type="hidden" name="calisia_ticket_new" value="1">
    <input type="hidden" name="user_id" id="ticket-user-id" value="0">
    <input type="hidden" name="calisia_ticket_new_nonce" value="<?php echo $args['nonce'];?>">
    <input type="hidden" name="calisia_ticket_new_token" value="<?php echo $args['token'];?>">
    <button class="button" type="submit"><?php _e('Save','calisia-ticket-system'); ?></button>
</form>