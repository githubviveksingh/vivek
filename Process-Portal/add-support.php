<?php
include("includes/check_session.php");
include("includes/header.php");
include("PHPMailer/class.phpmailer.php");
	$activeHref 	= "support.php";
	$empID 			= isset($_GET['empid'])?intval($_GET['empid']):"0";
	$contentArray 	= array();
	$contentArray2 	= array();
	$id 			= $_POST["empID"];
	if($_POST['itemID']!="")
	{
	$contentArray["itemID"] 			= $_POST['itemID'];
	}
	if($_POST['itemCategory']!="")
	{
		$contentArray["itemCategory"] 		= $_POST['itemCategory'];
	}
	$contentArray["DateofReport"] 		= $_POST['DateOfReport']; 	
	$contentArray["Classification"] 	= $_POST['Classification'];
	$contentArray["ServiceReportNo"] 	= $_POST['ServiceReportNo']; 
	$contentArray["identificationInfo"] = $_POST['identificationInfo']; 
	$contentArray["noOfVisit"] 			= $_POST['noOfVisit']; 
	$contentArray["raisedBy"]			= $_POST['raisedBy'];
	$contentArray["additionalInfo"] 	= $_POST['additionalInfo'];
	 if(count($_FILES['serviceReport']['name']) > 0){
		for($i=0; $i<count($_FILES['serviceReport']['name']); $i++) {
			 $tmpFilePath 	= $_FILES['serviceReport']['tmp_name'][$i];
			 $shortname 	= $_FILES['serviceReport']['name'][$i];
			 $filePath 		= "support/".$shortname;
			 move_uploaded_file($tmpFilePath, $filePath);
		}
		$serviceReport 		= implode(",",$_FILES['serviceReport']['name']);
		if(!empty($serviceReport)){
			$contentArray["serviceReport"] = $serviceReport;
		}		
	} 
    if(isset($_POST['EmployeeID'])){
		if(!empty($_POST['DateOfResolution'])){
			$contentArray["DateOfResolution"] 	= $_POST['DateOfResolution'];		
		}        
		$contentArray["technicianID"] 			= $_POST['EmployeeID'];
        $contentArray2["EmployeeID"] 			= $_POST['EmployeeID']; 
	}	
	$cusID 						= $_POST['CustomerID'];   
	$contentArray["CustomerID"] = $cusID;
	$contentArray["Status"] 	= $_POST['Status'];
	$contentArray2["Location"] 	= $_POST['address'];		
	if(!empty($_POST['closingNote'])){
	$contentArray['closingNote']=$_POST['closingNote'];
	}
	
