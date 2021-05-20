<form method="POST">
    <?php echo $args['select']; ?>
    <input type="hidden" name="calisia_ticket_status" value="1">
    <button class="button" type="submit"><?php _e('Save','calisia-ticket-system'); ?></button>
</form>