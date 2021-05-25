<div id="calisia-ticket-messages" style="display:flex; flex-direction:column; max-height: 400px; overflow:auto; background: whitesmoke; padding: 10px;">
    <?php echo $args['messages']; ?>
</div>

<script>
var element = document.getElementById("calisia-ticket-messages");
element.scrollTop = element.scrollHeight;
</script>