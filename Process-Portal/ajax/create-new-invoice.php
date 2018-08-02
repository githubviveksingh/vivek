<?php
include("../includes/check_session.php");
include("../includes/connect.php");
include("../includes/functions.php");
if(isset($_POST['dataid'])){

	$invoiceId   	= $_POST['dataid'];

	$invoiceInfo 	= getInvoiceDetails($invoiceId);
	$customer_id   	= $invoiceInfo['buyer'];

	$cbillCycle 	= getCustomerbillCycle($customer_id);


	// Start - genearte new invoice no
    $invDesc_qry 	= "SELECT max(invoice_no) as invoice_no FROM tblInvoice where 1 limit 1";
    $invDesc_count 	= 0;
    $invDesc 		= fetchData($invDesc_qry, array(), $invDesc_count); 
    
    $invoice_no 	= substr($invDesc[0]['invoice_no'], strpos($invDesc[0]['invoice_no'], "-") + 1);
    $new_invoice_no = $invoice_no+1;

    $new_invoice_no = str_replace($invoice_no, $new_invoice_no, $invDesc[0]['invoice_no']);

    //End 

	/* get billCycle date differance */
    if($cbillCycle['billCycle']==1){
        $date    = $invoiceInfo["date"];
        $dueDate = date('d-M-Y', strtotime('+1 week', strtotime($date)));
    }elseif ($cbillCycle['billCycle']==2) {
        $date    = $invoiceInfo["date"];
        $dueDate = date('d-M-Y', strtotime('+1 month', strtotime($date)));
    }elseif ($cbillCycle['billCycle']==3) {
        $date    = $invoiceInfo["date"];
        $dueDate = date('d-M-Y', strtotime('+3 month', strtotime($date)));
    }elseif ($cbillCycle['billCycle']==4) {
        $date    = $invoiceInfo["date"];
        $dueDate = date('d-M-Y', strtotime('+12 month', strtotime($date)));
    }elseif ($cbillCycle['billCycle']==5) {
        $dueDate = 'One Time Payment';
    }


    $invoiceArray['company_name'] 	= $invoiceInfo['company_name'];
    $invoiceArray['buyer'] 			= $customer_id;
    $invoiceArray['date'] 			= $dueDate;
    $invoiceArray['total_amount'] 	= $invoiceInfo['total_amount'];
    $invoiceArray['invoice_no'] 	= $new_invoice_no;
    $invoiceArray['tax'] 			= $invoiceInfo['tax'];
    $invoiceArray['customer_info']	= $invoiceInfo['customer_info'];
    $invoiceArray['total_qty']		= '';  


    $invoiceID      = addData("tblInvoice", $invoiceArray);

	$rowsData 		= getAll_invoice_desc($invoiceInfo['identifier']);
	foreach ($rowsData as $invoiceDesc) {
			$contentArray['invoice_no'] 	= $invoiceID;
            $contentArray['name'] 			= $invoiceDesc['name'];
            $contentArray['desc'] 			= $invoiceDesc['desc'];
            $contentArray['qty'] 			= $invoiceDesc['qty'];
            $contentArray['rate'] 			= $invoiceDesc['rate'];
            $contentArray['amount'] 		= $invoiceDesc['amount'];
            $contentArray['hsn_sac'] 		= $invoiceDesc['hsn_sac'];
            $contentArray['gst_rate'] 		= $invoiceDesc['gst_rate'];

            addData("tblInvoiceDesc", $contentArray);
	}
    $return = array();
    $return['invoice_no'] 	= $invoiceArray['invoice_no'];
    $return['invoice_id'] 	= $invoiceID;
    $return['customer_id']	= $customer_id;


    echo json_encode($return);
            die();


	

}
?>