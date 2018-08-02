<?php
include("includes/check_session.php");
include("includes/header.php");

$activeHref = "employee.php";
$empID = isset($_GET['empid'])?intval($_GET['empid']):"0";

if(isset($_POST['submit'])){
    $contentArray = array();
    $id = $_POST["empID"];
    $contentArray["name"] = $_POST['empname'];
    $contentArray["email"] = $_POST['email'];
    if(!empty($_POST['password'])){
        $contentArray["password"] = md5($_POST['password']);
    }
    $contentArray["pan"] = $_POST['pan'];
    $contentArray["aadhar"] = $_POST['aadhar'];
    $contentArray["type"] = $_POST['emptype'];
    $contentArray["locationID"] = $_POST['location'];
    $contentArray["DoJ"] = $_POST['doj'];
    $contentArray["DoR"] = $_POST['dor'];
    $contentArray["status"] = $_POST['empstatus'];
    $contentArray["address"] = $_POST['address'];
//var_dump($contentArray);
//die();
    if(checkDuplicateEmail($contentArray["email"], $id) === true){
        $_SESSION["error"] = "Email Already Exist.";
        $name = $_POST["empname"];
        $email = "";
        $password =  "";
        $pan = $_POST['pan'];
        $aadhar = $_POST['aadhar'];
        $empType = $_POST['emptype'];
        $location = $_POST['location'];
        $doj = $_POST['doj'];
        $dor = $_POST['dor'];
        $empStatus = $_POST['empstatus'];
        $address = $_POST['address'];

    }else{
        if($id == "0"){
            $empID = addData("tblEmployee", $contentArray);

            //ADD AUDIT ENTRY
            $auditID = generateAudit("EMP_ADD", $empID, $contentArray, "");

            $_SESSION["success"] = "Employee Added Successfully.";
            header("Location: employee.php");
            exit();
        }else{
            updateData("tblEmployee", $contentArray, "identifier", $id);

            //ADD AUDIT ENTRY
            $auditData = array("old_values"=>$_SESSION['oldData'], "new_values"=>$contentArray);
            $auditID = generateAudit("EMP_UPD", $empID, $auditData, "");
            unset($_SESSION["oldData"]);

            $_SESSION["success"] = "Employee Updated Successfully.";
            header("Location: employee.php");
            exit();
        }
    }
}

if($empID != "0"){
    $empData = getEmpDetails($empID);
    $_SESSION["oldData"] = $empData;
    if(count($empData)){
        $name = $empData["name"];
        $email = $empData["email"];
        $password =  "";
        $pan = $empData["pan"];
        $aadhar = $empData["aadhar"];
        $empType = $empData["type"];
        $location = $empData["locationID"];
        $doj = $empData["DoJ"];		
        $dor = $empData["DoR"];		
		if($dor<=0){
			$dor="";
		}
        $empStatus = $empData["status"];
        $address = $empData["address"];
        $heading = "Update Employee Details";
    }else{
        $_SESSION['error'] = "Employee does not exist.";
        header("Location: employee.php");
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
    $heading = "Add Employee";
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
                                                        <input class="form-control" placeholder="Enter Employee Name" type="text" name="empname" value="<?php echo $name;?>">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label">Email<span class="required" aria-required="true"> * </span></label>
                                                    <div class="col-md-4">
                                                        <input class="form-control" placeholder="Enter Employee Email" type="text" name="email" value="<?php echo $email;?>">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label">Password</label>
                                                    <div class="col-md-4">
                                                        <input class="form-control" placeholder="Enter Password" type="password" name="password">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label">PAN<span class="required" aria-required="true"> * </span></label>
                                                    <div class="col-md-4">
                                                        <input class="form-control lowertoupper" placeholder="Enter PAN" id="panCard" type="text" name="pan" value="<?php echo $pan;?>">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label">Aadhar No<span class="required" aria-required="true"> * </span></label>
                                                    <div class="col-md-4">
                                                        <input class="form-control" placeholder="Enter Aadhar"  id="adharCard" type="text" name="aadhar" value="<?php echo $aadhar;?>">
                                                    </div>
                                                </div>

                                                <?php include("partial-forms/emp-type.php");?>
                                                <?php include("partial-forms/location.php");?>

                                                <div class="form-group">
                                                    <label class="col-md-3 control-label">Date of Joining<span class="required" aria-required="true"> * </span></label>
                                                    <div class="col-md-4">
                                                        <input class="form-control" placeholder="Date of Joining" type="text" name="doj" id="doj" value="<?php echo $doj;?>">
                                                    </div>
                                                </div>  
                                                <?php include("partial-forms/emp-status.php");?>
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label">Date of Resign</label>
                                                    <div class="col-md-4">
                                                        <input class="form-control" placeholder="Date of Resign" type="text" name="dor" id="dor" value="<?php echo $dor;?>">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label">Address<span class="required" aria-required="true"> * </span></label>
                                                    <div class="col-md-9">
                                                        <textarea class="form-control" placeholder="Enter Employee Address" name="address"><?php echo $address;?></textarea>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="form-actions fluid">
                                                <div class="row">
                                                    <div class="col-md-offset-3 col-md-9">
                                                        <input type="submit" class="btn green" name="submit" value="Submit">
														<?php if(!empty($_GET['empid'])){ ?>
														<a class="btn yellow" href="/employee.php">Back</a>
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
        <script src="assets/pages/add-employee.js"></script>
        <script>
        $(document).ready(function(){
            $(".selectbox").select2();        
			$("#doj").datepicker({
					format: 'yyyy-mm-dd',
					autoclose: true,
					}).on('changeDate', function (selected) {
					var startDate = new Date(selected.date.valueOf());
					$('#dor').datepicker('setStartDate', startDate);
					}).on('clearDate', function (selected) {
					$('#dor').datepicker('setStartDate', null);
				});
			$("#dor").datepicker({
			   format: 'yyyy-mm-dd',
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
