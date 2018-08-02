<div class="form-group">
    <label class="col-md-3 control-label">PO Reference</label>
    <div class="col-md-4">
        <select name="poReference" class="selectbox form-control">
        <option value="" selected disabled>Select PO Reference No</option>
        <?php
            $allPos = getAllPO();
            foreach($allPos as $podetail){
                if($podetail['identifier'] == $poReference){
                    $selected = 'selected="selected"';
                }else{
                    $selected = '';
                }
        ?>
        <option value="<?php echo $podetail['identifier']?>" <?php echo $selected;?>><?php echo $podetail["POID"];?></option>
        <?php }
        ?>
        </select>
    </div>
</div>
