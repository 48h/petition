<?php

/* 
 * Landing code after user clicks confirmation link in his email
 */

include_once './config.php';
include_once '../../../wp-includes/pluggable.php';
include_once './personinfo.php';
include_once './MailHandler2.php';
include_once './ConfirmationHandler.php';

// ================ Processing Confirmation Request ============================
$conf_table="confirmed_petition_tb";
$petition_temp_tb="temp_petition_tb";
$parlamenterier_tb="parliamentarier_tb";
$personalkey=filter_input (INPUT_GET,'personalkey',FILTER_SANITIZE_STRING);

$adapter = new \DbAdapter();
$db = $adapter->getDB();
$confHandler = new \ConfirmationHandler($petition_temp_tb, $conf_table,$parlamenterier_tb,$personalkey);

/*1*/$personalInfo = $confHandler->getTempRow($db);
/*2*/$confHandler->deleteTempRow($db);
if($personalInfo->keepMyInfo){
    $confHandler->insertToConfirmTable($db, $personalInfo);
}

// ================ Prepare to send mailer to the Parlamenterians===============
$receivers = $confHandler->getParlamenterierEmail($db);
$sender = get_option( 'admin_email' );

$subject = $personalInfo->getPageTitle();
$message = $personalInfo->petitionPageContent();

$mailer = new \MailHandler2($sender,$receivers);
$mailer->sendmail($message, $subject, true);

/*
$mailer = new \PHPMailer();
$mailHandle = new \MailHandler($mailer);

$mailHandler->headerInfoSettings();
$mailHandler->smtpSettings(465,'ssl');

$mailHandler->receiver($personalInfo->getEmail());
$mailHandler->setMailCredentials(get_option( 'admin_email'),"!Esther2Nango!"); //sender

//-----------------sending mail-------------------------
$subject = $personalInfo->getPageTitle();
$message = "<p>Mail body</p>";    
//             Sending mail             
$mailHandler->sendMail($subject, $message);
 */