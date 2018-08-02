<div class="form-group">
    <label class="col-md-3 control-label">BillCycle<span class="required" aria-required="true"> * </span></label>
    <div class="col-md-4">
        <select name="billCycle" class="form-control">
		<option value="" selected disabled>Select BillCycle</option>
        <?php
            foreach($SUPPBILLCYL as $key=>$value){
                if($key == $billCycle){
                    $selected = 'selected="selected"';
                }else{
                    $selected = "";
                }
        ?>
            <option value="<?php echo $key?>" <?php echo $selected;?>><?php echo $value;?></option>
        <?php }
        ?>
        </select>
    </div>
</div>
