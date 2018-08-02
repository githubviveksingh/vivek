<div class="form-group">
    <label class="col-md-3 control-label">Delivery Location</label>
    <div class="col-md-6">
        <select id="DeliveryLocation" class="selectbox form-control" name="deliveryLocation">
            <option></option>
        <?php
            $locationsArray = getAllLocations();
            foreach($locationsArray as $singleLocation){
                if($singleLocation['identifier'] == $DeliveryLocation){
                    $selected = 'selected="selected"';
                }else{
                    $selected = '';
                }
        ?>
            <option value="<?php echo $singleLocation['identifier']?>" <?php echo $selected;?>><?php echo ucfirst($singleLocation["name"]);?></option>
        <?php }
        ?>
        </select>
    </div>
</div>
