<?php
namespace calisia_ticket_system;

if ( ! defined( 'ABSPATH' ) ) {
	exit();
}

class email{
    protected $to;
    protected $subject;
    protected $message;
    protected $headers = array('Content-Type: text/html; charset=UTF-8');
    protected $attachments;

    public function get_to(){
        return $this->to;
    }

    public function set_to($to){
        $this->to = $to;
    }

    public function get_subject(){
        return $this->subject;
    }

    public function set_subject($subject){
        $this->subject = $subject;
    }

    public function get_message(){
        return $this->message;
    }

    public function set_message($message){
        $this->message = $message;
    }

    public function get_headers(){
        return $this->headers;
    }

    public function set_headers($headers){
        $this->headers = $headers;
    }

    public function get_attachments(){
        return $this->attachments;
    }

    public function set_attachments($attachments){
        $this->attachments = $attachments;
    }

    public function send(){
        return wp_mail( $this->to, $this->subject, $this->message, $this->headers, $this->attachments );
    }

}