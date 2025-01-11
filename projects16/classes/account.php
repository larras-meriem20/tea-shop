<?php
include("connect.php");
include("order.php");


class Account 
{
    
  private $account_id;
  private $account_name;
  private $account_email;
  private $account_address;
  private $account_nemuro;
  private $account_password;
  

  public function __construct(
    $account_id="",  $name="" , $email ="", $password ="" , $address="", $nemuro=""
    ) {
      $this->account_id = $account_id;
      $this->account_name = $name;
      $this->account_email = $email;
      $this->account_address = $address;
      $this->account_nemuro = $nemuro;
      $this->account_password = $password;
            }

  public function get_account_id() {
    return $this->account_id;
  }
 
  public function get_account_name() {
    return $this->account_name;
  }

  public  function get_account_address() {
      return $this->account_address;
  }
 
  public  function get_account_password() {
      return $this->account_password;
  }

 
  public function add_new_account(){
      global $bdd;
      $inseraccount = $bdd->prepare("INSERT INTO `users` (`user_id`,`name`, `email`, `address`,`nemuro` ,`password`) VALUES (?,?,?,?, ?, ?)");

      $inseraccount->execute(array($this->account_id,$this->account_name,$this->account_email ,$this->account_address,$this->account_nemuro, $this->account_password));
   

  }
  public function mody_account($array_keys){
   
    $values=[];
    $kk=["name","email","address","nemuro","password"];
    $kk2=[$this->account_name,$this->account_email ,$this->account_address,$this->account_nemuro, $this->account_password];
    $keys="";
    for($i=0;$i<sizeof($array_keys);$i++){
      $keys.=" `".$array_keys[$i]."` = ? ";

      array_push($values,$kk2[array_search($array_keys[$i],$kk)]);
      if($i!=sizeof($array_keys)-1){
        $keys.=",";
      }
   
    }
 
      global $bdd;
      $inserarticle = $bdd->prepare("UPDATE `users` SET $keys WHERE user_id = ? ;");

      $inserarticle->execute(array(...$values,$this->account_id)); 

      
  }
  public function delete_account(){
      global $bdd;
      $delete_order_by_client=new Order("","",$this->account_id);
      $delete_order_by_client->delete_order_by_client();
      $deleteaccount = $bdd->prepare("DELETE FROM `users` WHERE user_id = ? ;");

      $deleteaccount->execute(array($this->account_id)); 

  }

  public static function get_all_account():array{
      global $bdd;
      $getallaccount = $bdd->prepare("SELECT * FROM `users`;");
      $getallaccount->execute();
  return $getallaccount->fetchAll();
    
  }
  public static function is_exist($email):bool{
    global $bdd;
    $exist = $bdd->prepare("SELECT * FROM `users` WHERE email= ? ;");
    $exist->execute([$email]);
      
   return $exist->rowCount()>0 ;  
}
public static function get_account_by_email($email):array{
  global $bdd;
  $acc = $bdd->prepare("SELECT * FROM `users` WHERE email= ? ;");
  $acc->execute([$email]);
    
 return $acc->fetchAll()[0] ;  
}
}
// echo "<pre>";
// print_r(Account::get_account_by_email("meriem@gmail.com"));
// echo "</pre>";
