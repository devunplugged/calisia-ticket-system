<h1><?php _e( 'Ticket System Settings', 'calisia-ticket-system' ); ?></h1>
<form action="options.php" method="post">
    <?php 
        settings_fields( 'calisia-ticket-system-options' );
        do_settings_sections( 'calisia-ticket-system-settings-page' ); 
    ?>
    <input name="submit" class="button button-primary" type="submit" value="<?php _e( 'Save', 'calisia-ticket-system' ); ?>" />
</form>