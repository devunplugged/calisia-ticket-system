<?php
namespace calisia_ticket_system;

require_once CALISIA_TICKET_SYSTEM_ROOT . '/src/models/message.php';

class message{
    protected $model;

    public function get_model(){
        return $this->model;
    }

    function __construct($message_id = 0){
        $this->model = new models\message();
        
        if($message_id === 0)
            return;

        if(!is_numeric($message_id))
            return;

        $message_id = (int)$message_id;

        if(!is_int($message_id))
            return;

        $this->model->set_id($message_id);
        $this->load_class_values();
    }

    public function load_class_values(){
        $this->model->db_fill();  
    }

    public function get_attachments(){
        global $wpdb;

        $results = $wpdb->get_results(
            $wpdb->prepare(
            "SELECT * FROM ".$wpdb->prefix."calisia_ticket_system_file WHERE message_id = %d",
            array(
                $this->model->get_id()
               )
            )
        );

        $attachments = array();
        foreach($results as $result){
            $file = new file();
            $file->get_model()->fill($result);
            $attachments[] = $file;
        }

        return $attachments;
    }
}