<?php
include("includes/check_session.php");
include("includes/header.php");
$activeHref = "dashboard-hardware.php";
include("includes/html-header.php");
$emp=$_GET['empid'];
?>
<style>
.caption{border-bottom: solid 1px;margin-bottom:5px}
.badge-success a{ color: white;}
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
                                             <i class="fa fa-mobile"></i> Hardware Inventory
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
                                        <div class="col-md-6">
										<!-- BEGIN WIDGET THUMB -->
										<h4 class="block caption-subject font-green bold uppercase">Hardware Inventory</h4>
                                        <ul class="list-group">											
											<li class="list-group-item"> Total  Items
                                                <span class="badge badge-success"> <?php echo getNoItemsInventory('tblhardware','','') ?></span>
                                            </li>
											<li class="list-group-item"> Action
                                                <span class="badge badge-success"> <a href="hardware-inventory.php" class="white"> View All </a></span>
                                            </li>
                                        </ul>
										<!-- END WIDGET THUMB -->
										</div>
                                        <div class="col-md-6">
										<!-- BEGIN WIDGET THUMB -->
										<h4 class="block caption-subject font-green bold uppercase">Location</h4>
                                        <ul class="list-group">
											<?php
											$locationsArray = getAllLocations();
											foreach($locationsArray as $location){  ?>
											<li class="list-group-item"> <?php echo $location['name']; ?>
                                                <span class="badge badge-success"><a href="hardware-inventory.php?sSearch=<?php echo $location['name']; ?>"> <?php echo getNoItemsInventory('tblhardware','locationID',$location['identifier']) ?> </a> </span>
                                            </li>
											<?php } ?>
                                        </ul>
										<!-- END WIDGET THUMB -->
										</div>										
										<div class="col-md-6">
										<!-- BEGIN WIDGET THUMB -->
										<h4 class="block caption-subject font-green bold uppercase">Category</h4>
                                        <ul class="list-group">
										<?php foreach($PRODUCTCAT as $key=>$value) {?>
                                            <li class="list-group-item"> <?php echo $value[0]; ?>
                                                <span class="badge badge-success"> <a href="hardware-inventory.php?sSearch=<?php echo $value[0]; ?>"><?php echo getNoItemsInventory('tblhardware','productCat',$key) ?> </a> </span>
                                            </li>
										<?php } ?>                                           
                                        </ul>
										<!-- END WIDGET THUMB -->
										</div>
                                        <div class="col-md-6">
										<!-- BEGIN WIDGET THUMB -->
										<h4 class="block caption-subject font-green bold uppercase">Status</h4>
                                            <ul class="list-group">
										    <?php foreach($STATUSCODE as $key=>$value) {?>
                                            <li class="list-group-item"> <?php echo $value[0]; ?>
                                                <span class="badge badge-success"><a href="hardware-inventory.php?sSearch=<?php echo $value[0]; ?>"> <?php echo getNoItemsInventory('tblhardware','statusCode',$key) ?> </a> </span>
                                            </li>
										   <?php } ?>                                           
                                            </ul>
										<!-- END WIDGET THUMB -->
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
    </body>
</html>
