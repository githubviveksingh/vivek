<?php
include("includes/check_session.php");
include("includes/header.php");
$activeHref = "saleleads.php";
$empID = isset($_GET['empid'])?intval($_GET['empid']):"0";
	  	
if(isset($_POST['submit'])){   
	$contentArray = array();
	$id = $_POST["empID"];
	$contentArray["EmployeeID"] = $_POST['EMPID'];
	$contentArray["Mode"] = $_POST['Mode'];
	$contentArray["Name"] = $_POST['Name'];   
	$contentArray["Email"] = $_POST['Email'];
	$contentArray["Phone"] = $_POST['Phone'];
	$contentArray["Status"] = $_POST['Status']; 
	$contentArray["Location"] = $_POST['address']; 
	$contentArray['Classification']=$_POST['itemclassification'];
    if($id == "0"){
        $empID = addData("tblSaleLead", $contentArray);
        //ADD AUDIT ENTRY
        $auditID = generateAudit("SL_ADD", $empID, $contentArray, "EMAIL");
        $_SESSION["success"] = "Sale Lead Added Successfully.";
        header("Location:saleleads.php");
        exit();
    }else{
		updateData("tblSaleLead", $contentArray, "identifier", $id);
		//ADD AUDIT ENTRY
		$auditData = array("old_values"=>$_SESSION['oldData'], "new_values"=>$contentArray);
		$auditID = generateAudit("SL_UPD", $empID, $auditData, "EMAIL");
		unset($_SESSION["oldData"]);
		$_SESSION["success"] = "Sale Lead Updated Successfully.";
		header("Location:saleleads.php");
		exit();
    }
}

if($empID != "0"){
    $empData = getSaleLeadDetails($empID);
    $_SESSION["oldData"] = $empData;
    if(count($empData)){
        $EMPID = $empData["EmployeeID"];
        $Mode = $empData["Mode"];
        $Name = $empData["Name"];
        $Email = $empData["Email"];
        $Phone = $empData["Phone"];   
        $Status=$empData['Status'];		
		$address=$empData['Location'];	
		$itemclassification=$empData['Classification'];
        $heading = "Update Sale Lead Details";
    }else{
        $_SESSION['error'] = "Sale Lead does not exist.";
        header("Location: saleleads.php");
        exit();
    }
}
if($empID == "0" && !isset($_POST['submit'])){
    $itemID = "";
    $itemclassification = "";  
    $pan = "";
    $GST = "";
    $VAT = "";
    $ST = "";
    $doj = "";
    $dor = "";
    $EMPID = "";
    $billCycle = "";
    $heading = "Add Sale Lead";
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
                                        <form class="form-horizontal" id="addEmp" method="post" action="">
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
                                            <div class="form-body">
											 <?php include("partial-forms/emp-list.php");?> 
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label">Mode</label>
                                                    <div class="col-md-4">
                                                        <input class="form-control" placeholder="Enter Mode" type="text" name="Mode" value="<?php echo $Mode;?>">
                                                    </div>
                                                </div>
												<?php include("partial-forms/item-classifications.php");?>   
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label">Name</label>
                                                    <div class="col-md-4">
                                                        <input class="form-control" placeholder="Enter Name" type="text" name="Name" value="<?php echo $Name;?>">
                                                    </div>
                                                </div>												
												<div class="form-group">
                                                    <label class="col-md-3 control-label">Email</label>
                                                    <div class="col-md-4">
                                                        <input class="form-control" placeholder="Enter Email" type="text" name="Email" value="<?php echo $Email;?>">
                                                    </div>
                                                </div>
												<div class="form-group">
                                                    <label class="col-md-3 control-label">Phone</label>
                                                    <div class="col-md-4">
                                                        <input class="form-control" placeholder="Enter Phone" type="text" name="Phone" value="<?php echo $Phone;?>">
                                                    </div>
                                                </div>
												<div class="form-group">
                                                    <label class="col-md-3 control-label">Address</label>
                                                    <div class="col-md-9">
                                                        <textarea placeholder="Enter Address" class="form-control"  name="address"><?php echo $address;?></textarea>
                                                    </div>
                                                </div>
                                               <?php include("partial-forms/sales-status-list.php");?>
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
        <link href="assets/global/plugins/select2/css/select2.min.css" rel="stylesheet" />
        <link href="assets/global/plugins/select2/css/select2-bootstrap.min.css" rel="stylesheet" />
        <link href="assets/global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css" rel="stylesheet" />
        <script src="assets/global/plugins/select2/js/select2.full.min.js"></script>
        <script src="assets/global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js"></script>
        <script src="assets/pages/add-salelead.js"></script>
        <script>
        $(document).ready(function(){
            $(".selectbox").select2();
            $("#doreport").datetimepicker({
                format: "yyyy-mm-dd hh:ii",
                autoclose: true
            });
            $("#dor").datetimepicker({
                format: "yyyy-mm-dd hh:ii",
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
    </body>
</html>
