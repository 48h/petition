<?php

 require_once __DIR__.'/config.php';
 require_once __DIR__.'/SQLInterfacer.php';
 require_once __DIR__."/../../../wp-load.php"; 
 require_once __DIR__.'/MailHandlerSwift.php';
 // -------------table name -----------------

    // Random confirmation code 
    $confirm_code=md5(uniqid(rand()));
     //-------------form Data ------------------
    $email=filter_input(INPUT_POST,'email',FILTER_SANITIZE_EMAIL);
    $name  = filter_input (INPUT_POST,'name',FILTER_SANITIZE_STRING);  
    
   //--------------------------------------------
    $adapter = new \DbAdapter();
    
    $db = $adapter->getDB();
    SQLInterfacer::setConfirm_code($confirm_code);
    
    if(SQLInterfacer::handlePostedData($db)){
        
        $senderInf = array(get_option('admin_email')=>get_option('blogname'));
        $receiverInf = array($email=>$name);

        $subject = get_option('blogname');
        $message = confirmationLink($confirm_code);
       
        // sending mail
        MailHandler3::sendMail($subject, $message,$senderInf,$receiverInf ,true);
        
        redirectToNotificationPage();
        //wp_safe_redirect( get_permalink(264), 301); // local app
        wp_safe_redirect( get_permalink(436 ),301); // live app
        exit;
    }
/*}else{
    throw new Exception("Bad Request Exception. This request is nolonger valid!");
}*/


function confirmationLink($confirm_code){
$messege=  "<!DOCTYP html>"
           ."<html>"
           ."<body>"
           ."<h3>Ihr Bestätigungslink</h3>"
                ."Bitte dieser Link anklicken, um die Email zu den abgeordneten weiter zu schicken.\r\n"
                ."<p><em>Wenn dieser sich nicht anklicken lässt, bitte einfach kopieren und im Browser-Adressfeld einfügen und bestätigen.</em></p>"
                .plugins_url()."/myplugin/confirmation.php?personalkey=$confirm_code</p>"
           ."</body>"
           ."</html>";
   return $messege;
}
