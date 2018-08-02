<?php include"../includes/connect.php"; ?>
<?php include"../includes/functions.php"; ?>
<?php 
$cID=$_GET['customerID'];
$customerID=getCustomerDetails($cID)['accountID'];
if($_GET['q']=="2")
{
	?>
<div class="form-group" >
	<label class="col-md-3 control-label">Item<span class="required" aria-required="true"> * </span></label>
	<div class="col-md-4">
	<select name="itemID" class="selectbox form-control" id="">
	<option value="" selected disabled>Select Item</option>
	<?php 
$inventory = array();
    $query = "SELECT * FROM tblCustomerItems where customerID='$customerID'";
    $count = 0;
    $inventory = fetchData($query, array(), $count);
    foreach($inventory as $inv)
    { 
	if($inv['identifier'] == $itemID){
	$selected = 'selected="selected"';
	}else{
	$selected = '';
	}
	$itemHrd=getInventoryDetails($inv['itemID'],"tblHardware");
	$itemSIM=getInventoryDetails($inv['simID'],"tblSim");
    $hrd=$itemHrd['IMEI'];	
	if($itemHrd['IMEI']=="")
	{
		$hrd=$itemHrd['model'];	
	}
	if($inv['devicetype']=="301")
	{	    
		$item=$inv['vehicleNo'].' | '.$hrd.' | '.$itemSIM['MSISDN'];
	}else{
		$item=$hrd;
	}	
     ?>  	
   <option value="<?php echo $inv['identifier'];?>" <?php echo $selected;?>><?php echo $item; ?></option>
   <?php } 
?>
</option>
</select>
</div>
</div>
<?php } else{ }?>
        