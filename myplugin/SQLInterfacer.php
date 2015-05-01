<?php

/**
* quasi static class
*/
class SQLInterfacer{
    
    static $Temp_tb="temp_petition_tb";
    static $confirm_tb = "confirmed_petition_tb";
    static $confirm_code= null;
    static $parlamenterier_tb = "parliamentarier_tb";
    
    public static function getConfirm_code() {
        return self::$confirm_code;
    }

    public static function setConfirm_code($confirm_code) {
        self::$confirm_code = $confirm_code;
    }
    
    /**
     * Gets the first row with the confirm_code!
     * @author Harrison Ssamanya <ssmny2@yahoo.co.uk>
     * @param DbAdapter $db The PersonalInfo object
     * @return PersonalInfo The personalInfo object
     */
    public static function getTempRow($db){
        
        $personObj = new \PersonInfo();
        
        $sql1="SELECT * FROM ". self::$Temp_tb ." WHERE confirm_code=:confirm_code";        
        $stmt = $db->prepare($sql1);
        $stmt->bindParam(':confirm_code',self::$confirm_code,  PDO::PARAM_STR);
      
        try{
           $stmt->execute();
           $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
           
            if(count($res) > 0){
                
                $personObj ->setName($res[0]['name']);
                $personObj->setEmail($res[0]['email']);
                $personObj->setStreet($res[0]['street']);
                $personObj->setZip($res[0]['zip']);
                $personObj->setPlace($res[0]['place']);
                $personObj->setCountry($res[0]['country']);
                $personObj->setPageNo($res[0]['pageNo']);
                $personObj->setKeepMyInfo($res[0]['keepmyinfo']);
                $personObj->set_Title($res[0]['pageNo']);
            }  else {
                echo 'Temp Table is Empty!';
            }
            
            //print_r($personObj->getEmail());
        }  catch (Exception $e){
            echo nl2br('Database Error:\nSource:"<ConfirmationHandler->fromTempTbToConfirmTb>"\n'+$e,true);
        } 
        return $personObj;
    }
    
    /**
     * communicates with the database:Temp_table, to remove the row the the 
     * confirm_code.
     * @author Harrison Ssamanya <ssmny2@yaho.co.uk>
     * @param DBAdaptor $db db addaptor Object
     */
    public static function deleteTempRow($db){        
        $sql = "DELETE FROM ".self::$Temp_tb." WHERE confirm_code=:confirm_code";
        $stmt = $db->Prepare($sql);
        $stmt->bindParam(':confirm_code',self::$confirm_code,PDO::PARAM_STR);
        
        try {
            $stmt->execute();
        } catch (\Exception $ex) {
            echo nl2br('Database DELETE Error:\nSource: "<deleteTempRow>"\n'.$ex->getMessage());
        } 
    }
        
    /**
     * Communicates with Database. Inserts data into confirm table
     * @author Harrison Ssamanya <ssmny2@yahoo.co.uk>
     * @param DBAdaptor $db Database adaptor object
     * @param PersonInfo $personInfo PersonInfo Object
     * @return void returnes nothing
     */
    public static function insertToConfirmTable($db,$persInfo){
        
        $sql="INSERT INTO ".self::$confirm_tb."(name, email,street,zip,place,country,pageNo,pageTitle,keepmyinfo)".
             "VALUES(:name, :email, :street,:zip,:place,:country,:pageNo,:pageTitle,:keepmyinfo)";

        $stmt = $db->prepare($sql);
        $stmt->bindParam(':name', $persInfo->getName());
        $stmt->bindParam(':email', $persInfo->getEmail());
        $stmt->bindParam(':street', $persInfo->getStreet());
        $stmt->bindParam(':zip', $persInfo->getZip());
        $stmt->bindParam(':place', $persInfo->getPlace());
        $stmt->bindParam(':country', $persInfo->getCountry());
        $stmt->bindParam(':pageNo', $persInfo->getPageNo());
        $stmt->bindParam(':pageTitle',$persInfo->getPageTitle());
        $stmt->bindParam(':keepmyinfo',$persInfo->getKeepMyInfo());

        try{
            $stmt->execute();            
        }  catch (Exception $ex){
            echo nl2br("Database Erro:\nSource: '<insertToTable>'Insert failed".$ex->getMessage());
        }
    }
    
    /**
     * processes information from the table of Palamenterians
     * @author Harrison Ssamanya <ssmny2@yahoo.co.uk>
     * @param PDO $db The adapter to the database
     * @return Array Ans associative array of "email"="name"
     */
    public static function getParlamenterierEmail($db){        
        
        $sql1="SELECT email,name FROM ". self::$parlamenterier_tb;        
        $stmt = $db->prepare($sql1);
        $emails = array();                
        try{
           $stmt->execute();
           $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
           
           foreach ($res as $singVal){               
               $emails[$singVal['email']]= $singVal['name'];               
           }          
        }  catch (Exception $e){
            echo nl2br($e->getMessage(),true);
        } 
        return $emails;
    }
    
    public static function handlePostedData($db){       
    
        $email=filter_input(INPUT_POST,'email',FILTER_SANITIZE_EMAIL);
        $name  = filter_input (INPUT_POST,'name',FILTER_SANITIZE_STRING);  
        $street=filter_input (INPUT_POST,'street',FILTER_SANITIZE_STRING);  
        $zip   =filter_input (INPUT_POST,'zip',FILTER_SANITIZE_STRING);  
        $place =filter_input (INPUT_POST,'place',FILTER_SANITIZE_STRING);  
        $country=filter_input (INPUT_POST,'country',FILTER_SANITIZE_STRING);
        $pageId=  filter_input(INPUT_POST, 'pageId',FILTER_SANITIZE_STRING);
       //--------------------------------------------
        
         // // Insert data into database 
        $sql="INSERT INTO ". self::$Temp_tb."(confirm_code, name, email,street,zip,place,country,pageNo)".
             "VALUES(:confirm_code, :name, :email, :street,:zip,:place,:country,:pageNo)";
        
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':confirm_code', self::$confirm_code);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':street', $street);
        $stmt->bindParam(':zip', $zip);
        $stmt->bindParam(':place', $place);
        $stmt->bindParam(':country', $country);
        $stmt->bindParam(':pageNo',$pageId);

        try {
            $stmt->execute();
            return true;
        } catch (Exception $ex) {
            echo (nl2br("Database Insert Failed\r\n".$ex->getMessage()));
        }
        return false;
    }
}
