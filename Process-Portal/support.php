<?php
include("includes/check_session.php");
include("includes/header.php");
$activeHref = "support.php";
include("includes/html-header.php");
?>
<style type="text/css">
     .btn-group.select_custom span, .btn-group.select_custom2 span {
    width: 180px !important;
}
.select2-container--bootstrap .select2-selection--single .select2-selection__arrow b {
    left: 100% !important;
}
</style>>
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
                                             <i class="fa fa-gift"></i>Support Tickets
                                         </div>
                                         <div class="actions">
                                            <div class="btn-group btn-group-devided">
                                                <div class="btn-group select_custom">
                                                    <select class="form-control select2 df changeCustomer">
                                                        <option value="">Filter By Customer</option>
                                                    <?php
                                                    $customer=getAllCustomer();
                                                    foreach ($customer as  $value) {  ?>
                                                        <option value="<?php echo($value['identifier']) ?>"><?php echo ucwords(($value['Name'])) ?></option>
                                                    <?php }?></select>
                                                </div>
                                            </div>
                                            <div class="btn-group btn-group-devided">
                                                <div class="btn-group select_custom">
                                                    <select class="form-control select2 df changeAssignTo">
                                                        <option value="">Filter By Assign To</option>
                                                    <?php
                                                    $employee=getAllEmp();
                                                    foreach ($employee as  $value) {  ?>
                                                        <option value="<?php echo($value['identifier']) ?>"><?php echo ucwords(($value['name'])) ?></option>
                                                    <?php }?></select>
                                                </div>
                                            </div>
                                            <div class="btn-group btn-group-devided">
                                                <div class="btn-group">
                                                    <a class="btn btn-primary btn-sm uppercase sbold" href="javascript:;" data-toggle="dropdown" data-hover="dropdown" data-close-others="true"> Filter By Status
                                                        <i class="fa fa-angle-down"></i>
                                                    </a>
                                                    <ul class="dropdown-menu pull-right custom_ul">
                                                        <?php foreach ($SUPPORTSTS as $key => $value) {
                                                            # code...
                                                       ?>
                                                        <li>
                                                          <label>
                                                          <INPUT TYPE="checkbox" class="checkStatus" name="Status[]" data-column="Status"  value ="<?php echo $key; ?>" >&nbsp;<span style="color: black;"> <?php echo $value[1]; ?></span>
                                                        </label>
                                                        </li>
                                                        <?php }?>
                                                    </ul>
                                                </div>
                                            </div>
                                            <a class="btn green" href="add-support.php">
                                            <i class="fa fa-plus"></i>Add Support Ticket</a>
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
                                                    <th>Date of Report</th>
                                                    <th>Issue Raised by</th>
													<!-- <th>Service Report No</th> --> 
                                                    <th>Type</th>
                                                    <th>Customer</th>	
                                                    <th>Assign To</th>
                                                    <th>Status</th>                                    <th>Date Of Resolution</th>                     
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
        <link href="assets/global/plugins/select2/css/select2.min.css" rel="stylesheet" />
        <link href="assets/global/plugins/select2/css/select2-bootstrap.min.css" rel="stylesheet" />
        
        <script src="assets/global/plugins/select2/js/select2.full.min.js"></script>
        <link rel="stylesheet" href="assets/global/plugins/datatables/datatables.min.css">
        <link rel="stylesheet" href="assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css">
        <script src="assets/global/plugins/datatables/datatables.min.js"></script>
        <script src="assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js"></script>
		<link href="assets/global/plugins/dropzone/dropzone.min.css" rel="stylesheet" />
        <script src="assets/global/plugins/dropzone/dropzone.min.js"></script>		
        <script>
        $(document).ready(function(){
           $('.select2').select2();
			 var tableParam = {
                "serverSide": true,
                "processing": true,
				"columnDefs": [ {
						"targets": [0,8],
						"orderable": false
						} ],
				"order": [[ 4, "asc" ]],
                "ajax": './ajax/supprt-list.php',
               // "scrollY":"100%"
            }
            <?php if(isset($_GET['sSearch'])){?>
                tableParam.oSearch = {"sSearch": "<?php echo $_GET[sSearch];?>"};
            <?php }?>
            var table = $('#dataTables').DataTable(tableParam);	
            $('.changeCustomer').on( 'change', function () {
                   var i = $(this).val();
                        table.column('4').search(i).draw();
                  
            });

            $('.changeAssignTo').on( 'change', function () {
                   var i = $(this).val();
                        table.column('5').search(i).draw();
                  
            });	
            $('.checkStatus').on( 'click', function () {
                    var i = $(this).attr('data-column'); 
                    var v = $('.checkStatus:checked').map(function() {return this.value;}).get().join(',')
                  table.column('6').search(v).draw();
             });	
        })
        </script>
    </body>

</html>
