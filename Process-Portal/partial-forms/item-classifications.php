<div class="form-group">
    <label class="col-md-3 control-label">Item Category</label>
    <div class="col-md-4">
        <select name="itemclassification" id="itemclassification" class="form-control" onchange="showinventory(this.value)">
       <option value="" selected disabled>Select Category</option>
        <?php
     foreach($ITEMCLASS as $key=>$value){
        $vals=$value[1];
                if($key == $itemclassification){
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
