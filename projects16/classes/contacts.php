<?php
include("connect.php");


class Contact 
{
    
    private $contact_id;
    private $full_name;
    private $contact_email;
    private $contact_message;

    public function __construct(
    $contact_id="", $full_name="" , $email="" , $message=""  
      ) {
     
        $this->contact_id = $contact_id;
        $this->full_name = $full_name ;
        $this->contact_email = $email ;
        $this->contact_message = $message;
        
        }

    public function get_contact_id() {
      return $this->contact_id;
    }
    public function get_full_name() {
        return $this->full_name;
    }
    public function get_contact_email() {
        return $this->contact_email;
    }

    public  function get_contact_message() {
        return $this->contact_message;
    }


    public function add_new_contact(){
        global $bdd;
        $insercontact = $bdd->prepare("INSERT INTO `contact` (`contact_id`,`full_name`,`email`, `message`) VALUES (?,?,?,?)");

        $insercontact->execute(array($this->contact_id,$this->full_name,$this->contact_email,$this->contact_message));

    }
    public function delete_contact(){
        global $bdd;
        $deletecontact = $bdd->prepare("DELETE FROM `contact` WHERE contact_id = ? ;");

        $deletecontact->execute(array($this->contact_id)); 

    }
    public static function get_all_contact():array{
        global $bdd;
        $getallcontacts = $bdd->prepare("SELECT * FROM `contact`;");
        $getallcontacts->execute();    
    return $getallcontacts->fetchAll();
    
    }
}

