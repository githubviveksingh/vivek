<div class="form-group">
    <label class="col-md-3 control-label">Employee Type<span class="required" aria-required="true"> * </span></label>
    <div class="col-md-4">
        <select name="emptype" class="form-control">
		<option value="" selected disabled>Select Employee Type</option>
        <?php
            foreach($EMPTYPE as $key=>$value){
                if($key == $empType){
                    $selected = 'selected="selected"';
                }else{
                    $selected = '';
                }
        ?>
            <option value="<?php echo $key?>" <?php echo $selected;?>><?php echo $value;?></option>
        <?php }
        ?>
        </select>
    </div>
</div>
