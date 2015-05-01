<?php

/* 
 * Landing code after user clicks confirmation link in his email
 */

include_once './config.php';
include_once '../../../wp-includes/pluggable.php';
include_once './personinfo.php';
include_once './MailHandlerSwift.php';
include_once './SQLInterfacer.php';


// ================ Processing Confirmation Request ============================

$personalkey=filter_input (INPUT_GET,'personalkey',FILTER_SANITIZE_STRING);

$adapter = new \DbAdapter();
$db = $adapter->getDB();

SQLInterfacer::setConfirm_code($personalkey);

$personalInfo = SQLInterfacer::getTempRow($db);
SQLInterfacer::deleteTempRow($db);

if($personalInfo->getKeepMyInfo()){    
    SQLInterfacer::insertToConfirmTable($db, $personalInfo);
}

// ================ Prepare to send mailer to the Parlamenterians===============
$receivers = SQLInterfacer::getParlamenterierEmail($db);
$sender = get_option( 'admin_email' );
//$sender = array($personalInfo->getEmail()=>$personalInfo->getName());

$subject = $personalInfo->getPageTitle();
$HtmlBody = $personalInfo->petitionPageContent();

// sending mail using Swift mailer
MailHandler3::sendMail($subject, $HtmlBody, $sender, $receivers, TRUE);

//wp_safe_redirect( get_permalink(268 ), 301 ); // test local
wp_safe_redirect( get_permalink(443 ), 301 ); // Live app
exit;