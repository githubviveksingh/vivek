<?php
include("includes/check_session.php");
include("includes/header.php");

$activeHref = "po-list.php";
$empID = isset($_GET['empid'])?intval($_GET['empid']):"0";

if(isset($_POST['submit'])){
    $contentArray = array();
    $id = $_POST["empID"];
    $contentArray["POID"] = $_POST['poID'];
	if(!empty($_FILES['uploadLink']['name'])){
		$contentArray["uploadLink"] = $_FILES['uploadLink']['name']; 
	}    
     if(checkDuplicatePOREF($contentArray["POID"], $id) === true){
        $_SESSION["error"] = "PO Reference Number Already Exist.";
    }else{
            if($id == "0"){
			move_uploaded_file($_FILES['uploadLink']['tmp_name'],'sample/'.$_FILES['uploadLink']['name']);
            $empID=addData("tblPO", $contentArray);
			//ADD AUDIT ENTRY
           // $auditID = generateAudit("SUP_ADD", $empID, $contentArray, "EMAIL");
            $_SESSION["success"] = "PO Details Added Successfully.";
            header("Location:po-list.php");
            exit();
        }else{
            updateData("tblPO", $contentArray, "identifier", $id);
			//ADD AUDIT ENTRY
           // $auditData = array("old_values"=>$_SESSION['oldData'], "new_values"=>$contentArray);
            //$auditID = generateAudit("SUP_UPD", $empID, $auditData, "EMAIL");
            //unset($_SESSION["oldData"]);
            $_SESSION["success"] = "PO Details Updated Successfully.";
            header("Location:po-list.php");
            exit();
        }
    }

}

if($empID != "0"){
    $empData = getPODetails($empID);
	$_SESSION["oldData"] = $empData;
    if(count($empData)){
        $poID = $empData["POID"];
		$uploadLink=$empData['uploadLink'];
        $heading = "Update PO Details";
    }else{
        $_SESSION['error'] = "PO does not exist.";
        header("Location: po-list.php");
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
    $heading = "Add PO";
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
                                        <form class="form-horizontal" id="addPOREF" method="post" action="" enctype="multipart/form-data">
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
                                                    <label class="col-md-3 control-label">PO ID<span class="required" aria-required="true"> * </span></label>
                                                    <div class="col-md-4">
                                                        <input class="form-control" placeholder="Enter  Name" type="text" name="poID" value="<?php echo $poID;?>">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label">PO PDF<span class="required" aria-required="true"> * </span></label>
                                                    <div class="col-md-4">
                                                        <input id="exampleInputFile" type="file" name="uploadLink" accept="application/pdf" >
														<a href="/sample/<?php echo $uploadLink; ?>" target="_blank"><?php echo $uploadLink;?></a>
														<p class="help-block">Upload Only PDF. </p>
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
        <link href="assets/global/plugins/select2/css/select2.min.css" rel="stylesheet" />
        <link href="assets/global/plugins/select2/css/select2-bootstrap.min.css" rel="stylesheet" />
        <link href="assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css" rel="stylesheet" />

        <script src="assets/global/plugins/select2/js/select2.full.min.js"></script>
        <script src="assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
        <script src="assets/pages/add-poref.js"></script>
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
