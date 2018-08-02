<?php
include("includes/check_session.php");
include("includes/header.php");
$activeHref = "Companys.php";
$empID = isset($_GET['empid'])?intval($_GET['empid']):"0";
if(isset($_POST['submit'])){
    $contentArray = array();
    $id = $_POST["empID"];
    $contentArray["com_name"] = $_POST['com_name'];
    $contentArray["com_email"] = $_POST['com_email'];   
    $contentArray["com_phone"] = $_POST['com_phone'];
    $contentArray["com_pan"] = $_POST['com_pan'];
    $contentArray["com_gst"] = $_POST['com_gst'];
    $contentArray["com_cin"] = $_POST['com_cin'];
    $contentArray["com_address"] = $_POST['com_address'];  
	$contentArray["com_service_taxno"] = $_POST['com_service_taxno'];  
	$contentArray["com_vat"] = $_POST['com_vat']; 
	$contentArray["com_bankname"] = $_POST['com_bankname']; 
	$contentArray["com_acno"] = $_POST['com_acno']; 
	$contentArray["com_bank_ifsc"] = $_POST['com_bank_ifsc']; 
		if($id == "0"){		
		$empID = addData("tblCompany", $contentArray);
		$_SESSION["success"] = "Company Added Successfully.";		
		header("Location:company.php");		
		}else{
		updateData("tblCompany", $contentArray, "identifier", $id);		
		$_SESSION["success"] = "Company Updated Successfully.";
		header("Location:company.php");
		exit();
		}
}
if($empID != "0"){
    $empData = getCompanyDetails($empID);
    $_SESSION["oldData"] = $empData;
    if(count($empData)){
        $com_name = $empData["com_name"];
        $com_email = $empData["com_email"];
        $com_phone = $empData["com_phone"];
        $com_pan = $empData["com_pan"];
        $com_gst = $empData["com_gst"];
        $com_address = $empData["com_address"];
		
		$com_cin = $empData["com_cin"];
        $com_service_taxno = $empData["com_service_taxno"];
        $com_vat = $empData["com_vat"];
        $com_acno = $empData["com_acno"];
        $com_bankname = $empData["com_bankname"];
        $com_bank_ifsc = $empData["com_bank_ifsc"];
		$com_name = $empData["com_name"];
        $heading = "Update Company Details";
    }else{
        $_SESSION['error'] = "Company does not exist.";
        header("Location: Companys.php");
        exit();
    }
}
if($empID == "0" && !isset($_POST['submit'])){   
    $heading = "Add Company Details";
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
                                            if(isset($_SESSION["error"])){?>
                                                <div class="alert alert-danger">
                                                    <button class="close" data-close="alert"></button>
                                                    <span><?php echo $_SESSION["error"];?></span>
                                                </div>
                                            <?php unset($_SESSION["error"]);}
                                            ?>
                                            <input type="hidden" name="empID" value="<?php echo $empID;?>" id="empID">
                                            <div class="form-body">
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label">Name<span class="required" aria-required="true"> * </span></label>
                                                    <div class="col-md-4">
                                                        <input class="form-control" placeholder="Enter Company Name" type="text" name="com_name" value="<?php echo $com_name;?>">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label">Email<span class="required" aria-required="true"> * </span></label>
                                                    <div class="col-md-4">
                                                        <input class="form-control" placeholder="Enter Company Email" type="text" name="com_email" value="<?php echo $com_email;?>">
                                                    </div>
                                                </div> 
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label">Phone<span class="required" aria-required="true"> * </span></label>
                                                    <div class="col-md-4">
                                                        <input class="form-control" placeholder="Enter Company Phone" type="text" name="com_phone" value="<?php echo $com_phone;?>">
                                                    </div>
                                                </div>                                               
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label">PAN<span class="required" aria-required="true"> * </span></label>
                                                    <div class="col-md-4">
                                                        <input class="form-control lowertoupper" placeholder="Enter PAN" id="com_pan" type="text" name="com_pan" value="<?php echo $com_pan;?>">
                                                    </div>
                                                </div>
												<div class="form-group">
                                                    <label class="col-md-3 control-label">Company's Service Tax No.</label>
                                                    <div class="col-md-4">
                                                        <input class="form-control lowertoupper" placeholder="Enter Company's Service Tax No." id="" type="text" name="com_service_taxno" value="<?php echo $com_service_taxno;?>">
                                                    </div>
                                                </div>
												<div class="form-group">
                                                    <label class="col-md-3 control-label">Company's VAT TIN:.</label>
                                                    <div class="col-md-4">
                                                        <input class="form-control lowertoupper" placeholder="Enter Company's VAT TIN" id="" type="text" name="com_vat" value="<?php echo $com_vat;?>">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label">GSTIN/UIN<span class="required" aria-required="true"> * </span></label>
                                                    <div class="col-md-4">
                                                        <input class="form-control lowertoupper" placeholder="Enter GST" id="gstNo" type="text" name="com_gst" value="<?php echo $com_gst;?>">
                                                    </div>
                                                </div>
												
												<div class="form-group">
                                                    <label class="col-md-3 control-label">CIN<span class="required" aria-required="true"> * </span></label>
                                                    <div class="col-md-4">
                                                        <input class="form-control lowertoupper" placeholder="Enter CIN" id="panCard" type="text" name="com_cin" value="<?php echo $com_cin;?>">
                                                    </div>
                                                </div>
												<div class="form-group">
                                                    <label class="col-md-3 control-label">Bank name<span class="required" aria-required="true"> * </span></label>
                                                    <div class="col-md-4">
                                                        <input class="form-control lowertoupper" placeholder="Enter Bank name" id="panCard" type="text" name="com_bankname" value="<?php echo $com_bankname;?>">
                                                    </div>
                                                </div>
												<div class="form-group">
                                                    <label class="col-md-3 control-label">Bank Acno<span class="required" aria-required="true"> * </span></label>
                                                    <div class="col-md-4">
                                                        <input class="form-control lowertoupper" placeholder="Enter com_acno" id="panCard" type="text" name="com_acno" value="<?php echo $com_acno;?>">
                                                    </div>
                                                </div>
												<div class="form-group">
                                                    <label class="col-md-3 control-label">Bank ifsc<span class="required" aria-required="true"> * </span></label>
                                                    <div class="col-md-4">
                                                        <input class="form-control lowertoupper" placeholder="Enter bank ifsc" id="panCard" type="text" name="com_bank_ifsc" value="<?php echo $com_bank_ifsc;?>">
                                                    </div>
                                                </div>
												
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label">Address<span class="required" aria-required="true"> * </span></label>
                                                    <div class="col-md-9">
                                                      <textarea class="form-control" placeholder="Enter Company Address" name="com_address"><?php echo $com_address;?></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-actions fluid">
                                                <div class="row">
                                                    <div class="col-md-offset-3 col-md-9">
                                                        <input type="submit" class="btn green" name="submit" value="Submit">
														<?php if(!empty($_GET['empid'])){ ?>
														<a class="btn yellow" href="/company.php">Back</a>
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
        <script src="assets/pages/add-Company.js"></script>
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
    </body>
</html>
