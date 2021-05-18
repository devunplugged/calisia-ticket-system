<div id="calisia-ticket-messages" style="display:flex; flex-direction:column; max-height: 300px; overflow:auto;">
    <?php echo $args['messages']; ?>
</div>

<script>
var element = document.getElementById("calisia-ticket-messages");
element.scrollTop = element.scrollHeight;
</script>