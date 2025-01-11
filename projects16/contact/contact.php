<?php 
  session_start();
  $login_user=isset( $_SESSION["email"]) && !isset($_SESSION["admin"]);
   if($login_user){
       include("../classes/account.php");
       $acc=Account::get_account_by_email($_SESSION["email"]);
    
   }else if(isset($_SESSION["admin"])){
       $acc["name"]=$_SESSION["name"];
   }
   if(isset( $_GET["logout"])){
       session_destroy();
       header('location:../page principale/index.php');
       exit;
   }  


include("../classes/contacts.php");


function validate($data){
    $data=trim($data);
    $data= nl2br($data);
    $data=stripslashes($data);
    $data=htmlspecialchars($data);
    return($data);
    
}

if(isset($_POST["message"])){
  
    $newname= validate($_POST["name1"]);
    $newname2= validate($_POST["name2"]);
    $newemail= validate($_POST["email"]);
    $newmsg= validate($_POST["msg"]);
    if( !empty($newname) &&
        !empty($newname2)&&
        !empty($newemail)&&
        !empty($newmsg)){

            $new_msg=new Contact("","$newname $newname2",$newemail,$newmsg);  
            $new_msg->add_new_contact();

    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/font-awesome-4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="style.css">
    
    <title>Document</title>
</head>
<body>
 
        <header>
            <img src="../assets/images/Logo.png" alt="">
            <ul>
                <li><a href="../page principale/index.php">Home</a></li>
                <li><a href="../page detail/detail.php">CaseStudies</a></li>
                <li><a href="../page shop/shop.php">tea</a></li>
                <li><a href="../contact/contact.php">contact</a></li>
                <li  <?php  if(isset( $_SESSION["email"])){echo'class="btn_log_login"';}else{echo'class="btn_log"';} ?> >
                    <?php  if(!isset( $_SESSION["email"])){ ?>
                    <a href="../login_signup/login_signup.php">Log in</a>
                    <?php  }else{?>
                         <i  class="fa fa-user fa-2x"></i>
                        <h3><?php echo $acc["name"]; ?></h3>
                        <ul class="under_log">
                            <li><a <?php if($login_user){echo'href="../profile user/profile.php"';}else{echo 'href="../admin/dashboard/dashboard.php"';} ?>><i class="fa fa-cog" aria-hidden="true"></i> Settings</a></li>
                            <li><a href="?logout"><i class="fa  fa-sign-out" aria-hidden="true"></i> <span>Log out</span></a></li>
                        </ul> 
                    <?php  }?>
                </li>
            </ul>
        </header>
        <div class="page">
            <div class="partie1">
                <h2>
                    Contact us
                </h2>
                <p>
                    Need to get in touch with us? Either fill out the form with your inquiry or <br>
                    find the department email you'd like do contact below
                </p>
            </div>
            <form method="post">
                <div>
                    <div>
                        <label for="">First name *</label>
                        <input type="text" name="name1" required>
                    </div>
                    <div>
                        <label for="">Last name</label>
                        <input type="text" name="name2" required>
                    </div>
                </div>
                <label for="">Email *</label>
                <input type="email" name="email" id="" required>
                <label for="">What can we help you with? *</label>
                <textarea name="msg" id="" required></textarea>
                <button type="submit" name="message">Send</button>
            </form>
        </div>
        <div class="cart">
        <div class="my_cart">
            <div>
                <h2>My Cart (<span class="count_order">0</span>)</h2>
                <i class="fa fa-close fa-2x " aria-hidden="true" onclick="fermer2()"></i>
            </div>
            <div>
              
               <div class="cas_error">
                    <p>Your cart is empty</p>
                    <a href="../page shop/shop.php">Continue the visit</a>
                    
                </div>
            </div>
            <div class="total">
                <p>total</p>
                <p class="price_total">0$</p>
                <p>Shipping costs are calculated at checkout.</p>
                <button onclick="  if(<?php echo $login_user==0?0:1;?>=='0' ){
                 document.querySelector('.error2').innerHTML='if you want to order,you must login';
                    document.querySelector('.error2').style.display='block';
 
                        }else{ window.sessionStorage.setItem('to_order', 'true' );
                window.location='../profile user/profile.php'}
                ">Order</button>
                <p style="color:red; display:none;" class="error2"></p>

            </div>
        </div>
    </div>
        <div class="cart_order">
            <i class="fa fa-shopping-cart" aria-hidden="true"></i>
        </div>
        <script>
            
        var cart_ovrer= document.querySelector(".cart_order");

cart_ovrer.onclick=function(){
    var page= document.querySelector(".cart");
    var scrol= document.querySelector("body");
    page.style.display="flex";
    scrol.style.overflowY ="hidden";
    this.style.display="none";

}

function fermer2(){
    var page=document.querySelector('.cart');
    var cart=document.querySelector('.cart_order');
    var scrol= document.querySelector("body");
    page.style.display="none";
    scrol.style.overflowY ="scroll";    
    cart.style.display="flex";
}
let orders=[];
        function orders_affich(cart){
            
            for(var l=0;l<orders.length;l++){
                            
                            let cart_tea = document.createElement('div');
                            cart_tea.id=`${orders[l]["product_id"]}_${orders[l]["gram"]}`;
                            let img=orders[l]["src_img"]==""?"default.jpg" :orders[l]["src_img"];
                           
                            cart_tea.innerHTML = `
                                    <img src="../assets/images/${img}" alt="">    
                                    <div>
                                        <h2 >${orders[l]["name"]} </h2>
                                        <p >${orders[l]["price"]}</p>
                                                
                                        <p>Weight: ${orders[l]["gram"]}</p>
                                        <div>
                                            <button class="less_cart">-</button>
                                            <p class="count_cart">${orders[l]["quantity"]}</p>
                                            <button class="more_cart">+</button>
                                            <p class="delet_cart" id="${orders[l]["product_id"]}_${orders[l]["gram"]}">Delete</p>
                                        </div>
                                    </div>
                            `;
                            
                                cart.appendChild(cart_tea);
                                
                            var less_cart=document.querySelectorAll('.less_cart');
                            var more_cart=document.querySelectorAll('.more_cart');
                            for(var i=0;i<less_cart.length;i++){
                            
                                less_cart[i].onclick=function(){
                                    
                                    var count=this.nextElementSibling;
                                        
                                    if(+count.innerText>1){
                                        let price= this.parentElement.previousElementSibling.previousElementSibling;
                                        let count_old=count.innerText;
                                        count.innerHTML=+count.innerText-1;
                                    
                                        let id =this.parentElement.parentElement.parentElement.id.split("_")[0];
                                       
                                        for(var m=0;m<orders.length;m++){
                                        let gram=this.parentElement.previousElementSibling.innerHTML.slice(8);

                                            if(orders[m]["product_id"]==id &&orders[m]["gram"]==gram){
                                                price.innerHTML=`${orders[m]["base_price"]*(+count.innerText)}$`;
                                                orders[m]["price"]= price.innerHTML;
                                                orders[m]["quantity"]=count.innerText;
                                                window.localStorage.setItem("orders", JSON.stringify(orders));

                                                break;
                                            }
                                        }
                                    
                                        let total=0;
                                        if(Object.entries(orders).length==1){
                                            total=orders[0]["price"];
                                        }else{
                                        for (let [key, value] of Object.entries(orders)) {
                                    
                                            total+=+value["price"].split("$")[0];
                                            
                                        }
                                        total=`${total}$`;
                                        }
                                        document.querySelector(".price_total").innerHTML=total;
                                    
                                                          
                                    }
                                
                                }

                                more_cart[i].onclick=function(){
                                    let count=this.previousElementSibling;
                                    let count_old=+count.innerText;
                                    count.innerHTML=+count.innerText+1;
                                    let price= this.parentElement.previousElementSibling.previousElementSibling;
                                    let id =this.parentElement.parentElement.parentElement.id.split("_")[0];

                                    for(var m=0;m<orders.length;m++){
                                        let gram=this.parentElement.previousElementSibling.innerHTML.slice(8);

                                            if(orders[m]["product_id"]==id &&orders[m]["gram"]==gram){
                                                price.innerHTML=`${orders[m]["base_price"]*(+count.innerText)}$`;
                                                orders[m]["price"]= price.innerHTML;
                                                orders[m]["quantity"]=count.innerText;
                                                window.localStorage.setItem("orders", JSON.stringify(orders));

                                                break;
                                            }
                                    }
                                    
                                    let total=0;
                                    if(Object.entries(orders).length==1){
                                        total=orders[0]["price"];
                                    }else{
                                    for (let [key, value] of Object.entries(orders)) {
                                        total+=+value["price"].split("$")[0];
                                    }
                                    total=`${total}$`;
                                    }
                                    document.querySelector(".price_total").innerHTML=total;
                                
                                }
                            }
                            var delete_cart=document.querySelectorAll('.delet_cart');
                            for(var d=0;d<delete_cart.length;d++){
                                delete_cart[d].onclick=function(){
                                    let id=this.id.split("_")[0];
                                    let gram=this.id.split("_")[1];
                                    for(var m=0;m<orders.length;m++){
                                     
                                            if(orders[m]["product_id"]==id && orders[m]["gram"]==gram){
                                                orders.splice(m, 1);

                                               let cart_or= document.querySelectorAll(`.my_cart>div:nth-of-type(2) >div`);
                                             

                                                for(var z=0;z<cart_or.length;z++){


                                                    if(cart_or[z].id.split("_")[0]==id && cart_or[z].id.split("_")[1]==gram){
                                                        cart_or[z].remove();
                                                        break;
                                                    }

                                                }
                                                if(orders.length==0){
                                                           let cart= document.querySelector(".my_cart>div:nth-of-type(2)");
                                                            cart.innerHTML=`
                                                                            <div class="cas_error">
                                                                                <p>Your cart is empty</p>
                                                                                <a href="">Continue the visit</a>
                                                                            </div>`;
                                                            document.querySelector(".total").style.display="none";
                                                            document.querySelector(".price_total").innerHTML="0$";
                                                    }else{
                                                            let total=0;
                                                            if(Object.entries(orders).length==1){
                                                                total=orders[0]["price"];
                                                            }else{
                                                            for (let [key, value] of Object.entries(orders)) {
                                                        
                                                                total+=+value["price"].split("$")[0];
                                                            }
                                                            total=`${total}$`;
                                                            }
                                                            document.querySelector(".price_total").innerHTML=total;

                                                    }
                                                document.querySelector(".count_order").innerHTML=orders.length;
                                                
                                                window.localStorage.setItem("orders", JSON.stringify(orders));

                                            
                                                break;
                                            }
                                    }

                                }

                            }

                        }
        }
        window.onload=function(){
            if(JSON.parse(window.localStorage.getItem("orders")!=null)){
                
             orders = JSON.parse(window.localStorage.getItem("orders"));
           
            }
            if(orders.length>0) {
                let cart= document.querySelector(".my_cart>div:nth-of-type(2)");
                cart.innerHTML="";  
                document.querySelector(".total").style.display="block";
                document.querySelector(".count_order").innerHTML=orders.length;
                let total=0;
                if(Object.entries(orders).length==1){
                    total=orders[0]["price"];
                }else{
                for (let [key, value] of Object.entries(orders)) {
            
                    total+=+value["price"].split("$")[0];
                }
                total=`${total}$`;
                }
                document.querySelector(".price_total").innerHTML=total;

                orders_affich(cart);
            }
        }
        </script>
</body>
</html>