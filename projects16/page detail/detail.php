
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

include("../classes/product.php");
$all=Product::get_all_product();
$all_des=Description::get_all_description();

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
    <div class="partie1">
        <div class="zyada"></div>
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
        
        <div class="welco">
            <h2>Discover the benefits of tea and the best ways to enjoy it</h2>
        </div>
    </div>
    <div class="partie4">
        <h2>Explore the Taste of Excellence</h2>
        <div class="all_tea">
           
        </div>

    </div>
        

    </div>
    <div class="detail">
        
    </div>
    <footer>
        <div>
            <i class="fa fa-facebook" aria-hidden="true"></i>
            <i class="fa fa-instagram" aria-hidden="true"></i>
            <i class="fa fa-twitter" aria-hidden="true"></i>
            <i class="fa fa-google-plus" aria-hidden="true"></i>
            <i class="fa fa-youtube-play" aria-hidden="true"></i>
        </div>
        <ul>
            <li><a href="../page principale/index.php">Home</a></li>
                    <li><a href="../page detail/detail.php">CaseStudies</a></li>
                    <li><a href="../page shop/shop.php">tea</a></li>
                    <li><a href="../contact/contact.php">contact</a></li>
            
        </ul>
        <p>
            copyright ©2024; designed by meri
        </p>
    </footer>
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
         
          let data = <?php echo json_encode($all); ?>;
          let all_tea = document.querySelector('.all_tea');
          for (let i = 0; i < data.length; i++) {
            if(data[i]["description"]){
               


                const tea = document.createElement('div');
                tea.classList.add("dede");
                tea.id=data[i]["product_id"];
                let img=data[i]["src_img"]==""?"default.jpg" :data[i]["src_img"];
                
                tea.innerHTML = `
                        <img src="../assets/images/${img}" alt="">
                        <div>
                            <h3 id="${data[i]["product_id"]}">${data[i]["name"]}</h3>
                        <p>
                            <span >☆</span>
                            <span >☆</span>
                            <span >☆</span>
                            <span >☆</span>
                            <span >☆</span>
                            <!-- <span style="font-size:300%;color:yellow;">★</span> -->
                            </p>
                            
                        </div>
            
                `;
                all_tea.appendChild(tea);
            }
    }
    var header=document.querySelector("header");
        var fonts=document.querySelectorAll("header ul li a");
        var btn=document.querySelector(".btn_log");
        var btn2="";
       if(btn==null){
          btn2=document.querySelector(".btn_log_login");
       }
       





        window.onscroll=function(){
          if(scrollY>300){
            header.style.position="fixed";
            header.style.backgroundColor="white";
            header.style.boxShadow="0 0  10px 2px rgba(0, 0, 0, 0.1) ";
            for(var i=0;i<fonts.length-1;i++){
                 fonts[i].classList.add("color_a");
            } 
              if(btn==null){
                    btn2.classList.add("btn_log_login2");
                }else{
                    btn.classList.add("btn_log2");
                    btn.classList.remove("btn_log");
                }
               
           

          }else{
            header.style.position="relative";
            header.style.backgroundColor="transparent";
            header.style.boxShadow="none";
           
            for(var i=0;i<fonts.length-1;i++){
                fonts[i].classList.remove("color_a");

            }
            if(btn==null){
                    btn2.classList.remove("btn_log_login2");
                }else{
                 btn.classList.add("btn_log");
                btn.classList.remove("btn_log2");
                }
               
          }
        }

         function fermer(){
            var page=document.querySelector('.detail');
            var scrol= document.querySelector("body");
            page.style.display="none";
            scrol.style.overflowY ="scroll";    

        }
        var dede= document.querySelectorAll(".dede");
        for(var i=0;i<dede.length;i++){
             dede[i].onclick=function(){
            var page= document.querySelector(".detail");
            var scrol= document.querySelector("body");
            page.style.display="flex";
            scrol.style.overflowY ="hidden";     
            let div = document.createElement('div');
            let data_des = <?php echo json_encode($all_des); ?>;
            let id_view=this.id;
             for(var y=0 ;y<data_des.length;y++){
                if(id_view==data_des[y]["product_id"]){
                    let data_row = data.filter((d)=>{ return d["product_id"]==id_view;});

                     let img=data[y]["src_img"]==""?"default.jpg" :data[y]["src_img"];
                  
                   document.querySelector(".detail").innerHTML = `
                        <div>
                        <i class="fa fa-times fa-2x close" aria-hidden="true" onclick="fermer()"></i>
                        <img src="../assets/images/${img}" alt="">
                        <div>
                            <h2>${data_row[0]["name"]}</h2>
                            <div>
                                <div>
                                    <p><span>type:</span><span> ${data_des[y]["type"]}</span></p>
                                    <p><span>origin:</span><span> ${data_des[y]["origin"]}</span></p>
                                </div>
                                <div>
                                    <p><span>caffeine:</span><span> ${data_des[y]["caffeine"]}</span></p>
                                </div>
                            </div>
                            <p><span>${data_des[y]["description"].slice(0,1)}</span>${data_des[y]["description"].slice(1)}</p>
                            <p><span>${data_des[y]["benefits"].slice(0,1)}</span>${data_des[y]["benefits"].slice(1)} </p>
                            <p><span>${data_des[y]["prepare"].slice(0,1)}</span>${data_des[y]["prepare"].slice(1)} </p>
                        </div>
                        </div>
                    
        `;
                    break;
                }
             }
             
        }
        }
       
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
        var less=document.querySelectorAll('.less');
        var more=document.querySelectorAll('.more');
        for(var i=0;i<less.length;i++){
           
            less[i].onclick=function(){
            
                var count=this.nextElementSibling;   
                if(+count.innerText>1){
                    count.innerHTML=+count.innerText-1;
                }
            }
            more[i].onclick=function(){
                var count=this.previousElementSibling;
                count.innerHTML=+count.innerText+1;
            }
        }
    </script>
</body>
</html>