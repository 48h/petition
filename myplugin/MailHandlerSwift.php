<?php

/* 
 * Mail Handler using Swift Mailer
 */
require_once __DIR__.'/swiftmailer/lib/swift_required.php';

class MailHandler3{
    
    public static function sendMail($subject, $HtmlBody,$senderInf,$receiverInf,$isHtml){
        
        $serverPort=25;
        $password='Inteoeff15';    
        $userName='wp12343970-407483';   
        $serverName='wp090.webpack.hosteurope.de';
        
        $transport = Swift_SmtpTransport::newInstance($serverName, $serverPort);
        $transport->setUsername($userName);
        $transport->setPassword($password);
        $transport->setEncryption('tls');
        
        $mailer = Swift_Mailer::newInstance($transport);
        $message =  Swift_Message::newInstance($subject,$HtmlBody);
        
        if($isHtml){
            $message->setContentType('text/html');
        }
        
        $message->setFrom($senderInf);
        $message->setTo($receiverInf);
        $no=$mailer->send($message);
        printf("Sent %d messages\n", $no);

        if($no < 1){
            echo nl2br("Message was not send");
        }
    }
}

