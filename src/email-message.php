<?php
namespace calisia_ticket_system;

require_once CALISIA_TICKET_SYSTEM_ROOT . '/src/core/email.php';

class email_message extends email{
    /*public function send_reply_to_user($message){
        $ticket = new ticket($message->get_model()->get_ticket_id());
        $user = get_user_by('ID', $ticket->get_model()->get_user_id());

        $this->to = $user->user_email;

        $this->subject = sprintf(__('New reply to ticket: (#%1$s) %2$s','calisia-ticket-system'), $ticket->get_model()->get_id(), $ticket->get_model()->get_title());
        
        $mail_header_template_path = options::get_option_value('email_header_path') ? options::get_option_value('email_header_path') : '/wp-content/plugins/woocommerce/templates/emails/email-header.php';
        $mail_footer_template_path = options::get_option_value('email_footer_path') ? options::get_option_value('email_footer_path') : '/wp-content/plugins/woocommerce/templates/emails/email-footer.php';

        //email template variables
        $email_heading = $this->subject;
        //email template variables

        ob_start();
        include ABSPATH . $mail_header_template_path;
        $this->message = ob_get_contents();
        ob_end_clean();

        $this->message .= __('Your ticket has a new response', 'calisia-ticket-system');
        $this->message .= '<br>';
        $this->message .= __('Reply content:', 'calisia-ticket-system');
        $this->message .= '<br>';
        $this->message .= $message->get_model()->get_text();
        $this->message .= '<br>';
        $this->message .= sprintf(__('Visit %1$s to read the whole conversation', 'calisia-ticket-system'), $ticket->get_frontend_ticket_url());
        $this->message .= '<br>';
        $this->message .= __('All the best from Spiżarnia Rumianek team', 'calisia-ticket-system');

        ob_start();
        include ABSPATH . $mail_footer_template_path;
        $this->message .= ob_get_contents();
        ob_end_clean();

        $this->attachments = $this->get_attachments_paths($message->get_attachments());

        return $this->send();
    }*/

    private function get_attachments_paths($attachments){
        $attachments_array = array();
        foreach($attachments as $attachment){
            $attachments_array[] = ABSPATH . substr(parse_url($attachment->get_model()->get_file_path(), PHP_URL_PATH), 1);
        }
        return $attachments_array;
    }

    public function send_notification_to_client($message){
        $ticket = new ticket($message->get_model()->get_ticket_id());
        $user = get_user_by('ID', $ticket->get_model()->get_user_id());

        $this->to = $user->user_email;

        $this->subject = sprintf(__('New reply to ticket: (#%1$s) %2$s','calisia-ticket-system'), $ticket->get_model()->get_id(), $ticket->get_model()->get_title());

        $this->message = __('Your ticket has a new response', 'calisia-ticket-system');
        $this->message .= '<br>';
        $this->message .= __('Reply content:', 'calisia-ticket-system');
        $this->message .= '<br>';
        $this->message .= $message->get_model()->get_text();
        $this->message .= '<br>';
        $this->message .= sprintf(__('Visit %1$s to read the whole conversation', 'calisia-ticket-system'), $ticket->get_frontend_ticket_url());
        $this->message .= '<br>';
        $this->message .= __('All the best from Spiżarnia Rumianek team', 'calisia-ticket-system');

        $this->attachments = $this->get_attachments_paths($message->get_attachments());

        $this->send_ticket_notification();

    }

    public function send_notification_to_support($message){
        $ticket = new ticket($message->get_model()->get_ticket_id());
        $user = get_user_by('ID', $ticket->get_model()->get_user_id());

        $this->to = options::get_option_value('support_email');

        $this->subject = sprintf(__('New reply to ticket: (#%1$s) %2$s','calisia-ticket-system'), $ticket->get_model()->get_id(), $ticket->get_model()->get_title());
        
        $this->message = __('Ticket has a new response', 'calisia-ticket-system');
        $this->message .= '<br>';
        $this->message .= __('Reply content:', 'calisia-ticket-system');
        $this->message .= '<br>';
        $this->message .= sprintf(__('User %1$s says:', 'calisia-ticket-system'), $user->user_firstname . " " . $user->user_lastname . " (" . $user->user_email . ")");
        $this->message .= '<br>';
        $this->message .= $message->get_model()->get_text();
        $this->message .= '<br>';
        $this->message .= sprintf(__('Visit %1$s to read the whole conversation', 'calisia-ticket-system'), $ticket->get_backend_ticket_url());
        $this->message .= '<br>';
        $this->message .= __('All the best from Spiżarnia Rumianek team', 'calisia-ticket-system');

        $this->attachments = $this->get_attachments_paths($message->get_attachments());

        $this->send_ticket_notification();

    }

    public function send_ticket_notification(){   
        $mail_header_template_path = options::get_option_value('email_header_path') ? options::get_option_value('email_header_path') : '/wp-content/plugins/woocommerce/templates/emails/email-header.php';
        $mail_footer_template_path = options::get_option_value('email_footer_path') ? options::get_option_value('email_footer_path') : '/wp-content/plugins/woocommerce/templates/emails/email-footer.php';

        /* email template variables*/
        $email_heading = $this->subject;
        /* email template variables*/

        ob_start();
        include ABSPATH . $mail_header_template_path;
        $message_header = ob_get_contents();
        ob_end_clean();

        ob_start();
        include ABSPATH . $mail_footer_template_path;
        $message_footer .= ob_get_contents();
        ob_end_clean();

        $this->message = $message_header . $this->message . $message_footer;

        return $this->send();
    }
}