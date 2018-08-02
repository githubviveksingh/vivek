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
                                             <i class="fa fa-gift"></i>Purchase List
                                         </div>
                                         <div class="actions CustomAction">
										  <div class="btn-group">
                                              <a class="btn btn-primary btn-sm uppercase sbold" href="javascript:;" data-toggle="dropdown" data-hover="dropdown" data-close-others="true"> Classification
                                              <i class="fa fa-angle-down"></i>
                                              </a>
                                              <ul class="dropdown-menu pull-right custom_ul">
                                              <?php
                                                foreach ($PURCHASECLASS as $key => $value) {  ?>
                                                <li>
                                                  <label><INPUT TYPE="checkbox" class="checkClassifications" name="PurchaseClassification[]" data-column="PurchaseClassification"  value="<?= $key ?>" >&nbsp; <span style="color: black;"> <?= $value[0] ?></span>
                                                  </label>
                                                </li>
                                              <?php } ?>
                                              </ul>
                                            </div>
											<div class="btn-group">
                                              <a class="btn btn-primary btn-sm uppercase sbold" href="javascript:;" data-toggle="dropdown" data-hover="dropdown" data-close-others="true"> Status
                                              <i class="fa fa-angle-down"></i>
                                              </a>
                                              <ul class="dropdown-menu pull-right custom_ul">
                                              <?php
                                                foreach ($PURCHASESTATUS as $key => $value) {  ?>
                                                <li>
                                                  <label>
                                                  <INPUT TYPE="checkbox" class="checkStatus" name="Status[]" data-column="Status"  value="<?= $key ?>" >&nbsp;<span style="color: black;"> <?= $value[0] ?></span>
                                                </label>
                                                </li>
                                              <?php } ?>
                                              </ul>
                                            </div>
                                             <a class="btn green" href="add-purchase.php">
                                                 <i class="fa fa-plus"></i>Add Purchase
                                             </a>
											<a class="btn green" href="/download-sim-inventory.php?tag=PURCHASE">
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
                                                    <th>PO Reference</th>
                                                    <th>Classification</th>
                                                    <th>Quantity</th>
                                                    <!--<th>Delivery Challan</th>-->
                                                    <th>Supplier</th>
                                                    <th>Status</th>
													<th>Created On</th>
													<th>Added By</th>
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

        <div class="modal fade" id="STATUS_413" role="dialog">
            <div class="modal-dialog">

              <!-- Modal content-->
              <div class="modal-content">
                <div class="modal-header">
                  <h4 class="modal-title" id="STATUS_H_413"></h4>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal" id="STATUS_FORM_413" method="post" action="">
                        <input type="hidden" name="current_status" id="current_status_413" value="">
                        <input type="hidden" name="purchaseIdentifier" id="purchaseIdentifier_413" value="">
                        <input type="hidden" name="purchaseStatus" id="purchaseStatus_413" value="">
                        <div class="form-body">
                            <!--<div class="form-group">
                                <label class="col-md-3 control-label">Date of Delivery</label>
                                <div class="col-md-6">
                                    <input class="form-control" placeholder="Delivery Date" type="text" id="deliveryDate" name="dod" value="">
                                </div>
                            </div>-->
                            <div class="form-group">
                                <label class="col-md-3 control-label">Delivery Challan (Optional)</label>
                                <div class="col-md-6">
                                    <input class="form-control" placeholder="Delivery Challan" type="text" name="challan" value="">
                                </div>
                            </div>
                            <?php //include("partial-forms/delivery-location.php");?>
                        </div>

                    </form>
                </div>
                <div class="modal-footer">
                    <a href="javascript:void(0);" class="btn btn-primary submitModelForm" form-id="STATUS_FORM_413">Submit</a>
                    <a href="javascript:;" class="btn btn-default pull-right closseModal">Close</a>
                </div>
              </div>

            </div>
        </div>

        <div class="modal fade" id="STATUS_414" role="dialog">
            <div class="modal-dialog modal-lg" style="width:80% !important;">

              <!-- Modal content-->
              <div class="modal-content">
                <div class="modal-header">
                  <h4 class="modal-title" id="STATUS_H_414"></h4>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal" id="STATUS_FORM_414" method="post" action="">
                        <input type="hidden" name="purchaseIdentifier" id="purchaseIdentifier_414" value="">
                        <input type="hidden" name="purchaseStatus" id="purchaseStatus_414" value="">
                        <div class="form-body">
                            <!--<div class="form-group">
                                <label class="col-md-3 control-label">Purpose</label>
                                <div class="col-md-4">
                                    <select class="select2 form-control" id="purpose" name="purpose" placeholder="Choose Purpose">
                                         <option></option>
                                         <?php
                                         foreach($PURPOSE as $key=>$itemCons){?>
                                         <option value="<?php echo $key;?>"><?php echo $itemCons." (".$key.")"?></option>
                                         <?php }
                                         ?>
                                     </select>
                                </div>
                            </div>-->
                            <div class="form-group">
                                <label class="col-md-3 control-label">Date IN</label>
                                <div class="col-md-6">
                                    <input class="form-control" placeholder="Delivery Date" type="text" id="dDate" name="dDate" value="<?php echo date("Y-m-d");?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">Delivery Challan ID</label>
                                <div class="col-md-6">
                                    <input class="form-control" placeholder="Delivery Challan" type="text" name="dChallan" value="" id="dChallan">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">Courier No. IN</label>
                                <div class="col-md-6">
                                    <input class="form-control" placeholder="Courier No. IN" type="text" name="cno" value="" id="dCourierno">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">IN Mode</label>
                                <div class="col-md-4">
                                    <select class="select2 form-control" id="inMode" name="inMode" placeholder="Choose IN Mode" id="dInMode">
                                         <option></option>
                                         <?php
                                         foreach($INOUTMODE as $key=>$itemCons){?>
                                         <option value="<?php echo $key;?>"><?php echo $itemCons." (".$key.")"?></option>
                                         <?php }
                                         ?>
                                     </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">Eway BillID</label>
                                <div class="col-md-6">
                                    <input class="form-control" placeholder="Eway BillID" type="text" name="eway" value="" id="dEway">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">Purchase Type</label>
                                <div class="col-md-4">
                                    <select class="select2 form-control" id="fileTypes" name="purType" placeholder="Choose Purchase Type">
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
                        <input type="hidden" name="purchaseIdentifier" id="apurchaseIdentifier" value="">
                        <input type="hidden" name="current_status" id="current_status_414" value="">
                        <input type="hidden" name="purchaseStatus" id="apurchaseStatus" value="">
                        <input type="hidden" value="" name="purpose" id="aPurpose">
                        <input type="hidden" value="" name="datein" id="aDatein">
                        <input type="hidden" value="" name="challanid" id="aChallanid">
                        <input type="hidden" value="" name="courierno" id="aCourierno">
                        <input type="hidden" value="" name="inmode" id="aInmode">
                        <input type="hidden" value="" name="ewayid" id="aEwayid">
                        <input type="hidden" value="" name="purchaseType" id="aPurchaseType">
                        <div class="dropzone-previews"></div>
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
                    <a href="javascript:void(0);" class="btn btn-primary" id="formsubmit" form-id="STATUS_FORM_414">Submit</a>
                    <a href="javascript:;" class="btn btn-default pull-right closseModal" id="CloseModalTrasit">Close</a>
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
        </div>


        <div class="modal fade" id="OTHER_STATUS" role="dialog">
            <div class="modal-dialog">
              <!-- Modal content-->
              <div class="modal-content">
                <div class="modal-header">
                  <h4 class="modal-title" id="OTHER_STATUS_H"></h4>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="purchase_ID" value="">
                    <input type="hidden" id="purchase_status" value="">
                    <input type="hidden" id="current_status_other" value="">
                    <p class="model-body-h"></p>
                </div>
                <div class="modal-footer">
                    <a href="javascript:void(0);" class="btn btn-primary modal_yes" form-id="OTHER_STATUS">YES</a>
                    <a href="javascript:;" class="btn btn-default pull-right closseModal">No</a>
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

        <link href="assets/global/plugins/select2/css/select2.min.css" rel="stylesheet" />
        <link href="assets/global/plugins/select2/css/select2-bootstrap.min.css" rel="stylesheet" />
        <link href="assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css" rel="stylesheet" />
        <script src="assets/global/plugins/select2/js/select2.full.min.js"></script>
        <script src="assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
        <link href="assets/global/plugins/dropzone/dropzone.min.css" rel="stylesheet" />
        <script src="assets/global/plugins/dropzone/dropzone.min.js"></script>

        <script>
        var purchaseStatuses = <?php echo json_encode($PURCHASESTATUS);?>;
		var oSearch = "";
		<?php if(isset($_GET['sSearch'])){?>
               oSearch = {"sSearch": "<?php echo $_GET[sSearch];?>"};
		<?php }?>
        $(document).ready(function(){
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
            });
			
        })
        </script>

        <script src="assets/pages/purchase.js"></script>
    </body>

</html>
