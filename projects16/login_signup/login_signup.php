<?php
include("../classes/account.php");
function validate($data){
    $data=trim($data);
    $data= nl2br($data);
    $data=stripslashes($data);
    $data=htmlspecialchars($data);
    return($data);
    
}
 session_start();
 $msg="";
 $msg2="";



if(isset($_POST["login"])){
    $msg="";
   $email= validate($_POST["email"]);
   $password= validate($_POST["password"]);
   
  
   if(!empty($email) && !empty($password)){
   
   
    $getalladmin = $bdd->prepare("SELECT * FROM `admin`;");
    $getalladmin->execute();
    $row=$getalladmin->fetch();
     
      if(password_verify($password,$row["password"]) && $row["email"]==$email){
            $_SESSION["email"]=$email;
            $_SESSION["name"]= $row["name"];
            $_SESSION["admin"]="yes";

            header('location:../admin/dashboard/dashboard.php');
        exit;
        }
      
 
    if(Account::is_exist($email)){
        $acc=Account::get_account_by_email($email);
        $_SESSION["email"]=$email;
        $_SESSION["name"]=$acc["name"];
        header('location:../page principale/index.php');
        exit;
        }
        $msg="Incorrect email or password.";
    }else{ 
        $mesg="email or password are required";
    }
    
}
if(isset($_POST["signup"])){
 $msg2="";
    $newname= validate($_POST["name"]);
    $newemail= validate($_POST["email2"]);
    $newpassword= validate($_POST["password2"]);
    $password2= validate($_POST["re_type_password"]);
    if( !empty($newname) &&
        !empty($newemail)&&
        !empty($newpassword)&&
        !empty($password2)&&
        ($newpassword==$password2)&&
        strlen($newpassword)>5){
            if(!Account::is_exist($newemail)){
                
          
              $new_acc=new Account("",$newname,$newemail,password_hash($newpassword,PASSWORD_DEFAULT));  
              $new_acc->add_new_account();
              $_SESSION["email"]=$newemail;
              $_SESSION["name"]=$newname;
              header('location:../page principale/index.php');
              exit;
              }else{
               $msg2="Your email is already exists!";
            }
    }else if (strlen($newpassword)>5){
        $msg2="The password must be more than five characters!";
    }else{
        $msg2="Your re-type password is not valid!";
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
    <div class="page">
        <div class="login">
            <div>
                <h2>Log In</h2>
               
            </div>
            <form method="post">
                <label for="email">email</label>
                <input type="email" name="email" id="email" placeholder="enter your email" required>
                <label for="pass">password</label>
                <div>
                    <input type="password" name="password" id="pass" placeholder="enter your password" required>
                    <i class="fa fa-eye eye" aria-hidden="true"></i>

                </div>
                <?php if($msg!=""){
                    echo '  <p style="color: red; margin-top:-10px;">'.$msg.'</p>';
                } ?>
              
                <div>
                    <div>
                        <input type="checkbox" name="" id="rem" checked>
                        <label for="rem">remember me</label>
                    </div>
                    
                </div>
                
                <input type="submit" value="Log in" id="login" name="login" >
            </form>
        </div>

        <div class="trans">
            <h2>welcome to Log In</h2>
            <p>Don't have an account ?</p>
            <button class="change" >Sign Up</button>
        </div>

        <div class="signup">
            <div>
                <h2>Sign Up</h2>
               
            </div>
         <form action="" method="post">
            <label for="name">full name</label>
            <input type="text" name="name" id="name"placeholder="enter your full name" required>
            <label for="email">email</label>
            <input type="email" name="email2" id="email" placeholder="enter your email" required>
            <label for="pass">password</label>
           <div>
             <input type="password" name="password2" id="pass" placeholder="enter your password" required>
            <i class="fa fa-eye eye" aria-hidden="true"></i>

           </div>
            <label for="re_type">re-type password</label>
            <div>

            <input type="password" name="re_type_password" id="re_type" placeholder="enter your password again" required>
            <i class="fa fa-eye eye" aria-hidden="true"></i>

            </div>
            <?php if($msg2!=""){
                    echo '  <p style="color: red; margin-top:-10px;">'.$msg2.'</p>';
                } ?>
            <input type="submit" value="Sign up" id="signup" name="signup">
        </form> 
        </div>
    </div>
  
<script>

    if(window.sessionStorage.getItem("sign")==null){
       window.sessionStorage.setItem("sign","in");
    }
    let element =document.querySelector(".change");
    let trans =document.querySelector(".trans");
    let login =document.querySelector(".login");
    let signup =document.querySelector(".signup");
    let text1 =document.querySelector(".trans h2");
    let text2 =document.querySelector(".trans p");
    let text3 =document.querySelector(".trans button");

 
 window.addEventListener("beforeunload",(event)=>{
     if( performance.getEntriesByType("navigation")[0].type!="reload" ){
        window.sessionStorage.removeItem("sign");
    }

 });
    window.onload=function(){
        
    
        if(window.sessionStorage.getItem("sign") =="up"){
       
      
          trans.style.left="0";
           login.style.right="0";
           login.style.zIndex="-1";
           login.style.transitionDuration ="0s";
           trans.style.transitionDuration ="0s";
           signup.style.transitionDuration ="0s";
           signup.style.right="0";
           signup.style.zIndex="5";
           text1.innerHTML="welcome to Sign Up";
   
           text2.innerHTML="Have an account ?";
           text3.innerHTML="Log In";
           
           trans.style.borderRadius="10px 0 0 10px";
           signup.style.borderRadius="0 10px 10px 0";
           login.style.borderRadius="0 10px 10px 0";
           
       }
    }
 
     
    var eye=document.querySelectorAll('.eye');
     for(i=0 ; i<eye.length;i++){
            eye[i].onclick=function(){
                let pass=this.previousElementSibling;
               
                if(pass.type!="password"){
                    pass.type="password";
                    this.classList.add("fa-eye");
                    this.classList.remove("fa-eye-slash");

                    show=false;
                }else{

                    this.classList.add("fa-eye-slash");
                    this.classList.remove("fa-eye");

                    pass.type="text";
                    show=true;
                }
               
                
        
           }
        }
    element.onclick=function(){
        
       let trans =document.querySelector(".trans");
       let login =document.querySelector(".login");
       let signup =document.querySelector(".signup");
       let text1 =document.querySelector(".trans h2");
       let text2 =document.querySelector(".trans p");
       let text3 =document.querySelector(".trans button");
     
       if(window.sessionStorage.getItem("sign") =="in"){
        trans.style.left="0";
        login.style.right="0";
        login.style.zIndex="-1";
        signup.style.right="0";
        signup.style.zIndex="5";
        text1.innerHTML="welcome to Sign Up";
        text2.innerHTML="Have an account ?";
        text3.innerHTML="Log In";
        trans.style.borderRadius="10px 0 0 10px";
        signup.style.borderRadius="0 10px 10px 0";
        login.style.borderRadius="0 10px 10px 0";
        window.sessionStorage.setItem("sign","up");
        
       
       }else{
        login.style.right="50%";
        login.style.zIndex="5";
        text1.innerHTML="welcome to Log In";
        text2.innerHTML="Don't have an account ?";
        text3.innerHTML="Sign Up";
        signup.style.right="50%";
        signup.style.zIndex="-1";
        trans.style.left="50%";
        trans.style.borderRadius="0 10px 10px 0";
        login.style.borderRadius=" 10px 0 0 10px";
        signup.style.borderRadius=" 10px 0 0 10px";
        window.sessionStorage.setItem("sign","in");
        login.style.transitionDuration ="1s";
        trans.style.transitionDuration ="1s";
        signup.style.transitionDuration ="1s";
        
       }
      
       
    }
</script>
</body>
</html>