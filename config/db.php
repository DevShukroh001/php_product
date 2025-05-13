<?php
$servername = "localhost";
$username = "root";
$password = "";
$db = "shuk_learning";


$connect = mysqli_connect($servername, $username, $password, $db);


if(!$connect){
  die("There is error in connection");
} 