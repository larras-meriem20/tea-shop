<?php
$servername = "localhost";
$username = "root";
$password_con = "";
$dbname= "shop";

try{
$bdd = new PDO("mysql:host=$servername; dbname=$dbname;", $username, $password_con);

}catch(PDOException $e){
    echo 'Failed to connect' . $e->getMessage();
}


?>