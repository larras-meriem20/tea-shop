<?php
include("connect.php");
include("description.php");  

class Product 
{
    
  private $product_id;
  private $product_name;
  private $product_src_img;
  private $product_price;
  private $product_gram;
  private $product_description;
  private $product_stockQuantity;
  private $product_status;

  public function __construct(
  $product_id="", $name=""  , $src_img=""  , $price="" ,$gram="", $stockquantity="" ,$description=false
    ) {
     
   
        $this->product_id = $product_id;
        $this->product_src_img=$src_img;
        $this->product_name = $name;
        $this->product_price = $price;
        $this->product_gram = $gram;
        $this->product_description = $description;
        $this->product_stockQuantity = $stockquantity;
            }

  public function get_product_id() {
    return $this->product_id;
  }
 
  public function get_product_name() {
    return $this->product_name;
  }

  public  function get_product_src_img() {
      return $this->product_src_img;
  }
 
  public  function get_product_price() {
      return $this->product_price;
  }
 
  public  function get_product_stockQuantity() {
      return $this->product_stockQuantity;
  }
  public function get_product_status() {
      return $this->product_status;
  }
  public function set_product_status($status) {
      $this->product_status= $status;
}
  
  public function add_new_product(){
      global $bdd;
      $inserproduct = $bdd->prepare("INSERT INTO `product` (`product_id`,`name`,`description`, `src_img`, `price`,`gram`, `stock_quantity`) VALUES (?,?,?,?,?, ?, ?)");

      $inserproduct->execute(array($this->product_id,$this->product_name ,$this->product_description,$this->product_src_img, $this->product_price,$this->product_gram, $this->product_stockQuantity));

  }
  public function mody_product($array_keys){
    $values=[];
    $kk=["name",
    "src_img",
    "price",
    "gram",
    "stock_quantity",
    "status",
    "description"];
    $kk2=[$this->product_name,
    $this->product_src_img ,
    $this->product_price ,
    $this->product_gram,
    $this->product_stockQuantity,
    $this->product_status,
    $this->product_description];
    $keys="";
    for($i=0;$i<sizeof($array_keys);$i++){
      $keys.=" `".$array_keys[$i]."` = ? ";

      array_push($values,$kk2[array_search($array_keys[$i],$kk)]);
      if($i!=sizeof($array_keys)-1){
        $keys.=",";
      }
    }
      global $bdd;
      $inserarticle = $bdd->prepare("UPDATE `product` SET $keys WHERE product_id = ? ;");

      $inserarticle->execute(array(...$values,$this->product_id)); 

      
  }
  public function delete_product(){
      global $bdd;
      $delete_des =new Description($this->product_id);
      $delete_des->delete_description();
      $delete_order =new Order("",$this->product_id);
      $delete_order->delete_order_by_product();
      $deleteproduct = $bdd->prepare("DELETE FROM `product` WHERE product_id = ? ;");

      $deleteproduct->execute(array($this->product_id)); 

  }
  public static function get_all_product():array{
      global $bdd;
      $getallproduct = $bdd->prepare("SELECT * FROM `product`;");
      $getallproduct->execute();
      $cccc= $getallproduct->rowCount();
  return $getallproduct->fetchAll();
  
  }
  public static function get_product_by_id($id):array{
    global $bdd;
    $getallproduct = $bdd->prepare("SELECT * FROM `product` where  product_id = ?;");
    $getallproduct->execute(array($id));
    $cccc= $getallproduct->rowCount();
return $getallproduct->fetchAll();

}
}
