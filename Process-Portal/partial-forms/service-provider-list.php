<div class="form-group">
    <label class="col-md-3 control-label">Service Provider</label>
    <div class="col-md-4">
        <select name="serviceProvider" class="form-control">
        <option value="" selected disabled>Select Service Provider</option>
        <?php
            foreach($SIMSERPROVIDER as $key=>$value){
				 $vals=$value[1];
                if($key == $serviceProvider){
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
