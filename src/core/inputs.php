<?php
namespace calisia_ticket_system;

class inputs{

    public static function input($options, $output = false){
        $input = '<div><label>'.$options['label'].'</label></div>';
        $input .= '<input type="'.$options['type'].'" id="'.$options['id'].'" name="'.$options['name'].'" class="'.$options['class'].'" value="'.$options['value'].'">';
        if(!$output)
            return $input;

        echo $input;
    }

    public static function textarea($options, $output = false){
        $ta = '<label>'.$options['label'].'</label>';
        $ta .= '<textarea placeholder="'.$options['placeholder'].'" id="'.$options['id'].'" name="'.$options['id'].'" class="'.$options['class'].'">';
        $ta .= $options['value'];
        $ta .= '</textarea>';

        if(!$output)
            return $ta;

        echo $ta;
    }

    public static function select($options,  $output = false){
        $select = '<select name="'.$options['name'].'" id="'.$options['id'].'" class="'.$options['class'].' calisia-select2">';
        foreach($options['options'] as $key => $value){
            $select .= '<option value="'.$value.'" ';
            if($options['value'] == $value)
                $select .= 'selected';
            $select .= '>';
            $select .= $key;
            $select .= '</option>';
        } 
        $select .= '</select>';

        if(!$output)
            return $select;

        echo $select;
    }
}