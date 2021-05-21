<div class="d-flex mt-20">
    <?php echo $args['type_select']; ?>
    <?php echo $args['status_select']; ?>
    <input type="hidden" id="calisia-current-url" value="<?php echo menu_page_url( 'calisia-tickets', false ); ?>">
    <button id="ticket-table-controls-submit" class="button"><?php _e('Search', 'calisia-ticket-system'); ?></button>
</div>

<script>
    document.querySelector('#ticket-table-controls-submit').addEventListener('click', (event) => {
        let current_url = document.querySelector('#calisia-current-url').value;
        let ticket_type = document.querySelector('#calisia_ticket_system_control_ticket_type').value;
        let ticket_status = document.querySelector('#calisia_ticket_system_control_ticket_status').value;

        const queryString = window.location.search;
        const urlParams = new URLSearchParams(queryString);

        const params = ["order", "orderby", "paged", "user_id", "id"];
        params.forEach( (element) => {
            if(urlParams.has(element))
                current_url += '&' + element + '=' + urlParams.get(element);
        });

        window.location.href = current_url + '&kind=' + ticket_type + '&status=' + ticket_status;
    });

</script>