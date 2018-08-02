<div class="form-group">
    <label class="col-md-3 control-label">Purchase Classification</label>
    <div class="col-md-4">
        <select name="PurchaseClassification" id="PurchaseClassification" class="selectbox form-control">
        <?php
     foreach($PURCHASECLASS as $key=>$value){
        $vals=$value[1];
                if($key == $PurchaseClassification){
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
