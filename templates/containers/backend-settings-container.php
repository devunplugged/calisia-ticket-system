<div class="backend-settings-container">
    <?php echo $args['conversation']; ?>
    <?php echo $args['user']; ?>
    <?php if(isset($args['order'])){ ?>
        <?php echo $args['order']; ?>
    <?php } ?>
    <?php echo $args['other_tickets_table']; ?>
</div>