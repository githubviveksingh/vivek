<?php
include("includes/check_session.php");
include("includes/header.php");

$activeHref = "audit.php";

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
                                         <div class="caption" id="caption">
                                             <i class="fa fa-gift"></i>Audit
                                         </div>
                                     </div>
                                     <div class="portlet-body">
                                         <table class="table table-bordered table-hover table-striped" id="dataTables" >
                                             <thead>
                                                 <tr>
                                                    <th>#</th>
                                                    <th>Action Done</th>
                                                    <th>Target ID</th>
                                                    <th>Notify Action</th>
                                                    <th>Triggered By</th>
                                                    <th>Updated on </th>
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

        <div class="modal fade" id="auditModel" role="dialog">
	        <div class="modal-dialog">

	          <!-- Modal content-->
	          <div class="modal-content">
	            <div class="modal-header">
	              <h4 class="modal-title" id="modalTitle"></h4>
	            </div>
	            <div class="modal-body">
                    <table class="table table-bordered table-hover" id="modalTable">
                        <thead>
                            <tr>
                               <th>#</th>
                               <th>Action Done</th>
                               <th>Target ID</th>
                               <th>Notify Action</th>
                               <th>Triggered By</th>
                               <th>Created On</th>
                           </tr>
                        </thead>
                        <tbody>
                       </tbody>
                    </table>
	            </div>
                <div class="modal-footer">
                    <a href="javascript:void(0);" class="btn btn-primary" id="closeModal">Close</a>
                </div>
	          </div>

	        </div>
	    </div>

        <?php
            include("includes/common_js.php");
        ?>
        <link rel="stylesheet" href="assets/global/plugins/datatables/datatables.min.css">
        <link rel="stylesheet" href="assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css">
        <script src="assets/global/plugins/datatables/datatables.min.js"></script>
        <script src="assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js"></script>

        <script>
        var table;
        $(function(){
            $("#caption").tooltip({content: "Hello"})
        })
        $(document).ready(function(){
            $("#closeModal").click(function(){
                $("#auditModel").modal("hide");
            })
            table = $('#dataTables').DataTable({
                 "serverSide": true,
                 "processing": true,
                 "ajax": './ajax/audit-list.php',
                 "createdRow": function(row, data, dataIndex){
                     $(row).addClass("tableRow");
                 },
                 "order":[[5, "desc"]],
				 "lengthMenu": [[25, 50, -1], [25, 50, "All"]]
            });
        })
        </script>
        <script src="assets/pages/audit.js"></script>
    </body>

</html>
