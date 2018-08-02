<div class="form-group">
    <label class="col-md-3 control-label">Devices</label>
    <div class="col-md-4">
        <select name="hrdDevice" id="hrdDevice" class="selectboxNew form-control">
        <option value="" selected disabled>Select A Device</option>
        <?php
            $getAllEmpArray = getAllHardwareDevices();
            foreach($getAllEmpArray as $singleLocation){
                if($singleLocation['identifier'] == $hrdDevice){
                    $selected = 'selected="selected"';
                }else{
                    $selected = '';
                }
        ?>
            <option value="<?php echo $singleLocation['identifier']?>" <?php echo $selected;?>><?php echo $singleLocation["IMEI"];?>-<?php echo $singleLocation["model"];?></option>
        <?php }
        ?>
        </select>
    </div>
</div>
