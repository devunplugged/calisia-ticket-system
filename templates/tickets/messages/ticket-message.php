<?php
    $align = 'flex-start';
    if($args['message']->added_by == get_current_user_id())
        $align = 'flex-end';
?>

<div style="padding:10px; max-width:90%; background:white; margin-bottom:10px; border-radius:3px; align-self:<?php echo $align; ?>;">
    <div style="font-size:.8em; color:gray;"><?php echo $args['message']->added; ?></div>
    <div><?php echo $args['message']->text; ?></div>

    <?php foreach($args['attachments'] as $attachment){ ?>
        <div><a href="<?php echo $attachment->file_path; ?>"><?php echo $attachment->file_name; ?></a></div>
    <?php } ?>
</div>

