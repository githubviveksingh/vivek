<div class="form-group">
    <label class="col-md-3 control-label">Support Type<span class="required" aria-required="true"> * </span></label>
    <div class="col-md-4">
        <select name="Classification" class="form-control" onchange="showCustomerItems(this.value)" id="SoftwareStatus">
        <option value="" selected disabled>Select Type</option>
        <?php
            foreach($SUPPORTTYPE as $key=>$value){
                if($key == $Classification){
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
