<?php
include("includes/check_session.php");
include("includes/header.php");

$activeHref = "suppliers.php";
$empID = isset($_GET['empid'])?intval($_GET['empid']):"0";

if(isset($_POST['submit'])){	
    $contentArray = array();
    $id = $_POST["empID"];
	$supplier_type=$_POST['supplier_type'];
	if($supplier_type!=""){
	$supplier_type=implode(',',$supplier_type);
	$contentArray["supplier_type"] = $supplier_type;
	}
    $contentArray["name"] = $_POST['empname'];
    $contentArray["email"] = $_POST['email'];   
    $contentArray["phone"] = $_POST['phone'];
    $contentArray["pan"] = $_POST['pan'];
    //$contentArray["vat"] = $_POST['vat'];
    $contentArray["gst"] = $_POST['gst'];
   // $contentArray["st"] = $_POST['st'];
	$contentArray["contact_phone"] = $_POST['contact_phone'];
	$contentArray["contact_name"] = $_POST['contact_name'];
    $contentArray["billCycle"] = $_POST['billCycle'];
    $contentArray["address"] = $_POST['address'];                 
		if($id == "0"){
           $empID=addData("tblSupplier", $contentArray);
			//ADD AUDIT ENTRY
            $auditID = generateAudit("SUP_ADD", $empID, $contentArray, "");
            $_SESSION["success"] = "Supplier Added Successfully.";
            header("Location:suppliers.php");
            exit();
        }else{
            updateData("tblSupplier", $contentArray, "identifier", $id);
			//ADD AUDIT ENTRY
            $auditData = array("old_values"=>$_SESSION['oldData'], "new_values"=>$contentArray);
            $auditID = generateAudit("SUP_UPD", $empID, $auditData, "");
            unset($_SESSION["oldData"]);
            $_SESSION["success"] = "Supplier Updated Successfully.";
            header("Location: suppliers.php");
            exit();
        }
}

if($empID != "0"){
    $empData = getSuppDetails($empID);
	$_SESSION["oldData"] = $empData;
    if(count($empData)){
        $name = $empData["name"];
        $email = $empData["email"];
        $pan = $empData["PAN"];
        $phone = $empData["phone"];
        $gst = $empData["GST"];
        $vat = $empData["VAT"];
        $st = $empData["ST"];
		$contact_name = $empData["contact_name"];
		$contact_phone = $empData["contact_phone"];		 
        $billCycle = $empData["billCycle"];
		$supplier_type = explode(',',$empData["supplier_type"]);
        $address = $empData["address"];
        $heading = "Update Supplier Details";
    }else{
        $_SESSION['error'] = "Supplier does not exist.";
        header("Location: suppliers.php");
        exit();
    }

}
if($empID == "0" && !isset($_POST['submit'])){
    $name = "";
    $email = "";
    $password =  "";
    $pan = "";
    $aadhar = "";
    $empType = "";
    $location = "";
    $doj = "";
    $dor = "";
    $empstatus = "";
    $address = "";
    $heading = "Add Supplier";
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
                                            <input type="hidden" name="empID" value="<?php echo $empID;?>" id="empID">
                                            <div class="form-body">
											 <?php include("partial-forms/supplier-type.php");?>
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label">Name<span class="required" aria-required="true"> * </span></label>
                                                    <div class="col-md-4">
                                                        <input class="form-control" placeholder="Enter  Name" type="text" name="empname" value="<?php echo $name;?>">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label">Email<span class="required" aria-required="true"> * </span></label>
                                                    <div class="col-md-4">
                                                        <input class="form-control" placeholder="Enter  Email" type="text" id="emails" name="email" value="<?php echo $email;?>">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label">Phone<span class="required" aria-required="true"> * </span></label>
                                                    <div class="col-md-4">
                                                        <input class="form-control" placeholder="Enter  Contact No/Phone" type="text" name="phone" value="<?php echo $phone;?>">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label">GST<span class="required" aria-required="true"> * </span></label>
                                                    <div class="col-md-4">
                                                        <input class="form-control lowertoupper" placeholder="Enter GST" type="text" name="gst" id="gstNo" value="<?php echo $gst;?>">
                                                    </div>
                                                </div>
                                                <!-- <div class="form-group">
                                                    <label class="col-md-3 control-label">VAT</label>
                                                    <div class="col-md-4">
                                                        <input class="form-control" placeholder="Enter VAT" type="text" name="vat" value="<?php echo $vat;?>">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label">ST</label>
                                                    <div class="col-md-4">
                                                        <input class="form-control" placeholder="Enter ST" type="text" name="st" value="<?php echo $st;?>">
                                                    </div>
                                                </div>-->
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label">PAN</label>
                                                    <div class="col-md-4">
                                                        <input class="form-control lowertoupper" placeholder="Enter PAN" type="text" id="panCard" name="pan" value="<?php echo $pan;?>">
                                                    </div>
                                                </div>
												<div class="form-group">
                                                    <label class="col-md-3 control-label">Contact Person Name</label>
                                                    <div class="col-md-4">
                                                        <input class="form-control" placeholder="Enter Person Name" type="text"  name="contact_name" value="<?php echo $contact_name;?>">
                                                    </div>
                                                </div>
												<div class="form-group">
                                                    <label class="col-md-3 control-label">Person Phone</label>
                                                    <div class="col-md-4">
                                                        <input class="form-control" placeholder="Enter Person Phone" type="text"  name="contact_phone" value="<?php echo $contact_phone;?>">
                                                    </div>
                                                </div>
                                                   <?php include("partial-forms/supp-billcycle.php");?>                                              
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label">Address<span class="required" aria-required="true"> * </span></label>
                                                    <div class="col-md-9">
                                                        <textarea class="form-control" placeholder="Enter Supplier Address" name="address"><?php echo $address;?></textarea>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="form-actions fluid">
                                                <div class="row">
                                                    <div class="col-md-offset-3 col-md-9">
                                                        <input type="submit" class="btn green" name="submit" value="Submit">
														<?php if(!empty($_GET['empid'])){ ?>
														<a class="btn yellow" href="/suppliers.php">Back</a>
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
        <script src="assets/pages/add-supplier.js"></script>
        <script>
        $(document).ready(function(){
            $(".selectbox").select2();
			$("#multiple").select2();
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
    </body>
</html>
