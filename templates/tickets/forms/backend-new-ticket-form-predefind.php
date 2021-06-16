<form method="POST" enctype="multipart/form-data">
    <label for="title"><?php _e('Ticket title:','calisia-ticket-system');?></label>
    <div class="ticket-form-row"><input type="text" name="title" id="title" class="w-100" required></div>
    <label for="status-select"><?php _e('Ticket status:','calisia-ticket-system');?></label>
    <div class="ticket-form-row"><?php echo $args['status-select']; ?></div>

    <div class="ticket-selected-user">
        <div class="ticket-form-row" id="selected-user-info"><?php _e('User:','calisia-ticket-system'); echo ' ' . $args['user'];?></div>
    </div>

    <?php if(isset($_GET['kind'])){ ?>
        <div class="ticket-form-row"><?php _e('Kind:','calisia-ticket-system'); echo ' ' . $args['kind_name'];?></div>
        <div class="ticket-form-row"><?php _e('Element ID:','calisia-ticket-system'); echo ' #' . $_GET['element_id'];?> </div>
        <input type="hidden" name="kind" value="<?php echo $args['kind']; ?>">
    <?php }else{ ?>
        <label for="kind-select"><?php _e('Ticket type:','calisia-ticket-system');?></label>
        <div class="ticket-form-row"><?php echo $args['kind-select']; ?></div>

        <div class="element-search-container" style="display:none;">
            <label for="element-search"><?php _e('Search for element:','calisia-ticket-system');?></label>
            <div class="ticket-selected-element" style="display:none;">
                <div class="ticket-form-row" id="selected-element-info"></div>
                <div class="ticket-form-row"><button id="select-other-element-button" class="button" type="button">Select Other</button></div>
            </div>
            <div class="ticket-form-row"><input type="text" name="element-search" id="ticket-element-search"></div>
            <div class="ticket-form-row" id="element-suggestion" style="display:none;"></div>
        </div>
    <?php } ?>


    <?php wp_editor( '', 'msg', array( 'textarea_name' => 'msg', 'textarea_rows' => 10 ) ); ?>
    <div class="ticket-form-row"><input type="file" name="calisia_file_upload[]" multiple="multiple"  style="margin-top:10px; margin-bottom:10px;"/></div>

    <input type="hidden" name="max_file_size" value="2000000">
    <input type="hidden" name="element_id" id="ticket-element-id" value="<?php echo isset($_GET['element_id']) ? $_GET['element_id'] : 0;?>">
    <input type="hidden" name="calisia_ticket_new" value="1">
    
    <input type="hidden" name="user_id" id="ticket-user-id" value="<?php echo isset($_GET['user_id']) ? $_GET['user_id'] : 0;?>">
    <input type="hidden" name="calisia_nonce" value="<?php echo $args['nonce'];?>">
    <input type="hidden" name="calisia_form_token" value="<?php echo $args['token'];?>">
    <button class="button" type="submit"><?php _e('Save','calisia-ticket-system'); ?></button>
</form>