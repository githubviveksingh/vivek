<?php
include("includes/check_session.php");
include("includes/header.php");
echo $_SERVER['PHP_SELF'];
die();
$activeHref = "inventory.php";

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
                                             <i class="fa fa-gift"></i>Upload Purchases
                                         </div>
                                         <div class="actions">

                                         </div>
                                     </div>
                                     <div class="portlet-body">
                                        <form class="form-horizontal" id="addSupp" method="post" action="">
                                            <div class="form-body">
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label">Purchase ID</label>
                                                    <div class="col-md-4">
                                                        <input class="form-control" placeholder="Enter  Purchase ID" type="text" id="PurchaseID" value="<?php echo $PurchaseID;?>">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label">PO Reference</label>
                                                    <div class="col-md-4">
                                                        <input class="form-control" placeholder="Enter  PO Reference" type="text" id="POReference" value="<?php echo $POReference;?>">
                                                    </div>
                                                </div>
                                                <?php include("partial-forms/purchase-classification.php"); ?>
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label">Quantity</label>
                                                    <div class="col-md-4">
                                                        <input class="form-control" placeholder="Enter Quantity" type="text" id="Quantity" value="<?php echo $Quantity;?>">
                                                    </div>
                                                </div>

                                                 <div class="form-group">
                                                    <label class="col-md-3 control-label">Date Of Delivery</label>
                                                    <div class="col-md-4">
                                                        <input class="form-control" placeholder="Enter Date Of Delivery" type="text" name="DateOfDelivery" id="DateOfDelivery" value="<?php echo $DateOfDelivery;?>">
                                                    </div>
                                                </div>
                                                 <div class="form-group">
                                                    <label class="col-md-3 control-label">Delivery Challan</label>
                                                    <div class="col-md-4">
                                                        <input class="form-control" placeholder="Enter Delivery Challan" type="text" id="DeliveryChallan" value="<?php echo $DeliveryChallan;?>">
                                                    </div>
                                                </div>
                                                 <?php include("partial-forms/delivery-location.php");?>
                                                 <?php include("partial-forms/employees-list.php");?>
                                                  <?php include("partial-forms/purchase-status.php");?>
                                                  <?php include("partial-forms/suppliers-list.php");?>
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label">Type</label>
                                                    <div class="col-md-4">
                                                        <select class="select2 form-control" id="fileTypes" placeholder="Choose Upload Type">
                                                 <option></option>
                                                 <option value="machine">Machine & Tools</option>
                                                 <option value="hardware">Device Hardware</option>
                                                 <option value="ITEM_SIM">SIM</option>
                                             </select>
                                                    </div>
                                                </div>

                                            </div>
                                        </form>
                                        <form id="my-awesome-dropzone" class="dropzone" action="ajax/upload-excel.php" method="post">
                                            <input type="hidden" id="filetype" name="filetype">
                                            <input type="hidden" id="PurchaseIDs" name="PurchaseIDs">
                                            <input type="hidden" id="POReferences" name="POReferences">
                                            <input type="hidden" id="PurchaseClassifications" name="PurchaseClassifications">
                                            <input type="hidden" id="Quantitys" name="Quantitys">
                                            <input type="hidden" id="DateOfDeliverys" name="DateOfDeliverys">
                                            <input type="hidden" id="DeliveryChallans" name="DeliveryChallans">
                                            <input type="hidden" id="DeliveryLocations" name="DeliveryLocations">
                                            <input type="hidden" id="CollectedBys" name="CollectedBys">
                                            <input type="hidden" id="Statuss" name="Statuss">
                                            <input type="hidden" id="SupplierIDs" name="SupplierIDs">
                                            <div class="dropzone-previews"></div> <!-- this is were the previews should be shown. -->
                                        </form>
                                        <a class="btn btn-success" style="margin-top:10px;" href="javascript:void(0);" id="formsubmit">Submit</a>
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

        <?php
            include("includes/common_js.php");
        ?>
        <link href="assets/global/plugins/select2/css/select2.min.css" rel="stylesheet" />
        <link href="assets/global/plugins/select2/css/select2-bootstrap.min.css" rel="stylesheet" />
        <link href="assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css" rel="stylesheet" />
        <script src="assets/global/plugins/select2/js/select2.full.min.js"></script>
         <script src="assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
        <script>
        $(document).ready(function(){
            $(".select2").select2({
                placeholder: "Choose Upload Type"
            });
             $("#DateOfDelivery").datepicker({
                format: "yyyy-mm-dd",
                autoclose: true
            });
        })
        </script>

        <link href="assets/global/plugins/dropzone/dropzone.min.css" rel="stylesheet" />
        <script src="assets/global/plugins/dropzone/dropzone.min.js"></script>
        <script>
        function processData(totalRows){
            for(var i=2; i<=totalRows;i++){
                $.ajax({
                    type:"post",
                    data:{rowNumber:i},
                    url:"ajax/process-row.php",
                    success:function(data){
                        var perc = 100;
                        var jsonData = $.parseJSON(data);

                        var end = jsonData.end;
                        var rowNumber = jsonData.rowNumber;
                         var filepath = jsonData.filepath;
                     //  alert(filepath);
                        if(totalRows != "0"){
                            perc = (rowNumber-1)*100/totalRows;
                            var percent = perc+"%";
                            $("#progress-bar-excel").css("width", percent);
                        }
                        if(end != "1"){
                            console.log(perc);
                            //processData(rowNumber, fileName);
                        }else{
                            i = totalRows+1;
                        }
                    },
                    async:false
                });
            }
    		$("#myModal").modal("hide");
    		location.reload(true);
        }
        $(document).ready(function(){
            Dropzone.options.myAwesomeDropzone = {
                addRemoveLinks: true,
                init: function() {
                    var myDropzone = this;
                    document.getElementById("formsubmit").addEventListener("click", function(e) {
                        $("#formsubmit").attr("disabled", true);
                        if($("#fileTypes").val() != ""){
                            var showme = myDropzone.files;
                            if (showme==""){
                                alert("Please choose file to upload");
                                $("#formsubmit").attr("disabled", false);
                            }else{
                                $("#filetype").val($("#fileTypes").val());
                                $("#PurchaseIDs").val($("#PurchaseID").val());
                                $("#POReferences").val($("#POReference").val());
                                $("#PurchaseClassifications").val($("#PurchaseClassification").val());
                                $("#Quantitys").val($("#Quantity").val());
                                $("#DateOfDeliverys").val($("#DateOfDelivery").val())
                                $("#DeliveryChallans").val($("#DeliveryChallan").val());
                                $("#DeliveryLocations").val($("#DeliveryLocation").val());
                                $("#CollectedBys").val($("#CollectedBy").val());
                                $("#Statuss").val($("#Status").val());
                                $("#SupplierIDs").val($("#SupplierID").val());
                            }
                            e.preventDefault();
                            e.stopPropagation();
                            myDropzone.processQueue();
                        }else{
                            alert("Please Choose Upload Type");
                            $("#formsubmit").attr("disabled", false);
                        }
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
