<?php
include("includes/check_session.php");
include("includes/header.php");
$activeHref = "employee.php";
include("includes/html-header.php");
$sType = isset($_GET['sType'])?intval($_GET['sType']):"0";
if(isset($_POST['submit'])){
    $contentArray = array();
    $id = $_POST["sType"];
	$contentArray["supplier_type"] = $_POST['supplier_type'];	
	if(checkDuplicateTypeSupplier($contentArray["supplier_type"], $id) === true){
		 $_SESSION["error"] = "Name Already Exist.";
	}else{
	if($id == "0"){
		$sid = addData("tblSupplierTypes", $contentArray);
		$_SESSION["success"] = "New Type Added Successfully.";
		header("Location:supplier-types.php");
	}else{
		updateData("tblSupplierTypes", $contentArray, "identifier", $id);
		$_SESSION["success"] = "Type Updated Successfully.";
		header("Location:supplier-types.php");
	}
	}
}

if($sType != "0"){
	$empData = getSuppliersTypes($sType)[0];
	if(count($empData)){
		$supplier_type = $empData["supplier_type"];
		$heading="Update Type Name";
	}else{
	}	
}
if($sType == "0" && !isset($_POST['submit'])){
	$supplier_type = "";
	$heading="Add New Type";
}
?><style>
.caption{border-bottom: solid 1px;margin-bottom:15px}
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
                             <div class="col-lg-12 col-xs-12 col-sm-12">
                                 <div class="portlet box yellow">
                                     <div class="portlet-title">
                                         <div class="caption">
                                             <i class="fa fa-gift"></i>Suppliers Type List
                                         </div>                                         
                                     </div>
                                     <div class="portlet-body">
									        <?php
                                            if(isset($_SESSION["error"])){?>
                                                <div class="alert alert-danger">
                                                    <button class="close" data-close="alert"></button>
                                                    <span><?php echo $_SESSION["error"];?></span>
                                                </div>
                                            <?php unset($_SESSION["error"]);	
                                                 }
                                            if(isset($_SESSION["success"])){?>
                                                <div class="alert alert-success">
                                                    <button class="close" data-close="alert"></button>
                                                    <span><?php echo $_SESSION["success"];?></span>
                                                </div>
                                            <?php
											unset($_SESSION["success"]);	
											}											
                                            ?>
									<div class="row widget-row">		
										<div class="col-md-5">
                                        <div class="caption">
                                            <i class="icon-social-dribbble font-green"></i>
                                            <span class="caption-subject font-green bold uppercase"><?php echo $heading;?></span>
                                        </div>
										<form class="form-horizontal" id="addEmp" method="post" action="">
										<input type="hidden" name="sType" value="<?php echo $sType;?>" id="sType">
                                            <div class="form-body">
                                                <div class="form-group">
                                                    <label class="col-md-5 control-label">Supplier Type<span class="required" aria-required="true"> * </span></label>
                                                    <div class="col-md-7">
                                                        <input class="form-control" placeholder="Enter Supplier Type" type="text" name="supplier_type" value="<?php echo $supplier_type;?>">
                                                    </div>
                                                </div>
										    </div>	
                                            <div class="form-actions fluid">
                                                <div class="row">
                                                    <div class="col-md-offset-3 col-md-9">
													<?php if(!empty($_GET['sType'])){ ?>
														<a class="btn yellow" href="/supplier-types.php">Back</a>
														<?php } ?>
                                                        <input type="submit" class="btn green" name="submit" value="Submit">
                                                    </div>
                                                </div>
                                            </div>											
										</form>
										</div>
										<div class="col-md-7">
										<div class="caption">
                                            <i class="icon-social-dribbble font-green"></i>
                                            <span class="caption-subject font-green bold uppercase">Types List</span>
                                        </div>
                                         <table class="table table-bordered table-hover table-striped" id="dataTables" >
                                             <thead>
                                                 <tr>
                                                    <th class="sorting_disabled">#</th>
                                                    <th>Name</th>
                                                    <th>Created On</th>
                                                    <th>Action</th>
                                                </tr>
                                             </thead>
                                             <tbody>
                                            </tbody>
                                         </table>
										</div>
									</div>
										
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
        <link rel="stylesheet" href="assets/global/plugins/datatables/datatables.min.css">
        <link rel="stylesheet" href="assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css">
        <script src="assets/global/plugins/datatables/datatables.min.js"></script>
        <script src="assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js"></script>
        <script>
        $(document).ready(function(){
			 var tableParam = {
                "serverSide": true,
                "processing": true,				
				"order": [[ 1, "asc" ]],
				"paging":false,
                "ajax": './ajax/supplier-types-list.php',
            }
            <?php if(isset($_GET['sSearch'])){?>
                tableParam.oSearch = {"sSearch": "<?php echo $_GET[sSearch];?>"};
            <?php }?>
            var table = $('#dataTables').DataTable(tableParam);          
        })
        </script>
    </body>

</html>
