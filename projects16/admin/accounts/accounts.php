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
include("../../classes/account.php");
$all=Account::get_all_account();


function validate($data){
    $data=trim($data);
    $data= nl2br($data);
    $data=stripslashes($data);
    $data=htmlspecialchars($data);
    return($data);
    
}
$msg="";

if(isset($_POST["add"])){
  
    $msg="";
    $newname= validate($_POST["name"]);
    $newemail= validate($_POST["email"]);
    $newaddress= validate($_POST["address"]);
    $newnemuro= validate($_POST["nemuro"]);
    $newpassword= validate($_POST["password"]);
    $password2= validate($_POST["re_type_password"]);
    if( !empty($newname) &&
        !empty($newemail)&&
        !empty($newaddress)&&
        !empty($newnemuro)&&
        !empty($newpassword)&&
        !empty($password2)&&
        ($newpassword==$password2)&&
        strlen($newpassword)>5){
          
            
            if(!Account::is_exist($newemail)){
              $new_acc=new Account("",$newname,$newemail,password_hash($newpassword,PASSWORD_DEFAULT),$newaddress);  
              $new_acc->add_new_account();
              $ok="ok";
              $_POST = array();
              header("Refresh:0");
              }else{
               $msg="Your email is already exists!";
              }


        }else if (strlen($newpassword)<5){
                $msg="The password must be more than five characters!";
        }else{
                $msg="Your re-type password is not valid!";

    }
}

