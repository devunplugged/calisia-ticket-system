<?php
namespace calisia_ticket_system;

require_once CALISIA_TICKET_SYSTEM_ROOT . '/src/models/message.php';

class message{
    protected $model;

    public function get_model(){
        return $this->model;
    }

    function __construct(){
        $this->model = new models\message();
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