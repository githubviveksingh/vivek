<div class="form-group">
    <label class="col-md-3 control-label">Employee Status<span class="required" aria-required="true"> * </span></label>
    <div class="col-md-4">
        <select name="empstatus" id="empStatus" class="form-control">
		<option value="" selected disabled>Select Employee Status</option>
        <?php
            foreach($EMPSTATUS as $key=>$value){
                if($key == $empStatus){
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
