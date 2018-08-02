<div class="form-group">
    <label class="col-md-3 control-label">Status</label>
    <div class="col-md-4">
        <select id="Status" class="selectbox form-control">
        <?php
     foreach($PURCHASESTATUS as $key=>$value){
        $vals=$value[1];
                if($key == $Status){
                    $selected = 'selected="selected"';
                }else{
                    $selected = "";
                }
        ?>
            <option value="<?php echo $key?>" <?php echo $selected;?>><?php echo $vals;?></option>
        <?php
    }
        ?>
        </select>
    </div>
</div>
