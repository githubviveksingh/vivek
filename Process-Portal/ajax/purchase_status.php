<?php
include("../PHPMailer/class.phpmailer.php");
include("../includes/check_session.php");
include("../includes/connect.php");
include("../includes/functions.php");
$return = array("error"=>"", "success"=>"");
if(isset($_POST['purchaseStatus'])){
    $prevStatus = $_POST['currentStatus'];
    $status = $_POST['purchaseStatus'];
    $id = $_POST['purchaseID'];

    $contentArray = array();
    $contentArray["Status"] = $status;

    $update=updateData("tblPurchase", $contentArray, "identifier", $id);
	if($update)
	{
		$purchaseD=getPurchaseDetails($id);	    
        $mailbox =file_get_contents("../partial-forms/add-purchase-mail.html");
	        $mailbox =str_replace("[PURCHASEID]" , $purchaseD['PurchaseID'] , $mailbox);
			$mailbox =str_replace("[POREFERENCE]" , $purchaseD['POReference'] , $mailbox);
			$mailbox =str_replace("[CLASSIFICATION]" , $PURCHASECLASS[$purchaseD['PurchaseClassification']][1] , $mailbox);
			$mailbox =str_replace("[QUANTITY]" , $purchaseD['Quantity'] , $mailbox);
			$supplierD=getSuppliers($purchaseD['SupplierID']);
			$supplierName=ucwords($supplierD[0]['name']);
			$mailbox =str_replace("[SUPPLIERID]" , $supplierName , $mailbox);
			$empDetails=getEmpDetails($_SESSION['user']['identifier']); 
			$empname=ucwords($empDetails['name']);
			$mailbox =str_replace("[EMPLOYEE]" , $empname , $mailbox);
			$mailbox =str_replace("[STATUS]" , $PURCHASESTATUS[$purchaseD['Status']][1] , $mailbox);
			$TITLE=' Changed Your Purchase Status';
			$mailbox =str_replace("[TITLE]" , $TITLE , $mailbox);
			$protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
            $url = $protocol . $_SERVER['HTTP_HOST'];			
			$target=$url.'/purchase.php?sSearch='.$purchaseD['POReference'];
			$mailbox =str_replace("[TARGETLINK]" , $target , $mailbox);
			$LINKTITLE='View Now';
			$mailbox =str_replace("[LINKTITLE]" , $LINKTITLE , $mailbox);
            $auditD=getAuditDetails('auditCode','PUR_ADD','targetID',$id);
            $empD=getEmpDetails($auditD['triggeredBy']); 
            $toemails=$empD['email'];
			
			$date=date('jS  F Y');
			$subject="Process Portal- New Purchase Entry: ".$PURCHASESTATUS[$purchaseD['Status']][1];
			//$mail  = mail($toemails,$subject,$mailbox,$headers);		
            mailSend($toemails,$subject,$mailbox);			
		$return["success"] = "Purchase Status Successfully Updated.";    
		//CREATE AUDIT LOG
		$contentArray = array("Previous_Status"=>$prevStatus, "Current_Status"=>$status);
		generateAudit("PUR_STATUS", $id, $contentArray, $notifyAction="");
		echo json_encode($return);
	}  

}
?>
