<?php
namespace calisia_ticket_system;

if ( ! defined( 'ABSPATH' ) ) {
	exit();
}

class Form_Token{
    public static function create_token($token_name = ''){
        //$_SESSION['calisia-form-token'] = self::generateRandomString();
        $token_name = 'calisia-form-token_' . $token_name; 
        update_user_meta(get_current_user_id(), $token_name, self::generateRandomString());
        return get_user_meta(get_current_user_id(), $token_name, true );
    }

    public static function check_token($token, $token_name = ''){
        $token_name = 'calisia-form-token_' . $token_name;
        $result = false;
        if($token == get_user_meta(get_current_user_id(), $token_name, true ))
            $result = true;
        
        delete_user_meta(get_current_user_id(), $token_name);
        return $result;
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