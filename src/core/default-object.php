<?php
namespace calisia_ticket_system;

class default_object{
    protected function fill($object){
        foreach(get_object_vars($object) as $key => $value){
            if(property_exists($this, $key))
                $this->$key = $value;
        }
    }
}