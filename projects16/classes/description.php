<?php
include("connect.php");


class Description 
{
    
  private $product_id;
  private $description_type;
  private $description_origin;
  private $description_caffeine;
  private $description;
  private $description_benefits;
  private $description_prepare;

  public function __construct(
    $product_id ,
    $type = ""  ,
    $origin = "" ,
    $caffeine = "" ,
    $description = "",
    $benefits = "" ,
    $prepare = ""
    ) 
  {
       $this->product_id = $product_id;
       $this->description_type = $type;
       $this->description_origin = $origin;
       $this->description_caffeine = $caffeine;
       $this->description = $description;
       $this->description_benefits = $benefits;
       $this->description_prepare = $prepare;
  }
  
  public function add_new_description(){
      global $bdd;
      $inserdescription = $bdd->prepare("INSERT INTO `description` (`product_id`,`type`,`origin`,`caffeine`, `description`, `benefits`, `prepare`) VALUES (?,?,?,?,?, ?, ?)");

      $inserdescription->execute(array($this->product_id,$this->description_type,$this->description_origin ,$this->description_caffeine, $this->description, $this->description_benefits, $this->description_prepare));

  }
  public function mody_description($array_keys){
    $values=[];
    $kk=["type","origin","caffeine", "description", "benefits", "prepare"];
    $kk2=[$this->description_type,$this->description_origin ,$this->description_caffeine, $this->description, $this->description_benefits, $this->description_prepare];
    $keys="";
    for($i=0;$i<sizeof($array_keys);$i++){
      $keys.=" `".$array_keys[$i]."` = ? ";

      array_push($values,$kk2[array_search($array_keys[$i],$kk)]);
      if($i!=sizeof($array_keys)-1){
        $keys.=",";
      }
    }

      global $bdd;
      $inserdescription = $bdd->prepare("UPDATE `description` SET $keys WHERE product_id = ? ;");

      $inserdescription->execute(array(...$values,$this->product_id)); 

      
  }
  public function delete_description(){
      global $bdd;
      $deletedescription = $bdd->prepare("DELETE FROM `description` WHERE product_id = ? ;");

      $deletedescription->execute(array($this->product_id)); 

  }
  public static function get_all_description():array{
      global $bdd;
      $getalldescription = $bdd->prepare("SELECT * FROM `description` ;");
      $getalldescription->execute();
      return $getalldescription->fetchAll();
   
  }
}
