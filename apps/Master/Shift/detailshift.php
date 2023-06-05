<?php
if($_GET['id'] != ''){
session_start();
include "../../../funlibs.php";
$con = new Database;

	$jenis = 'edit';
} else {
	$jenis = 'simpan';
}
	
	foreach ($con->select("laundry_master_shift","*","shift_id = '$_GET[id]'") as $shift) {}

?>
<input name="jenis" id="jenis" value="<?=$jenis?>" type="hidden" />
<input name="kode" id="kode" value="<?=$_GET[id]?>" type="hidden" />
<div class="form-group">
	<label class="col-sm-12 control-label"><b>Shift Name</b><span class="required">*</span></label>
	<div class="col-sm-12">
		<input type="text" name="name" id="name" class="form-control" value="<?=$shift[shift_name]?>" onkeypress="return allvalscript(event)" required/>
	</div>
</div>
<div class="form-group">
	<label class="col-sm-12 control-label"><b>Start Time</b><span class="required">*</span></label>
	<div class="col-sm-12">
		<input type="text" name="starttime" id="starttime" data-plugin-timepicker class="form-control" data-plugin-options='{ "showMeridian": false }' value="<?=$shift[shift_time_start]?>" required>
	</div>
</div>
<div class="form-group">
	<label class="col-sm-12 control-label"><b>End Time</b><span class="required">*</span></label>
	<div class="col-sm-12">
		<input type="text" name="endtime" id="endtime" data-plugin-timepicker class="form-control" data-plugin-options='{ "showMeridian": false }'  value="<?=$shift[shift_time_end]?>" required>
	</div>
</div>


<?php if($_GET['id'] != '') { ?>
	<div class="form-group">
		<label class="col-sm-6 control-label"><b>Status</b></label>
		<div class="col-sm-6">
				<label class="switch">
  					<input type="checkbox" id="status_process" name="status_process" value="1" <?php if($shift['shift_status'] == 1){echo "checked";} ?>  /> 
  					<span class="slider round"></span>
        		</label>
		</div>
	</div> 
<?php } ?>