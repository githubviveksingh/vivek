<?php
include("includes/check_session.php");
include("includes/header.php");
include("PHPMailer/class.phpmailer.php");
$activeHref = "purchase.php";

$id = isset($_GET['id'])?intval($_GET['id']):"0";
if(isset($_POST['submit'])){

//     echo "<pre>";
// print_r($_POST); exit();

    $identifier = $_POST['identifier'];
    $purchaseID = $_POST["PurchaseID"];
    $poReference = $_POST["poReference"];
    $PurchaseClassification = $_POST["PurchaseClassification"];
    $quantity = $_POST["quantity"];
    $SupplierID = $_POST['supplierID'];
    $purchaseType = $_POST["purchaseType"];

    $contentArray = array();
    $contentArray["PurchaseID"] = $purchaseID;
    $contentArray["POReference"] = $poReference;
    $contentArray["PurchaseClassification"] = $PurchaseClassification;
    $contentArray["Quantity"] = $quantity;
    
	if(!empty($_FILES['uploadLink']['name'])){
    move_uploaded_file($_FILES['uploadLink']['tmp_name'],'upload/popdf/'.$_FILES['uploadLink']['name']);
	$contentArray["uploadPO"] = $_FILES['uploadLink']['name'];
	}
    if($identifier == "0"){

        $empname=$_POST['empname'];
        if(isset($empname) && !empty($empname)){
            // Add in array supplier user informations..............
            $supplierArray              = array();
            $supplier_type              = $_POST['supplier_type'];
            if($supplier_type!=""){
            $supplier_type  = implode(',',$supplier_type);
            $supplierArray["supplier_type"] = $supplier_type;
            }
            $supplierArray["name"]      = $_POST['empname'];
            $supplierArray["email"]     = $_POST['email'];   
            $supplierArray["phone"]     = $_POST['phone'];
            $supplierArray["pan"]       = $_POST['pan'];
            $supplierArray["gst"]       = $_POST['gst'];
            $supplierArray["contact_phone"]     = $_POST['contact_phone'];
            $supplierArray["contact_name"]      = $_POST['contact_name'];
            $supplierArray["billCycle"]         = $_POST['billCycle'];
            $supplierArray["address"]           = $_POST['address'];     

            // Save supplier information in database............................
            $empID      = addData("tblSupplier", $supplierArray);
            //ADD AUDIT ENTRY
            $auditID    = generateAudit("SUP_ADD", $empID, $supplierArray, "");
        }
        // Check add new supplier..............................
        if(isset($empID) && $empID!='') {
            $contentArray["SupplierID"] = $empID;
        }else{
            $contentArray["SupplierID"] = $SupplierID;
        }


        $contentArray["status"] = "411";
        $addedID = addData("tblPurchase", $contentArray);
        if($addedID){
			/* Send Mail */
			$mailbox =file_get_contents("partial-forms/add-purchase-mail.html");
             if($_POST['PID']=='1')
               { $pType='External';
		       }else{
				 $pType='Internal';
			   }
	        $mailbox =str_replace("[PURCHASEID]" , $pType , $mailbox);
			$mailbox =str_replace("[POREFERENCE]" , $poReference , $mailbox);
			$mailbox =str_replace("[CLASSIFICATION]" , $PURCHASECLASS[$PurchaseClassification][1] , $mailbox);
			$mailbox =str_replace("[QUANTITY]" , $quantity , $mailbox);
			$supplierD=getSuppliers($SupplierID);
			$supplierName=ucwords($supplierD[0]['name']);
			$mailbox =str_replace("[SUPPLIERID]" , $supplierName , $mailbox);
			$empDetails=getEmpDetails($_SESSION['user']['identifier']);
			$empname=ucwords($empDetails['name']);
			$mailbox =str_replace("[EMPLOYEE]" , $empname , $mailbox);
			$mailbox =str_replace("[STATUS]" , $PURCHASESTATUS[411][1] , $mailbox);
			$protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
            $url = $protocol . $_SERVER['HTTP_HOST'];
			$target=$url.'/purchase.php?sSearch='.$poReference;
			$mailbox =str_replace("[TARGETLINK]" , $target , $mailbox);
			$TITLE='added a new purchase';
			$mailbox =str_replace("[TITLE]" , $TITLE , $mailbox);
			$LINKTITLE='Initialize / Cancel';
			$mailbox =str_replace("[LINKTITLE]" , $LINKTITLE , $mailbox);
			foreach($permission=$permissionsArray["inventory"]["411"]["notification"] as $role)
	          {
				$admins=getAllempBYRole($role);
				foreach($admins as $admin)
				{
					$emails[]=$admin['email'];
				}
            }
	         
			$emailTo=implode(',',$emails);
			$bcc=$emailTo;
			$subject="Process Portal- New Purchase Request For Initialization";
			$mailSender=mailSend($emailTo,$subject,$mailbox);
			/* Add Audit */
            $auditID = generateAudit("PUR_ADD", $addedID, $contentArray, "EMAIL");
            $_SESSION["success"] = "Purchase Added Successfully.";
            header("Location: purchase.php");
            exit();
        }else{
            $_SESSION["success"] = "Purchase could not be added. Please try again later.";
            header("Location: purchase.php");
            exit();
        }
    }else{
        updateData("tblPurchase", $contentArray, "identifier", $identifier);

        $auditData = array("old_values"=>$_SESSION['oldData'], "new_values"=>$contentArray);
        $auditID = generateAudit("PUR_UPD", $identifier, $auditData, "EMAIL");

        $_SESSION["success"] = "Purchase Updated Successfully.";
        header("Location: purchase.php");
        exit();
    }
}

