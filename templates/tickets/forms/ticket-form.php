<form method="POST" enctype="multipart/form-data">
    <input type="text" name="title"><br>
    <textarea name="msg"></textarea>
    <input type="hidden" name="kind" value="<?php echo $args['kind']; ?>">
    <input type="hidden" name="order_id" value="<?php echo $args['order_id']; ?>">
    <input type="hidden" name="calisia_ticket" value="1">
    <button type="submit">Wyślij</button>
</form>