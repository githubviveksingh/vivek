<?php
session_start();
if(!isset($_SESSION["user"])){
	$actual_link = $_SERVER[REQUEST_URI];
	if(!empty($actual_link) && $actual_link != "/"){
		$_SESSION['redirect_url'] = $actual_link;
	}
    header("Location: login.php");
    exit();
}
?>
