<?php
namespace calisia_ticket_system;

class ajax{
    public static function search(){
/*
        if(!wp_verify_nonce( $_POST['calisia_nonce'], 'calisia_delete_product_note_' . $_POST['post_id'] )){
            echo json_encode(array('result'=>0, 'id'=>$_POST['id']));
            wp_die();
        }
*/
       // $users = get_users( array( 'search' => '*' . $_POST['phrase'] . '*' ) );
       //$users = get_users( array( 'role__in' => array( 'administrator', 'subscriber') ));
       
       switch($_POST['kind']){
           case 'user': $results = self::user_search(); break;
           case 'order': $results = self::order_search(); break;
           default: $results = array();
       }
        
        echo json_encode(
            array(
                'results'=> $results,
                'phrase' => $_POST['phrase'],
                'kind' => $_POST['kind']
            )
        );
        wp_die();
    }

    private static function user_search(){
        return data::get_users('%' . $_POST['phrase'] . '%');
    }

    private static function order_search(){
        $results = data::get_user_orders('%' . $_POST['phrase'] . '%', $_POST['user_id']);
        for($i = 0; $i < count($results); $i++){
            $results[$i]->order_status = wc_get_order_status_name( $results[$i]->order_status );
        }
        return $results;
    }
}