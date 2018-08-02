<?php include"../includes/connect.php"; ?>
<?php include"../includes/functions.php"; ?>
<?php 
$customerID=$_GET['customerID'];
if($_GET['q']=="202")
{
	?>
	<option value="" selected disabled>Select Item</option>
	<?php 
$inventory = array();
    $query = "SELECT MSISDN,itemID FROM tblSim where customerID='$customerID'";
    $count = 0;
    $inventory = fetchData($query, array(), $count);
    foreach($inventory as $inv)
    { 
	if($inv['itemID'] == $itemID){
	$selected = 'selected="selected"';
	}else{
	$selected = '';
	}
     ?>  	
   <option value="<?php echo $inv['itemID'];?>" <?php echo $selected;?>><?php echo $inv['itemID'];?>-<?php echo $inv['MSISDN'];?></option>
   <?php }
   
}
?>
<?php 
if($_GET['q']=="201")
{
	?>
	<option value="" selected disabled>Select Item</option>
	<?php 
$inventory = array();
    $query = "SELECT model,itemID FROM tblHardware where customerID='$customerID'";
    $count = 0;
    $inventory = fetchData($query, array(), $count);
    foreach($inventory as $inv)
    { 
	if($inv['itemID'] == $itemID){
	$selected = 'selected="selected"';
	}else{
	$selected = '';
	}
     ?>  	
   <option value="<?php echo $inv['itemID'];?>" <?php echo $selected;?>><?php echo $inv['itemID'];?>-<?php echo $inv['model'];?></option>
        <?php }
    }
?>
<?php 
if($_GET['q']=="203")
{
	?>
	<option value="" selected disabled>Select Item</option>
	<?php 
$inventory = array();
    $query = "SELECT name,itemID FROM tblMachine where customerID='$customerID'";
    $count = 0;
    $inventory = fetchData($query, array(), $count);
    foreach($inventory as $inv)
    { 
	if($inv['itemID'] == $itemID){
	$selected = 'selected="selected"';
	}else{
	$selected = '';
	}
     ?>  	
   <option value="<?php echo $inv['itemID'];?>" <?php echo $selected;?>><?php echo $inv['itemID'];?>-<?php echo $inv['name'];?></option>
        <?php }
    }
?>
<?php 
if($_GET['q']=="204")
{
	?>
	<option value="" selected disabled>Select Item</option>
	<?php 
$inventory = array();
    $query = "SELECT itemName,itemID FROM tblOfficeItem";
    $count = 0;
    $inventory = fetchData($query, array(), $count);
    foreach($inventory as $inv)
    { 
	if($inv['itemID'] == $itemID){
	$selected = 'selected="selected"';
	}else{
	$selected = '';
	}
     ?>  	
   <option value="<?php echo $inv['itemID'];?>" <?php echo $selected;?>><?php echo $inv['itemID'];?>-<?php echo $inv['itemName'];?></option>
        <?php }
    }
	$customer=getCustomerDetails($customerID);
	echo "[".$customer['address'];
?>

        