<?php 

    session_start();
    if(!isset( $_SESSION["email"]) ||(isset( $_SESSION["email"]) && isset($_SESSION["admin"]))){
        header('location:../page principale/index.php');
        exit;
    }  

    if(isset( $_GET["logout"])){
        session_destroy();
        header('location:../page principale/index.php');
        exit;
    }  
    include("../classes/account.php");
    include("../classes/product.php");
    $acc=Account::get_account_by_email($_SESSION["email"]);
    $orders =Order::get_order_by_client($acc["user_id"]);
    function validate($data){
        $data=trim($data);
        $data= nl2br($data);
        $data=stripslashes($data);
        $data=htmlspecialchars($data);
        return($data);
        
    }
    $msg="";
   

    if(isset($_POST["delete"])){
        if(!empty($_POST["id_delete"])){
             $idall=$_POST["id_delete"];
            for($i=0;$i<count($idall);$i++){
                settype($idall[$i], "integer");
                
                $delete =new Order($idall[$i]);
                $delete->delete_order();
                $_POST = array();
                header("Refresh:0");
            }
          
        }
       
    }
   
    if(isset($_POST["order"])){
        $orders_add = json_decode($_POST["orders"], true);
        for($i=0;$i<count($orders_add);$i++){
           
            if(Product::get_product_by_id($orders_add[$i]["product_id"])[0]["stock_quantity"]<$orders_add[$i]["quantity"]){
           
            $add_new=new Order("",$orders_add[$i]["product_id"],$acc["user_id"],date("Y-m-d h:i:s"),$orders_add[$i]["quantity"],$orders_add[$i]["price"],$orders_add[$i]["gram"],"unacceptable");
            $add_new->add_new_order(); 
          }else{
            $add_new=new Order("",$orders_add[$i]["product_id"],$acc["user_id"],date("Y-m-d h:i:s"),$orders_add[$i]["quantity"],$orders_add[$i]["price"],$orders_add[$i]["gram"]);
            $add_new->add_new_order(); 
          }
          
        }
        $_POST = array();
        header("Refresh:0");
    }

    if(isset($_POST["modify"])){
       
        $array_keys=[];
        $newlast_name= validate($_POST["l_name"]);
        $newfirst_name= validate($_POST["f_name"]);
        $newemail= validate($_POST["email"]);
        $newaddress= validate($_POST["address"]);
        $newnemuro= validate($_POST["nemuro"]);
        $newpassword= validate($_POST["password"]);
        $newpassword= $newpassword=="********"?"":$newpassword;
      
        if(strlen($newpassword)>5 || $newpassword==""){
       
            $is_email=false;
           
           
                        if($acc["name"]!="$newlast_name $newfirst_name")
                        {
                            $array_keys[]="name";
                        }
                     
                          if($acc["email"]!=$newemail  && $newemail!="" && !Account::is_exist($newemail) )
                        {
                            $array_keys[]="email";
                            $is_email=true;
                           
                            
                        }else if($acc["email"]!=$newemail && Account::is_exist($newemail)){
                             $msg="Your email is already exists!";

                        }
                        if($acc["address"]!=$newaddress)
                        {
                            $array_keys[]="address";
                            
                        }
                        if($acc["nemuro"]!=$newnemuro)
                        {
                            $array_keys[]="nemuro";
                            
                        }
                       
                       
                        if(!password_verify($newpassword,$acc["password"]) && $newpassword!="" )
                        {
                            $array_keys[]="password";
                            
                        }
                     
                    
                
                  if($array_keys!=[]){
    
                        $new_acc=new Account($acc["user_id"],"$newlast_name $newfirst_name",$newemail,password_hash($newpassword,PASSWORD_DEFAULT),$newaddress,$newnemuro);  
                        $new_acc->mody_account($array_keys);
                        if($is_email){
                            $_SESSION["email"]=$newemail;
                        }
                        $_POST = array();
                        header("Refresh:0");
                  
                    }
      
                   
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/font-awesome-4.7.0/css/font-awesome.min.css">
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
                <li class="btn_log">
                <i  class="fa fa-user fa-2x"></i>
                <h3><?php echo $acc["name"]; ?></h3>
               <ul class="under_log">
                <li><a href="../profile user/profile.php"><i class="fa fa-cog" aria-hidden="true"></i> Settings</a></li>
                <li><a href="?logout"><i class="fa  fa-sign-out" aria-hidden="true"></i> <span>Log out</span></a></li>
 
               </ul>
            </li>
        </ul>
    </header>

    <div class="partie">
        <div>
            <h2>My profile</h2>
        </div>
        <div>
            <div class="profile">
                <i  class="fa fa-user fa-5x"></i>
                <h3><?php echo $acc["name"]; ?></h3>
                <p><?php echo $acc["email"]; ?></p>
                
                
            </div>
            <div class="profile2">
                <div class="my_info activ">
                    <div>
                        <h3>personal information</h3> 
                        <div>
                            <button class="edit" name="modify">edit <i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>
                            <i class="fa fa-angle-down fa-2x my_inf" aria-hidden="true" ></i>
                        </div>
                    </div>
                    <div>
                        <div>
                            <div>
                                <label for="">last name</label>
                                <p class="mody" id="l_name"><?php echo explode(' ', $acc["name"])[0]; ?></p>
                            </div>
                            <div>
                                <label for="">phone</label>
                                <p   class="mody" id="nemuro"><?php echo $acc["nemuro"]==""?"Phone not added yet.":$acc["nemuro"]; ?></p>
                            </div>
                            <div>
                                <label for="">password</label>
                                <p class="mody" id="password">********</p>
                            </div>
                        </div>
                        <div>
                             <div>
                                <label for="">first name</label>
                                <p class="mody" id="f_name" ><?php echo count(explode(' ', $acc["name"],2))!=1? explode(' ', $acc["name"],2)[1]:""; ?></p>
                            </div>
                            <div>
                                <label for="">email address</label>
                                <p class="mody" id="email" ><?php echo $acc["email"]; ?></p>
                            </div>
                            <div>
                                <label for="">address</label>
                                <p class="mody" id="address" ><?php echo $acc["address"]==""?"Address not added yet.":$acc["address"]; ?></p>
                            </div>
                            
                        </div>
                       
                    </div>
                    <p style="color:red; <?php echo $msg==""?"display:none;":""; ?> " class="error"><?php echo $msg; ?></p>
                
                </div>
                <div class="my_order">
                    <div>
                        <h3>My orders</h3> 
                        <div>
                            <button class="delete" name="delete" >cancel order</button>
                             <i class="fa fa-angle-down fa-2x my_or" aria-hidden="true" ></i>
                              
                           
                        </div>
                       
                    </div>
                    <div>
                    
                        <div class="cas_error">
                            <p>Your orders is empty</p>
                            <a href="../page shop/shop.php">Continue the visit</a>
                        </div>
 
                    </div>
                </div>
                <div class="my_cart" >
                    <div>
                        <h3>My Cart <span class="count_or">(0)</span></h3>
                        <div>
                            <p>Total</p> 
                            <p class="total">0$</p>
                        </div>
                        <form method="post">
                            <button class="order" name="order">order </button>
                            <i class="fa fa-angle-down fa-2x my_ca" aria-hidden="true"></i>
                            <input type="hidden" name="orders" class="in_or">

                        </form>
                    </div>
                    <div>
                        <div class="cas_error">
                            <p>Your cart is empty</p>
                            <a href="../page shop/shop.php">Continue the visit</a>
                        </div>
                    </div>
                    <p style="color:red; display:none;" class="error2"></p>

                </div>
            </div>
    </div>
</div>
<script>
    
        let data = <?php echo json_encode($acc); ?>;
        let data_order = <?php echo json_encode($orders); ?>;
        if(data_order.length!=0){
        document.querySelector(".my_order>div:nth-of-type(2)").innerHTML="";
       for(x=0;x<data_order.length;x++){

        let order_tea = document.createElement('div');
                         order_tea.id=data_order[x]["id_order"];
        let img=data_order[x]["src_img"]==""?"default.jpg" :data_order[x]["src_img"];
        
        order_tea.innerHTML = `
                          
                                <div>
                                    <img src="../assets/images/${img}" alt="">    
                                    <div>
                                        
                                        <h2 >${data_order[x]["name_product"]} </h2>
                                        <p >price: ${data_order[x]["order_price"]}</p>  
                                        <p>Weight: ${data_order[x]["order_gram"]}</p>
                                        <p >Qty: ${data_order[x]["order_quantity"]}</p>

                                    </div>
                                </div> 
                                <div>
                                    <label for="">status</label>
                                    <p>${data_order[x]["status"]}</p>
                                </div>
                
                               
        `;
        
            document.querySelector(".my_order>div:nth-of-type(2)").appendChild(order_tea);
                                
       }
    }
        let orders=[];
        function orders_affich(cart){
           
           document.querySelector(".in_or").value=JSON.stringify(orders);
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
                                                 document.querySelector(".in_or").value=JSON.stringify(orders);

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
                                        document.querySelector(".total").innerHTML=total;
                                    
                                                          
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
                                                document.querySelector(".in_or").value=JSON.stringify(orders);

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
                                    document.querySelector(".total").innerHTML=total;
                                
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
                                                                                <a  href="../page shop/shop.php">Continue the visit</a>
                                                                            </div>`;
                                                            document.querySelector(".total").innerHTML="0$";
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
                                                            document.querySelector(".total").innerHTML=total;

                                                    }
                                                document.querySelector(".count_or").innerHTML=`(${orders.length})`;
                                                
                                                window.localStorage.setItem("orders", JSON.stringify(orders));
                                                 document.querySelector(".in_or").value=JSON.stringify(orders);

                                            
                                                break;
                                            }
                                    }

                                }

                            }

                        }
        }
        
            if(JSON.parse(window.localStorage.getItem("orders")!=null)){
                orders = JSON.parse(window.localStorage.getItem("orders"));
            }
            if(orders.length>0) {
                let cart= document.querySelector(".my_cart>div:nth-of-type(2)");
                cart.innerHTML="";  
        
                document.querySelector(".count_or").innerHTML=`(${orders.length})`;
                let total=0;
                if(Object.entries(orders).length==1){
                    total=orders[0]["price"];
                }else{
                for (let [key, value] of Object.entries(orders)) {
                    total+=+value["price"].split("$")[0];
                }
                total=`${total}$`;
                }
                document.querySelector(".total").innerHTML=total;

                orders_affich(cart);
            }else{
                
                document.querySelector(".total").innerHTML="0$";

            }
       
         

     
      if(JSON.parse(window.sessionStorage.getItem("order")!=null)){
        if(window.sessionStorage.getItem("order")=="oui"){
            window.sessionStorage.setItem("order", "non");
        }else{
            trans_mode(document.querySelector(".my_or"));
            window.sessionStorage.removeItem("order");

        }
      
        }

        if(JSON.parse(window.sessionStorage.getItem("to_order")!=null)){

            trans_mode(document.querySelector(".my_ca"));
            window.sessionStorage.removeItem("to_order")
            }
    document.querySelector(".edit").onclick=function(event){


       this.classList.toggle("edit_click");
       if(this.classList.length==2){
            event.preventDefault();
            let form = document.createElement('form');
            form.method="post";
            form.append(...document.querySelector(".activ").children);
            form.classList.add("my_info", "activ");
            document.querySelector(".activ").parentNode.replaceChild(form, document.querySelector(".activ"));
        

            let can = document.createElement('button');
            can.innerHTML="cancel";
            can.classList.add("cancel");
            this.parentElement.insertBefore(can,this);
            let trans=document.querySelectorAll(".mody");
            for(var i=0;i<trans.length;i++){
            
                    let input = document.createElement('input');
                    if(i==trans.length-1){
                        input.style.width="270px";
                    }
                    if(trans[i].id=="password"){
                    input.placeholder=trans[i].innerHTML;                 
                    }else if(trans[i].innerHTML=="Address not added yet."|| 
                    trans[i].innerHTML=="Phone not added yet."){
                        input.value="";
                    }else{
                         input.value=trans[i].innerHTML;
                    }
                   

                    
                    
                    input.name=trans[i].id;
                    input.classList.add("mody");
                    trans[i].parentElement.appendChild(input);
                    trans[i].remove();

                
            }
            function test_input(){
                    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    let msg =  document.querySelector(".error")
                    
                    if(document.querySelectorAll(".mody")[0].value.length==0 ||
                    ( document.querySelectorAll(".mody")[2].value.length<5 &&
                    document.querySelectorAll(".mody")[2].value.length!=0) ||
                    document.querySelectorAll(".mody")[3].value.length==0 ||
                    !emailRegex.test(document.querySelectorAll(".mody")[4].value 
                )
                    ){
                        document.querySelector(".edit").disabled=true;
                        msg.style.display="block";
                        if(document.querySelectorAll(".mody")[0].value.length==0 ){
                            msg.innerHTML="Last Name is required";

                        }
                        if ( document.querySelectorAll(".mody")[2].value.length<5 &&
                        document.querySelectorAll(".mody")[2].value.length!=0){
                        msg.innerHTML="The password must be more than five characters!";
                            
                        }
                        if( document.querySelectorAll(".mody")[3].value.length==0 ){
                        msg.innerHTML="First Name is required";
                            
                        }
                        if( !emailRegex.test(document.querySelectorAll(".mody")[4].value)){
                        msg.innerHTML="email is not valid";
                            
                        }
                    

                    }else{
                        document.querySelector(".edit").disabled=false;
                        msg.style.display="none";
                        msg.innerHTML="";


                    }
          }
           document.querySelectorAll(".mody")[0].addEventListener("focusout",test_input);
           document.querySelectorAll(".mody")[2].addEventListener("focusout",test_input);
           document.querySelectorAll(".mody")[3].addEventListener("focusout",test_input);
           document.querySelectorAll(".mody")[4].addEventListener("focusout",test_input);
            
            document.querySelector(".cancel").onclick=function(){
                this.nextElementSibling.classList.remove("edit_click");
                let trans=document.querySelectorAll(".mody");
                for(var i=0;i<trans.length;i++){
                
                    let text = document.createElement('p');
                    text.innerHTML=trans[i].name=="l_name"||trans[i].name=="f_name"?
                                    data[trans[i].name].split(" ",2)[trans[i].name=="l_name"?0:1]
                                    :trans[i].name=="password"?"********":data[trans[i].name];
                    text.id=trans[i].name;           
                    text.classList.add("mody");
                    trans[i].parentElement.appendChild(text);
                    trans[i].remove();

                }
                this.remove();
            }
    


        }
    
        
    }

    document.querySelector(".delete").onclick=function(){
      this.classList.toggle("delete_click");
      if(this.classList.length==2){
        event.preventDefault();
            let form = document.createElement('form');
            form.method="post";
            form.append(...document.querySelector(".activ").children);
            form.classList.add("my_order", "activ");
            document.querySelector(".activ").parentNode.replaceChild(form, document.querySelector(".activ"));
        
         let can = document.createElement('button');
        can.innerHTML="go back";
        can.classList.add("go_back");
        this.parentElement.insertBefore(can,this);
        let trans=document.querySelectorAll(".my_order >div:nth-of-type(2)> div");
        for(var i=0;i<trans.length;i++){
           
                let input = document.createElement('input');
                let label = document.createElement('label');
                input.type="checkbox";
                input.name="id_delete[]";
                input.value=trans[i].id;
                input.id=`fofo${i}`;
                label.htmlFor=`fofo${i}`;
                label.append(...trans[i].children);
                input.classList.add("dele");
                label.classList.add("lable_dele");
                trans[i].appendChild(label);
                trans[i].insertBefore(input,trans[i].firstChild);
               

            
        }
        
        document.querySelector(".go_back").onclick=function(){
        
            this.nextElementSibling.classList.remove("delete_click");
            let trans=document.querySelectorAll(".dele");
            let trans2=document.querySelectorAll(".lable_dele");
            for(var i=0;i<trans.length;i++){
            
                    trans[i].remove();

                
            }
        
            
            for(var i=0;i<trans.length;i++){
                    trans[i].remove();
            }
            for(var i=0;i<trans.length;i++){
                trans2[i].parentElement.append(...trans2[i].children);
                    trans2[i].remove();
            }
            this.remove();
        }
       }else{
        window.sessionStorage.setItem("order", "oui");
       }
 
       
    }
 
    document.querySelector(".order").onclick=function(){

   if(
    document.querySelectorAll(".mody")[1].innerHTML=="Phone not added yet."||
    document.querySelectorAll(".mody")[5].innerHTML=="Address not added yet."
   ){
    document.querySelector(".error2").innerHTML="if you want to order,you must enter your phone number and address";
    document.querySelector(".error2").style.display="block";
    event.preventDefault();
   }else{

        orders=[];
        let cart= document.querySelector(".my_cart>div:nth-of-type(2)");
        cart.innerHTML=`
                        <div class="cas_error">
                            <p>Your cart is empty</p>
                            <a href="../page shop/shop.php">Continue the visit</a>
                        </div>`;
        document.querySelector(".total").innerHTML="0$";
        document.querySelector(".count_or").innerHTML=`(${orders.length})`;


        window.localStorage.setItem("orders", JSON.stringify(orders));
        window.sessionStorage.setItem("order","oui");
        
   }
  

        
     
    }
    function trans_mode(mode){
        let activ=document.querySelector(".activ");
        
        if( activ.children[0].children[1].children.length==2){
            
        if(activ.classList[0]=="my_cart" ){
           activ.children[0].children[1].style.display="none";
            activ.children[0].children[2].children[0].style.display="none";
            activ.children[0].children[2].children[1].style.display="block";
            document.querySelector(".count_or").style.display="none";


        }else{
           
            activ.children[0].children[1].children[1].style.display="block";
            activ.children[0].children[1].children[0].style.display="none";
        }
       
        activ.children[1].style.display="none";
        activ.classList.remove("activ");
        mode.parentElement.parentElement.parentElement.classList.add("activ");
        let activ_new=document.querySelector(".activ");
        if(activ_new.classList[0]=="my_cart" ){
            if(orders.length==0){
                activ_new.children[0].children[1].style.display="none";
            activ_new.children[0].children[2].children[0].style.display="none";

            }else{
                activ_new.children[0].children[1].style.display="block";
            activ_new.children[0].children[2].children[0].style.display="block";

            }
            
            activ_new.children[0].children[2].children[1].style.display="none";
            document.querySelector(".count_or").style.display="inline";
        }else if(activ_new.classList[0]=="my_order"){
    
            if(data_order.length==0){

                activ_new.children[0].children[1].children[0].style.display="none";
               

            }else{
                activ_new.children[0].children[1].children[0].style.display="block";
              

            }
            activ_new.children[0].children[1].children[1].style.display="none";
        }else{
             activ_new.children[0].children[1].children[0].style.display="block";
             activ_new.children[0].children[1].children[1].style.display="none";

        }
       
        activ_new.children[1].style.display="flex";
        }
    }
    document.querySelector(".my_or").onclick=function(){
            trans_mode(this);
    };
    document.querySelector(".my_ca").onclick=function(){
            trans_mode(this);
    };
    
    document.querySelector(".my_inf").onclick=function(){
            trans_mode(this);        
    };
</script>
</body>
</html>