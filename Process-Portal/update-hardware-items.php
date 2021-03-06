<?php
include("includes/check_session.php");
include("includes/header.php");
include("PHPMailer/class.phpmailer.php");
$activeHref = "hardware-inventory.php";

$table="tblHardware";
$smid = isset($_GET['smid'])?intval($_GET['smid']):"0";
if(isset($_POST['submit'])){
    $contentArray = array();
    $id = $_POST["smid"];
    $contentArray["IMEI"] = $_POST['IMEI'];
    $contentArray["model"] = $_POST['model'];  
    $contentArray["productCat"] = $_POST['productCat'];
	//$contentArray["locationID"] = $_POST['location'];
	$contentArray["statusCode"] = $_POST['statusCode'];
	if(!empty($_POST['CustomerID']))
	{
	$contentArray["customerID"] = $_POST['CustomerID'];
	}
	if(!empty($_POST['EMPID']))
	{
    	$contentArray["empID"] = $_POST['EMPID'];

        $empID          = $_POST['EMPID'];
        $trigger        = $_SESSION["user"]["name"];
        $empDetails     = getEmpDetails($_POST['EMPID']);
        $itemName       = $_POST['IMEI'];
        $model          = $_POST['model'];
        $empname        = ucwords($empDetails['name']);
        $empemail       = $empDetails['email'];

        $category = $_POST['productCat'];
        $categoryName='';
        foreach($PRODUCTCAT as $key=>$value){
                    if($key == $category){
                        $categoryName=$value[1];
                    }
                }

        /* Get Hardware Information */
        $hrdInfo  =  getHardwareInfo($id);

        if($hrdInfo[0]['EMPID'] != $contentArray["empID"]){

            $contentArray['alote_status'] = 0;

            $currentEmpDetails     = getEmpDetails($hrdInfo[0]['EMPID']);
            $currentEmpemail       = $currentEmpDetails['email'];

            $auditData = array("old_values"=>$_SESSION['oldData'], "new_values"=>$contentArray);
            $auditID   = generateAudit("HRD_ANOTH", $smid, $auditData, "");

            $mailbox        = file_get_contents("partial-forms/alotted-hardware-to-other-employee.html");
            $mailbox        = str_replace("[NEWEMPLOYEE]" , $empname , $mailbox);
            $mailbox        = str_replace("[IMEINAME]" , $itemName , $mailbox);
            $mailbox        = str_replace("[MODEL]" , $model , $mailbox);
            $mailbox        = str_replace("[CATEGORYNAME]" , $categoryName , $mailbox);
            $mailbox        = str_replace("[TRIGGERBY]" , $trigger , $mailbox);
            
            $subject        = "Process Portal- allotted hardware assign to another employee";
            $mailSender     = mailSend($currentEmpemail,$subject,$mailbox);

        }

	}
    
    if($smid != "0"){
	$updateHrd = updateData($table, $contentArray, "identifier", $id);
    $auditData = array("old_values"=>$_SESSION['oldData'], "new_values"=>$contentArray);
    $auditID   = generateAudit("HRD_UPD", $smid, $auditData, "");

    if(!empty($_POST['EMPID'])){

        if($updateHrd){
            /* Send Mail */
            if($hrdInfo[0]['EMPID'] != $contentArray["empID"]){
                $mailbox        = file_get_contents("partial-forms/alotted-hardware-item-employee.html");
                $mailbox        = str_replace("[NEWEMPLOYEE]" , $empname , $mailbox);
                $mailbox        = str_replace("[IMEINAME]" , $itemName , $mailbox);
                $mailbox        = str_replace("[MODEL]" , $model , $mailbox);
                $mailbox        = str_replace("[CATEGORYNAME]" , $categoryName , $mailbox);
                $mailbox        = str_replace("[TRIGGERBY]" , $trigger , $mailbox);
                $protocol       = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
                $url            = $protocol . $_SERVER['HTTP_HOST'];
                $target         = $url.'/view-employee-details.php?empid='.$empID;
                $mailbox        = str_replace("[TARGETLINK]" , $target , $mailbox);
                $TITLE          = 'allotted a new hardware items';
                $mailbox        = str_replace("[TITLE]" , $TITLE , $mailbox);
                
                $subject        = "Process Portal- allotted hardware item";
                $mailSender     = mailSend($empemail,$subject,$mailbox);

                $_SESSION["success"] = "Hardware allotted Successfully.";
            }
        }else{
            $_SESSION["success"] = "Hardware could not be allotted. Please try again later.";
            header("Location: update-hardware-items.php");
            exit();
        }
    }

	$_SESSION["success"] = "Item Details Updated Successfully.";
	header("Location: hardware-inventory.php");
	exit();
    }else{
	  $_SESSION['error'] = "Item does not exist.";
      header("Location: hardware-inventory.php");
      exit();
}
}
if($smid != "0"){
    $empData = getInventoryDetails($smid,$table);
	$_SESSION["oldData"] = $empData;
    if(count($empData)){
        $IMEI       = $empData["IMEI"];
        $model      = $empData["model"];
		$productCat = $empData['productCat'];
		$location   = $empData['locationID'];
        $statusCode = $empData["statusCode"];
		$CustomerID = $empData["customerID"];
	    $EMPID      = $empData["EMPID"];
        $heading    = "Update hardware Inventory Item Details";
    }else{
       $_SESSION['error'] = "Item does not exist.";
       header("Location: hardware-inventory.php");
        exit();
    }

}else{
	  $_SESSION['error'] = "Item does not exist.";
      header("Location: hardware-inventory.php");
      exit();
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
                                            <i class="fa fa-gift"></i><?php echo $EMPID;?>
                                        </div>
                                    </div>
                                    <div class="portlet-body form">
                                        <form class="form-horizontal" id="addSupp" method="post" action="">
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
                                            <input type="hidden" name="smid" value="<?php echo $smid;?>" id="smid">
                                            <div class="form-body">
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label">IMEI</label>
                                                    <div class="col-md-4">
                                                        <input class="form-control" placeholder="Enter IMEI" type="text" name="IMEI" value="<?php echo $IMEI;?>">
                                                    </div>
                                                </div>
												<div class="form-group">
                                                    <label class="col-md-3 control-label">Model</label>
                                                    <div class="col-md-4">
                                                        <input class="form-control" placeholder="Enter model" type="text" name="model" value="<?php echo $model;?>">
                                                    </div>
                                                </div>
											<?php include"partial-forms/product-category-list.php";?>   									
											<?php include"partial-forms/inventory-statauscodes-list.php";?>  
                                             <div id="InExtraFields"><?php if($statusCode=='409' Or $statusCode=='402' Or $statusCode=='403'){
													include"partial-forms/customers-list.php";
												}elseif($statusCode=='407'){
													include"partial-forms/emp-list.php";
												}else{}?></div>  											
                                            </div>
                                            <div class="form-actions fluid">
                                                <div class="row">
                                                    <div class="col-md-offset-3 col-md-9">
                                                        <input type="submit" class="btn green" name="submit" value="Submit">
														<a class="btn yellow" href="/hardware-inventory.php">Back</a>
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
        <script src="assets/pages/update-hardware-inventory.js"></script>
        <script>
        $(document).ready(function(){
            $(".selectbox").select2();
            $("#doj").datepicker({
                format: "yyyy-mm-dd",
                autoclose: true
            });
            $("#dor").datepicker({
                format: "yyyy-mm-dd",
                autoclose: true
            });
        })
        $(document).ready(function(){
            $(".nav-item").each(function(){
                var href = $(this).find("a.nav-link").attr("href");
                console.log(href);
            })
        })
        </script>
		<script>
		function showinventoryfileds(str) {
			if(str=="409")
			{
				$("#HardwareDiv").removeClass('hide');
			}
			if (str == "") {
			 document.getElementById("InExtraFields").innerHTML = "";
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
				 document.getElementById("InExtraFields").innerHTML = this.responseText;
			 }
			};
			 xmlhttp.open("GET","includes/getExtraFiledsInventory.php?q="+str+"&q2=HARDWARE",true);
			 xmlhttp.send();
			}
			$("hrdDevice").select2();
		}
		</script>
    </body>
</html>
