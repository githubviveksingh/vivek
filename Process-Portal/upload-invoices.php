<?php
include("includes/check_session.php");
include("includes/header.php");
$activeHref = "customers.php";
include("includes/html-header.php");
if(isset($_SESSION['history_link'])){
	unset($_SESSION["history_link"]);
}
?>
<style>
.scroller {    height: auto !important; }
.modal-dialog {  width: 1020px; }
.actions > .open {
    height: 172px !important;
}
.invoiceWidth{
	width: 606px;
}
.modal-footer {
    padding: 15px;
    text-align: center;
    border-top: 1px solid #e5e5e5;}

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
                                             <i class="fa fa-gift"></i>Invoice List
                                         </div>
										 <div class="actions">
                                            <div class="btn-group btn-group-devided">                                                
												 <!--  <a class="btn green" href="">
												            <i class="icon-cloud-download"></i> Download Excel
												 		</a>  -->
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
                                                    <th>Company</th>
                                                    <th>Customer</th>
                                                    <th>Invoice No</th>
                                                    <th>Invoice Date</th>
                                                    <th>Next Date</th>
                                                    <th>Status</th>	
                                                    <th>Amount</th>
													<th>Updated On</th>
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
			  Modal content
			  <div class="modal-content">
				<div class="modal-header">
				  <h4 class="modal-title" id="OTHER_STATUS_H">Upload Invoice. </h4>				  
				</div>
				<div class="modal-body">
				    <form id="my-awesome-dropzone" class="dropzone" action="ajax/upload-excel-invoice.php" method="post">
					    <input type="hidden" name="purchaseType" id="aPurchaseType" value="<?php echo getInvoiceID();?>">
						<div class="dropzone-previews"></div> this is were the previews should be shown.
					</form>
					<div class="modal fade" id="myModal" role="dialog">
            	        <div class="modal-dialog">

            	          Modal content
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

		<div id="responsiveModal" class="modal fade" tabindex="-1" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
						<h4 class="modal-title">Invoice Description</h4>
					</div>
					<div class="modal-body">
						<div class="scroller" style="height:300px" data-always-visible="1" data-rail-visible1="1">
							<div class="row">
								<div class="col-md-12">														
									<div id="invoice_details_list">							
									</div>						
								</div>						
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" data-dismiss="modal" class="btn dark btn-outline">Close</button>
					</div>
				</div>
			</div>
		</div>
	
		<!--  Generate new invoice popup   -->
		<div id="InvoiceModal" class="modal fade" tabindex="-1" aria-hidden="true">
			<div class="modal-dialog invoiceWidth">
				<div class="modal-content">
					<div class="modal-header">
						<h4 class="text-center"> Next invoice successfully generated. </h4>
					</div>
					<div class="modal-body">
						<div class="scroller" style="height:300px" data-always-visible="1" data-rail-visible1="1">
							<div class="row">
								<div class="col-md-12">														
									<div id="invoiceSuccess">
									  <h4 class="text-center"> Invoive No:- <b id="invoiceNo"></b></h4>
									</div>						
								</div>						
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<span id="downloadPdfLink"></span>
						<span id="closeLink"></span>
					</div>
				</div>
			</div>
		</div>

		<!--  Pay invoice amount   -->
		<div id="PaidModal" class="modal fade" tabindex="-1" aria-hidden="true">
			<div class="modal-dialog invoiceWidth">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
						<h4 class="text-center"> Invoice Payment Information </h4>
					</div>
					<div class="modal-body">
						<div class="scroller" style="height:300px" data-always-visible="1" data-rail-visible1="1">
							<div class="row">
								<div class="portlet-body form">
	                                <form class="form-horizontal" id="addSupp" method="post" action="ajax/save-invoice-paid-amount.php">
	                                    <input type="hidden" name="invoiceId" id="invoiceId">
	                                    <input type="hidden" name="cus_id" id="cusId">
	                                    <div class="form-body">
	                                        <div class="form-group">
	                                            <label class="col-md-3 control-label">Amount</label>
	                                            <div class="col-md-4">
	                                                <input class="form-control" id="totalAmount" placeholder="Enter Amount" type="text" name="amount">
	                                                <input class="form-control" id="totalMainAmount" type="hidden">
	                                            </div>
	                                        </div>
	                                        <div class="form-group">
	                                            <label class="col-md-3 control-label">Status</label>
	                                            <div class="col-md-4">
	                                            	<select name="status" class="form-control valid">
														<option value=2>Paid</option>
														<option value=1>Pending</option>
														<option value=0>Unpaid</option>
													</select> 
	                                            </div>
	                                        </div>                
	                                    </div>
	                                    <div class="form-actions fluid">
	                                        <div class="row">
	                                            <div class="col-md-offset-3 col-md-9">
	                                                <input type="submit" class="btn green" name="paidAmount" value="Save">
	                                                <button type="button" data-dismiss="modal" class="btn dark btn-outline">Cancel</button>
	                                            </div>
	                                        </div>
	                                    </div>
                                	</form>
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
						"targets": [0,5,9],
						"orderable": false
						} ],
				 "order": [[ 8, "desc" ]],
                "ajax": './ajax/invoices-list.php?customer=<?php  echo $_GET['customer'];?>',
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
				var colArray = {"INVOICE_EXL": <?php echo json_encode($INVOICE_EXL);?>};
				selectedArray = colArray['INVOICE_EXL'];
				var headerHtml = "";
				for(var i = 0; i<selectedArray.length;i++){
					headerHtml += "<th>"+selectedArray[i]+"</th>";
				}
				headerHtml = "<tr>"+headerHtml+"<th>Error</th></tr>";
				$(".errorTable thead").html(headerHtml);
			});	

			$("#dataTables").on("click", ".CustumA", function(){
				var dataID = $(this).attr("data-id");
				$.ajax({
		            type:"post",
		            data:{dataid:dataID},
		            url:"ajax/process-invoice-DescList.php",
		            success:function(data){
						$("#responsiveModal").modal('show');
						$('#invoice_details_list').html(data);
					}
				});	
	        });	

		/* Generate new invoice*/
			$("#dataTables").on("click", ".NewInvoice", function(){
				var dataID 	= $(this).attr("data-id");
				$.ajax({
		            type:"post",
		            data:{dataid:dataID},
		            url:"ajax/create-new-invoice.php",
		            success:function(data){
		            	var jsonData 	= $.parseJSON(data);
		            	var name 		= jsonData.invoice_no;
		            	var id 			= jsonData.invoice_id;
		            	var customerId 	= jsonData.customer_id;
		            	$('#InvoiceModal').modal({backdrop: 'static', keyboard: false});
						$("#InvoiceModal").modal('show');
						var link 		= '<a href="invoice_pdf.php?invoice_no='+id+'" class="btn btn-success">Download PDF</a>';
						var closelink 	= '<a href="upload-invoices.php?customer='+customerId+'" class="btn dark btn-outline">Close</a>';
						$('#downloadPdfLink').html(link);
						$('#closeLink').html(closelink);
						$('#invoiceNo').html(name);
					}
				});	
	        });	

        /* Pay invoice amount*/
			$("#dataTables").on("click", ".PaidAmount", function(){
				$("#PaidModal").modal('show');
				var dataID 	= $(this).attr("data-id");
				$.ajax({
	            type:"post",
	            data:{dataid:dataID},
	            url:"ajax/fetch-invoice-payment-info.php",
		            success:function(data){
		            	var jsonData 	= $.parseJSON(data);
		            	var status 		= jsonData.status;

		            	$('#totalAmount').val(jsonData.total_amount);
		            	$('#totalMainAmount').val(jsonData.total_amount);
						$('#invoiceId').val(jsonData.invoice_id);
						$('#cusId').val(jsonData.cus_id);	            	
					}
				});	
	        });	
        });
		function processData(totalRows){
				var i;
				for(i=2; i<=totalRows;i++){
				console.log("Normal: ");
				console.log(i);
				$.ajax({
					type:"post",
					data:{rowNumber:i},
					url:"ajax/process-row-invoice.php",
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
