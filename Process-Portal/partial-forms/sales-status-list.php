<div class="form-group">
    <label class="col-md-3 control-label">Status</label>
    <div class="col-md-4">
        <select name="Status" class="selectbox form-control">
        <option value="" selected disabled>Select Status</option>
        <?php
            foreach($SALESSTS as $key=>$value){
				 $vals=$value[1];
                if($key == $Status){
                    $selected = 'selected="selected"';
                }else{
                    $selected = '';
                }		
        ?>
            <option value="<?php echo $key?>" <?php echo $selected;?>><?php echo $vals;?></option>
        <?php }
        ?>
        </select>
    </div>
</div>
