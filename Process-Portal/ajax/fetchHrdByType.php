<?php include"../includes/connect.php"; ?>
<?php include"../includes/functions.php"; ?>
<option value="" selected disabled>Select Device</option>
	<?php 
	$qa=$_GET['q'];
    $inventory = array();
    $query = "SELECT identifier,IMEI,model FROM tblHardware where productCat='$qa'";
    $count = 0;
    $inventory = fetchData($query, array(), $count);
    foreach($inventory as $inv)
    { 
     ?>  	
   <option value="<?php echo $inv['identifier'];?>"><?php echo $inv['IMEI'];?>-<?php echo $inv['model'];?></option>
    <?php
    }
?>