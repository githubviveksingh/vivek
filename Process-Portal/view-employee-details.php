<?php
include("includes/check_session.php");
include("includes/header.php");
$activeHref = "employee.php";
include("includes/html-header.php");
$emp=$_GET['empid'];
?>
<style>
.caption{border-bottom: solid 1px;margin-bottom:5px}
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
                                             <i class="icon-user-following"></i><?php echo getEmpDetails($emp)['name']; ?>
                                         </div>										 
                                     </div>
                                     <div class="portlet-body">
											<?php
											if(isset($_SESSION["success"])){?>
											<div class="alert alert-success">
												<button class="close" data-close="alert"></button>
												<span><?php echo $_SESSION["success"];?></span>
											</div>
											<?php unset($_SESSION["success"]);}
											?> 
                                    <!-- Profile Div -->
									<div class="row widget-row">
										<div class="col-md-3">
										<!-- BEGIN WIDGET THUMB -->
										<div class="widget-thumb widget-bg-color-white text-uppercase margin-bottom-20 bordered">
											<h4 class="widget-thumb-heading">Total Items Assigned</h4>
											<div class="widget-thumb-wrap">
											   <i class="widget-thumb-icon bg-red icon-layers"></i>											
												<div class="widget-thumb-body">
													<span class="widget-thumb-body-stat"><?php 
													$sim=getNoItemsInventory('tblSim','empID',$emp);
													$hrd=getNoItemsInventory('tblHardware','EMPID',$emp);
													$mhn=getNoItemsInventory('tblMachine','employeeID',$emp);
													$ofc=getNoItemsInventory('tblOfficeItem','EMPID',$emp);
													echo $sim+$hrd+$mhn+$ofc;
													?></span>
												</div>
											</div>
										</div>
										<!-- END WIDGET THUMB -->
										</div>
										<div class="col-md-2">
										<!-- BEGIN WIDGET THUMB -->
										<div class="widget-thumb widget-bg-color-white text-uppercase margin-bottom-20 bordered">
											<h4 class="widget-thumb-heading">SIM Assigned</h4>
											<div class="widget-thumb-wrap">
												<i class="widget-thumb-icon bg-green fa fa-credit-card"></i>
												<div class="widget-thumb-body">
													<span class="widget-thumb-body-stat"><?php echo $sim;?></span>
												</div>
											</div>
										</div>
										<!-- END WIDGET THUMB -->
										</div>										
										<div class="col-md-2">
										<!-- BEGIN WIDGET THUMB -->
										<div class="widget-thumb widget-bg-color-white text-uppercase margin-bottom-20 bordered">
											<h4 class="widget-thumb-heading">Hardware</h4>
											<div class="widget-thumb-wrap">
												<i class="widget-thumb-icon bg-green fa fa-desktop"></i>
												<div class="widget-thumb-body">
													<span class="widget-thumb-body-stat"><?php echo $hrd; ?></span>
												</div>
											</div>
										</div>
										<!-- END WIDGET THUMB -->
										</div>
										<div class="col-md-3">
										<!-- BEGIN WIDGET THUMB -->
										<div class="widget-thumb widget-bg-color-white text-uppercase margin-bottom-20 bordered">
											<h4 class="widget-thumb-heading">Machine & Tools</h4>
											<div class="widget-thumb-wrap">
												<i class="widget-thumb-icon bg-green fa fa-cubes"></i>
												<div class="widget-thumb-body">
													<span class="widget-thumb-body-stat"><?php echo $mhn; ?></span>
												</div>
											</div>
										</div>
										<!-- END WIDGET THUMB -->
										</div>
										<div class="col-md-2">
										<!-- BEGIN WIDGET THUMB -->
										<div class="widget-thumb widget-bg-color-white text-uppercase margin-bottom-20 bordered">
											<h4 class="widget-thumb-heading">Office Items</h4>
											<div class="widget-thumb-wrap">
												<i class="widget-thumb-icon bg-green fa fa-industry"></i>
												<div class="widget-thumb-body">
													<span class="widget-thumb-body-stat"><?php echo $ofc; ?></span>
												</div>
											</div>
										</div>
										<!-- END WIDGET THUMB -->
										</div>
									</div>
                                    <!-- End Profile Div -->  
									<div class="row widget-row">
									<?php if($sim >=1){ ?>
									<div class="col-md-12">
									<div class="caption">
                                            <i class="icon-social-dribbble font-green"></i>
                                            <span class="caption-subject font-green bold uppercase">SIM Item List</span>
                                        </div>
                                     <table class="table table-bordered table-hover table-striped" id="dataTables" >
                                             <thead>
                                                 <tr>
                                                    <th>#</th>
													 <th>MSISDN</th>
                                                    <th>Service Provider</th>
													<th>Assign By</th>
													<th>Assign Date</th>
													<th>Allotted Status</th>
                                                </tr>
                                             </thead>
                                             <tbody>
                                            </tbody>
                                        </table>	
									</div>
									<?php } if($hrd >=1){ ?>
									<div class="col-md-12">
                                    <div class="caption">
                                            <i class="icon-social-dribbble font-green"></i>
                                            <span class="caption-subject font-green bold uppercase">Hardware Item List</span>
                                        </div>
                                    <table class="table table-bordered table-hover table-striped" id="dataTablesHRD" >
                                             <thead>
                                                 <tr>
                                                    <th>#</th>
													 <th>IMEI</th>
                                                    <th>productCat</th>
													<th>Assign By</th>
													<th>Assign Date</th>
													<th>Allotted Status</th>
                                                </tr>
                                             </thead>
                                             <tbody>
                                            </tbody>
                                        </table>
									</div>
									<?php } if($mhn >=1){ ?>
									<div class="col-md-12">
                                    <div class="caption">
                                            <i class="icon-social-dribbble font-green"></i>
                                            <span class="caption-subject font-green bold uppercase">Machine & Tools Item List</span>
                                        </div>
                                    <table class="table table-bordered table-hover table-striped" id="dataTablesMCN" >
                                             <thead>
                                                 <tr>
                                                    <th>#</th>
													 <th>Name</th>
                                                    <th>Classification</th>
													<th>Assign By</th>
													<th>Assign Date</th>
													<th>Allotted Status</th>
                                                </tr>
                                             </thead>
                                             <tbody>
                                            </tbody>
                                        </table>
									</div>
									<?php } if($ofc >=1){ ?>
									<div class="col-md-12">
                                    <div class="caption">
                                            <i class="icon-social-dribbble font-green"></i>
                                            <span class="caption-subject font-green bold uppercase">Office Item List</span>
                                        </div>
                                    <table class="table table-bordered table-hover table-striped" id="dataTablesOFF" >
                                             <thead>
                                                 <tr>
                                                    <th>#</th>
													<th>itemName</th>
                                                    <th>description</th>
													<th>Assign By</th>
													<th>Assign Date</th>
													<th>Allotted Status</th>
                                                </tr>
                                             </thead>
                                             <tbody>
                                            </tbody>
                                        </table>	
									</div>
								<?php } ?>
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
				 "paging":   false,					
				 "info":     false,
                 "ajax": './ajax/emp-sim-inventory-list.php?emp=<?php echo $_GET[empid];?>',
            };
            var table = $('#dataTables').DataTable(tableParam);      
			 $('#dataTablesHRD').DataTable({
				 "serverSide": true,
                 "processing": true,
				 "paging":   false,					
				 "info":     false,
                 "ajax": './ajax/emp-hrd-inventory-list.php?emp=<?php echo $_GET[empid];?>',
			 }) ; 
			 $('#dataTablesMCN').DataTable({
				 "serverSide": true,
                 "processing": true,
				 "paging":   false,					
				 "info":     false,
                 "ajax": './ajax/emp-machine-inventory-list.php?emp=<?php echo $_GET[empid];?>',
			 });
			  $('#dataTablesOFF').DataTable({
				 "serverSide": true,
                 "processing": true,
				 "paging":   false,					
				 "info":     false,
                 "ajax": './ajax/emp-office-inventory-list.php?emp=<?php echo $_GET[empid];?>',
			 });


        })
        </script>		
    </body>
</html>