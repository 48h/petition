<?php
include_once '../../../wp-includes/pluggable.php';
/**
* Constructor for the mailer object. I sets the mailer with sender and
*  the receivers email addresses
* @author Harrison Ssamanya <ssmny2@yahoo.co.uk>
* @param String $_sender Senders email address
* @param String $_receiver A string containing the receivers' mail 
* @param String $_header  The header of the email.
* address(es)
*/
class MailHandler2{
    
    private $sender = null;
    private $receiver=null;
    private $header = null;
    private $recName = null;
    
    function getHeader() {
        return $this->header;
    }
    function setHeader($header) {
        $this->header = $header;
    }

    
        
    function getRecName() {
        return $this->recName;
    }
    function setRecName($recName) {
        $this->recName = $recName;
    }

        /**
     * Constructor for the mailer object. I sets the mailer with sender and
     *  the receivers email addresses
     * @author Harrison Ssamanya <ssmny2@yahoo.co.uk>
     * @param String $_sender Senders email address
     * @param String $_receiver A string containing the receivers' mail 
     * @param String $_header  The header of the email.
     * address(es)
     */
    function MailHandler2($_sender,$_receiver){
        $this->sender = $_sender;
        $this->receiver=$_receiver;
    }
    
    /**
     * Using the Wordpress mail method i.e. wp_mail(), the method sends the 
     * email
     * @param String $body The sring body of the email. It must be of html type
     * @param String $subject The Subject of the email
     * @param bool $isHtml Specifies whether the email being sent is of html type
     */
    function sendmail($body,$subject,$isHtml){
        if($isHtml==true){
            $this->header .= "Content-type: text/html; charset=iso-8859-1\r\n";            
        }
        if(wp_mail($this->receiver, $subject, $body,$this->header)){
            echo "Mail Sent From:".$this->sender. ", to:".$this->receiver;
        }else{
            //throw new Exception("wp_mail could not send the mail \n"
            //        . "From:".$this->sender. ", to:".$this->receiver);
            echo "Failed To Send From:".$this->sender. ", to:".$this->receiver;
        }
    }
}

