<?php
include("includes/check_session.php");
include("includes/header.php");
$activeHref = "employee.php";
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
                             <div class="col-lg-12 col-xs-12 col-sm-12">
                                 <div class="portlet box yellow">
                                     <div class="portlet-title">
                                         <div class="caption">
                                             <i class="fa fa-gift"></i>Employees List
                                         </div>
                                         <div class="actions">
                                             <a class="btn green" href="add-employee.php">
                                                 <i class="fa fa-plus"></i>Add Employee
                                             </a>												 
											<a class="btn green" href="/download-employees.php">
                                                   <i class="icon-cloud-download"></i> Download Excel
											</a>
												
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
                                         <table class="table table-bordered table-hover table-striped" id="dataTables" >
                                             <thead>
                                                 <tr>
                                                    <th class="sorting_disabled">#</th>
													<th>Employee ID</th>
                                                    <th>Name</th>
                                                    <th>Email</th>
                                                    <th>Location</th>
                                                    <th>Type</th>
                                                    <th>Status</th>
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
				"columnDefs": [ {
						"targets": [0,7],
						"orderable": false
						} ],
				 "order": [[ 6, "asc" ]],
				 "paginate":false,
                "ajax": './ajax/employee-list.php',
            }
            <?php if(isset($_GET['sSearch'])){?>
                tableParam.oSearch = {"sSearch": "<?php echo $_GET[sSearch];?>"};
            <?php }?>
            var table = $('#dataTables').DataTable(tableParam);
			$("#dataTables thead tr th").removeClass("sorting_disabled");            
        })
        </script>
    </body>

</html>
