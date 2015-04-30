<?php
require_once '../../../wp-load.php';

/**
* Contructor
* @param String $_name The name of the user
* @param email $_email The email of the user
* @param String $_street The street of the user
* @param String $_zip The zip code
* @param String $_place The place of the user
* @param String $_countr The Country of the user
* @param Integer $pageId The page of the petition the user sent
* @author Harrison Ssamanya <ssmny2@yahoo.co.uk>
*/
class PersonInfo{
    private $name=null;
    private $email=null;
    private $street=null;
    private $zip=null;
    private $place=null;
    private $country=null;
    private $pageNo = null;
    private $pageTitle=null;
    private $keepMyInfo=null;
    
    function getKeepMyInfo() {
        return $this->keepMyInfo;
    }

    function getName() {
        return $this->name;
    }

    function getEmail() {
        return $this->email;
    }

    function getStreet() {
        return $this->street;
    }

    function getZip() {
        return $this->zip;
    }

    function getPlace() {
        return $this->place;
    }

    function getCountry() {
        return $this->country;
    }
    
    function getPageTitle() {
        return $this->pageTitle;
    }
    
    function getPageNo() {
        return $this->pageNo;
    }

            
    function set_Title($pageId){

        $page = get_post($pageId);
        if(is_page($page)){
            $this->pageTitle = $page->title;
        }else{
            echo nl2br('Resource not found! This page must have been deleted');
        }
    }
     
    /**
     * Contructor
     * @param String $_name The name of the user
     * @param email $_email The email of the user
     * @param String $_street The street of the user
     * @param String $_zip The zip code
     * @param String $_place The place of the user
     * @param String $_countr The Country of the user
     * @param Integer $pageId The page of the petition the user sent
     * @author Harrison Ssamanya <ssmny2@yahoo.co.uk>
     */
    function PersonalInfo($_name,$_email,$_street,$_zip,$_place,$_countr,$pageId){
        $this->name=$_name;
        $this->email=$_email;
        $this->street=$_street;
        $this->zip = $_zip;
        $this->place=$_place;
        $this->country=$_countr;
        $this->pageNo=$pageId;
        $this->set_Title($pageId);
    }
    
    /**
     * The function returns the html content of  page
     * @author Harrison Ssamanya <ssmny2@yahoo.co.uk>
     * @return String The Html Content of  page
     */
    function petitionPageContent(){
        $post = get_page($this->getPageNo()); 
        $content = apply_filters('the_content', $post->post_content);
        return $content;
    }
}
