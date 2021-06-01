<?php
namespace calisia_ticket_system\models;

class model{
    public function fill($object){
        foreach(get_object_vars($object) as $key => $value){
            if(property_exists($this, $key))
                $this->$key = $value;
        }
    }

    public function save(){
        global $wpdb;

        $params = array();
        foreach(get_object_vars($this) as $key => $value){
            if($key == 'id') //skip id when inserting data
                continue;
            $params[$key] = $value;
        }

        $result = $wpdb->insert( 
            $this->get_table_name(), 
            $params
        );
        $this->set_id($wpdb->insert_id);
    }

    public function update(/*$params = array(), $where = array(), $types = array(), $where_type = array()*/){

      //  if(empty($params)){
            $params = array();
            $types = array();
            foreach(get_object_vars($this) as $key => $value){
                $params[$key] = $value;
                if(is_float($value)){
                    $types[] = '%f';
                }elseif(is_int($value)){
                    $types[] = '%d';
                }else{
                    $types[] = '%s';
                }
            }
      //  }

       // if(empty($where)){
            $where = array('id' => $this->get_id());
            $where_type = array( '%d' );
        //}

        global $wpdb;
        return $wpdb->update( 
            $this->get_table_name(), 
            $params, 
            $where, 
            $types, 
            $where_type
        );
    }

    public function get_table_name(){
        global $wpdb;
        return $wpdb->prefix . 'calisia_ticket_system_' . $this->get_class_name($this);
    }

    public function get_class_name(){
        $class_parts = explode('\\', get_class($this));
        return end($class_parts);
    }

}