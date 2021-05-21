<form 
<?php if(isset($args['action'])){ ?>
    action="<?php echo $args['action']; ?>" 
<?php } ?>
<?php if(isset($args['method'])){ ?>
    method="<?php echo $args['method']; ?>" 
<?php } ?>
>
<?php echo $args['content']; ?>
</form>