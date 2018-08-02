<div class="form-group">
    <label class="col-md-3 control-label">Classification</label>
    <div class="col-md-4">
        <select name="classification" class="form-control">
        <option value="" selected disabled>Select classification</option>
        <?php
            foreach($MACHINEANDTOOLCLASS as $key=>$value){
				 $vals=$value[1];
                if($key == $classification){
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
