<?php
include("../includes/check_session.php");
include("../includes/connect.php");
include("../includes/functions.php");
if(isset($_POST['invoiceId'])){

	$invoceID=explode(',', $_POST['invoiceId']);

	foreach ($invoceID as $value) {
	$table 							= 'tblInvoice';
	$contentArray 					= array();
	$invoiceId   					= $value;
	$contentArray['status'] 	    = 2;

	$updateInvoice = updateData($table, $contentArray, "identifier", $invoiceId);
	}
	if($updateInvoice){
		echo 'success';
		die();
	}else{
		echo 'error';
		die();
	}

}
?>