<?php
namespace calisia_ticket_system;

class default_object{
    protected function fill($object){
        foreach(get_object_vars($object) as $key => $value){
            if(property_exists($this, $key))
                $this->$key = $value;
        }
    }

    public static function get_models($results, $class_name){
        $elements = array();
        foreach($results as $result){
            $element = new $class_name();
            $element->get_model()->fill($result);
            $elements[] = $element;
        }
        return $elements;
    }
}