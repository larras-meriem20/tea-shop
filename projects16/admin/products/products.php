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

include("../../classes/product.php");
include("../../classes/order.php");  

$all=Product::get_all_product();
$all_des=Description::get_all_description();


function validate($data){
    $data=trim($data);
    $data= nl2br($data);
    $data=stripslashes($data);
    $data=htmlspecialchars($data);
    return($data);
    
}
$msg="";
$msg2="";
 $ok="";


if(isset($_POST["add"])){
    
    $msg="";
    $newname= validate($_POST["name"]);
    $newprice= validate($_POST["price"]);
    $newstock= validate($_POST["stock"]);
    $newgram= validate($_POST["gram"]);
    $stock = explode(",", $newstock);
    $price = explode(",", $newprice);
    $gram = explode(",", $newgram);

    if( !empty($newname) &&
        !empty($newprice)&&
        !empty($newgram)&&
        !empty($newstock)&&
        !(count($stock)<=>count($price)) &&
       !( count($price) <=> count($gram))){
            $src_img="";
            $src="";
            if($_FILES["image"]){
                $src_img=$_FILES["image"]["tmp_name"];
            $src=$_FILES["image"]["name"];
            }
        
           
            $new_product=new Product("", $newname, $src, $newprice,$newgram, $newstock,isset($_POST["add_des"])?true:false);  
            $new_product->add_new_product();
            move_uploaded_file($src_img,"../../assets/images/$src");
           
            $_POST = array();
            $ok="ok";
            header("Refresh:0");
        }elseif( count($stock)!=count($price) ||
        count($price) != count($gram)){
            $msg="there must be stocks and price and gram same exp gram:100g price50$ stock:10,20 is wrong";

        
        }
}
if(isset($_POST["add_des"])){
    $id=$_POST["id_mody_des"];
    $msg2="";
    $newtype= validate($_POST["type"]);
    $neworigin= validate($_POST["origin"]);
    $newcaffeine= validate($_POST["caffeine"]);
    $newdescription= validate($_POST["description"]);
    $newbenefits= validate($_POST["benefits"]);
    $newprepare= validate($_POST["prepare"]);

    
    if( !empty($newtype) &&
    !empty($neworigin)&&
    !empty($newcaffeine)&&
    !empty($newdescription)&&
    !empty($newbenefits)&&
    !empty($newprepare)){
        
             
        $new_des=new Description($id, $newtype, $neworigin, $newcaffeine, $newdescription, $newbenefits, $newprepare);  
        $new_des->add_new_description();
        $new_product=new Product($id,"","","","","",true);
        $new_product->mody_product(["description"]);
        $_POST = array();
        header("Refresh:0");
      

        

    }
    
}
if(isset($_POST["modify"])){

    $id=$_POST["id_mody"];
    settype($id, "integer");
    $array_keys=[];
    $newname= validate($_POST["name"]);
    $newprice= validate($_POST["price"]);
    $newstock= validate($_POST["stock"]);
    $newgram= validate($_POST["gram"]);
    $stock = explode(",", $newstock);
    $price = explode(",", $newprice);
    $gram = explode(",", $newgram);
    $src_img="";
    $src="";
    if($_FILES["image"]){
        $src_img=$_FILES["image"]["tmp_name"];
        $src=$_FILES["image"]["name"];

    }
    $msg="";
   
    if( !empty($newname) &&
    !empty($newprice)&&
    !empty($newstock)&&
    !(count($stock)<=>count($price)) &&
    !( count($price) <=> count($gram))){
      
        for($i =0;$i<count($all);$i++){
 
            
            if($all[$i]["product_id"]==$id)
            {
                
                if($all[$i]["name"]!=$newname)
                {
                    $array_keys[]="name";
                }
                  if($all[$i]["price"]!=$newprice)
                {
                    $array_keys[]="price";
                    
                }
                if($all[$i]["gram"]!=$newgram)
                {
                    $array_keys[]="gram";
                    
                }
                if($all[$i]["stock_quantity"]!=$newstock)
                {
                    $array_keys[]="stock_quantity";
                    
                }
                if($all[$i]["src_img"]!=$src && $src!="")
                {
                    $array_keys[]="src_img";
                    
                }
                break;
            }
       }
      
       if($array_keys!=[]){
 
        
        $new_product=new Product($id, $newname, $src, $newprice,$newgram, $newstock);  

        $new_product->mody_product($array_keys);
       
        move_uploaded_file($src_img,"../../assets/images/$src");
        
       }
     
       $_POST = array();
        
       header("Refresh:0");
        

    }elseif( count($stock)!=count($price) ||
    count($price) != count($gram)){
        $msg="there must be stocks and price and gram same exp gram:100g price50$ stock:10,20 is wrong";

    
    }else{
    $msg="Your r is not valid!";

    }

}
if(isset($_POST["mod_des"])){
   
    $id=$_POST["id_mody_des"];

    $msg2="";
    $newtype= validate($_POST["type"]);
    $neworigin= validate($_POST["origin"]);
    $newcaffeine= validate($_POST["caffeine"]);
    $newdescription= validate($_POST["description"]);
    $newbenefits= validate($_POST["benefits"]);
    $newprepare= validate($_POST["prepare"]);

    
    if( !empty($newtype) &&
    !empty($neworigin)&&
    !empty($newcaffeine)&&
    !empty($newdescription)&&
    !empty($newbenefits)&&
    !empty($newprepare)){
        $array_keys=[];
        for($i =0;$i<count($all);$i++){

    
            if($all_des[$i]["product_id"]==$id)
            {
                if($all_des[$i]["type"]!=$newtype)
                {
                    $array_keys[]="type";
                }
                  if($all_des[$i]["origin"]!=$neworigin)
                {
                    $array_keys[]="origin";
                    
                }
                if($all_des[$i]["caffeine"]!=$newcaffeine)
                {
                    $array_keys[]="caffeine";
                    
                }
                if($all_des[$i]["description"]!=$newdescription)
                {
                    $array_keys[]="description";
                    
                }
                if($all_des[$i]["benefits"]!=$newbenefits)
                {
                    $array_keys[]="benefits";
                    
                }
                if($all_des[$i]["prepare"]!=$newbenefits)
                {
                    $array_keys[]="prepare";
                    
                }
                break;
            }
       }
             
    if($array_keys!=[]){
        $new_des=new Description($id, $newtype, $neworigin, $newcaffeine, $newdescription, $newbenefits, $newprepare);  
         $new_des->mody_description($array_keys);
         $_POST = array();
  //             $ok="ok";
            header("Refresh:0");
     }
          
          
        

    }else{
        $msg2="Your re-type password is not valid!";

    }
    
}
if(isset($_POST["delete"])){
    $id=$_POST["id_delete"];
    settype($id, "integer");
    
    $delete =new Product($id);
    $delete->delete_product();
    $_POST = array();
header("Refresh:0");
}
if(isset($_POST["delete_des"])){
    $id=$_POST["id_delete"];
    settype($id, "integer");
      $new_product=new Product($id,"","","","","",false);
        $new_product->mody_product(["description"]);
    $delete =new Description($id);
    $delete->delete_description();
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
        <h2 onclick=" window.location = '../../page principale/index.php' ">Tea shop</h2>
        <ul>
            <li ></li>
            <li class="before"><a href="../dashboard/dashboard.php"><i class="fa fa-tachometer" aria-hidden="true"></i> <span>Dashboard</span></a></li>
            <li  class="now"><a href="../products/products.php"><i class="fa fa-archive" aria-hidden="true"></i> <span>Products</span></a></li>
            <li class="after" ><a href="../orders/orders.php"><i class="fa fa-shopping-cart" aria-hidden="true"></i> <span>Orders</span></a></li>
            <li><a href="../accounts/accounts.php"><i class="fa fa-users" aria-hidden="true"></i> <span>Accounts</span></a></li>
            <li><a href="../contact/contact.php"><i class="fa fa-comments" aria-hidden="true"></i> <span>Contact</span></a></li>
            <li><a href="?logout"><i class="fa  fa-sign-out" aria-hidden="true"></i> <span>Log out</span></a></li>
            
        </ul>
    </div>



    <div class="page">
        <div class="partie1">
            <div>
                <i class="fa fa-bars fa-2x barbar" aria-hidden="true"></i>
                <h3 onclick=" window.location = '../../page shop/shop.php' "> Product</h3>
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
                <h2>All product</h2>
                <p>Dashboard > Product</p>
            </div>
            <div >
                <i class="fa fa-search" aria-hidden="true"></i>
                <input type="search" class="search" placeholder="search product">
                <button id="add_new_pro">Add product</button>
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
    <div class="detait">
        <div>
            
        </div>
    </div>
    <div class="add_mody">
        
        <form action="" method="post" class="before_des" enctype="multipart/form-data">
            <i class="fa fa-times fa-2x close" aria-hidden="true" onclick="fermer()"></i>
            <h2>add new product</h2>
            <label for="">product name <span>*</span></label>
            <input type="text"  name="name" class="name" value="<?php if(isset($_POST["name"])){echo$_POST["name"];} ?>" required >
            <label for="">Weight <span>*</span></label>
            <input type="text"  name="gram" class="gram"  pattern = '^[0-9]+g(\s*,\s*([0-9]+g)\s*)*\s*$'  placeholder="please write like this 1000g or  200g, 100g" value="<?php if(isset($_POST["gram"])){echo$_POST["gram"];} ?>" required>
            <label for="">price <span>*</span></label>
            <input type="text"  name="price" class="price"  pattern = '^[0-9]+\$(\s*,\s*([0-9]+\$)\s*)*\s*$'  placeholder="please write like this 1536$  or  100$, 200$" value="<?php if(isset($_POST["price"])){echo$_POST["price"];} ?>" required >
            <label for="">stock <span>*</span></label>
            <input type="text"  name="stock" class="stock"  pattern = '^[0-9]+(\s*,\s*([0-9]+)\s*)*\s*$' placeholder="please write like this 600 or  50, 70" value="<?php if(isset($_POST["stock"])){echo$_POST["stock"];} ?>"  required >
            <label for="">image of product <span>*</span></label>
            <div>
                <input type="file"  name="image" class="image"  accept=".png, .jpg, .jpeg" id="file_new" onchange="previewFile();" value="<?php if(isset($_FILES["image"])){echo$_POST["name"];} ?>"  >
                <img src="../../assets/images/default.jpg" alt="" id="new_pro">
            </div>
            <?php if($msg!=""){
                    echo '  <p style="color: red; margin-top:-10px;" class="msg">'.$msg.'</p>';
                } ?>
            
            <input  type="hidden" class="id_mody" name="id_mody" >
            <input style="margin-top: 10px;" type="submit" value="Add" name="add" class="submit">
    
        </form>
        <div>
           
            <h2>Delete product</h2>
            <p>
                Are you sure you want to delete this product?
            </p>
            <form method="post">
                <input type="submit"  value="Cancel" onclick="fermer()">
                <input type="submit" class="delete_btn" name="delete" value="Delete" onclick="fermer()">
                <input style="position: absolute;" type="hidden" class="id_delete" name="id_delete" >
            </form>
        </div>
        <form method="post" class="descrip">
            <i class="fa fa-times fa-2x close" aria-hidden="true" onclick="fermer3()"></i>
            <h2>add description</h2>
            <label for="">type</label>
            <input type="text"  name="type" class="type" value="<?php if(isset($_POST["type"])){echo$_POST["type"];} ?>" required >
            <label for="">origin</label>
            <input type="text"  name="origin" class="origin" value="<?php if(isset($_POST["origin"])){echo$_POST["origin"];} ?>" required>
            <label for="">caffeine</label>
            <input type="text"  name="caffeine" class="caffeine" value="<?php if(isset($_POST["caffeine"])){echo$_POST["caffeine"];} ?>" required>
            <label for="">description</label>
            <input type="text"  name="description" class="description" value="<?php if(isset($_POST["description"])){echo$_POST["description"];} ?>" required >
            <label for="">the health benefits</label>
            <input type="text"  name="benefits" class="benefits" value="<?php if(isset($_POST["benefits"])){echo$_POST["benefits"];} ?>" required>
            <label for="">How to prepare it</label>
            <input type="text"  name="prepare" class="prepare" value="<?php if(isset($_POST["prepare"])){echo$_POST["prepare"];} ?>" required>
            <?php if($msg2!=""){
                    echo '  <p style="color: red; margin-top:-10px;" class="msg">'.$msg2.'</p>';
                } ?>
            <input  type="hidden" class="id_mody_des" name="id_mody_des" >
            <input type="submit" class="submit2" value="Add description" name="add_des" onclick="return_to()" >
        </form>
    </div>
    <div class="detail">
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
 let issubmit=false;
 window.addEventListener("beforeunload",(event)=>{
     if(!issubmit ){
        window.sessionStorage.removeItem("add_mody_pro");
    }

 });
            document.querySelector(".submit").onclick=function(){
                        issubmit=true;

                    }
          document.querySelector(".submit2").onclick=function(){
            issubmit=true;

          }
          
       var ok="<?php echo $ok; ?>";
        if(ok=='ok'){window.sessionStorage.removeItem('add_mody_pro');}
        
          
            if(window.sessionStorage.getItem("add_mody_pro") =="add"){

                var page=document.querySelector('.add_mody');
                page.style.display="flex";
            }else if(window.sessionStorage.getItem("add_mody_pro") =="mody"){

                var page=document.querySelector('.add_mody');
                page.style.display="flex";
                var titre=document.querySelector('.add_mody form h2');
                var btn=document.querySelector('.submit');
                btn.value="Modify";
                btn.name="modify";
                titre.innerHTML="Modify product";            
            }
        

   

         let data = <?php echo json_encode($all); ?>;
         let data_des = <?php echo json_encode($all_des); ?>;

         const itemsPerPage = 6;
         let currentPage = 1;
         let totalPages;
function updateTable(data) {
 
    totalPages = Math.ceil(data.length / itemsPerPage)==0?1:Math.ceil(data.length / itemsPerPage);
    document.querySelector('#total').innerHTML= totalPages;

    const tableBody = document.querySelector('.table table');
  
     tableBody.innerHTML = ` <tr>
                <th>No</th>
                <th>image</th>
                <th>product name</th>
                <th>Weight</th>
                <th>description</th>
                <th>price</th>
                <th>stock</th>
                <th>status</th>
                <th>action</th>
            </tr>`;
   

    const start = (currentPage - 1) * itemsPerPage;
    const end = Math.min(start + itemsPerPage, data.length);

    for (let i = start; i < end; i++) {
        const row = document.createElement('tr');
        let img=data[i]["src_img"]==""?"../../assets/images/default.jpg" :data[i]["src_img"];
           let is_exsist=data[i]["description"]?"View":"Add";
           let is_exsist2=data[i]["description"]?"view":"Add_desc";
         
        row.innerHTML = `
        <td>${i}</td>
        <td><img src="../../assets/images/${img}" alt=""></td>
        <td> ${data[i]["name"]}</td>
        <td> ${data[i]["gram"]}</td>
        <td  ><button class='${is_exsist2}'style="width:40px" id="${data[i]["product_id"]}" >${is_exsist}</button></td>
        <td> ${data[i]["price"]}</td>
        <td> ${data[i]["stock_quantity"]}</td>
        <td> ${data[i]["status"]}</td>
        <td>
                   <i class="fa fa-pencil-square-o edit" aria-hidden="true" id="${data[i]["product_id"]}" ></i> 
                   <i class="fa fa-trash delete" aria-hidden="true" id="${data[i]["product_id"]}"></i>
        </td>     
        `;
        tableBody.appendChild(row);
    }
   
    document.querySelector('.prev').disabled = currentPage === 1;
    document.querySelector('.next').disabled = currentPage === totalPages;
        var view= document.querySelectorAll(".view");
        for(var i=0;i<view.length;i++){
             view[i].onclick=function(){
            var page= document.querySelector(".detail");
            var scrol= document.querySelector("body");
            page.style.display="flex";
            scrol.style.overflowY ="hidden";   
            let div = document.createElement('div');
           
            let id_view=this.id;
             for(var y=0 ;y<data_des.length;y++){
                if(id_view==data_des[y]["product_id"]){
                  var name=this.parentElement.previousElementSibling.previousElementSibling.innerHTML
                    document.querySelector(".detail").innerHTML = `
                        <div>
                    
                        <i style="right: 90px" class="fa  fa-pencil-square fa-2x mody_des" aria-hidden="true" id="${id_view}" onclick="fermer2()"></i>
                        <i style="right: 50px" class="fa fa-trash fa-2x delete_des" aria-hidden="true" id="${id_view}" onclick="fermer2()"></i>
                        <i class="fa fa-times fa-2x close" aria-hidden="true" onclick="fermer2()"></i>
                        <img src="../../assets/images/green_tea_for_buy.jpg" alt="">
                        <div>
                            <h2>${name}</h2>
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
             var open_mody_des=document.querySelector('.mody_des');
            open_mody_des.onclick=function(){
                    var page=document.querySelector('.add_mody');
                    var page2=document.querySelector('.add_mody> form');
                    var page3=document.querySelector('.detail');

                    page3.style.display="none";
                
                    var descrip=document.querySelector('.descrip');
                    page.style.display="flex";
                    page2.style.display="none";
                    descrip.style.display="flex";
                
                        var titre=document.querySelector('.descrip h2');
                            page.style.display="flex";
                            titre.innerHTML="Modify description";
                                var type=document.querySelector('.type');
                                var origin=document.querySelector('.origin');
                                var caffeine=document.querySelector('.caffeine');
                                var description=document.querySelector('.description');
                                var benefits=document.querySelector('.benefits');
                                var prepare=document.querySelector('.prepare');

                                type.required = false;
                                origin.required = false;
                                caffeine.required = false;
                                description.required = false;
                                benefits.required = false;
                                prepare.required = false;
                                var btn=document.querySelector('.submit2');
                                let id_mody=this.id;
                            document.querySelector('.id_mody_des').value=id_mody;

                                btn.value="Modify description";
                                btn.name="mod_des";
                                for(var i =0;i<data.length;i++){
                            
                                        if(data_des[i]["product_id"]==id_mody)
                                        {
                                            type.value=data_des[i]["type"];
                                            origin.value=data_des[i]["origin"];
                                            caffeine.value=data_des[i]["caffeine"];
                                            description.value=data_des[i]["description"];
                                            benefits.value=data_des[i]["benefits"];
                                            prepare.value=data_des[i]["prepare"];
                                            break;
                                        }}
                }
       var ouvrer_delete_des=document.querySelector('.delete_des');
      
              ouvrer_delete_des.onclick=function(){
             var page3=document.querySelector('.detail');
             page3.style.display="none";
            var page=document.querySelector('.add_mody');
            var page3=document.querySelector('.add_mody > div');
            let id_delete=document.querySelector('.id_delete');
            let delete_btn=document.querySelector('.delete_btn');
            delete_btn.name="delete_des";
             id_delete.value=this.id;
         
             var page2=document.querySelector('.add_mody form');
             page.style.display="flex";
            page2.style.display="none";
            page3.style.display="flex";
            
        }
   
        }
        }
        var open_des=document.querySelectorAll('.Add_desc');
        for(var i=0;i<open_des.length;i++){
        open_des[i].onclick=function(){
                 var page=document.querySelector('.add_mody');
                 var page2=document.querySelector('.add_mody> form');
                 document.querySelector('.id_mody_des').value=this.id;
               
                var descrip=document.querySelector('.descrip');
                page.style.display="flex";
                page2.style.display="none";
                descrip.style.display="flex";
               
            }
        }
   
       
               
   
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
           window.sessionStorage.setItem("add_mody_pro","mody");

            var page=document.querySelector('.add_mody');
            var titre=document.querySelector('.add_mody form h2');
                page.style.display="flex";
                titre.innerHTML="Modify product";
                    var name=document.querySelector('.name');
                    var gram=document.querySelector('.gram');
                    var price=document.querySelector('.price');
                    var stock=document.querySelector('.stock');
                    name.required = false;
                    gram.required = false;
                    price.required = false;
                    stock.required = false;
                    var btn=document.querySelector('.submit');
                    let id_mody=document.querySelector('.id_mody');
                    id_mody.value=this.id;
                    btn.value="Modify";
                    btn.name="modify";
                    for(var i =0;i<data.length;i++){
                    
                        
                            if(data[i]["product_id"]==this.id)
                            {
                                name.value=data[i]["name"];
                                gram.value=data[i]["gram"];
                                price.value=data[i]["price"];
                                stock.value=data[i]["stock_quantity"];
                                break;
                            }
                    }
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
            || data[i]["price"].toString().toLowerCase().indexOf(sear)  >-1
            || data[i]["stock_quantity"].toString().toLowerCase().indexOf(sear)  >-1
            || data[i]["status"].toLowerCase().indexOf(sear)  >-1 ){
                if(new_data.indexOf(data[i])==-1){
                  new_data.push(data[i]);
              }
              
      
            }
        }
        updateTable(new_data);
     

     }
  
       
        function fermer2(){
            var page=document.querySelector('.detail');
            var scrol= document.querySelector("body");
            page.style.display="none";
            scrol.style.overflowY ="scroll";    

        }

    
      
        function previewFile() {
            var preview = document.querySelector('#new_pro');
            var file    = document.querySelector('#file_new').files[0];
            var reader  = new FileReader();

            reader.onloadend = function () {
                preview.src = reader.result;
                let src_img=document.querySelector('.src_img');
                src_img.value= preview.src;
            }

            if (file) {
                reader.readAsDataURL(file);
            } else {
                preview.src = "";
            }
        }
        
        function fermer(){
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
            document.querySelector('.stock').value="";
            document.querySelector('.price').value="";
            document.querySelector('.gram').value="";
                window.sessionStorage.removeItem("add_mody_pro");

         }

      
         function fermer3(){
            
                var page=document.querySelector('.add_mody');
                 var page2=document.querySelector('.add_mody> form');
               
                var descrip=document.querySelector('.descrip');
            descrip.style.display="none";
            page2.style.display="flex";
            page.style.display="none";
            var btn=document.querySelector('.submit2');
            btn.value="Add description";
            btn.name="add";
            document.querySelector('.type').value="";
            document.querySelector('.origin').value="";
            document.querySelector('.caffeine').value="";
            document.querySelector('.description').value="";
            document.querySelector('.benefits').value="";
            document.querySelector('.prepare').value="";
                window.sessionStorage.removeItem("add_mody_pro");

         }
       
    
        var ouvrer_new=document.querySelector('#add_new_pro');
        ouvrer_new.onclick=function(){
           window.sessionStorage.setItem("add_mody_pro","add");
            
            var page=document.querySelector('.add_mody');
            var titre=document.querySelector('.add_mody form h2');

            titre.innerHTML="add new product";
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