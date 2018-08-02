<div class="form-group">
    <label class="col-md-3 control-label">Collected By</label>
    <div class="col-md-4">
        <select id="CollectedBy" class="selectbox employeeList form-control" placeholder="Choose Employee">
            <option></option>
        <?php
            $getAllEmpArray = getAllEmp();
            foreach($getAllEmpArray as $singleLocation){
                if($singleLocation['identifier'] == $CollectedBy){
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
