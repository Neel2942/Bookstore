<?php
//session_start();
$host="localhost:3308";
$username="root";
$pass="";
$db="group7_bookstore";
 
$conn=mysqli_connect($host,$username,$pass,$db);
if(!$conn){
	die("Database connection error");
}
?>