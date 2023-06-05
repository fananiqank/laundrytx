<?php
if($_GET['id'] != ''){
session_start();
include "../../../funlibs.php";
$con = new Database;

	$jenis = 'edit';
} else {
	$jenis = 'simpan';
}
	
	foreach ($con->select("laundry_master_machine","*","machine_id = '$_GET[id]'") as $machine) {}
		//echo "select * from laundry_master_machine where machine_id = '$_GET[id]'";
?>
<input name="jenis" id="jenis" value="<?=$jenis?>" type="hidden" />
<input name="kode" id="kode" value="<?=$_GET[id]?>" type="hidden" />
<div class="form-group">
	<label class="col-sm-12 control-label"><b>Machine Name</b><span class="required">*</span></label>
	<div class="col-sm-12">
		<input type="text" name="name" id="name" class="form-control" value="<?=$machine[machine_name]?>" onkeypress="return allvalscript(event)" required/>
	</div>
</div>
<div class="form-group">
	<label class="col-sm-12 control-label"><b>Machine Category</b><span class="required">*</span></label>
	<div class="col-sm-12">
		<select data-plugin-selectTwo class="form-control populate"  placeholder="None Selected" name="category" id="category" onchange="checkcode(this.value)" required>
			<option value="">Choose</option>
			<option value="Dry" <?php if($machine['machine_category'] == 'Dry'){echo "selected";}else{echo "";} ?>>Dry Process</option>
			<option value="Wet" <?php if($machine['machine_category'] == 'Wet'){echo "selected";}else{echo "";} ?>>Wet Process</option>
			<option value="QC" <?php if($machine['machine_category'] == 'QC'){echo "selected";}else{echo "";} ?>>QC Table</option>															
		</select>
	</div>
</div>
<div class="form-group">
	<label class="col-sm-12 control-label"><b>Machine Code</b></label>
	<div class="col-sm-12">
		<input type="text" name="machine_code" id="machine_code" class="form-control" value="<?=$machine[machine_code]?>" onkeypress="return allvalscript(event)" readonly/>
	</div>
</div>
<div class="form-group">
	<label class="col-sm-12 control-label"><b>Machine Type</b></label>
	<div class="col-sm-12">
		<select data-plugin-selectTwo class="form-control populate"  placeholder="None Selected" name="machine_type_use" id="machine_type_use" required>
			<option value="">Choose</option>
			<option value="0" <?php if($machine['machine_type_use'] == '0'){echo "selected";}else{echo "";} ?>>Single Lot</option>
			<option value="1" <?php if($machine['machine_type_use'] == '1'){echo "selected";}else{echo "";} ?>>Multiple Lot (Max 5)</option>													
		</select>
	</div>
</div>
<?php if($_GET['id'] != '') { ?>
	<div class="form-group">
		<label class="col-sm-6 control-label"><b>Status</b></label>
		<div class="col-sm-6">
				<label class="switch">
  					<input type="checkbox" id="status_process" name="status_process" value="1" <?php if($machine['machine_status'] == 1){echo "checked";} ?>  /> 
  					<span class="slider round"></span>
        		</label>
		</div>
	</div> 
<?php } ?>