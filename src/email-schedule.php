<?php
namespace calisia_ticket_system;

require_once CALISIA_TICKET_SYSTEM_ROOT . '/src/models/email-schedule.php';

class email_schedule{

    protected $model;

    public function get_model(){
        return $this->model;
    }

    function __construct(){
        $this->model = new models\email_schedule();
    }

    public function schedule($message_id, $send_to){
       // require_once CALISIA_TICKET_SYSTEM_ROOT . '/src/models/email-schedule.php';
       // $schedule = new email_schedule();
        $this->model->set_message_id($message_id);
        $this->model->set_date_added(current_time( 'mysql' ));
        $this->model->set_send_to($send_to);
        $this->model->save();
    }

    public function send_schedule(){
        $scheduled_emails = data::get_scheduled_emails();

        if(!is_array($scheduled_emails) || count($scheduled_emails) == 0)
            return;

        require_once CALISIA_TICKET_SYSTEM_ROOT . '/src/email-message.php';
        $email_message = new email_message();
        require_once CALISIA_TICKET_SYSTEM_ROOT . '/src/message.php';

        foreach($scheduled_emails as $scheduled_email){
            $message_id = $scheduled_email->get_model()->get_message_id();
            $message = new message($message_id);
            if($scheduled_email->get_model()->get_send_to() == 'customer'){
                $email_message->send_notification_to_client($message);
            }else{
                $email_message->send_notification_to_support($message);
            }
        }
    }

    public function test(){
        $mail_header_template_path = options::get_option_value('email_header_path') ? options::get_option_value('email_header_path') : '/wp-content/plugins/woocommerce/templates/emails/email-header.php';
        $mail_footer_template_path = options::get_option_value('email_footer_path') ? options::get_option_value('email_footer_path') : '/wp-content/plugins/woocommerce/templates/emails/email-footer.php';

        /* email template variables*/
        $email_heading = 'ytyyyyy';
        /* email template variables*/

        ob_start();
        include ABSPATH . $mail_header_template_path;
        $message_header = ob_get_contents();
        ob_end_clean();

        ob_start();
        include ABSPATH . $mail_footer_template_path;
        $message_footer .= ob_get_contents();
        ob_end_clean();

        $message = $message_header . 'hhhhhhhhh' . $message_footer;

        return $message;
    }
}