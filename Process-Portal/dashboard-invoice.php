<?php
include("includes/check_session.php");
include("includes/header.php");
$activeHref = "dashboard-invoice.php";
include("includes/html-header.php");
$emp=$_GET['empid'];
?>
<style>
.caption{border-bottom: solid 1px;margin-bottom:5px}
.badge-success a{ color: white;}
.invoice-custom {
    float: right;
}
.invoice-custom {
    font-size: 17px !important;
    height: auto;
    width: 130px;
    text-align: left;
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
                                             <i class="fa fa-mobile"></i> Invoice
                                         </div>	
                                         <div class="actions">
                                            <div class="btn-group btn-group-devided">
                                                <a href="invoices.php" class="btn green">
                                                    <i class="fa fa-eye"></i>View All
                                                </a>
                                            </div>
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
                                    <!-- Current Mont Invoice Information-->
									<div class="row widget-row">
                                    <h4 class="block caption-subject bold uppercase"> &nbsp; Current Month Invoice Information</h4>                                       
                                        <div class="col-md-4">
										<!-- BEGIN WIDGET THUMB -->
										<h4 class="block caption-subject font-green bold uppercase">Paid Invoice</h4>
                                        <ul class="list-group">											
											<li class="list-group-item"> Total Paid Invoice
                                                <span class="invoice-custom"><b> <?php echo getCurrentMonthInvoices('tblInvoice',2) ?> </b></span>
                                            </li>
											<li class="list-group-item"> Total Paid Amount
                                                <span class="invoice-custom"><b> Rs.<?php echo getCurrentStatusWiseAmount('tblInvoice',2) ?></b></span>
                                            </li>
                                            <li class="list-group-item"> Action
                                                <span class="badge badge-success"> <a href="invoices.php?status=2&cur_month=1" class="white"> View All </a></span>
                                            </li>
                                        </ul>
										<!-- END WIDGET THUMB -->
										</div>
                                        <div class="col-md-4">
										<!-- BEGIN WIDGET THUMB -->
										<h4 class="block caption-subject font-green bold uppercase">Unpaid Invoice</h4>
                                        <ul class="list-group">
                                            <li class="list-group-item"> Total Unpaid Invoice
                                                <span class="invoice-custom"><b> <?php echo getCurrentMonthInvoices('tblInvoice',0) ?> </b></span>
                                            </li>
                                            <li class="list-group-item"> Total Unpaid Amount
                                                <span class="invoice-custom"><b> Rs.<?php echo getCurrentStatusWiseAmount('tblInvoice',0) ?></b></span>
                                            </li>
                                            <li class="list-group-item"> Action
                                                <span class="badge badge-success"> <a href="invoices.php?status=0&cur_month=1" class="white"> View All </a></span>
                                            </li>
                                        </ul>
										<!-- END WIDGET THUMB -->
										</div>	
                                        <div class="col-md-4">
                                        <!-- BEGIN WIDGET THUMB -->
                                        <h4 class="block caption-subject font-green bold uppercase">Pending Invoice</h4>
                                        <ul class="list-group">
                                            <li class="list-group-item"> Total Pending Invoice
                                                <span class="invoice-custom"><b> <?php echo getCurrentMonthInvoices('tblInvoice',1) ?> </b></span>
                                            </li>
                                            <li class="list-group-item"> Total Pending Amount
                                                <span class="invoice-custom"><b> Rs.<?php echo getCurrentStatusWiseAmount('tblInvoice',1) ?></b></span>
                                            </li>
                                            <li class="list-group-item"> Action
                                                <span class="badge badge-success"> <a href="invoices.php?status=1&cur_month=1" class="white"> View All </a></span>
                                            </li>
                                        </ul>
                                        <!-- END WIDGET THUMB -->
                                        </div>  									
											
									</div>

                                    <hr>	
                                    <!-- Over All Invoice Information -->                                 
                                    <div class="row widget-row">      
                                    <h4 class="block caption-subject bold uppercase"> &nbsp; Over All Invoice Information</h4>                                 
                                        <div class="col-md-4">
                                        <!-- BEGIN WIDGET THUMB -->
                                        <h4 class="block caption-subject font-green bold uppercase">Paid Invoice</h4>
                                        <ul class="list-group">                                         
                                            <li class="list-group-item"> Total Paid Invoice
                                                <span class="invoice-custom"><b> <?php echo getInvoices('tblInvoice',2) ?> </b></span>
                                            </li>
                                            <li class="list-group-item"> Total Paid Amount
                                                <span class="invoice-custom"><b> Rs.<?php echo getStatusWiseAmount('tblInvoice',2) ?></b></span>
                                            </li>
                                            <li class="list-group-item"> Action
                                                <span class="badge badge-success"> <a href="invoices.php?status=2" class="white"> View All </a></span>
                                            </li>
                                        </ul>
                                        <!-- END WIDGET THUMB -->
                                        </div>
                                        <div class="col-md-4">
                                        <!-- BEGIN WIDGET THUMB -->
                                        <h4 class="block caption-subject font-green bold uppercase">Unpaid Invoice</h4>
                                        <ul class="list-group">
                                            <li class="list-group-item"> Total Unpaid Invoice
                                                <span class="invoice-custom"><b> <?php echo getInvoices('tblInvoice',0) ?> </b></span>
                                            </li>
                                            <li class="list-group-item"> Total Unpaid Amount
                                                <span class="invoice-custom"><b> Rs.<?php echo getStatusWiseAmount('tblInvoice',0) ?></b></span>
                                            </li>
                                            <li class="list-group-item"> Action
                                                <span class="badge badge-success"> <a href="invoices.php?status=0" class="white"> View All </a></span>
                                            </li>
                                        </ul>
                                        <!-- END WIDGET THUMB -->
                                        </div>  
                                        <div class="col-md-4">
                                        <!-- BEGIN WIDGET THUMB -->
                                        <h4 class="block caption-subject font-green bold uppercase">Pending Invoice</h4>
                                        <ul class="list-group">
                                            <li class="list-group-item"> Total Pending Invoice
                                                <span class="invoice-custom"><b> <?php echo getInvoices('tblInvoice',1) ?> </b></span>
                                            </li>
                                            <li class="list-group-item"> Total Pending Amount
                                                <span class="invoice-custom"><b> Rs.<?php echo getStatusWiseAmount('tblInvoice',1) ?></b></span>
                                            </li>
                                            <li class="list-group-item"> Action
                                                <span class="badge badge-success"> <a href="invoices.php?status=1" class="white"> View All </a></span>
                                            </li>
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
