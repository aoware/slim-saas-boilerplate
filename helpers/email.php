<?php

namespace helpers;

class email {

    function send_email($template,$recipient_name,$recipient_email,$email_parameters,$twig) {
        
        // Generating the email content
        $subject_template = $twig->load($template . '.subject');
        $subject = $subject_template->render($email_parameters);
        
        $content_template = $twig->load($template . '.html');
        $email_content = $content_template->render($email_parameters);
        
        // Saving the entry in the DB
        $e = new \models\emails($this->db);
        
        $sm = new \helpers\string_manipulation;
        $unique_id = $sm->generate_random_code(32);
        $e->getRecordByView_online_id($unique_id);
        while (count($e->recordSet) > 0) {
            $unique_id = $sm->generate_random_code(32);
            $e->getRecordByView_online_id($unique_id);
        }   
        
        $e->view_online_id     = $unique_id;
        $e->recipient_email    = $recipient_email;
        $e->recipient_name     = $recipient_name;
        $e->creation_date      = date('Y-m-d H:i:s');
        $e->trigger_date       = date('Y-m-d H:i:s');
        $e->sent_date          = null;
        $e->status             = 'processing';
        $e->status_description = null;
        $e->subject            = $subject;
        $e->content            = $email_content;
        
        $result_save = $e->saveRecord();
        
        $email_id = $e->inserted_id;
        
        if ($result_save !== true) {
            throw new \Exception($result_save);
        }
        
        $p = new \PHPMailer\PHPMailer\PHPMailer();
        $p->isSMTP();
        $p->Host     = CONF_smtp_host;
        $p->SMTPAuth = true;
        $p->Port     = CONF_smtp_port;
        $p->Username = CONF_smtp_username;
        $p->Password = CONF_smtp_password;
        
        $p->setFrom(CONF_smtp_sender_email, CONF_smtp_sender_name);
        $p->addAddress($recipient_email, $recipient_name);
        
        $p->Subject = $subject;
        
        $p->isHTML(true);
        $p->msgHTML($email_content);
        
        $result_send = $p->send();
        
        if ($result_send) {
            $e->sent_date          = date('Y-m-d H:i:s');
            $e->status             = 'sent';
            
            $result_update = $e->updateRecord($email_id);
            
            if ($result_update !== true) {
                throw new \Exception($result_update);
            }
            
            return true;
        }
        else {
            $e->sent_date          = date('Y-m-d H:i:s');
            $e->status             = 'error';
            $e->status_description = $p->ErrorInfo;
                       
            $result_update = $e->updateRecord($email_id);
            
            if ($result_update !== true) {
                throw new \Exception($result_update);
            }
            
            return false;
        }
    }
    
}