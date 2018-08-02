<?php
include("includes/check_session.php");
include("includes/header.php");
$activeHref = "saleleads.php";
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
                                             <i class="fa fa-gift"></i>Sale Leads List
                                         </div>
                                         <div class="actions">
                                             <a class="btn green" href="add-salelead.php">
                                               <i class="fa fa-plus"></i>Add Sale Lead</a>
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
                                                    <th>#</th>
													<th>Employee</th> 
                                                    <th>Mode</th>													
                                                    <th>Classification</th>
                                                    <th>Name</th>	
                                                    <th>Email</th>													
                                                    <th>Phone</th>
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
            $('#dataTables').DataTable({
                 "serverSide": true,
                 "processing": true,
                 "ajax": './ajax/salesleads-list.php'
            });
        })
        </script>
    </body>

</html>
