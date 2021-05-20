<div class="bg-primary p-20 calisia-single-ticket-title-bar">
    <div>
        <h1><?php printf(__( 'Ticket #%1$s', 'calisia-ticket-system' ), $args['ticket_id']); ?></h1>
    </div>
    <div>
        <?php echo $args['status_form']; ?>
    </div>
</div>