
<?php if($args['pagination']['left_cut']){ ?>
    <a href="<?php echo $args['pagination']['first']['url']; ?>"><?php echo $args['pagination']['first']['page']; ?></a> | 
<?php } ?>

<?php  
    $pagination = "";
    foreach($args['pagination']['elements'] as $page){ 
        $pagination .= '<a ';
        if(isset($page['current']))
            $pagination .= 'style="color:red;" ';
        $pagination .= 'href="'.$page['url'].'">'.$page['page'].'</a> | ';
    }
    echo rtrim($pagination, ' | ');
?>

<?php if($args['pagination']['right_cut']){ ?>
    | <a href="<?php echo $args['pagination']['last']['url']; ?>"><?php echo $args['pagination']['last']['page']; ?></a>
<?php } ?>