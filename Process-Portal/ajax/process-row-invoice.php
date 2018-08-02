<?php
include("../includes/check_session.php");
include("../includes/connect.php");
include("../includes/functions.php");

include '../excel/Classes/PHPExcel/IOFactory.php';

if(isset($_POST['rowNumber'])){
    $rowNumber = $_POST['rowNumber'];
    $invoice_no=$_SESSION['upload']['invoice'];
    $filepath=$_SESSION["upload"]['filepath'];
    $itemType=$_SESSION["upload"]['type'];
    $returnData 	= array("rowNumber"=>"", "end"=>0, "error"=>"", "rowData"=>"");
        $inputFileType 	= PHPExcel_IOFactory::identify($filepath);
    	$objPHPExcel 	= PHPExcel_IOFactory::load($filepath);
    	$sheetData 		= $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
    	$contentArray 	= [];
    	foreach ($sheetData as $key => $value) {
    		$companyName 					= $sheetData[3]['A'];
    		$contentArray['company_name'] 	= $companyName;
			$contentArray['identifier'] 	= $invoice_no;
    		if($value['G'] == 'Invoice No.'){
    			$key 	 					= $key+1;				
    			$contentArray['invoice_no']	= $sheetData[$key]['G'];				
    			$contentArray['date'] 		= $sheetData[$key]['J'];
    		}			
    		if($value['A'] == 'Buyer'){
    			$key 	 				= $key+1;
    			$buyerEname 	= $sheetData[$key]['A'];
				$buyer=getTableDetailsByColumn('tblcustomer','Name',$buyerEname)['identifier'];
				if($buyer=="")	
					{
					$_SESION['error']=$buyerEname;
					$contentArray['buyer']=$buyerEname;
				}else{
					$contentArray['buyer']=$buyer;
				}
    		}
			if($value['B'] == 'Total'){
    			$contentArray['total_qty'] 		= $sheetData[$key]['J'];
    			$contentArray['total_amount'] 	= preg_replace("/[^0-9,.]/", "",$sheetData[$key]['M']);
			}  
          if(intval($value['A']) >=1 && $sheetData[$key]['B']!=""){
			    $contentArrayDESC 	= array();
			    $data 		= strtolower($sheetData[$key]['B']);
    			$mainData 	= str_replace(' ', '_', $data);
    			$contentArrayDESC['name'] 		= $sheetData[$key]['B'];
    			$contentArrayDESC['hsn_sac'] 	= $sheetData[$key]['H'];
    			$contentArrayDESC['gst_rate'] 	= $sheetData[$key]['I'];
    			$contentArrayDESC['qty'] 		= $sheetData[$key]['J'];
    			$contentArrayDESC['rate'] 		= $sheetData[$key]['K'];
    			$contentArrayDESC['amount'] 	= $sheetData[$key]['M'];
				$contentArrayDESC['invoice_no']	= $invoice_no;
				addData("tblInvoiceDesc", $contentArrayDESC);	
		  } 
		 if($sheetData[$key]['C']!="" && $sheetData[$key]['M']!="" ){           	  
				 $contentA['C'][] =$sheetData[$key]['C'];
				 $contentA['M'][]  =$sheetData[$key]['M'];
			  }		  
    	}
		$tax=implode(',',$contentA['C']);
		$contentArray['tax']=$tax;
		addData("tblInvoice", $contentArray);
		$returnData['end'] = "1";
		$returnData['rowNumber'] = $rowNumber+1;
		echo json_encode($returnData);
}
?>
