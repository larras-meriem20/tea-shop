<?php 
 session_start();
 if(!isset( $_SESSION["email"])){
        header('location:../../page principale/index.php');
        exit;
 }  

 if(isset( $_GET["logout"])){
    session_destroy();
    header('location:../../page principale/index.php');
    exit;
}  
include("../../classes/product.php");
include("../../classes/contacts.php");
include("../../classes/account.php");
$products=count(Product::get_all_product());
$orders=count(Order::get_all_order());
$users=count(Account::get_all_account());
$contacts=count(Contact::get_all_contact());

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../assets/font-awesome-4.7.0/css/font-awesome.min.css">

    <link rel="stylesheet" href="style.css">
    <title>Document</title>
</head>
<body>
    <div class="bar">
        <h2>Tea shop</h2>
        <ul>
            <li class="before"></li>
            <li class="now"><a href="../dashboard/dashboard.php"><i class="fa fa-tachometer" aria-hidden="true"></i> <span>Dashboard</span></a></li>
            <li class="after" ><a href="../products/products.php"><i class="fa fa-archive" aria-hidden="true"></i> <span>Products</span></a></li>
            <li  ><a href="../orders/orders.php"><i class="fa fa-shopping-cart" aria-hidden="true"></i> <span>Orders</span></a></li>
            <li><a href="../accounts/accounts.php"><i class="fa fa-users" aria-hidden="true"></i> <span>Accounts</span></a></li>
            <li><a href="../contact/contact.php"><i class="fa fa-comments" aria-hidden="true"></i> <span>Contact</span></a></li>
            <li><a href="?logout"><i class="fa  fa-sign-out" aria-hidden="true"></i> <span>Log out</span></a></li>
            
        </ul>
    </div>

    <div class="page">
        <div class="partie1">
            <div>
                <i class="fa fa-bars fa-2x barbar" aria-hidden="true"></i>
                <h3> Dashboard</h3>
            </div>
            <div onclick=" window.location = '../profile/profile.php' ">
                <i  class="fa fa-user fa-2x"></i>
                <div>
                    <h3><?php echo $_SESSION["name"];?></h3>
                    <p>administrator</p>
                </div>
            </div>
        </div>

        
        <div class="partie2">
        <div>
    
    <div>
        <a href="../products/products.php">
            <i class="fa fa-archive fa-3x" aria-hidden="true"></i>
        <div>
            <h2>products</h2>
            <p><?php echo $products;?> product</p>
        </div>  
        </a>
      
    </div>
    <div>
        <a href="../orders/orders.php">
            <i class="fa fa-shopping-cart fa-3x" aria-hidden="true"></i>
        <div>
            <h2>orders</h2>
            <p><?php echo $orders;?> order</p>
        </div> 
        </a>
       
    </div>
    <div>
        <a href="../accounts/accounts.php">
            <i class="fa fa-users fa-3x" aria-hidden="true"></i>
        <div>
            <h2>users</h2>
            <p><?php echo $users;?> user</p>
        </div> 
        </a>
       
    </div>
    <div>
        <a href="../contact/contact.php">
            <i class="fa fa-comments fa-3x" aria-hidden="true"></i>
        <div>
            <h2>contacts</h2>
            <p><?php echo $contacts;?> contact</p>
        </div>
        </a>
        
    </div>
  </div>
       
    </div>
    <script>
          if(window.localStorage.getItem("barbar")=="hide"){

var texts=document.querySelectorAll(".bar ul li a span");
var bar=document.querySelector(".bar");
var titre=document.querySelector(".bar h2");
var now=document.querySelector(".now");
for(var i=0;i<texts.length;i++){
texts[i].style.display="none";
}

titre.innerHTML="...";
bar.style.width="50px";
now.style.paddingLeft="15px";
isclick=true;

}          
        var hide=document.querySelector('.barbar');
        hide.onclick=function(){
        

                var texts=document.querySelectorAll(".bar ul li a span");
                var bar=document.querySelector(".bar");
                var titre=document.querySelector(".bar h2");
                var now=document.querySelector(".now");
                if(window.localStorage.getItem("barbar")!="hide"){
                    window.localStorage.setItem("barbar","hide");
        
                    for(var i=0;i<texts.length;i++){
                    texts[i].style.display="none";
                }
                
                titre.innerHTML="...";
                bar.style.width="50px";
                now.style.paddingLeft="15px";
                  
                    
                }else{
                    window.localStorage.removeItem("barbar");
        
                    titre.innerHTML="Tea shop";
                    bar.style.width="180px";
                    now.style.paddingLeft="40px";
                    for(var i=0;i<texts.length;i++){
                    texts[i].style.display="inline";
                    }
                    
                    


                }
                 
        }
    </script>
</body>
</html>