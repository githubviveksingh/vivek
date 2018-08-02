<?php
include("../PHPMailer/class.phpmailer.php");
include("../includes/check_session.php");
include("../includes/connect.php");
include("../includes/functions.php");

if(isset($_POST['deliveryLocation']) && $_POST['deliveryLocation']>0){  
    $location = $_POST['deliveryLocation'];
    $remarks = $_POST["remarks"];
    $previous_Location = $_POST['current_location'];
	$itemID = $_POST['itemID'];
    $contentArray = array();
	$contentArray["itemID"] = $itemID;
    $contentArray["remarks"] = $remarks;
    $contentArray["newLocationID"] = $location;
	$contentArray["currentLocationID"] = $previous_Location;
	$contentArray["employeeID"] = $_SESSION["user"]["identifier"];
	addData("tblInventoryMovement", $contentArray);
    $contentArrayAudit = array("Previous_Location"=>$previous_Location, "Current_Location"=>$location,"Current_Remarks"=>$remarks);
    generateAudit("OIT_LUP", $itemID, $contentArrayAudit, $notifyAction="");

$item=getInventoryShortDetails($itemID,'204');
$empD=getEmpDetails($_SESSION["user"]["identifier"]);

$html=file_get_contents("../partial-forms/new-tech.html");
$html =str_replace("[TITLE]" , "OFFICE Inventory" , $html);
$protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
$url = $protocol . $_SERVER['HTTP_HOST'];
$link=$url.'/office-item.php?sSearch='.$item;
$html =str_replace("[TARGETLINK]" , $link , $html);	
$itemHTML=$item.'('.$itemID.')';
$html =str_replace("[ItemID]" , $itemHTML , $html);
$cuurentlcHTML=getLocationName($previous_Location).'('.$previous_Location.')';
$html =str_replace("[CURRENTLC]" , $cuurentlcHTML , $html);
$newlcHTML=getLocationName($location).'('.$location.')';
$html =str_replace("[NEWLC]" , $newlcHTML , $html);
$triggerbyHTML=$empD['name'];
$html =str_replace("[TRIGGERBY]" , $triggerbyHTML , $html);
$html =str_replace("[REMARKS]" , $remarks , $html);

foreach($permission=$permissionsArray["inventory"]["411"]["notification"] as $role)
  {
	$admins=getAllempBYRole($role);
	foreach($admins as $admin)
	{
		$emails[]=$admin['email'];
	}
  }
$emailTo=implode(',',$emails);
$date=date('jS  F Y');
$subject="Process Portal- Inventory Movement Request";
$mail  = mail($email,$subject,$html,$headers);
mailSend($emailTo,$subject,$html); 
}
?>
