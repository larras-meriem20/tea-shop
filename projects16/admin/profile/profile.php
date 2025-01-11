<?php 
 session_start();
 if(!isset( $_SESSION["admin"])){
        header('location:../../page principale/index.php');
        exit;
 }  

 if(isset( $_GET["logout"])){
    session_destroy();
    header('location:../../page principale/index.php');
    exit;
}  
include("../../classes/connect.php");



$getalladmin = $bdd->prepare("SELECT * FROM `admin`;");
$getalladmin->execute();   
$acc=$getalladmin->fetch();



function validate($data){
    $data=trim($data);
    $data= nl2br($data);
    $data=stripslashes($data);
    $data=htmlspecialchars($data);
    return($data);
    
}

if(isset($_POST["modify"])){
    
    $array_keys=[];
    $newlast_name= validate($_POST["l_name"]);
    $newfirst_name= validate($_POST["f_name"]);
    $newemail= validate($_POST["email"]);
    $newpassword= validate($_POST["password"]);
    $newpassword= $newpassword=="********"?"":$newpassword;
  
    if(strlen($newpassword)>5 || $newpassword==""){
   

                    
                    if($acc["name"]!="$newlast_name $newfirst_name")
                    {
                        $array_keys[]="name";
                    }
                      if($acc["email"]!=$newemail  && $newemail!="" )
                    {
                        $array_keys[]="email";
                        
                        
                    }
                
                   
                    if(!password_verify($newpassword,$acc["password"]) && $newpassword!="" )
                    {
                        $array_keys[]="password";
                        
                    }
                 
                
           
                    
           
              if($array_keys!=[]){
                

                    $values=[];
                    $kk=["name","email","password"];
                    $kk2=["$newlast_name $newfirst_name",$newemail,password_hash($newpassword,PASSWORD_DEFAULT)];
                    $keys="";
                    for($i=0;$i<sizeof($array_keys);$i++){
                    $keys.=" `".$array_keys[$i]."` = ? ";
                
                    array_push($values,$kk2[array_search($array_keys[$i],$kk)]);
                    if($i!=sizeof($array_keys)-1){
                        $keys.=",";
                    }
                    global $bdd;
                    $mody = $bdd->prepare("UPDATE `admin` SET $keys WHERE id_admin = 0 ;");
                    $mody->execute(array(...$values)); 
                    if(in_array("email", $array_keys)){
                    $_SESSION["email"]=$newemail;

                    }
                    if(in_array("name", $array_keys)){
                        $_SESSION["name"]="$newlast_name $newfirst_name"; 
                    }
                   
                   
                    
                    $_POST = array();
                    header("Refresh:0");
                
              
                }
               
              }


    }
}
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
            <li class="after"><a href="../products/products.php"><i class="fa fa-archive" aria-hidden="true"></i> <span>Products</span></a></li>
            <li ><a href="../orders/orders.php"><i class="fa fa-shopping-cart" aria-hidden="true"></i> <span>Orders</span></a></li>
            <li ><a href="../accounts/accounts.php"><i class="fa fa-users" aria-hidden="true"></i> <span>Accounts</span></a></li>
            <li ><a href="../contact/contact.php"><i class="fa fa-comments" aria-hidden="true"></i> <span>Contact</span></a></li>
            <li><a href="?logout"><i class="fa  fa-sign-out" aria-hidden="true"></i> <span>Log out</span></a></li>
            
        </ul>
    </div>


    <div class="page">
        <div class="partie1">
            <div>
                <i class="fa fa-bars fa-2x barbar" aria-hidden="true"></i>
                <h3> My profile</h3>
            </div>
            <div onclick=" window.location = '../profile/profile.php' ">
                <i  class="fa fa-user fa-2x"></i>
                <div>
                    <h3><?php echo $acc["name"];?></h3>
                    <p>administrator</p>
                </div>
            </div>
        </div>

        
        <div class="partie2">
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
                                    <label for="">password</label>
                                    <p class="mody" id="password">********</p>
                                </div>
                            </div>
                            <div>
                                <div>
                                    <label for="">first name</label>
                                    <p class="mody" id="f_name" ><?php echo explode(' ', $acc["name"],2)[1]; ?></p>
                                </div>
                                <div>
                                    <label for="">email address</label>
                                    <p class="mody" id="email" ><?php echo $acc["email"]; ?></p>
                                </div>
                               
                                
                            </div>
                        
                        </div>
                        <p style="color:red; display:none " class="error"></p>
                    </div>
                </div>
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
                 
                    if(trans[i].id=="password"){
                    input.placeholder=trans[i].innerHTML;
                    
                 
                    }else{
                    input.value=trans[i].innerHTML;

                    }
                    if(trans[i].id=="email"){
                        input.type="email";
                  
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
            ( document.querySelectorAll(".mody")[1].value.length<5 &&
            document.querySelectorAll(".mody")[1].value.length!=0) ||
            document.querySelectorAll(".mody")[2].value.length==0 ||
            !emailRegex.test(document.querySelectorAll(".mody")[3].value)
             ){
                document.querySelector(".edit").disabled=true;
                msg.style.display="block";
                if(document.querySelectorAll(".mody")[0].value.length==0 ){
                    msg.innerHTML="Last Name is required";

                }
                if ( document.querySelectorAll(".mody")[1].value.length<5 &&
                document.querySelectorAll(".mody")[1].value.length!=0){
                msg.innerHTML="The password must be more than five characters!";
                    
                }
                if( document.querySelectorAll(".mody")[2].value.length==0 ){
                msg.innerHTML="First Name is required";
                    
                }
                if( !emailRegex.test(document.querySelectorAll(".mody")[3].value)){
                msg.innerHTML="email is not valid";
                    
                }
              

            }else{
                document.querySelector(".edit").disabled=false;
                msg.style.display="none";
                msg.innerHTML="";


            }
        }
        for(var x=0;x<4;x++){
           document.querySelectorAll(".mody")[x].addEventListener("focusout",test_input);

        }
         
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
    </script>
</body>
</html>