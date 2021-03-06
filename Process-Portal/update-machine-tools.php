<?php
include("includes/check_session.php");
include("includes/header.php");
include("PHPMailer/class.phpmailer.php");
$activeHref = "machine-tools.php";
$table="tblMachine";
$smid = isset($_GET['smid'])?intval($_GET['smid']):"0";
if(isset($_POST['submit'])){
    $contentArray = array();
    $id = $_POST["smid"];
    $contentArray["name"] = $_POST['name'];
    $contentArray["description"] = $_POST['description'];  
    $contentArray["classification"] = $_POST['classification'];
	$contentArray["employeeID"] = $_POST['EMPID'];
	//$contentArray["locationID"] = $_POST['location'];
    // get provide name------------------------
    $classification = $_POST['classification'];
    $classificationName='';

    foreach($MACHINEANDTOOLCLASS as $key=>$value){
        if($key == $classification){
            $classificationName = $value[1];
        } 
    }
	
	$contentArray["statusCode"] = $_POST['statusCode'];

    if(!empty($_POST['CustomerID']))
    {
    $contentArray["customerID"] = $_POST['CustomerID'];
    }

    if(!empty($_POST['EMPID'])){
        $contentArray['alote_status'] = 0;
        $trigger        = $_SESSION["user"]["name"];
        $empID          = $_POST['EMPID'];
        $empDetails     = getEmpDetails($_POST['EMPID']);
        $name           = $_POST['name'];
        $description    = $_POST['description'];
        
        $empname        = ucwords($empDetails['name']);
        $empemail       = $empDetails['email'];
        /* Get machine information */
        $machineInfo  =  getMachineInfo($id);

        if($machineInfo[0]['employeeID'] != $contentArray["empID"]){

            $contentArray['alote_status'] = 0;

            $currentEmpDetails     = getEmpDetails($machineInfo[0]['employeeID']);
            $currentEmpemail       = $currentEmpDetails['email'];

            $auditData = array("old_values"=>$_SESSION['oldData'], "new_values"=>$contentArray);
            $auditID   = generateAudit("MCN_ANOTH", $smid, $auditData, "");

            $mailbox        = file_get_contents("partial-forms/alotted-machine-to-other-employee.html");
            $mailbox        = str_replace("[NEWEMPLOYEE]" , $empname , $mailbox);
            $mailbox        = str_replace("[NAME]" , $name , $mailbox);
            $mailbox        = str_replace("[DESCRIPTION]" , $description , $mailbox);
            $mailbox        = str_replace("[CLASSIFICATION]" , $classificationName , $mailbox);
            $mailbox        = str_replace("[TRIGGERBY]" , $trigger , $mailbox);

            $subject        = "Process Portal- allotted machime-tools to another employee";
            $mailSender     = mailSend($currentEmpemail,$subject,$mailbox);
        }
    }

    if($smid != "0"){
	$updateMachine = updateData($table, $contentArray, "identifier", $id);
    $auditData = array("old_values"=>$_SESSION['oldData'], "new_values"=>$contentArray);
    $auditID = generateAudit("MCN_UPD", $smid, $auditData, "");
    if(!empty($_POST['EMPID'])){
        if($updateMachine){
            if($machineInfo[0]['employeeID'] != $contentArray["empID"]){
            /* Send Mail */
                $mailbox        = file_get_contents("partial-forms/alotted-machine-tool-employee.html");
                $mailbox        = str_replace("[EMPLOYEE]" , $empname , $mailbox);
                $mailbox        = str_replace("[NAME]" , $name , $mailbox);
                $mailbox        = str_replace("[DESCRIPTION]" , $description , $mailbox);
                $mailbox        = str_replace("[CLASSIFICATION]" , $classificationName , $mailbox);
                $mailbox        = str_replace("[TRIGGERBY]" , $trigger , $mailbox);
                $protocol       = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
                $url            = $protocol . $_SERVER['HTTP_HOST'];
                $target         = $url.'/view-employee-details.php?empid='.$empID;
                $mailbox        = str_replace("[TARGETLINK]" , $target , $mailbox);
                $TITLE          = 'allotted a Machine';
                $mailbox        = str_replace("[TITLE]" , $TITLE , $mailbox);
                
                $subject        = "Process Portal- allotted machime-tools";
                $mailSender     = mailSend($empemail,$subject,$mailbox);

                $_SESSION["success"] = "Machine Tools allotted Successfully.";
                // header("Location: machine-tools.php");
                // exit();
            }
        }else{
            $_SESSION["success"] = "Machine Tools could not be allotted. Please try again later.";
            header("Location: update-machine-tools.php");
            exit();
        }
    }

	$_SESSION["success"] = "Item Details Updated Successfully.";
	header("Location: machine-tools.php");
	exit();
    }else{
	  $_SESSION['error'] = "Item does not exist.";
      header("Location: machine-tools.php");
      exit();
}
}
if($smid != "0"){
    $empData = getInventoryDetails($smid,$table);
	$_SESSION["oldData"] = $empData;
    if(count($empData)){
        $name = $empData["name"];
        $description = $empData["description"];
		$classification=$empData['classification'];
		$EMPID=$empData['employeeID'];
		$CustomerID = $empData["customerID"];
		$location=$empData['locationID'];
        $statusCode = $empData["statusCode"];
        $heading = "Update Machine and Tools Item Details";
    }else{
       $_SESSION['error'] = "Item does not exist.";
       header("Location: machine-tools.php");
        exit();
    }

}else{
	  $_SESSION['error'] = "Item does not exist.";
      header("Location: machine-tools.php");
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
                                            <input type="hidden" name="smid" value="<?php echo $smid;?>" id="smid">
                                            <div class="form-body">
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label">name</label>
                                                    <div class="col-md-4">
                                                        <input class="form-control" placeholder="Enter name" type="text" name="name" value="<?php echo $name;?>">
                                                    </div>
                                                </div>
												<div class="form-group">
                                                    <label class="col-md-3 control-label">description</label>
                                                    <div class="col-md-4">
                                                        <input class="form-control" placeholder="Enter description" type="text" name="description" value="<?php echo $description;?>">
                                                    </div>
                                                </div>
											<?php include"partial-forms/machineandtools-classifications.php";?>                                                
											<?php //include"partial-forms/location.php";?>    									
											<?php include"partial-forms/inventory-statauscodes-list.php";?>
                                            <?php include"partial-forms/emp-list.php";?> 
											<div id="InExtraFields">
											<?php if($statusCode=='409' Or $statusCode=='402' Or $statusCode=='403'){
											include"partial-forms/customers-list.php";
											}?></div>   											
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
        <script src="assets/pages/update-machine-inventory.js"></script>
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
			 xmlhttp.open("GET","includes/getExtraFiledsInventory.php?q="+str+"&q2=MACHINE",true);
			 xmlhttp.send();
			}
			$("hrdDevice").select2();
		}
		</script>
    </body>
</html>
