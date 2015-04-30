<?php

/* 
 * Class of parlamenteriers
 */

class Perlamenterier{
    private $id=0;
    private $name=null;
    private $email=null;
    
    function Perlamenterier($_id,$_name,$_email){
        $this->id=$_id;
        $this->name=$_name;
        $this->email=$_email;
    }
}