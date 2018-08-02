<div class="form-group">
    <label class="col-md-3 control-label">Status<span class="required" aria-required="true"> * </span></label>
    <div class="col-md-4">
        <select name="Status" id="SupportStatus" class="form-control" onchange="(this.value)">
        <option value="" selected disabled>Select Status</option>
        <?php
            foreach($SUPPORTSTS as $key=>$value){
				$vals=$value[1];
                if($key == $Status){
                    $selected = 'selected="selected"';
                }else{
                    $selected = '';
                }
				if($key=='431' && $_GET['supportID']!="")
				{
				  $selected = 'selected="selected"';
				}
				if($_GET['empid']=="" && $key=='432')
				{
				  $selected = 'selected="selected"';
				}	
                if($_GET['supportID']!="" && $key=='432')
				{
				  $selected = 'style="display:none';
				}				
				if($Status=='433' && ($key=='431' || $key=='432'))
				{
				  $selected = 'style="display:none"';
				}
        ?>
            <option value="<?php echo $key?>" <?php echo $selected;?>><?php echo $vals;?></option>
        <?php 
		}
        ?>
        </select>
    </div>
</div>
