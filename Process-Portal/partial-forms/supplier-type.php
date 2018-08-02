<div class="form-group"   id="techID">
    <label class="col-md-3 control-label">Supplier Type</label>
    <div class="col-md-4">
        <select name="supplier_type[]" id="multiple" class="form-control select2-multiple" multiple>		
        <?php
            $getAll = getSuppliersTypes();			
            foreach($getAll as $type){
                if(in_array($type['identifier'], $supplier_type)){
                    $selected = 'selected="selected"';
                }else{
                    $selected = '';
                }
        ?> <option value="<?php echo $type['identifier']?>" <?php echo $selected;?>><?php echo ucfirst($type["supplier_type"]);?></option>
        <?php }
        ?>
        </select>
    </div>
</div>
