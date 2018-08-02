<?php 
include("PHPMailer/class.phpmailer.php");
include("includes/connect.php");
include("includes/functions.php");
$returnData = array("success"=>"", "error"=>"");
if(isset($_POST['email'])){
	$email = $_POST['email'];
	$query = "SELECT status,identifier,name FROM tblEmployee WHERE email = '$email' ";
	$count = 0;
    $data = fetchData($query, array(), $count);
    if(count($data)){
        $userFData = $data[0];
        if($userFData["status"] == "R"){
        	$returnData["error"] = "You Can not Reset Your Password.";
        }else{
        	$newPass = GeneratePass();
        	$contentArray = array("password"=>md5($newPass));
        	updateData("tblEmployee", $contentArray, "identifier", $userFData["identifier"]);
    		$subject = "Proecss Protal - Password Reset Request";
    		$to = $email;
    		$mailBody = file_get_contents("forget-temp.html");
    		$mailBody = str_replace("[USERNAME]", $userFData["name"] , $mailBody);
    		$mailBody = str_replace("[PASSWORD]", $newPass , $mailBody);
			mailSend($to,$subject,$mailBody);     
        	$returnData["pass"] = $newPass;
        	$returnData["success"] = "Password has been sent to your email ID.";
        }
    }else{
    	$returnData["error"] = "Email Not Found.";
    }
}
echo json_encode($returnData);
?>