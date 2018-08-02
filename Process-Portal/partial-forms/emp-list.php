<div class="form-group">
    <label class="col-md-3 control-label">Employee</label>
    <div class="col-md-4">
        <select name="EMPID" class="selectbox form-control">
        <option value="" selected disabled>Select A Employee</option>
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
