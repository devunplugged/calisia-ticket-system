<?php
namespace calisia_ticket_system;

class Form_Token{
    public static function create_token(){
        //$_SESSION['calisia-form-token'] = self::generateRandomString();
        update_user_meta(get_current_user_id(), 'calisia-form-token', self::generateRandomString());
        return get_user_meta(get_current_user_id(), 'calisia-form-token', true );
    }

    public static function check_token($token){
        if($token == get_user_meta(get_current_user_id(), 'calisia-form-token', true ))
            return true;
        return false;
    }

    public static function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}