if($id != "0"){
    $query = "SELECT * FROM tblPurchase where identifier=:id";
    $array = array(":id"=>$id);
    $count = 0;
    $data = fetchData($query, $array, $count);
    if($count){
        $data = $data[0];
        $_SESSION["oldData"] = $data;
        $purchaseID = $data["PurchaseID"];
        $PurchaseClassification = $data["PurchaseClassification"];
        $poReference = $data["POReference"];
        $quantity = $data["Quantity"];
        $SupplierID = $data["SupplierID"];
        $heading = "Update Purchase";
    }else{
        $_SESSION['error'] = "Purchase does not exist.";
        header("Location: purchase.php");
        exit();
    }
}
if($id == "0" && !isset($_POST['submit'])){
    $purchaseID = "";
    $poReference = "";
    $PurchaseClassification = "";
    $quantity = "";
    $SupplierID = "";
    $heading = "New Purchase";
}

include("includes/html-header.php");
?>
<style type="text/css">span.select2.select2-container{width: 100% !important;}</style>>
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
                                             <i class="fa fa-gift"></i>New Purchase
                                         </div>
                                         <div class="actions">

                                         </div>
                                     </div>
                                     <div class="portlet-body">
                                        <form class="form-horizontal" id="addSupp" method="post" action="" enctype="multipart/form-data">
                                            <input type="hidden" id="identifier" name="identifier" value="<?php echo $id;?>">
                                            <?php if($id=="0"){?>
                                                <input type="hidden" id="PurchaseID" value="<?php echo $CPID=generatePurchaseID();?>" name="PurchaseID">
                                                <input type="hidden" id="CPID" value="<?php echo $CPID=generatePurchaseID();?>">
                                            <?php }else{?>
                                                <input type="hidden" id="PurchaseID" value="<?php echo $purchaseID?>" name="PurchaseID">
                                            <?php }?>

                                            <div class="form-body">
                                                <?php if($id=="0"){?>
                                                    <div class="form-group">
                                                        <label class="col-md-3 control-label">Purchase ID</label>
                                                        <div class="col-md-1">
                                                            <input  type="radio" class="radioCls" name="PID" value="1" checked>External
                                                        </div>
                                                         <div class="col-md-1">
                                                            <input  class="radioCls" type="radio" name="PID" value="2">Internal
                                                        </div>
                                                    </div>
                                                <?php }?>
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label">PO Reference</label>
                                                    <div class="col-md-4">
                                                        <input class="form-control" placeholder="Enter  PO Reference" type="text" name="poReference" id="POReference" value="<?php echo $poReference;?>">
                                                    </div>
                                                </div>
                                                <?php include("partial-forms/purchase-classification.php"); ?>
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label">Quantity</label>
                                                    <div class="col-md-4">
                                                        <input class="form-control" placeholder="Enter Quantity" name="quantity" type="text" id="Quantity" value="<?php echo $quantity;?>">
                                                    </div>
                                                </div>
                                                <?php include("partial-forms/suppliers-list.php");?>												
												<div id="supplierForm">
												<h4>New Supplier Details.</h4>
                                                <?php include("partial-forms/supplier-type.php");?>
												<div class="form-group">
                                                    <label class="col-md-3 control-label">Name<span class="required" aria-required="true"> * </span></label>
                                                    <div class="col-md-4">
                                                        <input class="form-control" placeholder="Enter  Name" type="text" name="empname" value="<?php echo $name;?>">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label">Email<span class="required" aria-required="true"> * </span></label>
                                                    <div class="col-md-4">
                                                        <input class="form-control" placeholder="Enter  Email" type="text" id="emails" name="email" value="<?php echo $email;?>">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label">Phone<span class="required" aria-required="true"> * </span></label>
                                                    <div class="col-md-4">
                                                        <input class="form-control" placeholder="Enter  Contact No/Phone" type="text" name="phone" value="<?php echo $phone;?>">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label">GST<span class="required" aria-required="true"> * </span></label>
                                                    <div class="col-md-4">
                                                        <input class="form-control lowertoupper" placeholder="Enter GST" type="text" name="gst" id="gstNo" value="<?php echo $gst;?>">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label">PAN</label>
                                                    <div class="col-md-4">
                                                        <input class="form-control lowertoupper" placeholder="Enter PAN" type="text" id="panCard" name="pan" value="<?php echo $pan;?>">
                                                    </div>
                                                </div>
												<div class="form-group">
                                                    <label class="col-md-3 control-label">Contact Person Name</label>
                                                    <div class="col-md-4">
                                                        <input class="form-control" placeholder="Enter Person Name" type="text"  name="contact_name" value="<?php echo $contact_name;?>">
                                                    </div>
                                                </div>
												<div class="form-group">
                                                    <label class="col-md-3 control-label">Person Phone</label>
                                                    <div class="col-md-4">
                                                        <input class="form-control" placeholder="Enter Person Phone" type="text"  name="contact_phone" value="<?php echo $contact_phone;?>">
                                                    </div>
                                                </div>
                                                   <?php include("partial-forms/supp-billcycle.php");?>                                              
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label">Address<span class="required" aria-required="true"> * </span></label>
                                                    <div class="col-md-9">
                                                        <textarea class="form-control" placeholder="Enter Supplier Address" name="address"><?php echo $address;?></textarea>
                                                    </div>
                                                </div>
												</div>
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label">PO PDF<span class="required" aria-required="true"> * </span></label>
                                                    <div class="col-md-4">
                                                        <input id="exampleInputFile" type="file" name="uploadLink" accept="application/pdf" >
														<a href="/sample/<?php echo $uploadLink; ?>" target="_blank"><?php echo $uploadLink;?></a>
														<p class="help-block">Upload Only PDF. </p>
                                                    </div>
                                                </div>
                                                <!--<div class="form-group">
                                                    <label class="col-md-3 control-label">Purchase Type</label>
                                                    <div class="col-md-4">
                                                        <select class="select2 form-control" id="fileTypes" name="purchaseType" placeholder="Choose Upload Type">
                                                             <option></option>
                                                             <?php
                                                             foreach($ITEMCLASS as $key=>$itemCons){?>
                                                             <option value="<?php echo $itemCons[0];?>"><?php echo $itemCons[1]." (".$key.")"?></option>
                                                             <?php }
                                                             ?>
                                                         </select>
                                                    </div>
                                                </div>-->

                                            </div>
                                            <div class="form-actions fluid">
                                                <div class="row">
                                                    <div class="col-md-offset-3 col-md-9">
                                                        <input type="submit" class="btn green" name="submit" value="Submit">
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
                include("includes/footer.php");
            ?>
        </div>
        <?php
            include("includes/common_js.php");
        ?>
        <link href="assets/global/plugins/select2/css/select2.min.css" rel="stylesheet" />
        <link href="assets/global/plugins/select2/css/select2-bootstrap.min.css" rel="stylesheet" />
        <link href="assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css" rel="stylesheet" />
        <script src="assets/global/plugins/select2/js/select2.full.min.js"></script>
         <script src="assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
		 <script src="assets/pages/add-purchase.js"></script>
        <script>
        var selectedArray;
        $(document).ready(function(){

			$("#supplierForm").hide();
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
              $(".selectbox2").select2();      
            $(".employeeList").select2({
                placeholder: "Choose Employee"
            })

             $("#DateOfDelivery").datepicker({
                format: "yyyy-mm-dd",
                autoclose: true
            });
			$("#SupplierID").change( function(){
				var supplier=$(this).val();
				if(supplier=='AddNew'){
					$("#supplierForm").show();
				}else{
					$("#supplierForm").hide();
				}
			})
            $("#multiple").select2();
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
