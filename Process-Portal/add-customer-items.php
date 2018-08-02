<?php
include("includes/check_session.php");
include("includes/header.php");
$activeHref = "customers.php";
$empID = $_GET['empid'];
if(isset($_POST['submit'])){

$no = count($_POST['devicetype']);
   for ($i=0; $i <$no ; $i++) { 
   $contentArray = array();
   $devicetype=$_POST['devicetype'][$i];
   if($devicetype!=""){
	$contentArray['devicetype']=$devicetype;
		$itemID=$_POST['itemID'][$i];
		if($itemID!=""){
		$contentArray['itemID']=$itemID;
		}
		if($devicetype=='301')
		{
			$simID=$_POST['simID'][$i];
			if($simID!=""){
			$contentArray['simID']=$simID;
			}
			$vehicleNo=$_POST['vehicleNo'][$i];
			if($vehicleNo!=""){
			$contentArray['vehicleNo']=$vehicleNo;
			}
		}		
		$contentArray['customerID']=$_GET['empid'];
		//var_dump($contentArray);
		//die();
		
		$id = addData("tblCustomerItems", $contentArray);
	}
   
   }
       $_SESSION["success"] = "Customer Item Details Added Successfully.";
        header("Location:add-customer-items.php?empid=".$_GET['empid']);   
}
include("includes/html-header.php");
?>
<style>
.select2{
	width:100% !important;
}

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
                             <div class="col-md-12 tabbable-line boxless tabbable-reversed tab-content tab_0">
                                <div class="portlet box yellow">
                                    <div class="portlet-title">
                                        <div class="caption">
                                            <i class="fa fa-gift"></i><?php echo $_GET['empid'];?>  :
                                        </div>
                                    </div>
                                    <div class="portlet-body form">
                                        <form class="form-horizontal" id="addEmp" method="post" action="">
                                        <?php
                                            if(isset($_SESSION["success"])){?>
                                                <div class="alert alert-success">
                                                    <button class="close" data-close="alert"></button>
                                                    <span><?php echo $_SESSION["success"];?></span>
                                                </div>
                                            <?php unset($_SESSION["success"]);}
                                            ?>
                                            <?php
                                            if(isset($_SESSION["error"])){?>
                                                <div class="alert alert-danger">
                                                    <button class="close" data-close="alert"></button>
                                                    <span><?php echo $_SESSION["error"];?></span>
                                                </div>
                                            <?php unset($_SESSION["error"]);}
                                            ?>
                                            <input type="hidden" name="empID" value="<?php echo $empID;?>" id="empID">
                                            <div class="form-body" id="containerItems">
                                                <div class="form-group">
													<div class="col-md-2">
													<select name="devicetype[]" class="form-control selectbox" id="productType_0" data-id="0">
													<option value="" selected disabled>Select Device Type</option>
													<?php
													foreach($PRODUCTCAT as $key=>$value){
													$vals=$value[1];
													?>
													<option value="<?php echo $key?>" <?php echo $selected;?>><?php echo $vals;?></option>
													<?php }
													?>
													</select>
													</div>
													<div class="col-md-3" >
													<select name="itemID[]"  class="form-control selectboxItem" id="ItemsList_0" data-id="0">
													<option value="" selected disabled>Select Device</option>
													</select>
                                                    </div>
													<div class="col-md-3 hide" id="sim_0">
													<select name="simID[]"  class="form-control selectbox">
													<option value="" selected disabled>Select Sim IMSI</option>
														<?php
														$inventory = array();
														$query = "SELECT MSISDN,identifier FROM tblSim ";
														$count = 0;
														$inventory = fetchData($query, array(), $count);
														foreach($inventory as $inv)
														{
														if($inv['identifier'] == $simID){
														$selected = 'selected="selected"';
														}else{
														$selected = '';
														}
														?>
														<option value="<?php echo $inv['identifier'];?>" <?php echo $selected;?>><?php echo $inv['MSISDN'];?></option>
														<?php }
														?>
													</select>
                                                    </div>
													<div class="col-md-2 hide" id="vehicle_0">
													<input type="" class="form-control" placeholder="Vehicle No" name="vehicleNo[]">
                                                    </div>
													<div class="col-md-2" id="add_field_0"  data-id="0">
													<a href="javascript:void(0)" id="" class="btn green add_field"><span class='fa fa-plus'></span> More</a>
													 </div>
                                                </div>                                                  
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
							 <div class="col-md-12 tabbable-line boxless tabbable-reversed tab-content tab_0">
							 <div class="portlet box yellow">
                                    <div class="portlet-title">
                                        <div class="caption">
                                            <i class="fa fa-gift"></i>Items List
                                        </div>
                                    </div>
                                    <div class="portlet-body form">
									<table class="table table-bordered table-hover table-striped" id="dataTables" >
                                             <thead>
                                                 <tr>
                                                    <th>#</th>
                                                    <th>Device Type</th>
                                                    <th>Hardware</th>
                                                    <th>Sim</th>
                                                    <th>Vehicle No</th>                                                  
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
        <link href="assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css" rel="stylesheet" />
		
		 <link rel="stylesheet" href="assets/global/plugins/datatables/datatables.min.css">
        <link rel="stylesheet" href="assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css">
        <script src="assets/global/plugins/datatables/datatables.min.js"></script>
        <script src="assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js"></script>

        <script src="assets/global/plugins/select2/js/select2.full.min.js"></script>
        <script src="assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
        <script src="assets/pages/add-customerItem.js"></script>	        
		<script type="text/javascript">
		var counter = 0;
		$(function(){
			 $("#addEmp").on("click", ".add_field", function(){
				 var id = $(this).attr("data-id");
				 $("#add_field_0").addClass('hide');
				 $("#add_field_"+id).addClass('hide');
			counter += 1;
			$('#containerItems').append('<div class="form-group"><div class="col-md-2"><select name="devicetype[]" class="form-control selectbox" id="productType_' + counter + '" data-id="' + counter + '"><option>Select</option><?php
			foreach($PRODUCTCAT as $key=>$value){?><option value="<?php echo $key;?>"><?php echo $value['1'];?></option><?php } ?></select></div><div class="col-md-3" ><select name="itemID[]"  class="form-control selectboxItem" id="ItemsList_' + counter + '" data-id="0"><option value="" selected disabled>Select Item</option></select></div><div class="col-md-3 hide" id="sim_' + counter + '" ><select name="simID[]"  class="form-control selectbox"><option value="" selected disabled>Select Sim</option><?php $inventory = array();$query = "SELECT IMEI,identifier FROM tblSim ";	$count = 0;	$inventory = fetchData($query, array(), $count); foreach($inventory as $inv){	if($inv['identifier'] == $simID){		$selected = 'selected="selected"';			}else{		$selected = '';	}?><option value="<?php echo $inv['identifier'];?>" <?php echo $selected;?>><?php echo $inv['IMEI'];?></option>	<?php }?></select> </div><div class="col-md-2 hide" id="vehicle_' + counter + '" ><input type="" class="form-control" placeholder="Vehicle No" name="vehicleNo[]"> </div><div class="col-md-2" id=""><a href="javascript:void(0)" id="add_field_'+ counter +'" data-id="'+ counter +'" class="btn green add_field"><span class="fa fa-plus"></span> More</a></div></div>');
			$(".selectbox").select2();
			$(".selectboxItem").select2();
		});
		});
		</script>
        <script>
        $(document).ready(function(){	
			
            function fetch_itemList(str, id)
    		{
    			if (str == "") {
                    $("#ItemsList_"+id).html("");
    				return;
    			}else {
					if(str == "301"){
					$("#vehicle_"+id).removeClass("hide");
					$("#sim_"+id).removeClass("hide");
				    }else{
					$("#vehicle_"+id).addClass("hide");
					$("#sim_"+id).addClass("hide");
					}
                    $.ajax({
                        url: "ajax/fetchHrdByType.php",
                        type: "get",
                        data: {q: str},
                        success: function(data){
                            $("#ItemsList_"+id).html(data);
                        }
                    })
    			}
    		}			
            $(".selectbox").select2();
			$(".selectboxItem").select2();
            $("#doj").datepicker({
                format: "yyyy-mm-dd",
                autoclose: true
            });
            $("#dor").datepicker({
                format: "yyyy-mm-dd",
                autoclose: true
            });

            $("#addEmp").on("change", ".selectbox", function(){
                var id = $(this).attr("data-id");
                var selectedVal = $(this).val();
                fetch_itemList(selectedVal, id);
            })
        })
        $(document).ready(function(){
            $(".nav-item").each(function(){
                var href = $(this).find("a.nav-link").attr("href");
                console.log(href);
            });	
            $('#dataTables').DataTable({
                 "serverSide": true,
                 "processing": true,
				 "columnDefs": [ {
						"targets": [0,5],
						"orderable": false
						} ],
				 "order": [[ 1, "asc" ]],
                 "ajax": './ajax/customer-items-list.php?customer=<?php echo $_GET['empid']; ?>'
            });			
        })
        </script>
    </body>
</html>
