<?php
namespace calisia_ticket_system;

require_once CALISIA_TICKET_SYSTEM_ROOT . '/src/intarface/kind.php';

class order implements kind{
    public function exists($id){
        if(get_post_type($id) == "shop_order")
            return true;
        return false;
    }

    public function can_open($user_id, $order_id){
        $order = wc_get_order( $order_id );

        if(!$order)
            return false;

        if($user_id == $order->get_user_id())
            return true;

        return true;
    }
}