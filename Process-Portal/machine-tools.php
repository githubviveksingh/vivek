<?php
include("includes/check_session.php");
include("includes/header.php");
$activeHref = "machine-tools.php";
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
                                             <i class="fa fa-gift"></i>Machine & Tools
                                         </div>
                                         <div class="actions">

                                             <a class="btn green" href="add-purchase.php">
                                                 <i class="fa fa-plus"></i>Add Purchase
                                             </a>
											  <a class="btn green" href="/download-sim-inventory.php?tag=MACHINE-INVENTORY">
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
                                                    <th>#</th>
                                                    <!-- <th>Item ID</th> -->
                                                    <th>Name</th>
                                                    <th>Description</th>
                                                    <th>Category</th>
                                                    <th>Location</th>
                                                    <th>Status</th>
                                                    <th>Assigned To</th>
                                                    <th>Added On</th>
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
		<div class="modal fade" id="auditModel" role="dialog">
	        <div class="modal-dialog">

	          <!-- Modal content-->
	          <div class="modal-content">
	            <div class="modal-header">
	              <h4 class="modal-title green" id="modalTitle"></h4>
	            </div>
	            <div class="modal-body">
                    <form class="form-horizontal" method="post" action="" id="LOCATION_FORM">
                        <input type="hidden" name="current_location" id="currentLocation" value="">
						<input type="hidden" name="itemID" id="itemID" value="">
                        <div class="form-body">  
                            <?php include("partial-forms/delivery-location.php");?>						
                            <div class="form-group">
                                <label class="col-md-3 control-label">Remarks</label>
                                <div class="col-md-9">
                                    <textarea class="form-control" placeholder="Remarks" id="remarks" name="remarks" value=""></textarea>
                                </div>
                            </div>                            
                        </div>
                    </form>
	            </div>
                <div class="modal-footer">
				<a href="javascript:void(0);" class="btn btn-primary submitModelForm" id="Submit_LC">Submit</a>
                    <a href="javascript:void(0);" class="btn btn-primary" id="closeModal">Close</a>
                </div>
	          </div>

	        </div>
	    </div>
		<div class="modal fade" id="auditModel2" role="dialog">
	        <div class="modal-dialog">

	          <!-- Modal content-->
	          <div class="modal-content">
	            <div class="modal-header">
	              <h4 class="modal-title green" id="modalTitle2"></h4>
	            </div>
	            <div class="modal-body">
                    <form class="form-horizontal" method="post" action="" id="LOCATION_FORM_CHANGE">
                        <input type="hidden" name="curr_location" id="currLocation" value="">
						<input type="hidden" name="new_location" id="newLocation" value="">
						<input type="hidden" name="sitemID" id="sitemID" value="">
						<input type="hidden" name="identifier" id="identifier" value="">
						<input type="hidden" name="status" id="changestatus" value="">
                        <div class="form-body"> 				
                            <div class="form-group">
                               <h4><b>Change Item Location From <span id="cl"></span></h4>
                            </div>                            
                        </div>
                    </form>
	            </div>
                <div class="modal-footer">
				<a href="javascript:void(0);" class="btn btn-primary submitModelForm" id="Submit_LCSTASTUS">Submit</a>
                    <a href="javascript:void(0);" class="btn btn-primary" id="closeModal2">Close</a>
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
        $(document).ready(function(){
            var tableParam = {
                // "oSearch": {"sSearch": "Micromax Laptab"},
                "serverSide": true,
                "processing": true,
				"columnDefs": [ {
						"targets": [0,8],
						"orderable": false
						} ],
				 "order": [[ 1, "asc" ]],
                "ajax": './ajax/machine-tools-list.php',
                "scrollY":"100%"
            }
            <?php if(isset($_GET['sSearch'])){?>
                tableParam.oSearch = {"sSearch": "<?php echo $_GET[sSearch];?>"};
            <?php }?>
            var table = $('#dataTables').DataTable(tableParam);
			$("#closeModal").click(function(){
                $("#auditModel").modal("hide");
            })
			$("#closeModal2").click(function(){
                $("#auditModel2").modal("hide");
            })
			$("#dataTables").on("click", ".changelc", function(){
				 var currentlocation = $(this).attr("current-location");
				 document.getElementById('DeliveryLocation').value = currentlocation;
				 $('#currentLocation').val(currentlocation);
				 $("#modalTitle").html($(this).attr("ctext"));
				 $("#itemID").val($(this).attr("itemid"));
				 $('#auditModel').modal('show');				
		    });
			$("#Submit_LC").click(function(){
              $.ajax({
                type: "post",
                data: $("#LOCATION_FORM").serialize(),
                url: "ajax/machinetools-change-location.php",
                success: function(data){
					$("#modalTitle").html('Change Location Request Send Successfully To Admin.');
                    location.reload(true);
                }
               })
            });
		  $("#dataTables").on("click", ".changellaction", function(){
				 var newlocation = $(this).attr("new-location");
				 var currentlocation = $(this).attr("current-location");
				 var itemid = $(this).attr("itemid");
				 var status = $(this).attr("change-status");
				 var movementdata = $(this).attr("movement-data");
				 $('#changestatus').val(status);
				 $('#currLocation').val(currentlocation);
				 $('#newLocation').val(newlocation);
				 $('#identifier').val($(this).attr("iden"));
				 if(status!='1'){
					  $("#modalTitle2").html("Do you Want To Reject Request?");
				 }else{
					 $("#modalTitle2").html("Do you Want To Allow to change Location?");
				 }
                 $('#cl').html(movementdata);	
                 $('#nl').html(newlocation);				 
				 $("#sitemID").val($(this).attr("itemid"));
				 $('#auditModel2').modal('show');		
		    });
			$("#Submit_LCSTASTUS").click(function(){
					  $.ajax({
						type: "post",
						data: $("#LOCATION_FORM_CHANGE").serialize(),
						url: "ajax/verify-stock-movement.php",
						success: function(data){
							$("#modalTitle").html('Process Done Successfully.');
							location.reload(true);
						}
					})
				  });
        })
        </script>
    </body>

</html>
