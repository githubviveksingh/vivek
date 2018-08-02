<?php
include("includes/check_session.php");
include("includes/header.php");
$activeHref = "purchase.php";
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
                                             <i class="fa fa-gift"></i>Add Purchases
                                         </div>
                                         <div class="actions">

                                         </div>
                                     </div>
                                     <div class="portlet-body">
                                        <form class="form-horizontal" id="addSupp" method="post" action="">
                                            <input type="hidden" id="PurchaseID" value="<?php echo $CPID=generatePurchaseID();?>">
                                            <input type="hidden" id="CPID" value="<?php echo $CPID=generatePurchaseID();?>">
                                            <div class="form-body">
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label">Purchase ID</label>
                                                    <div class="col-md-1">
                                                        <input  type="radio" class="radioCls" name="PID" value="1" checked>External
                                                    </div>
                                                     <div class="col-md-1">
                                                        <input  class="radioCls" type="radio" name="PID" value="2">Internal
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
                                                             <?php
                                                             foreach($ITEMCLASS as $key=>$itemCons){?>
                                                             <option value="<?php echo $itemCons[0];?>"><?php echo $itemCons[1]." (".$key.")"?></option>
                                                             <?php }
                                                             ?>
                                                         </select>
                                                    </div>
                                                    <div class="col-md-1"><a href="javascript:void(0);" class="btn btn-primary" id="downloadBtn">Download Sample</a></div>
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

                         <div class="row">
                             <div class="col-lg-12 col-xs-12 col-sm-12">
                                 <div class="portlet box red">
                                     <div class="portlet-title">
                                         <div class="caption">
                                             <i class="fa fa-gift"></i>Errors
                                         </div>
                                     </div>
                                     <div class="portlet-body">
                                         <table class="table table-bordered table-hover table-striped errorTable">
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
        var selectedArray;
        $(document).ready(function(){
            //To Generate PurchaseID
              $('.radioCls').click(function () {
               var selectedOption = $("input:radio[name=PID]:checked").val();
               if(selectedOption=="2")
                   {
                      $("#PurchaseID").val('1');
                   }else{
                    $("#PurchaseID").val($("#CPID").val());
                   }
              });
            $(".select2").select2({
                minimumResultsForSearch: -1,
                placeholder: "Choose Upload Type"
            });

            $(".employeeList").select2({
                placeholder: "Choose Employee"
            })

             $("#DateOfDelivery").datepicker({
                format: "yyyy-mm-dd",
                autoclose: true
            });

            $("#fileTypes").change(function(){
                var val = $(this).val();

                var colArray = {"ITEM_SIM": <?php echo json_encode($ITEM_SIM);?>, "ITEM_OFFICE": <?php echo json_encode($ITEM_OFFICE);?>,
                                "ITEM_HARDWARE": <?php echo json_encode($ITEM_HARDWARE);?>, "ITEM_MNT": <?php echo json_encode($ITEM_MNT);?>};
                if(typeof colArray[val] != 'undefined'){
                    $("#downloadBtn").attr("href", "download-sample.php?file="+val+".xlsx");
                    selectedArray = colArray[val];

                    var headerHtml = "";
                    for(var i = 0; i<selectedArray.length;i++){
                        headerHtml += "<th>"+selectedArray[i]+"</th>";
                    }

                    headerHtml = "<tr>"+headerHtml+"<th>Error</th></tr>";
                    $(".errorTable thead").html(headerHtml);
                }else{
                    $("#downloadBtn").attr("href", "javascrpt:void(0);");
                    console.error("Item does not exist");
                }


            })

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
                        console.log(jsonData);
                        var end = jsonData.end;
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
                            for(var i = 0;i<selectedArray.length;i++){
                                var charCode = 65+i;
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
                        $("#formsubmit").attr("disabled", true);
                        if($("#POReference").val() == ""){
                            alert("Please Provide PO Reference");
                            $("#formsubmit").attr("disabled", false);
                            return;
                        }
                        if($("#Quantity").val() == ""){
                            alert("Please Provide Quantity");
                            $("#formsubmit").attr("disabled", false);
                            return;
                        }
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
