<?php
include("includes/check_session.php");
include("includes/header.php");
$activeHref = "support.php";
$supportID=$_GET['supportID'];
if(isset($_POST['submit'])){
    $contentArray = array();
	$contentArray2 = array();
    $id = $_POST["emp"];
    $contentArray["SupportID"] = $supportID;
    $contentArray["EmployeeID"] = $_POST['EmployeeID'];   
    $contentArray["Location"] = $_POST['address'];
    $contentArray["Status"] = $_POST['Status'];
		$tid=updateData("tblTechnicianScheduling", $contentArray, "identifier", $id);
		// update Status For 
		$contentArray2["Status"] = $_POST['Status'];  
		$contentArray2["technicianID"] = $_POST['EmployeeID'];
		if($_POST['Status']=="433"){
		$contentArray2["DateOfResolution"]=date("Y-m-d h:i");
		}
		//var_dump($contentArray2);
		$sid=updateData("tblSupport", $contentArray2, "identifier", $supportID);
		$_SESSION["success"] = "Assigned Details Updated Successfully.";
		header("Location:support.php");
		exit();
		//}  
}
if($supportID!="0"){
		$empData = getAssignTechDetails($supportID);		
		if(count($empData)){	
        $_SESSION["oldData"] = $empData;		
		$EmployeeID = $empData["EmployeeID"];
		$SupportID=$empData['SupportID'];
		$address = $empData["Location"];
		$Status = $empData["Status"];        
		$heading = "Update Assigned Details.";
		$emp=$empData["identifier"];
		}else{
		$emp=0;		
		$heading = " Assign To Technician.";
		}
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
                                            <input type="hidden" name="emp" value="<?php echo $emp;?>" id="empID">
                                            <div class="form-body">
                                                <?php include("partial-forms/techsection.php");?>                                                       
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label">Client Address</label>
                                                    <div class="col-md-9">
                                                        <textarea class="form-control" placeholder="Enter  Address" name="address"><?php echo $address;?></textarea>
                                                    </div>
                                                </div>                                               
                                                <?php include("partial-forms/support-status.php");?>												
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
        <link href="assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css" rel="stylesheet" />

        <script src="assets/global/plugins/select2/js/select2.full.min.js"></script>
        <script src="assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
        <script src="assets/pages/assign-to-technician.js"></script>
        <script>
        $(document).ready(function(){
			$('.selectbox2').focus();
            $(".selectbox").select2();
			$(".selectbox2").select2();
            $("#doreport").datepicker({
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
</script>
    </body>
</html>
