<form method="POST" enctype="multipart/form-data">
    <textarea name="msg"></textarea>
    <input type="hidden" name="ticket_id" value="<?php echo $args['ticket_id']; ?>">
    <input type="hidden" name="calisia_ticket_reply" value="1">
    <button type="submit">Wyślij</button>
</form>