<?php
include("includes/check_session.php");
include("includes/header.php");
$activeHref = "customers.php";
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
                                             <i class="fa fa-gift"></i>Customers List
                                         </div>
										 <div class="actions">
                                            <div class="btn-group btn-group-devided">
                                                <a class="btn green" href="add-customer.php">
                                                 <i class="fa fa-plus"></i>Add Customer
                                                </a>
												 <a class="btn green" href="/download-sim-inventory.php?tag=Customers">
                                                   <i class="icon-cloud-download"></i> Download Excel
											    </a>
                                                <label class="btn red" id="uploadOption">
                                                   <i class="icon-cloud-upload"></i> Upload Excel
												</label>
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
                                         <table class="table table-bordered table-hover table-striped" id="dataTables" >
                                             <thead>
                                                 <tr>
                                                    <th>#</th>
                                                    <th>Name</th>
                                                    <th>Email</th>
                                                    <th>Phone</th>
                                                    <th>Account Manager</th>
                                                    <th>AccountID</th>													
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
		<div class="modal fade" id="UPLOADEXCEL" role="dialog">
			<div class="modal-dialog" style="width:85% !important;">
			  <!-- Modal content-->
			  <div class="modal-content">
				<div class="modal-header">
				  <h4 class="modal-title" id="OTHER_STATUS_H">Upload Customers. <a href="download-sample.php?file=Customers.xlsx" class="btn btn-primary right" id="downloadBtn">Download Sample</a> </h4>				  
				</div>
				<div class="modal-body">
				    <form id="my-awesome-dropzone" class="dropzone" action="ajax/upload-excel-customers.php" method="post">
					    <input type="hidden" name="purchaseType" id="aPurchaseType" value="120">
						<div class="dropzone-previews"></div> <!-- this is were the previews should be shown. -->
					</form>
					<div class="modal fade" id="myModal" role="dialog">
            	        <div class="modal-dialog">

            	          <!-- Modal content-->
            	          <div class="modal-content">
            	            <div class="modal-header">
            	              <h4 class="modal-title">Progress Update</h4>
            	            </div>
            	            <div class="modal-body">
            	                <div class="progress">
            	                    <div class="progress-bar progress-bar-primary progress-bar-striped progress-bar-green" role="progressbar" id="progress-bar-excel" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
            	                      <span class="sr-only">40% Complete (success)</span>
            	                    </div>
            	                </div>
            	            </div>
            	          </div>

            	        </div>
            	    </div>
				</div>
				
				<div class="modal-footer">
				     
					 <a href="javascript:void(0);" class="btn btn-primary" id="formsubmit" form-id="UPLOADEXCEL">Submit</a>
                    <a href="javascript:;" id="closeModal" class="btn btn-default pull-right closseModal">Close</a>
				</div>
				<div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12 col-xs-12 col-sm-12">
                            <div class="portlet box red">
                                <div class="portlet-title">
                                    <div class="caption">
                                        <i class="fa fa-gift"></i>Errors
                                    </div>
                                </div>
                                <div class="portlet-body">
                                    <table class="table table-bordered table-hover table-striped table-responsive errorTable" id="DataErrorTbl">
                                        <thead>

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
        <?php
            include("includes/common_js.php");
        ?>
        <link rel="stylesheet" href="assets/global/plugins/datatables/datatables.min.css">
        <link rel="stylesheet" href="assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css">
        <script src="assets/global/plugins/datatables/datatables.min.js"></script>
        <script src="assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js"></script>
		<link href="assets/global/plugins/dropzone/dropzone.min.css" rel="stylesheet" />
        <script src="assets/global/plugins/dropzone/dropzone.min.js"></script>
        <script>
        $(document).ready(function(){
           
			var tableParam = {
						"serverSide": true,
						"processing": true,
						"columnDefs": [ {
						"targets": [0,6],
						"orderable": false
						} ],
				      "order": [[ 1, "asc" ]],
						"ajax": './ajax/customers-list.php'
					}
					<?php if(isset($_GET['sSearch'])){?>
						tableParam.oSearch = {"sSearch": "<?php echo $_GET[sSearch];?>"};
					<?php }?>
			var table = $('#dataTables').DataTable(tableParam);
			$("#closeModal").click(function(){
                $("#UPLOADEXCEL").modal("hide");
            })
        $("#uploadOption").click( function(){
				$("#UPLOADEXCEL").modal('show');
				var colArray = {"CUSTOMERS_EXL": <?php echo json_encode($CUSTOMERS_EXL);?>};
                    selectedArray = colArray['CUSTOMERS_EXL'];
                    var headerHtml = "";
                    for(var i = 0; i<selectedArray.length;i++){
                        headerHtml += "<th>"+selectedArray[i]+"</th>";
                    }
                    headerHtml = "<tr>"+headerHtml+"<th>Error</th></tr>";
                    $(".errorTable thead").html(headerHtml);
			});			
        })
		function processData(totalRows){
				var i;
				for(i=2; i<=totalRows;i++){
				console.log("Normal: ");
				console.log(i);
				$.ajax({
					type:"post",
					data:{rowNumber:i},
					url:"ajax/process-row-customers.php",
					success:function(data){
						var perc = 100;
						var jsonData = $.parseJSON(data);
						// console.log(jsonData);
						var end = jsonData.end;
						console.log("Response: ");
						console.log(end);
						var rowNumber = jsonData.rowNumber;
						var error = jsonData.error;
						var rowData = jsonData.rowData;

						if(totalRows != "0"){
							perc = (rowNumber-1)*100/totalRows;
							var percent = perc+"%";
							$("#progress-bar-excel").css("width", percent);
						}

						if(error != ""){
							console.log(error);
							console.log(rowData["A"]);
							var trHtml = "";
							for(var j = 0;j<selectedArray.length;j++){
								var charCode = 65+j;
								var val = rowData[String.fromCharCode(charCode)];
								if(!val){
									val = "";
								}
								trHtml += "<td>"+val+"</td>";
							}
							trHtml = "<tr>"+trHtml+"<td>"+error+"</td></tr>";
							$(".errorTable tbody").append(trHtml);
						}

						if(end != "1"){

						}else{
							i = totalRows+1;
							console.log("Else: ");
							console.log(i);
						}
					},
					async:false
				});
				}
				$("#myModal").modal("hide");
				if($(".errorTable tbody tr").length == 0){
				alert("Uploaded Successfully");
				location.reload(true);
				}
		}  
		$(document).ready(function(){            
			Dropzone.options.myAwesomeDropzone = {
				addRemoveLinks: true,
				init: function() {
					var myDropzone = this;
					document.getElementById("formsubmit").addEventListener("click", function(e) {
							var showme = myDropzone.files;
							if (showme==""){
								alert("Please choose file to upload");
							}
						   // var validFlag = $("#UPLOADEXCEL").valid();
							$("#aPurchaseType").val('20');
							e.preventDefault();
							e.stopPropagation();
							myDropzone.processQueue();
					});
					this.on("sendingmultiple", function() {
					});
					this.on("success", function(files, response) {
						console.log(response);
						var jsonRes = $.parseJSON(response);
						if(jsonRes.error != ""){
							alert(jsonRes.error);
							return;
						}
						var totalRows = jsonRes.totalRows;
					//	alert(totalRows);
						if(totalRows > 1){
							$("#formsubmit").attr("disabled", false);
							$("#myModal").modal({
								backdrop: 'static',
								keyboard: false
							});
							$('#myModal').modal('show');
							processData(totalRows);
						}else{
							alert("No data to process");
						}
					});
					this.on("errormultiple", function(files, response) {

					});
				  },
				  autoProcessQueue: false,
				  uploadMultiple: false,
				  parallelUploads: 100,
				  maxFiles: 1
			  };
		})
        </script>
    </body>

</html>
