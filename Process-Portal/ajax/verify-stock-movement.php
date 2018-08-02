<?php
include("../PHPMailer/class.phpmailer.php");
include("../includes/check_session.php");
include("../includes/connect.php");
include("../includes/functions.php");
if(isset($_POST['new_location'])){    
    $itemID = $_POST['sitemID'];
	$identifier=$_POST['identifier'];
    $location = $_POST['new_location'];
	$status=$_POST['status'];
	$data=getInventory('ItemID',$itemID);
	$itemclass=$data['ItemClass'];
	$moveData=getMovementStatus($itemID);	
    $employeeID=$moveData[0]['employeeID'];
	$emp=getEmpDetails($employeeID);
	if($itemclass=="201")
	{
		$tbl="tblHardware";
	}
	if($itemclass=="202")
	{
		$tbl="tblSim";
	}
	if($itemclass=="203")
	{
		$tbl="tblMachine";
	}
	if($itemclass=="204")
	{
		$tbl="tblOfficeItem";
	}
	$toemails=$emp['email'];

$item=getInventoryShortDetails($itemID,$itemclass);
	if($status!=1)
	{   
	$vrStatus="Your Location Movement Request For item:".$item." is Rejected by Admin";
	$contentArray['status']=$status;
	updateData("tblInventoryMovement", $contentArray, "identifier", $identifier);
	}else{	
    $vrStatus="Your Location Movement   is Done Successfully  For item:".$item;	
    $contentArray = array();
    $contentArray["locationID"] = $location;
    updateData($tbl, $contentArray, "itemID", $itemID);
	$contentArray2['status']=$status;
	updateData("tblInventoryMovement", $contentArray2, "identifier", $identifier);
	}
	$mailBody = file_get_contents("inventory-move-emp-mail.html");
	$mailBody =str_replace("[VRSTATUS]", $vrStatus , $mailBody);
  
$date=date('jS  F Y');
$subject="Process Portal- Inventory Movement Verification- ".$date;
//$mail  = mail($toemails,$subject,$mailBody,$headers);
mailSend($toemails,$subject,$mailBody);
}
?>
