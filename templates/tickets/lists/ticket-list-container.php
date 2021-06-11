<div class="calisia-ticket-list-container">
    <?php echo $args['title_bar']; ?>
    <?php 
        if(!empty($args['ticket_list'])){
            echo $args['ticket_list']; 
        }else{
    ?>
        <div class="calisia-tickets-row">
            <div class="p-10 calisia-tickets-row-content">
                <?php _e('You do not have tickets yet.','calisia-ticket-system');?>
            </div>
        </div>
    <?php
        }
    ?>
</div>