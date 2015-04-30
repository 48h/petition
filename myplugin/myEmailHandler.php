<?php

class MailHandler{

    private $Mailer = null;

    function MailHandler($mailer){
            $this->Mailer = $mailer;
    }

    function setMailCredentials(){
            $this->Mailer->Username = "wp12343970-407483";
            $this->Mailer->Password = "Inteoeff15";
    }

    function smtpSettings(){
        
            $this->Mailer->isSMTP(); //switch to smtp
            $this->Mailer->Port = 25;
            //$this->Mailer->SMTPAuth = true;		
            $this->Mailer->SMTPSecure = 'tls';
            $this->Mailer->Host = 'wp090.webpack.hosteurope.de';
    }

    function headerInfoSettings($sender){
        $user_info = get_userdata(1);
        $first_name = $user_info->first_name ==NULL?'':$user_info->first_name;
        $last_name = $user_info->last_name==NULL?'':$user_info->last_name;
        $userFname= $first_name.' '.$last_name;
        $sender = ($sender == NULL)||($sender == '')? get_option('admin_email'):$sender;
        
        $this->Mailer->From = get_option( 'admin_email' );
        $this->Mailer->FromName = $userFname;
        $this->Mailer->AddReplyTo($sender);
    }

    function receiver($recMail,$name){
            $this->Mailer->AddAddress($recMail,$name);
    }

    function sendMail($subject, $HtmlBody){
        
        $this->Mailer->Subject = $subject;
        $this->Mailer->Body = $HtmlBody;

        if($this->Mailer->Send()){
           echo '<div>';
           echo '<p>Danke, dass Sie sich an diese Petition beteiligen möchten'
                .'Eine Email würde an Ihre Adresse gesendet. Dort können Sie auf einen Link'
                . 'Clicken, diese Prozess abzuschliessen.'
                . '.</p>';
           echo '</div>';
        }else{
           //throw new Exception(nl2br("send Failed!\nError: {$this->Mailer->ErrorInfo}\n"));
            echo nl2br("send Failed!\nError: {$this->Mailer->ErrorInfo}\n");
        }
    }
}