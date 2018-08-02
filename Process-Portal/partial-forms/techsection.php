<div class="form-group"   id="techID">
    <label class="col-md-3 control-label">Assign To</label>
    <div class="col-md-4">
        <select name="EmployeeID" class="selectbox2 form-control">
        <option value="" disabled selected >Select A Name</option>
        <?php
            $getAllEmpArray = getAllempBYRole(6);
            foreach($getAllEmpArray as $singleLocation){
                if($singleLocation['identifier'] == $EmployeeID){
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
