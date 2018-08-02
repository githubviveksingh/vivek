<div class="form-group">
    <label class="col-md-3 control-label">Category</label>
    <div class="col-md-4">
        <select name="productCat" class="form-control">
        <option value="" selected disabled>Select Category</option>
        <?php
            foreach($PRODUCTCAT as $key=>$value){
				 $vals=$value[1];
                if($key == $productCat){
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
