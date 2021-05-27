<?php
namespace calisia_ticket_system;

class ajax{
    public static function user_search(){
/*
        if(!wp_verify_nonce( $_POST['calisia_nonce'], 'calisia_delete_product_note_' . $_POST['post_id'] )){
            echo json_encode(array('result'=>0, 'id'=>$_POST['id']));
            wp_die();
        }
*/
       // $users = get_users( array( 'search' => '*' . $_POST['phrase'] . '*' ) );
       //$users = get_users( array( 'role__in' => array( 'administrator', 'subscriber') ));
        $users = data::get_users('%' . $_POST['phrase'] . '%');
        echo json_encode(
            array(
                'users'=> $users,
                'phrase' => $_POST['phrase']
            )
        );
        wp_die();
    }
}