if(isset($_POST['submit'])){

 	if($_POST['Status']=="431"){
	   $mailbox = file_get_contents("partial-forms/support-tech-mail.html");	
	   $mailbox = str_replace("[SERVICEREPORTNO]" , $SUPPORTTYPE[$_POST['Classification']] , $mailbox);
	   if($_POST['itemID']!=""){
		    $itemdetails 	= getInventoryDetails($_POST['itemID'],"tblHardware");
	    	$itemdetail 	= $itemdetails['IMEI'].'-'.$itemdetails['model'];
	   
	   }else{
		   $itemdetail = "";
	   }	  
	   $mailbox 		= str_replace("[ITEMDETAILS]" , $itemdetail , $mailbox);
	   $mailbox 		= str_replace("[DATEOFREPORT]" , $_POST['DateOfReport'] , $mailbox);
	   $classification  = $SUPPORTCLASSIFICATION[$_POST['Classification']];
	   $mailbox 		= str_replace("[CLASSIFICATION]" , $classification , $mailbox);
	   $customer 		= getCustomerDetails($_POST['CustomerID']);
	   $mailbox 		= str_replace("[CUSTOMER]" , $customer['Name'] , $mailbox);
	   $mailbox 		= str_replace("[LOCATION]" , $_POST['address'] , $mailbox);
       $empDetails 		= getEmpDetails($_SESSION['user']['identifier']); 
	   $empTech 		= getEmpDetails($_POST['EmployeeID']);        
	   $mailbox 		= str_replace("[TITLE]" , "create new support ticket." , $mailbox);	
	   $mailbox 		= str_replace("[ADDINFO]" , $_POST['additionalInfo'] , $mailbox);	
       $mailbox 		= str_replace("[EMPLOYEE]" , $empDetails['name'] , $mailbox);	
	   $heading 		= "Service Report No:".$_POST['ServiceReportNo']." is assigned to you.Please take quick action.";
	   $mailbox 		= str_replace("[HEADING]" , $heading , $mailbox);	  
       $toemails 		= $empTech['email'];	   	
	   $subject 		= "Process Portal- Support Ticket- ".$_POST['ServiceReportNo'];
	   //$mailSender = mailSend($toemails,$subject,$mailbox,$bcc,$emails);	
	} 	
    if($id == "0"){	
        $contentArray["EmployeeID"] = $_SESSION['user']['identifier'];	
        $empID 						= addData("tblSupport", $contentArray);
		$contentArray2["SupportID"] = $empID;
		$tid 		= addData("tblTechnicianScheduling", $contentArray2);
        //ADD AUDIT ENTRY
        $auditID 	= generateAudit("SPT_ADD", $empID, $contentArray, "EMAIL");
		
        $_SESSION["success"] = "Support Added Successfully.";
        header("Location:support.php");
        exit();
    }else{

    	echo "<pre>";
    	print_r($contentArray);die();
        $oldData = $_SESSION["oldData"];			  
		updateData("tblSupport", $contentArray, "identifier", $id);		
		if($_POST['EmployeeID']!="")
		{
		updateData("tblTechnicianScheduling", $contentArray2, "SupportID", $id);
		if($_POST['Status']=="433")
		   {			   
		   $mailbox 	= file_get_contents("partial-forms/support-employee-mail.html");	
		   $mailbox 	= str_replace("[SERVICEREPORTNO]" , $_POST['ServiceReportNo'] , $mailbox);
		   $itemdetails = getInventoryDetails($_POST['itemID'],"tblHardware");
		   $itemdetail 	= $itemdetails['IMEI'].'-'.$itemdetails['model'];
		   $mailbox 	= str_replace("[ITEMDETAILS]" , $itemdetail , $mailbox);
		   $mailbox 	= str_replace("[DATEOFREPORT]" , $_POST['DateOfReport'] , $mailbox);
		   $mailbox 	= str_replace("[DATEOFRESOLUTION]" , $_POST['DateOfResolution'] , $mailbox);
		   $customer 	= getCustomerDetails($_POST['CustomerID']);
		   $mailbox 	= str_replace("[CUSTOMER]" , $customer['Name'] , $mailbox);      
		   $empTech 	= getEmpDetails($oldData['EmployeeID']); 
           $empDetails 	= getEmpDetails($_POST['EmployeeID']); 	
           $mailbox 	= str_replace("[TITLE]" , $SUPPORTSTS[$_POST['Status']][1] , $mailbox);	
	       $mailbox 	= str_replace("[ADDINFO]" , $_POST['additionalInfo'] , $mailbox);			   
	       $mailbox 	= str_replace("[ISSUIDENTIFICATION]" , $_POST['identificationInfo'] , $mailbox); 
	       $mailbox 	= str_replace("[RESOLUTION]" , $_POST['closingNote'] , $mailbox);		   
	       $mailbox 	= str_replace("[SITEVISIT]" , $_POST['noOfVisit'] , $mailbox);			   
		   $mailbox 	= str_replace("[TECHNICIAN]" , $empDetails['name'] , $mailbox);	
		   $heading 	= "Service Report No:".$_POST['ServiceReportNo']." is ".$SUPPORTSTS[$_POST['Status']][1];
		   $mailbox  	= str_replace("[HEADING]" , $heading , $mailbox);
		   $toemails 	= $empTech['email'];
		   $subject 	= "Process Portal- Support Ticket- ".$_POST['ServiceReportNo']."- ".$SUPPORTSTS[$_POST['Status']][1];
		  // $mailSender 	= mailSend($toemails,$subject,$mailbox,$bcc,$toemails);
		   }
		}
		//ADD AUDIT ENTRY
		$auditData 	= array("old_values"=>$_SESSION['oldData'], "new_values"=>$contentArray);
		$auditID 	= generateAudit("SPT_UPD", $empID, $auditData, "EMAIL");
		unset($_SESSION["oldData"]);
		$_SESSION["success"] = "Support Updated Successfully.";
		header("Location:support.php");
		exit();
    }
}
if($empID != "0"){
    $empData 				= getSupportDetails($empID);
	$empData2 				= getAssignTechDetails($empID);		
    $_SESSION["oldData"] 	= $empData;
    if(count($empData)){
        $itemID 			= $empData["itemID"];
        $itemCategory 		= $empData["itemCategory"];
        $DateOfReport 		= $empData["DateofReport"];		
        $DateOfResolution 	= $empData["DateOfResolution"];
		if($DateOfResolution<=0){
			$DateOfResolution=date("Y-m-d");
		}
        $Classification 	= $empData["Classification"];
        $ServiceReportNo 	= $empData["ServiceReportNo"];
        $CustomerID 		= $empData["CustomerID"];     
        $EmployeeID 		= $empData["technicianID"]; 
        $address 			= $empData2['Location'];		
        $Status 			= $empData['Status'];		
		$raisedBy 			= $empData['raisedBy'];	
		$additionalInfo 	= $empData['additionalInfo'];
		$identification 	= $empData['identificationInfo'];
		$noOfVisit 			= $empData['noOfVisit'];
		$closingNote 		= $empData['closingNote'];
        $heading 			= "Update Support Ticket Details";
    }else{
        $_SESSION['error'] = "Support Ticket does not exist.";
        header("Location: customers.php");
        exit();
    }
}
if($empID == "0" && !isset($_POST['submit'])){
    $itemID 			= "";
    $itemclassification = "";  
    $ServiceReportNo 	= "";
    $GST 				= "";
    $VAT 				= "";
    $ST 				= "";
    $doj 				= "";
    $dor 				= "";
    $EMPID 				= "";
    $billCycle 			= "";
	$DateOfReport 		= date("Y-m-d");	
    $heading 			= "Add Support Ticket";
}
include("includes/html-header.php");
?>
<style>
.select2 {
    width: auto !important;
}
</style>
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
                                        <form class="form-horizontal" id="addEmp" method="post" action="" enctype="multipart/form-data">
                                        <?php
                                            if(isset($_SESSION["success"])){?>
                                                <div class="alert alert-success">
                                                    <button class="close" data-close="alert"></button>
                                                    <span><?php echo $_SESSION["success"];?></span>
                                                </div>
                                            <?php unset($_SESSION["success"]);}
                                            ?>
                                            <?php
                                            if(isset($_SESSION["error"])){ ?>
                                                <div class="alert alert-danger">
                                                    <button class="close" data-close="alert"></button>
                                                    <span><?php echo $_SESSION["error"];?></span>
                                                </div>
                                            <?php unset($_SESSION["error"]);}
                                            ?>
                                            <input type="hidden" name="empID" value="<?php echo $empID;?>" id="empID">
                                            <div class="form-body"><div>
											    <?php include("partial-forms/customers-list.php");?>	
                                               <!-- <?php include("partial-forms/item-classifications.php");?>-->
											   <?php include("partial-forms/support-type.php");?>  
                                                <div  id="Inventry"><?php if($Classification==2){ 
												 $itemDetails=getInventoryDetails($itemID,"tblHardware");
												 ?>
													<div class="form-group" >
													<label class="col-md-3 control-label">Item<span class="required" aria-required="true"> * </span></label>
													<div class="col-md-4">
													<select name="itemID" class="selectbox form-control" id="">
													 <option value="<?php echo $itemID;?>" <?php echo $selected;?>><?php echo $itemDetails['IMEI'];?>-<?php echo $itemDetails['model'];?></option>
													</option>
													</select>
													</div>
													</div>
												<?php  }
												 ?>	</div> 
													<!-- Software Category List-->

												<?php if(isset($Classification) && $Classification==3){ ?>
												<div  id="SoftwareCategory">
													<div class="form-group" >
														<label class="col-md-3 control-label">Software Category<span class="required" aria-required="true"> * </span></label>
														<div class="col-md-4">
															<select name="itemCategory" class="selectbox form-control" id="" >
																<option value="" disabled selected >Select Software Category</option>
															<?php foreach ($SOFTWARECATEGORIES as $key => $value) { ?>
															 	<option value="<?php echo $key; ?>"  <?php if ($key == $itemCategory) echo ' selected="selected"'; ?> ><?php echo $value; ?></option>
															<?php } ?>
															</select>
														</div>
													</div>
												</div>          

												<?php }else{ ?>

												<div  id="SoftwareCategory" style="display: none;">
													<div class="form-group" >
														<label class="col-md-3 control-label">Software Category<span class="required" aria-required="true"> * </span></label>
														<div class="col-md-4">
															<select name="itemCategory" class="selectbox form-control" id="" selected="<?php echo $itemCategory; ?>">
																<option value="" disabled selected >Select Software Category</option>
															<?php foreach ($SOFTWARECATEGORIES as $key => $value) { ?>
															 	<option value="<?php echo $key; ?>" ><?php echo $value; ?></option>
															<?php } ?>
															</select>
														</div>
													</div>
												</div>          

												<?php }  ?>	
												 

												<!-- Other Category List -->
												<?php if(isset($Classification) && $Classification==4){ ?>
												 <div  id="OthersCategory">
													<div class="form-group" >
														<label class="col-md-3 control-label">Other Category<span class="required" aria-required="true"> * </span></label>
														<div class="col-md-4">
															<select name="itemCategory" class="selectbox form-control" id="">
																<option value="" disabled selected >Select Other Category</option>	
															 	<?php foreach ($OTHERSCATEGORIES as $key => $value) { ?>
															 	<option value="<?php echo $key; ?>" <?php if ($key == $itemCategory) echo ' selected="selected"'; ?> ><?php echo $value; ?></option>
															<?php } ?>
															</select>
														</div>
													</div>
												</div>  
												<?php }else{ ?>    

												<div  id="OthersCategory" style="display: none;">
													<div class="form-group" >
														<label class="col-md-3 control-label">Other Category<span class="required" aria-required="true"> * </span></label>
														<div class="col-md-4">
															<select name="itemCategory" class="selectbox form-control" id="">
																<option value="" disabled selected >Select Other Category</option>	
															 	<?php foreach ($OTHERSCATEGORIES as $key => $value) { ?>
															 	<option value="<?php echo $key; ?>" ><?php echo $value; ?></option>
															<?php } ?>
															</select>
														</div>
													</div>
												</div>  
												<?php }?>    


                                                <div class="form-group">
                                                    <label class="col-md-3 control-label">Date Of Report<span class="required" aria-required="true"> * </span></label>
                                                    <div class="col-md-4">
                                                        <input class="form-control" placeholder="Enter Date Of Report" type="text" name="DateOfReport" id="doreport" value="<?php echo $DateOfReport;?>">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label">Raised by</label>
                                                    <div class="col-md-4">
                                                        <input class="form-control" placeholder="Enter Raised by" type="text" name="raisedBy" value="<?php echo $raisedBy;?>">
                                                    </div>
                                                </div>	
												<div class="form-group">
												<label class="col-md-3 control-label">Client Address</label>
												<div class="col-md-9">
												<textarea class="form-control" id="CAddress" placeholder="Enter  Address" name="address"><?php echo $address;?></textarea>
												</div>
												</div>												
                                                <?php include("partial-forms/support-status.php");?>

                                                <?php if(isset($Classification) && $Classification!=''){ 

                                                	if($Classification==3){ ?>

                                                	<div id="techFileds" class="hide">
													    <?php include("partial-forms/techsection.php");?>
                                                												
													</div> 
													<div id="softFileds" class="">
														    <?php include("partial-forms/employee.php");?>								
													</div> 

                                                <?php }else{?>

	                                                <div id="techFileds" class="">
														    <?php include("partial-forms/techsection.php");?>
	                                                												
													</div> 
													<div id="softFileds" class="hide">

														    <?php include("partial-forms/employee.php");?>								
													</div> 

                                               <?php } }else { ?>

                                               <div id="techFileds" class="hide">
													    <?php include("partial-forms/techsection.php");?>
                                                												
												</div> 
												<div id="softFileds" class="hide">

													    <?php include("partial-forms/employee.php");?>								
												</div> 

                                               <?php }?>
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label">Issue Details</label>
                                                    <div class="col-md-9">
                                                        <textarea class="form-control" id="additional" placeholder="Enter  issue details for technician" name="additionalInfo"><?php echo $additionalInfo;?></textarea>
                                                    </div>
                                                </div>												
												<div id="techFiledsolved" class="<?php if($Status!="433"){ echo 'hide';}?>">
												<?php if($Classification==1){ 
												 ?>
												<div class="form-group" >
												<label class="col-md-3 control-label">Item<span class="required" aria-required="true"> * </span></label>
												<div class="col-md-4">
												<select name="itemID" class="selectbox form-control" id="">
												<option value="" selected disabled>Select Item</option>
												<?php 
												$inventory = array();
												$query = "SELECT * FROM tblCustomerItems where customerID='$CustomerID'";
												$count = 0;
												$inventory = fetchData($query, array(), $count);
												foreach($inventory as $inv)
												{ 
												if($inv['identifier'] == $itemID){
												$selected = 'selected="selected"';
												}else{
												$selected = '';
												}
												$itemHrd=getInventoryDetails($inv['itemID'],"tblHardware");
												$itemSIM=getInventoryDetails($inv['simID'],"tblSim");	
													$hrd=$itemHrd['IMEI'];	
													if($itemHrd['IMEI']=="")
													{
													$hrd=$itemHrd['model'];	
													}
													if($inv['devicetype']=="301")
													{	    
													$item=$inv['vehicleNo'].' | '.$hrd.' | '.$itemSIM['IMEI'];
													}else{
													$item=$hrd;
													}
												?>  	
												<option value="<?php echo $inv['identifier'];?>" <?php echo $selected;?>><?php echo $item;?></option>
												<?php } 
												?>
												</option>
												</select>
												</div>
												</div>
												<?php  }
												 ?>	
												<div class="form-group">
                                                    <label class="col-md-3 control-label">Service Report No <span class="required" aria-required="true"> * </span></label>
                                                    <div class="col-md-4">
                                                        <input class="form-control" placeholder="Enter Service Report No" id="ServiceReportNo" type="text" name="ServiceReportNo" value="<?php echo $ServiceReportNo;?>">
                                                    </div>
                                                </div>	
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label">Service Report PDF</label>
                                                    <div class="col-md-4">
                                                        <input  placeholder="Select Service Report" type="file" multiple  accept="application/pdf" name="serviceReport[]">
                                                    </div>
                                                </div>	
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label">Site Visit</label>
                                                    <?php if(isset($noOfVisit) && $noOfVisit!=''){?>
                                                    <div class="col-sm-2">
                                                		<input type="radio" class="siteVisitYes" checked="checked" name="Site"> Yes
                                                		<input type="text" id="visitSite" class="form-control" placeholder="Enter no of visit" name="noOfVisit" value="<?php echo $noOfVisit;?>">
                                                	</div>
                                                	<div class="col-md-2">
                                                        <input type="radio" name="Site" class="siteVisitNo"> No
                                                     </div>
                                                	
                                                    <?php }else{?>
                                                	<div class="col-md-2">
                                                    		<input type="radio" name="Site" class="siteVisitYes"> Yes
                                                    		<input type="text" id="visitSite" class="hide form-control" placeholder="Enter no of visit" name="noOfVisit">
                                                    	</div>
                                                    	<div class="col-md-2">
                                                        <input type="radio" name="Site" checked="checked" class="siteVisitNo"> No
                                                     </div>
                                                    	
                                                    <?php }?>
                                                    
                                                </div>			
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label">Issue Identification</label>
                                                    <div class="col-md-9">
                                                        <textarea class="form-control" id="identification" placeholder="Enter issue identification" name="identificationInfo"><?php echo $identification;?></textarea>
                                                    </div>
                                                </div>										
												<div class="form-group">
                                                    <label class="col-md-3 control-label">Date Of Resolution</label>
                                                    <div class="col-md-4">
                                                        <input class="form-control" placeholder="Enter Date Of Resolution " type="text" name="DateOfResolution" id="dor" value="<?php echo $DateOfResolution;?>">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label">Issue Resolution</label>
                                                    <div class="col-md-9">
                                                        <textarea class="form-control" id="closingNote" placeholder="Enter issue resolution " name="closingNote"><?php echo $closingNote;?></textarea>
                                                    </div>
                                                </div>												
												</div>
												
												
                                            </div>
                                            <div class="form-actions fluid">
                                                <div class="row">
                                                    <div class="col-md-offset-3 col-md-9">
                                                        <input type="submit" class="btn green" name="submit" value="Submit">
														<!--<input type="submit" class="btn yellow" name="Assign_technician" value="Assign Technician">-->
														<?php if(!empty($_GET['empid'])){ ?>
														<a class="btn yellow" href="/support.php">Back</a>
														<?php } ?>
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
        <link href="assets/global/plugins/select2/css/select2.min.css" rel="stylesheet" />
        <link href="assets/global/plugins/select2/css/select2-bootstrap.min.css" rel="stylesheet" />
        <link href="assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css" rel="stylesheet" />

        <script src="assets/global/plugins/select2/js/select2.full.min.js"></script>
        <script src="assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
        <script src="assets/pages/add-support.js"></script>
        <script>
        $(document).ready(function(){
			$('#itemclassification').focus();
           $(".selectbox").select2();          
			$("#doreport").datepicker({
					format: "yyyy-mm-dd",
					autoclose: true,
					}).on('changeDate', function (selected) {
					var startDate = new Date(selected.date.valueOf());
					$('#dor').datepicker('setStartDate', startDate);
					}).on('clearDate', function (selected) {
					$('#dor').datepicker('setStartDate', null);
				});
			$("#dor").datepicker({
			   format: "yyyy-mm-dd",
			   autoclose: true
			});
			$("#customerID").change(function(){
				var address = $('option:selected', this).attr('address');
				document.getElementById("CAddress").value = address;
			});

			$(".siteVisitYes").click(function(){
				$("#visitSite").removeClass("hide");
				$("#noVisitSite").addClass("hide");
			})
			$(".siteVisitNo").click(function(){
				$("#visitSite").addClass("hide");
				$("#noVisitSite").removeClass("hide");
			})

			$("#SupportStatus").change(function(){
				var SStatus=$("#SupportStatus").val();
				var SoftwareStatus=$("#SoftwareStatus").val();
				if(SStatus=="431"){
					if(SoftwareStatus==3){
						$("#techFileds").addClass("hide");
						$("#softFileds").removeClass("hide");
						
					}else{
						$("#softFileds").addClass("hide");
						$("#techFileds").removeClass("hide");
						
					}
					$("#techFiledsolved").addClass("hide");
					$(".selectbox2").select2();
				}else if(SStatus=="433"){
					if(SoftwareStatus==3){
						$("#techFileds").addClass("hide");
						$("#softFileds").removeClass("hide");
						
					}else{
						$("#softFileds").addClass("hide");
						$("#techFileds").removeClass("hide");
						
					}
					$("#techFiledsolved").removeClass("hide");
					document.getElementById("ServiceReportNo").value = 'SR';
					$(".selectbox2").select2();					
				}else{
					$("#techFiledsolved").addClass("hide");
					if(SoftwareStatus==3){
						$("#softFileds").addClass("hide");
					}else{
						$("#techFileds").addClass("hide");
					}
				}
			});
		})
        $(document).ready(function(){
            $(".nav-item").each(function(){
                var href = $(this).find("a.nav-link").attr("href");
                console.log(href);
            })
        }) 	
		function showCustomerItems(str) { 
           var customer=$("#customerID").val();		
			if (str == "") {
				document.getElementById("Inventry").innerHTML = "";
				return;
			}else if(str == 3) { 
				$('#Inventry').hide();
				$('#OthersCategory').hide();
				$('#SoftwareCategory').show();
				
			}else if(str == 4) { 
				$('#Inventry').hide();
				$('#SoftwareCategory').hide();
				$('#OthersCategory').show();

			} else{ 
				$('#Inventry').show();
				$('#SoftwareCategory').hide();
				$('#OthersCategory').hide();
				if (window.XMLHttpRequest) {
					// code for IE7+, Firefox, Chrome, Opera, Safari
					xmlhttp = new XMLHttpRequest();
				} else {
					// code for IE6, IE5
					xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
				}
				xmlhttp.onreadystatechange = function() {
					if (this.readyState == 4 && this.status == 200) {
						var res = this.responseText.split("[");
						//document.getElementById("CAddress").value = res[1];
						document.getElementById("Inventry").innerHTML = this.responseText;
					}
				};
				xmlhttp.open("GET","ajax/fetchcustomerItems.php?q="+str+"&customerID="+customer,true);
				xmlhttp.send();
			}
		}
		function showinventory(str) { 
           var customer=$("#customerID").val();		
			if (str == "") {
				document.getElementById("Inventry").innerHTML = "";
				return;
			} else { 
				if (window.XMLHttpRequest) {
					// code for IE7+, Firefox, Chrome, Opera, Safari
					xmlhttp = new XMLHttpRequest();
				} else {
					// code for IE6, IE5
					xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
				}
				xmlhttp.onreadystatechange = function() {
					if (this.readyState == 4 && this.status == 200) {
						var res = this.responseText.split("[");
						//document.getElementById("CAddress").value = res[1];
						document.getElementById("Inventry").innerHTML = res[0];
					}
				};
				xmlhttp.open("GET","ajax/fetchinventory.php?q="+str+"&customerID="+customer,true);
				xmlhttp.send();
			}
		}
</script>
    </body>
</html>
