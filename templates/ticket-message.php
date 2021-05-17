<?php
    $align = 'flex-start';
    if($args['message']->added_by == get_current_user_id())
        $align = 'flex-end';
?>

<div style="padding:10px; max-width:90%; background:yellow; margin-bottom:10px; align-self:<?php echo $align; ?>;">
    <div style="font-size:.8em; color:gray;"><?php echo $args['message']->added; ?></div>
    <div><?php echo $args['message']->text; ?></div>
</div>