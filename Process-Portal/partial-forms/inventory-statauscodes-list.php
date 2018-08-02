<div class="form-group">
    <label class="col-md-3 control-label">Status</label>
    <div class="col-md-4">
        <select name="statusCode"  id="InStatusCode" class="form-control" onchange="showinventoryfileds(this.value)">
        <option value="" selected disabled>Select Status</option>
        <?php
            foreach($STATUSCODE as $key=>$value){
				 $vals=$value[1];
                if($key == $statusCode){
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