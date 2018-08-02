<div class="form-group">
    <label class="col-md-3 control-label">Customer<span class="required" aria-required="true"> * </span></label>
    <div class="col-md-4">
        <select name="CustomerID" id="customerID" class="selectbox form-control">
        <option value="" selected disabled>Select Customer</option>
        <?php
            $getAllEmpArray = getAllCustomer();
            foreach($getAllEmpArray as $singleLocation){
                if($singleLocation['identifier'] == $CustomerID){
                    $selected = 'selected="selected"';
                }else{
                    $selected = '';
                }
        ?>
            <option value="<?php echo $singleLocation['identifier']?>" <?php echo $selected;?> address="<?php echo $singleLocation['address'];?>"><?php echo ucfirst($singleLocation["Name"]);?></option>
        <?php }
        ?>
        </select>
    </div>
	<?php if(basename($_SERVER['PHP_SELF'])=="add-support.php"){
	?>
	<div class="col-md-2"><a href="add-customer.php?page=<?php echo basename($_SERVER['PHP_SELF']);?>" class="btn green pull-right"><i class="fa fa-plus"></i> Customer</a></div>
	<?php } ?>
</div>
