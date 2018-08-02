<div class="form-group">
    <label class="col-md-3 control-label">Classification<span class="required" aria-required="true"> * </span></label>
    <div class="col-md-4">
        <select name="Classification" class="form-control">
        <option value="" selected disabled>Select Classification</option>
        <?php
            foreach($SUPPORTCLASSIFICATION as $key=>$value){
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
