<?php
include("connect.php");


class Order 
{
    
    private $order_id;
    private $product_id;
    private $user_id;
    private $order_date;
    private $order_quantity;
    private $order_price;
    private $order_gram;
    private $order_status;

    public function __construct(
     $order_id="", $product_id="" , $user_id="" , $date="" ,$quantity="", $price="", $gram="",$status="in progress"
      ) {
      
         $this->order_id = $order_id;
        settype($product_id, "integer");
        settype($user_id, "integer");
      
        $this->product_id = $product_id ;
       

        $this->user_id= $user_id ;
        $this->order_date = $date;
        $this->order_price = $price;
        $this->order_gram = $gram;
        $this->order_quantity = $quantity;
        $this->order_status= $status;
              }

    public function get_order_id() {
      return $this->order_id;
    }
    public function get_product_id() {
        return $this->product_id;
    }
    public function get_user_id() {
        return $this->user_id;
    }

    public  function get_order_date() {
        return $this->order_date;
    }
   
    public  function get_order_price() {
        return $this->order_price;
    }
   
    public  function get_order_quantity() {
        return $this->order_quantity;
    }
    public function get_order_status() {
        return $this->order_status;
    }
    public function set_order_status($status) {
        $this->order_status= $status;
  }
    
    public function add_new_order(){
        global $bdd;
        $inserorder = $bdd->prepare("INSERT INTO `orders` (`product_id`,`user_id`, `order_date`, `order_quantity`, `order_price`,`order_gram`,`status`) VALUES (?,?,?,?, ?, ?,?)");
        
        $inserorder->execute(array($this->product_id,$this->user_id,$this->order_date,$this->order_quantity ,$this->order_price,$this->order_gram,$this->order_status));

    }
    public function mody_order($value){
   
        global $bdd;
        $md_order = $bdd->prepare("UPDATE `orders` SET `status`=? WHERE id_order = ? ;");

        $md_order->execute(array($value,$this->order_id)); 

        
    }
    public function delete_order(){
        global $bdd;
        $deleteorder = $bdd->prepare("DELETE FROM `orders` WHERE id_order = ? ;");

        $deleteorder->execute(array($this->order_id)); 

    }
    public function delete_order_by_client(){
        global $bdd;
        $deleteorder = $bdd->prepare("DELETE FROM `orders` WHERE user_id = ? ;");

        $deleteorder->execute(array($this->user_id)); 

    }
    public function delete_order_by_product(){
        global $bdd;
        $deleteorder = $bdd->prepare("DELETE FROM `orders` WHERE product_id = ? ;");

        $deleteorder->execute(array($this->product_id)); 

    }
    public static function get_all_order():array{
        global $bdd;
        $getallorders = $bdd->prepare("SELECT orders.id_order,
                                              product.product_id,
                                              product.src_img,
                                              product.name as name_product,
                                              users.name as name_user,
                                              users.address,
                                              users.nemuro,
                                              orders.order_date,
                                              orders.order_quantity,
                                              orders.order_price,
                                              orders.order_gram,
                                              orders.status 
                                        FROM orders
                                        INNER JOIN product
                                        ON orders.product_id=product.product_id
                                        INNER JOIN users
                                        ON  users.user_id=orders.user_id;
");
        $getallorders->execute();   
   
         return $getallorders->fetchAll();
    
    }
    public static function get_order_by_client($id_user):array{
        global $bdd;
        $getallorders = $bdd->prepare("SELECT orders.id_order,
                                              product.src_img,
                                              product.name as name_product,
                                              orders.order_quantity,
                                              orders.order_price,
                                              orders.order_gram,
                                              orders.status 
                                        FROM orders
                                        INNER JOIN product
                                        ON orders.product_id=product.product_id
                                        INNER JOIN users
                                        ON  users.user_id=orders.user_id
                                        Where users.user_id=?;
");
        $getallorders->execute(array($id_user));   
   
         return $getallorders->fetchAll();
    
    }
}
