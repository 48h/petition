<?php

/**
* Contructor of confirmationHandler Class
* @author Harrison Ssamanya <ssmny2@yahoo.co.uk>
* @param String $tempTb The name of the table for temporaly user information
* @param String $confTab The name of the table for confirmed petitions
* @param string $$parlam_tb The name of the Parlamenterian infos
* @param String $conf_code The code generated for a user
*/
class ConfirmationHandler{
    
    private $Temp_tb=null;
    private $confirm_tb = null;
    private $confirm_code= null;
    private $parlamenterier_tb = null;
    
    /**
     * Contructor of confirmationHandler Class
     * @author Harrison Ssamanya <ssmny2@yahoo.co.uk>
     * @param String $tempTb The name of the table for temporaly user information
     * @param String $confTab The name of the table for confirmed petitions
     * @param string $$parlam_tb The name of the Parlamenterian infos
     * @param String $conf_code The code generated for a user
     */
    function ConfirmationHandler($tempTb,$confTab,$parlam_tb,$conf_code){
        
        $this->Temp_tb=$tempTb;
        $this->confirm_tb=$confTab;
        $this->confirm_code=$conf_code;
        $this->parlamenterier_tb=$parlam_tb;
    }    
    
    /**
     * Gets the first row with the confirm_code!
     * @author Harrison Ssamanya <ssmny2@yahoo.co.uk>
     * @param DbAdapter $db The PersonalInfo object
     * @return PersonalInfo The personalInfo object
     */
    function getTempRow($db){
        
        $res = null;
        $personObj = null;
        
        $sql1="SELECT * FROM $this->Temp_tb WHERE confirm_code=:confirm_code";        
        $stmt = $db->prepare($sql1);
        $stmt->bindParam(':confirm_code',$this->confirm_code,  PDO::PARAM_STR);
                
        try{
           $stmt->execute();
           $res = $stmt->fetchAll();
            if(count($res) > 0){
                $personObj = new \PersonInfo($res[0]->name,$res[0]->email,
                                             $res[0]->street,$res[0]->zip,
                                             $res[0]->place,$res[0]->country,
                                             $res[0]->pageNo,$res[0]->keepmyinfo);
            }
        }  catch (Exception $e){
            echo nl2br('Database Error:\nSource:"<ConfirmationHandler->fromTempTbToConfirmTb>"\n'+$e,true);
        }  finally {
            if ($res){unset($res);}
            if ($stmt != null){ $stmt->closeCursor();}
        }
        return $personObj;
    }
    
    /**
     * communicates with the database:Temp_table, to remove the row the the 
     * confirm_code.
     * @author Harrison Ssamanya <ssmny2@yaho.co.uk>
     * @param DBAdaptor $db db addaptor Object
     */
    function deleteTempRow($db){
        
        $sql = "DELETE FROM "+$this->Temp_tb+" WHERE confirm_code=:confirm_code";
        $stmt = $db->Prepare($sql);
        $stmt->bindParam(':confirm_code',  $this->confirm_code,PDO::PARAM_STR);
        
        try {
            $stmt->execute();
        } catch (Exception $ex) {
            echo nl2br('Database DELETE Error:\nSource: "<deleteTempRow>"\n'+$ex);
        }  finally {
            if($stmt != NULL){ $stmt->closeCursor();}
        }
    }
        
    /**
     * Communicates with Database. Inserts data into confirm table
     * @author Harrison Ssamanya <ssmny2@yahoo.co.uk>
     * @param DBAdaptor $db Database adaptor object
     * @param PersonInfo $personInfo PersonInfo Object
     * @return void returnes nothing
     */
    function insertToConfirmTable($db,$persInfo){
        
        $sql="INSERT INTO $this->confirm_tb(name, email,street,zip,place,country,pageNo,pageTitle)".
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
            echo nl2br("Database Erro:\nSource: '<insertToTable>'Insert failed");
        }  finally {
            if($stmt != NULL){
                $stmt->closeCursor();
            }
        }
    }
    
    function getParlamenterierEmail($db){        
        
        $sql1="SELECT email FROM $this->parlamenterier_tb";        
        $stmt = $db->prepare($sql1);
                
        try{
           $stmt->execute();
           $res = $stmt->fetchColumn();
           $emails = implode(",", $res);
           
        }  catch (Exception $e){
            echo nl2br($e,true);
        }  finally {
            if ($res){unset($res);}
            if ($stmt != null){ $stmt->closeCursor();}
        }
        return $emails;
    }
}
