<?php
include("includes/check_session.php");
include("includes/header.php");
$activeHref = "customers.php";
$empID = isset($_GET['empid'])?intval($_GET['empid']):"0";
if(isset($_POST['submit'])){
    $contentArray = array();
    $id = $_POST["empID"];
    $contentArray["Name"] = $_POST['cusname'];
    $contentArray["email"] = $_POST['email'];   
    $contentArray["PAN"] = $_POST['pan'];
    $contentArray["accountID"] = $_POST['accountID'];
    $contentArray["GST"] = $_POST['GST'];
    //$contentArray["VAT"] = $_POST['VAT'];
    $contentArray["phone"] = $_POST['phone'];
    $contentArray["address"] = $_POST['address'];
    $contentArray["billCycle"] = $_POST['billCycle'];
	$contentArray["contactName"] = $_POST['contactName'];
    $contentArray["contactPhone"] = $_POST['contactPhone'];
    $contentArray["employeeID"] = $_POST['EMPID'];
    if(checkDuplicateEmailCustomer($contentArray["email"], $id) === true){
        $_SESSION["error"] = "Email Already Exist.";
        $name = $_POST["name"];
        $email = "";
        $pan = $_POST['pan'];
        $ST = $_POST['ST'];
        $GST = $_POST['GST'];
        $VAT = $_POST['VAT'];
        $phone = $_POST['phone'];
        $address = $_POST['address'];
        $billCycle = $_POST['billCycle'];
		$contactPhone = $_POST['contactPhone'];
		$contactName = $_POST['contactName'];
        $EMPID = $_POST['EMPID'];
    }else{
		if($id == "0"){
		$empID = addData("tblCustomer", $contentArray);
		//ADD AUDIT ENTRY
		$auditID = generateAudit("CUS_ADD", $empID, $contentArray, "");
		$_SESSION["success"] = "Customer Added Successfully.";
		if($_GET['page']!=""){
			echo $page=$_GET['page'];
		  header("Location:$page");
		}else{
		  header("Location:customers.php");
		}		
		exit();
		}else{
		updateData("tblCustomer", $contentArray, "identifier", $id);
		//ADD AUDIT ENTRY
		$auditData = array("old_values"=>$_SESSION['oldData'], "new_values"=>$contentArray);
		$auditID = generateAudit("CUS_UPD", $empID, $auditData, "");
		unset($_SESSION["oldData"]);

		$_SESSION["success"] = "Customer Updated Successfully.";
		header("Location:customers.php");
		exit();
		}
    }
}

if($empID != "0"){
    $empData = getCustomerDetails($empID);
    $_SESSION["oldData"] = $empData;
    if(count($empData)){
        $name = $empData["Name"];
        $email = $empData["email"];
        $phone = $empData["phone"];
        $pan = $empData["PAN"];
        $GST = $empData["GST"];
        $accountID = $empData["accountID"];
        $ST = $empData["ST"];
		$contactName = $empData["contactName"];
		$contactPhone = $empData["contactPhone"];
        $EMPID = $empData["employeeID"];
        $billCycle = $empData["billCycle"];
        $address = $empData["address"];
        $heading = "Update Customer Details";
    }else{
        $_SESSION['error'] = "Customer does not exist.";
        header("Location: customers.php");
        exit();
    }

}
if($empID == "0" && !isset($_POST['submit'])){
    $name = "";
    $email = "";  
    $pan = "";
    $GST = "";
    $VAT = "";
    $ST = "";
    $doj = "";
    $dor = "";
    $EMPID = "";
    $billCycle = "";
    $heading = "Add Customer";
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
                                                    <label class="col-md-3 control-label">Account ID<span class="required" aria-required="true"> * </span></label>
                                                    <div class="col-md-4">
                                                        <input class="form-control" placeholder="Enter Account ID" type="text" name="accountID" value="<?php echo $accountID;?>">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label">Name<span class="required" aria-required="true"> * </span></label>
                                                    <div class="col-md-4">
                                                        <input class="form-control" placeholder="Enter Customer Name" type="text" name="cusname" value="<?php echo $name;?>">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label">Email<span class="required" aria-required="true"> * </span></label>
                                                    <div class="col-md-4">
                                                        <input class="form-control" placeholder="Enter Customer Email" type="text" name="email" value="<?php echo $email;?>">
                                                    </div>
                                                </div> 
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label">Phone<span class="required" aria-required="true"> * </span></label>
                                                    <div class="col-md-4">
                                                        <input class="form-control" placeholder="Enter Customer Phone" type="text" name="phone" value="<?php echo $phone;?>">
                                                    </div>
                                                </div>                                               
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label">PAN<span class="required" aria-required="true"> * </span></label>
                                                    <div class="col-md-4">
                                                        <input class="form-control lowertoupper" placeholder="Enter PAN" id="panCard" type="text" name="pan" value="<?php echo $pan;?>">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label">GST<span class="required" aria-required="true"> * </span></label>
                                                    <div class="col-md-4">
                                                        <input class="form-control lowertoupper" placeholder="Enter GST" id="gstNo" type="text" name="GST" value="<?php echo $GST;?>">
                                                    </div>
                                                </div>
												<div class="form-group">
                                                    <label class="col-md-3 control-label">Contact Person Name</label>
                                                    <div class="col-md-4">
                                                        <input class="form-control" placeholder="Enter Contact Person Name" name="contactName" type="text" value="<?php echo $contactName;?>">
                                                    </div>
                                                </div>
												<div class="form-group">
                                                    <label class="col-md-3 control-label">Contact Person Phone</label>
                                                    <div class="col-md-4">
                                                        <input class="form-control" placeholder="Enter Contact Person Phone" name="contactPhone" type="text" value="<?php echo $contactPhone;?>">
                                                    </div>
                                                </div>
                                               <!--<div class="form-group">
                                                    <label class="col-md-3 control-label">VAT</label>
                                                    <div class="col-md-4">
                                                        <input class="form-control" placeholder="Enter VAT" type="text" name="VAT" value="<?php echo $VAT;?>">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label">ST</label>
                                                    <div class="col-md-4">
                                                        <input class="form-control" placeholder="Enter ST" type="text" name="ST" value="<?php echo $ST;?>">
                                                    </div>
                                                </div>   -->                                            
                                                <!-- Bill cycle -->
                                                     <?php include("partial-forms/supp-billcycle.php");?>
                                                      <?php include("partial-forms/account-managers.php");?>
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label">Address<span class="required" aria-required="true"> * </span></label>
                                                    <div class="col-md-9">
                                                      <textarea class="form-control" placeholder="Enter Customer Address" name="address"><?php echo $address;?></textarea>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="form-actions fluid">
                                                <div class="row">
                                                    <div class="col-md-offset-3 col-md-9">
                                                        <input type="submit" class="btn green" name="submit" value="Submit">
														<?php if(!empty($_GET['empid'])){ ?>
														<a class="btn yellow" href="/customers.php">Back</a>
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
        <script src="assets/pages/add-customer.js"></script>
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
