<div class="form-group">
    <label class="col-md-3 control-label">SupplierID</label>
    <div class="col-md-4">
        <select id="SupplierID" class="selectbox2 form-control" name="supplierID">
		<option selected disabled>Select Supplier</option>
		<option value="AddNew">Add New Supplier</option>
        <?php
            $getallsupp = getSuppliers();
            foreach($getallsupp as $singleLocation){
                if($singleLocation['identifier'] == $SupplierID){
                    $selected = 'selected="selected"';
                }else{
                    $selected = '';
                }
        ?>
            <option value="<?php echo $singleLocation['identifier']?>" <?php echo $selected;?>><?php echo ucfirst($singleLocation["name"]);?></option>
        <?php }
        ?>
        </select>
    </div>
</div>
