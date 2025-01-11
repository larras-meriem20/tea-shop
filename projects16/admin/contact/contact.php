
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

include("../../classes/contacts.php");

$all=Contact::get_all_contact();
if(isset($_POST["delete"])){
    $id=$_POST["id_delete"];
    settype($id, "integer");
    
 $delete =new Contact($id);
$delete->delete_contact();
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
            <li ><a href="../dashboard/dashboard.php"><i class="fa fa-tachometer" aria-hidden="true"></i> <span>Dashboard</span></a></li>
            <li ><a href="../products/products.php"><i class="fa fa-archive" aria-hidden="true"></i> <span>Products</span></a></li>
            <li ><a href="../orders/orders.php"><i class="fa fa-shopping-cart" aria-hidden="true"></i> <span>Orders</span></a></li>
            <li class="before"><a href="../accounts/accounts.php"><i class="fa fa-users" aria-hidden="true"></i> <span>Accounts</span></a></li>
            <li class="now"><a href="../contact/contact.php"><i class="fa fa-comments" aria-hidden="true"></i> <span>Contact</span></a></li>
            <li class="after" ><a href="?logout"><i class="fa  fa-sign-out" aria-hidden="true"></i> <span>Log out</span></a></li>
            
        </ul>
    </div>



    <div class="page">
        <div class="partie1">
            <div>
                <i class="fa fa-bars fa-2x barbar" aria-hidden="true"></i>
                <h3> Contacts</h3>
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
                <h2>All Contacts</h2>
                <p>Dashboard > Contacts</p>
            </div>
            <div>
                <i class="fa fa-search" aria-hidden="true"></i>
                <input type="search" class="search" placeholder="search contact">
                
            </div>
        </div>
        <div class="table">
        <table>
           
            
    
           
        </table>
        </div>
        <div class="suiv" >
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
        
        <div>
            <h2>Delete contact</h2>
            <p>
                Are you sure you want to delete this contact?
            </p>
            <form method="post">
                <input type="submit"  value="Cancel" onclick="fermer()">
                <input type="submit" name="delete" value="Delete" onclick="fermer()">
                <input style="position: absolute;" type="hidden" class="id_delete" name="id_delete" >
            </form>
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
        const data = <?php echo json_encode($all); ?>;

        let itemsPerPage = 6;
        let currentPage = 1;
        let totalPages ;
function updateTable(data) {

    totalPages = Math.ceil(data.length / itemsPerPage)==0?1:Math.ceil(data.length / itemsPerPage);
    document.querySelector('#total').innerHTML= totalPages;

    const tableBody = document.querySelector('.table table');
  
     tableBody.innerHTML = ` <tr>
                <th>No</th>
                <th>Full name</th>
                <th>Email</th>
                <th>Message</th>
                <th>Action</th>
            </tr>`;
   

    const start = (currentPage - 1) * itemsPerPage;
    const end = Math.min(start + itemsPerPage, data.length);

    for (let i = start; i < end; i++) {
        const row = document.createElement('tr');
        row.innerHTML = `
        <td>${i}</td>
        <td> ${data[i]["full_name"]}</td>
        <td > ${data[i]["email"]}</td>
        <td> ${data[i]["message"]}</td>
        <td>
                   <i class="fa fa-trash delete" aria-hidden="true" id="${data[i]["contact_id"]}" ></i>
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
             page.style.display="flex";
             let id_delete=document.querySelector('.id_delete');
             id_delete.value=this.id;
         
        }}
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
           
            if (data[i]["full_name"].toLowerCase().indexOf(sear) >-1
            || data[i]["email"].toLowerCase().indexOf(sear)  >-1
            || data[i]["message"].toLowerCase().indexOf(sear)  >-1 ){
               
                if(new_data.indexOf(data[i])==-1){
                  new_data.push(data[i]);
              }
              
      
            }
        }
        updateTable(new_data);
     

     }
        
       function fermer(){
        var page=document.querySelector('.add_mody');
            page.style.display="none";
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