if(isset($_POST["modify"])){
    $id=$_POST["id_mody"];
    settype($id, "integer");
    $array_keys=[];
    $newname= validate($_POST["name"]);
    $newemail= validate($_POST["email"]);
    $newaddress= validate($_POST["address"]);
    $newnemuro= validate($_POST["nemuro"]);
    $newpassword= validate($_POST["password"]);
    $password2= validate($_POST["re_type_password"]);
   
    $msg="";

    if($newpassword==$password2 && (strlen($newpassword)>5 || $newpassword=="")){
   

            $is_vrai=true;
            for($i =0;$i<count($all);$i++){
        
            
                if($all[$i]["user_id"]==$id)
                {
                    if($all[$i]["name"]!=$newname)
                    {
                        $array_keys[]="name";
                    }
                      if($all[$i]["email"]!=$newemail  && $newemail!="" )
                    {
                        $array_keys[]="email";
                        $is_vrai=false;
                        
                    }
                    if($all[$i]["address"]!=$newaddress)
                    {
                        $array_keys[]="address";
                        
                    }
                    if($all[$i]["nemuro"]!=$newnemuro)
                    {
                        $array_keys[]="nemuro";
                        
                    }
                   
                   
                    if(!password_verify($newpassword,$all[$i]["password"]) && $newpassword!="" )
                    {
                        $array_keys[]="password";
                        
                    }
                    break;
                }
           }
            
            if((!Account::is_exist($newemail))||$is_vrai){
              if($array_keys!=[]){


                $new_acc=new Account($id,$newname,$newemail,password_hash($newpassword,PASSWORD_DEFAULT),$newaddress,$newnemuro);  
                $new_acc->mody_account($array_keys);
                 
               
                }
                $_POST = array();
                header("Refresh:0");
              
             
              }else{
               $msg="Your email is already exists!";
              }



    }else if (strlen($newpassword)<5){
        $msg="The password must be more than five characters!";
    }
    else{
        $msg="Your re-type password is not valid!";

    }
}
if(isset($_POST["delete"])){
    $id=$_POST["id_delete"];
    settype($id, "integer");
        
    $delete =new Account($id);
    $delete->delete_account();
    $_POST = array();
    header("Refresh:0");
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
            <li ></li>
            <li><a href="../dashboard/dashboard.php"><i class="fa fa-tachometer" aria-hidden="true"></i> <span>Dashboard</span></a></li>
            <li ><a href="../products/products.php"><i class="fa fa-archive" aria-hidden="true"></i> <span>Products</span></a></li>
            <li  class="before"><a href="../orders/orders.php"><i class="fa fa-shopping-cart" aria-hidden="true"></i> <span>Orders</span></a></li>
            <li class="now"><a href="../accounts/accounts.php"><i class="fa fa-users" aria-hidden="true"></i> <span>Accounts</span></a></li>
            <li class="after"><a href="../contact/contact.php"><i class="fa fa-comments" aria-hidden="true"></i> <span>Contact</span></a></li>
            <li><a href="?logout"><i class="fa  fa-sign-out" aria-hidden="true"></i> <span>Log out</span></a></li>
            
        </ul>
    </div>


    <div class="page">
        <div class="partie1">
            <div>
                <i class="fa fa-bars fa-2x barbar" aria-hidden="true"></i>
                <h3> Accounts</h3>
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
                <h2>All Accounts</h2>
                <p>Dashboard > Accounts</p>
            </div>
            <div>
                <i class="fa fa-search" aria-hidden="true"></i>
                <input type="search" class="search"  placeholder="search Account">
                <button id="add_new_pro">Add Account</button>
            </div>
        </div>
        <div class="table">
        <table>
          
           
           
            
        </table>
        </div>
        <div class="suiv">
            <p>
                displaying page
            </p>
            <p><span id="from">1</span>/<span id="total">1</span></p>
         
         <button class="prev">prev</button>
         <button class="next">next</button> 
        </div>
        </div>
    </div>
    <div class="add_mody">
        
        <form action="" method="post">
            <i class="fa fa-times fa-2x close" aria-hidden="true" onclick="fermer()"></i>
            <h2>add new Account</h2>
            <label for="">full name <span>*</span></label>
            <input type="text" name="name" class="name" value="<?php if(isset($_POST["name"])){echo$_POST["name"];} ?>" required >
            <label for="">email <span>*</span></label>
            <input type="email" name="email" class="email" value="<?php if(isset($_POST["email"])){echo$_POST["email"];} ?>" required>
            <label for="">address</label>
            <input type="text" name="address" class="address" value="<?php if(isset($_POST["address"])){echo$_POST["address"];} ?>" required>
            <label for="">nemuro</label>
            <input type="text" name="nemuro" class="nemuro" value="<?php if(isset($_POST["nemuro"])){echo$_POST["nemuro"];} ?>" required>
            <label for="">password <span>*</span></label>
            <div>
            <input type="password" name="password" class="password" value="<?php if(isset($_POST["password"])){echo$_POST["password"];} ?>" required>
            <i class="fa fa-eye eye" aria-hidden="true"></i>
            </div>
            <label for="">re-type password <span>*</span></label>
            <div>
            <input type="password" name="re_type_password" class="password2" value="<?php if(isset($_POST["re_type_password"])){echo$_POST["re_type_password"];} ?>" required>
            <i class="fa fa-eye eye" aria-hidden="true"></i>
            </div>
            <?php if($msg!=""){
                    echo '  <p style="color: red; margin-top:-10px;" class="msg">'.$msg.'</p>';
                } ?>
            <input style="position: absolute;" type="hidden" class="id_mody" name="id_mody" value="<?php if(isset($_POST["id_mody"])){echo$_POST["id_mody"];} ?>" >
            <input style="margin-top: 10px;" type="submit" value="Add" name="add" class="submit"     >
            
        </form>
        <div>
            <h2>Delete account</h2>
            <p>
                Are you sure you want to delete this account?
            </p>

            <form method="post">
                <input type="submit"  value="Cancel" onclick="fermer()">
                <input type="submit" name="delete" value="Delete" onclick="fermer()">
                <input style="position: absolute;" type="hidden" class="id_delete" name="id_delete" 
>
            </form>
            
        </div>
       
    </div>
<script>
 let issubmit=false;
 window.addEventListener("beforeunload",(event)=>{
     if(!issubmit ){
        window.sessionStorage.removeItem("add_mody");
    }

 });
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
    window.onload=function(){
       
       
          
            if(window.sessionStorage.getItem("add_mody") =="add"){

                var page=document.querySelector('.add_mody');
                page.style.display="flex";
            }else if(window.sessionStorage.getItem("add_mody") =="mody"){

                var page=document.querySelector('.add_mody');
                page.style.display="flex";
                var titre=document.querySelector('.add_mody form h2');
                var btn=document.querySelector('.submit');
                btn.value="Modify";
                btn.name="modify";
                titre.innerHTML="Modify account";             
                var name=document.querySelector('.name');
                var email=document.querySelector('.email');
                var address=document.querySelector('.address');
                var nemuro=document.querySelector('.nemuro');
                name.required = false;
                email.required = false;
                address.required = false;
                nemuro.required = false;
                document.querySelector('.password').required = false;
                document.querySelector('.password2').required = false;
            }
        

    }
      
          document.querySelector(".submit").onclick=function(){
            issubmit=true;

          }

        const data = <?php echo json_encode($all); ?>;
        const itemsPerPage = 7;
        let currentPage = 1;
        let totalPages;
        function updateTable(data) {
            
            totalPages = Math.ceil(data.length / itemsPerPage)==0?1:Math.ceil(data.length / itemsPerPage);
            document.querySelector('#total').innerHTML= totalPages;

                const tableBody = document.querySelector('.table table');
            
                tableBody.innerHTML = ` <tr>
                            <th>No</th>
                            <th>full name</th>
                            <th>email</th>
                            <th>nemuro</th>
                            <th>address</th>
                            <th>action</th>
                        </tr>`;
            

            const start = (currentPage - 1) * itemsPerPage;
            const end = Math.min(start + itemsPerPage, data.length);

            for (let i = start; i < end; i++) {
                const row = document.createElement('tr');
                row.innerHTML = `
                <td>${i}</td>
                <td> ${data[i]["name"]}</td>
                <td> ${data[i]["email"]}</td>
                <td> ${data[i]["nemuro"]}</td>
                <td> ${data[i]["address"]}</td>
                <td>
                        <i class="fa fa-pencil-square-o edit" aria-hidden="true" id="${data[i]["user_id"]}" ></i> 
                        <i class="fa fa-trash delete" aria-hidden="true" id="${data[i]["user_id"]}" ></i>
                </td>
                `;
                tableBody.appendChild(row);
            }

            document.querySelector('.prev').disabled = currentPage === 1;
            document.querySelector('.next').disabled = currentPage === totalPages;
            var ouvrer_delete=document.querySelectorAll('.delete');
            for(i=0 ; i<ouvrer_delete.length;i++){

                ouvrer_delete[i].onclick=function(){
                    var page=document.querySelector('.add_mody');
                    var page3=document.querySelector('.add_mody > div');
                    let id_delete=document.querySelector('.id_delete');
                    id_delete.value=this.id;
                
                    var page2=document.querySelector('.add_mody form');
                    page.style.display="flex";
                    page2.style.display="none";
                    page3.style.display="flex";
                    
            
            }
            }
        
        
            var ouvrer_edit=document.querySelectorAll('.edit');
            for(i=0 ; i<ouvrer_edit.length;i++){

                ouvrer_edit[i].onclick=function(){
                    window.sessionStorage.setItem("add_mody","mody");
                    var page=document.querySelector('.add_mody');
                    var titre=document.querySelector('.add_mody form h2');
                    var name=document.querySelector('.name');
                    var email=document.querySelector('.email');
                    var address=document.querySelector('.address');
                    var nemuro=document.querySelector('.nemuro');
                    name.required = false;
                    email.required = false;
                    address.required = false;
                    nemuro.required = false;
                    document.querySelector('.password').required = false;
                    document.querySelector('.password2').required = false;
                    var btn=document.querySelector('.submit');
                    let id_mody=document.querySelector('.id_mody');
                    id_mody.value=this.id;
                    btn.value="Modify";
                    btn.name="modify";
                    for(var i =0;i<data.length;i++){
                    
                        
                            if(data[i]["user_id"]==this.id)
                            {
                                name.value=data[i]["name"];
                                email.value=data[i]["email"];
                                address.value=data[i]["address"];
                                nemuro.value=data[i]["nemuro"];
                                break;
                            }
                    }
                
                    page.style.display="flex";
                    titre.innerHTML="Modify account";
                }
           }

        }

        document.querySelector('.prev').onclick=function(){
            if (currentPage > 1) {
                currentPage--;
                document.querySelector('#from').innerHTML= currentPage;

                updateTable(data);
            }
        }

        document.querySelector('.next').onclick=function(){
            if (currentPage < totalPages) {
                currentPage++;
                document.querySelector('#from').innerHTML= currentPage;

                updateTable(data);
            }
        }

        updateTable(data);

        var search=document.querySelector('.search');
        var new_data=[];
        search.onkeyup=function(){
            new_data=[];
            sear= this.value.toLowerCase();
        

            for(var i=0 ; i<data.length;i++){
            
                if (data[i]["name"].toLowerCase().indexOf(sear) >-1
                || data[i]["email"].toLowerCase().indexOf(sear)  >-1
                || data[i]["address"].toLowerCase().indexOf(sear)  >-1 ){
                
                    if(new_data.indexOf(data[i])==-1){
                    new_data.push(data[i]);
                }
                
        
                }
            }
            updateTable(new_data);
        

       }

       function fermer(){
            window.sessionStorage.removeItem("add_mody");
      
            var page=document.querySelector('.add_mody');
            var page3=document.querySelector('.add_mody > div');
            var page2=document.querySelector('.add_mody form');
            var msg=document.querySelector('.msg');
           
            if(msg!=null){
               msg.remove();
            }
             
             page2.style.display="flex";
            page.style.display="none";
            page3.style.display="none";
            var btn=document.querySelector('.submit');
           btn.value="Add";
           btn.name="add";
            document.querySelector('.name').value="";
            document.querySelector('.email').value="";
            document.querySelector('.address').value="";
            document.querySelector('.nemuro').value="";
            document.querySelector('.password').value="";
            document.querySelector('.password2').value="";
           

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

      
        var ouvrer_new=document.querySelector('#add_new_pro');
        ouvrer_new.onclick=function(){
            window.sessionStorage.setItem("add_mody","add");

            var page=document.querySelector('.add_mody');
            var titre=document.querySelector('.add_mody form h2');

            titre.innerHTML="Add new account";
            page.style.display="flex";

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