<?php
include("../includes/check_session.php");
include("../includes/connect.php");
include("../includes/functions.php");
if(isset($_POST['paidAmount'])){

	$table 							= 'tblInvoice';
	$contentArray 					= array();
	$invoiceId   					= $_POST['invoiceId'];
	$customerId   					= $_POST['cus_id'];
	$contentArray['paid_amount'] 	= $_POST['amount'];
	$contentArray['status'] 	    = $_POST['status'];

	$updateInvoice = updateData($table, $contentArray, "identifier", $invoiceId);
	if($updateInvoice){
		$_SESSION["success"] 	= "Payment has been successfully paid";
		header("Location: {$_SERVER['HTTP_REFERER']}");
	}else{
		$_SESSION["error"] 		= "Payment has been not successfully paid";
		header("Location: {$_SERVER['HTTP_REFERER']}");
	}

}
?>