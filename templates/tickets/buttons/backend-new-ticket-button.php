<?php
$ticket = new calisia_ticket_system\ticket();
if(isset($args['user_id']))
    $_GET['user_id'] = $args['user_id'];

if(isset($args['kind']))
    $_GET['kind'] = $args['kind'];

if(isset($args['element_id']))
    $_GET['element_id'] = $args['element_id'];

echo "<a href='" . $ticket->get_backend_new_ticket_url() . "' class='page-title-action'>".__('New Ticket','calisia-ticket-system')."</a>";