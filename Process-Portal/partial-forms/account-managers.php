<div class="form-group">
    <label class="col-md-3 control-label">Account Manager<span class="required" aria-required="true"> * </span></label>
    <div class="col-md-4">
        <select name="EMPID" class="selectbox form-control">
        <option value="" selected disabled>Select Account Manager</option>
        <?php
            $getAllEmpArray = getAllEmp();
            foreach($getAllEmpArray as $singleLocation){
                if($singleLocation['identifier'] == $EMPID){
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
