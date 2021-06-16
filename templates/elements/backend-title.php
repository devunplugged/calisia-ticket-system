<div class="wrap">
    <h1 class="wp-heading-inline"><?php echo $args['title']; ?></h1> 
    <?php 
        if(isset($_GET['user_id'])){
            $user = get_user_by('ID', $_GET['user_id']);
            echo '<h2>' . $user->first_name . ' ' . $user->last_name . ' (' . $user->user_email . ')</h2>';
        }
    ?>
    <?php echo isset($args['button']) ? $args['button'] : ''; ?>
</div>