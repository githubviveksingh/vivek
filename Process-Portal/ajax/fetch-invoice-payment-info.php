<?php
include("../includes/check_session.php");
include("../includes/connect.php");
include("../includes/functions.php");
if(isset($_POST['dataid'])){

	$invoiceId   	= $_POST['dataid'];

	$invoiceInfo 	= getInvoiceDetails($invoiceId);

    $return = array();
    $return['total_amount'] 	 = $invoiceInfo['total_amount'];
    $return['invoice_id'] 	     = $invoiceInfo['identifier'];
    $return['status']            = $invoiceInfo['status'];
    $return['cus_id']	         = $invoiceInfo['buyer'];


    echo json_encode($return);
            die();
}
?>