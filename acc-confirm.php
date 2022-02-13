<?php
session_start();
require_once 'includes/conf.php';
require_once 'includes/functions.php';

if($_SESSION['logged'] == true)
{
 
 header("Location: ./");
 exit;
}



$key_c = trim($_COOKIE['hvld-k']); // username-valid
$get_key = trim($_GET['key']); // username-valid
$acc = base64_decode($_GET['acc']); // username
$check = mysqli_query($conn,"SELECT `username` FROM `users` WHERE `username`='$acc' AND `confirmed`='false'");
$n = mysqli_num_rows( $check);
if($n == 1)
{
	if(($key_c == $get_key) && ($key_c == md5($acc."-valid")) && ($get_key == md5($acc."-valid")))
	{
		
		mysqli_query($conn,"UPDATE `users` SET `confirmed`='true', `confirm-key`='$get_key' WHERE `username`='$acc'");
		setcookie("hvld-k",NULL,-1);
		@header("Location: ./login.php?valid=true");
		//refresh(0,'./login.php?valid=true');
		exit;
	}
	else
		{
			$result =  "<h4>Ключът ти е изтекъл ! (key-expired)</h4>";
		}
		
}
else
	{
	  $result =  "<h4>Няма такъв потребител! (no-acc)</h4>";
	}

	echo  "<meta charset=utf-8> <>". $result ;