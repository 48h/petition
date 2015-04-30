<?php
 require_once ('config.php');
 require_once('myEmailHandler.php');
 require_once("../../../wp-load.php"); 
 require_once './../../../wp-includes/class-phpmailer.php';
 
 
 // -------------table name -----------------
  // if the submit button is clicked, send the email
    if ( isset( $_POST['pt_submit'] ) ) {
        // -------------table name -----------------
        $tbl_name="temp_petition_tb";
        // Random confirmation code 
        $confirm_code=md5(uniqid(rand()));
         //-------------form Data ------------------
        $email=filter_input(INPUT_POST,'email',FILTER_SANITIZE_EMAIL);

        $name  = filter_input (INPUT_POST,'name',FILTER_SANITIZE_STRING);  
        $street=filter_input (INPUT_POST,'street',FILTER_SANITIZE_STRING);  
        $zip   =filter_input (INPUT_POST,'zip',FILTER_SANITIZE_STRING);  
        $place =filter_input (INPUT_POST,'place',FILTER_SANITIZE_STRING);  
        $country=filter_input (INPUT_POST,'country',FILTER_SANITIZE_STRING);
        $pageId=  filter_input(INPUT_POST, 'pageId',FILTER_SANITIZE_STRING);
       //--------------------------------------------
        // my database adopter
        $adapter = new \DbAdapter();
        $db = $adapter->getDB();
        
         // // Insert data into database 
        $sql="INSERT INTO $tbl_name(confirm_code, name, email,street,zip,place,country,pageNo)".
             "VALUES(:confirm_code, :name, :email, :street,:zip,:place,:country,:pageNo)";
        
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':confirm_code', $confirm_code);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':street', $street);
        $stmt->bindParam(':zip', $zip);
        $stmt->bindParam(':place', $place);
        $stmt->bindParam(':country', $country);
        $stmt->bindParam(':pageNo',$pageId);

        if($stmt->execute()){
            
            $mailer = new \PHPMailer();  $mailer->SMTPDebug=3;
            $mailHandler = new MailHandler($mailer);  
            
            $mailHandler->receiver($email,$name);
            $mailHandler->headerInfoSettings(null);
            $mailHandler->smtpSettings();
            $mailHandler->setMailCredentials(); //sender       
            
            //-----------------sending mail-------------------------
            $subject = "Petition Title";
            $message = "Your Comfirmation link \r\n";
            $message.= "Dieser Link anclicken, um die Email zu den abgeordneten weiter zu schicken \r\n";
            $message.= plugins_url()."/myplugin/confirmation.php?personalkey=$confirm_code";
            $header = "FROM: ".$name." <".$_sender.">\r\n"
                      ."MIME-Version: 1.0\r\n"; 
            
            try{
                $mailHandler->sendMail($subject, $message);	
            }catch(Exception $e){
                echo nl2br("Error: \n".$e->getMessage());
            }
        }else{
            echo nl2br("\nDatabase Error:\nData could not be inserted!\n"
                    . "Perhaps the email has already been used");
        }
    }