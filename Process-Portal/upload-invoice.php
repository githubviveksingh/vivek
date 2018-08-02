<?php
include("includes/check_session.php");
include("includes/header.php");
include 'excel/Classes/PHPExcel/IOFactory.php';

if(isset($_POST["submit"]))
{
		$filepath   	= $_FILES['file']['tmp_name'];
    	$returnData 	= array("rowNumber"=>"", "end"=>0, "error"=>"", "rowData"=>"");
        $inputFileType 	= PHPExcel_IOFactory::identify($filepath);
    	$objPHPExcel 	= PHPExcel_IOFactory::load($filepath);
    	$sheetData 		= $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
//var_dump($sheetData);
    	$contentArray 	= [];
		$contentA 	= array();
		$invoice_no=getInvoiceID();
		// $contentA 	= array();	
    	foreach ($sheetData as $key => $value) {
           

		//   $content[]=$sheetData[$key][$value];
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
				//if()
				//addData("tblInvoiceDesc", $contentArrayDESC);				
		  }		   
		  if($sheetData[$key]['C']!="" && $sheetData[$key]['M']!="" ){           	  
			 $contentA['C'][] =$sheetData[$key]['C'];
			 $contentA['M'][]  =$sheetData[$key]['M'];
		  }
    	}
		//addData("tblInvoice", $contentArray);
		$tax=implode(',',$contentA['C']);
	echo $tax;
		//print_r($contentArray);
		//print_r($contentArrayDESC);
		exit;
 }
include("includes/html-header.php");
?>
    <body class="page-header-fixed page-sidebar-closed-hide-logo page-content-white">
        <div class="page-wrapper">
            <?php
                include("includes/topheader.php");
            ?>
            <div class="clearfix"> </div>
            <div class="page-container">
                <?php
                    include("includes/left_sidebar.php");
                ?>
                <div class="page-content-wrapper">
                    <div class="page-content">
                         <div class="row">
                             <div class="col-md-12 tabbable-line boxless tabbable-reversed tab-content tab_0">
                                <div class="portlet box yellow">
                                    <div class="portlet-title">
                                        <div class="caption">
                                            <i class="fa fa-gift"></i><?php echo $heading;?>
                                        </div>
                                    </div>
                                    <div class="portlet-body form">
                                        <form class="form-horizontal" id="addSupp" method="post" action="" enctype="multipart/form-data">
                                         <?php
                                            if(isset($_SESSION["success"])){?>
                                                <div class="alert alert-success">
                                                    <button class="close" data-close="alert"></button>
                                                    <span><?php echo $_SESSION["success"];?></span>
                                                </div>
                                            <?php unset($_SESSION["success"]);}
                                            ?>
                                            <?php
                                            if(isset($_SESSION["error"])){?>
                                                <div class="alert alert-danger">
                                                    <button class="close" data-close="alert"></button>
                                                    <span><?php echo $_SESSION["error"];?></span>
                                                </div>
                                            <?php unset($_SESSION["error"]);}
                                            ?>
                                            
                                            <div class="form-body">
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label">Import CSV<span class="required" aria-required="true"> * </span></label>
                                                    <div class="col-md-4">
                                                        <input class="form-control" type="file" name="file">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-actions fluid">
                                                <div class="row">
                                                    <div class="col-md-offset-3 col-md-9">
                                                        <input type="submit" class="btn green" name="submit" value="Submit">
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                             </div>
                         </div>
                    </div>
                </div>
            </div>
            <?php
                include("includes/footer.php");
            ?>
        </div>

        <?php
            include("includes/common_js.php");
        ?>
        <script src="assets/pages/add-supplier.js"></script>
       
    </body>
</html>
