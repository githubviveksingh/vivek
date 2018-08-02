<?php
include("../includes/check_session.php");
include("../includes/connect.php");
include("../includes/functions.php");

if(isset($_POST['purchaseStatus'])){
 //   $dod = $_POST['dod'];
    $identifier = $_POST['purchaseIdentifier'];
    $statusCode = $_POST['purchaseStatus'];
    $challan = $_POST['challan'];
   // $location = $_POST['deliveryLocation'];
    $currentStatus = $_POST["current_status"];

    $contentArray = array();
   // $contentArray["DateOfDelivery"] = $dod;
    $contentArray["status"] = $statusCode;
	if($challan!=""){
    $contentArray["DeliveryChallan"] = $challan;
	}
   // $contentArray["DeliveryLocation"] = $location;

    updateData("tblPurchase", $contentArray, "identifier", $identifier);

    $contentArray = array("Previous_Status"=>$currentStatus, "Current_Status"=>$statusCode, "Other_Data"=>$contentArray);
    generateAudit("PUR_STATUS", $identifier, $contentArray, $notifyAction="");
}
?>
