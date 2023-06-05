<?php
if($_GET['id'] != ''){
session_start();
include "../../../funlibs.php";
$con = new Database;

	$jenis = 'edit';
} else {
	$jenis = 'simpan';
}
	
	foreach ($con->select("laundry_master_process","*","master_process_id = '$_GET[id]'") as $mprocess) {}

?>
<input name="jenis" id="jenis" value="<?=$jenis?>" type="hidden" />
<input name="kode" id="kode" value="<?=$_GET[id]?>" type="hidden" />
<div class="form-group">
	<label class="col-sm-12 control-label"><b>Process Name</b><span class="required">*</span></label>
	<div class="col-sm-12">
		<input type="text" name="name" id="name" class="form-control" value="<?=$mprocess[master_process_name]?>" onkeypress="return allvalscript(event)" required/>
	</div>
</div>
<div class="form-group">
	<label class="col-sm-12 control-label"><b>Type Process</b><span class="required">*</span></label>
	<div class="col-sm-12">
		<select data-plugin-selectTwo class="form-control populate"  placeholder="None Selected" name="typeprocess" id="typeprocess" required>
			<option value="4" <?php if($mprocess['master_type_process'] == 4){echo "selected";}else{echo "";} ?>>Dry Process</option>
			<option value="5" <?php if($mprocess['master_type_process'] == 5){echo "selected";}else{echo "";} ?>>Wet Process</option>															
		</select>
	</div>
</div>
<div class="form-group">
	<label class="col-sm-12 control-label"><b>Use Machine</b><span class="required">*</span></label>
	<div class="col-sm-12">
		&emsp;
		<input type="radio" id="yesuse" name="usemachine" value="1" <?php if($mprocess['master_process_usemachine'] == 1){echo "checked";}else{echo "";} ?>> Yes
		&emsp;
		<input type="radio" id="nouse" name="usemachine" value="0" <?php if($mprocess['master_process_usemachine'] == 0 && $_GET['id']){echo "checked";}else{echo "";} ?>> No
	</div>
</div>
<div class="form-group">
	<label class="col-sm-12 control-label"><b>Multi Machine</b><span class="required">*</span></label>
	<div class="col-sm-12">
		&emsp;
		<input type="radio" id="yesuse" name="usemultimachine" value="1" <?php if($mprocess['master_process_usemultimachine'] == 1){echo "checked";}else{echo "";} ?>> Yes
		&emsp;
		<input type="radio" id="nouse" name="usemultimachine" value="0" <?php if($mprocess['master_process_usemultimachine'] == 0 && $_GET['id']){echo "checked";}else{echo "";} ?>> No
	</div>
</div>
<div class="form-group">
	<label class="col-sm-12 control-label"><b>Split Lot</b><span class="required">*</span></label>
	<div class="col-sm-12">
		&emsp;
		<input type="radio" id="yesuse" name="usesplit" value="1" <?php if($mprocess['master_process_split_lot'] == 1){echo "checked";}else{echo "";} ?>> Yes
		&emsp;
		<input type="radio" id="nouse" name="usesplit" value="0" <?php if($mprocess['master_process_split_lot'] == 0 && $_GET['id']){echo "checked";}else{echo "";} ?>> No
	</div>
</div>
<div class="form-group">
	<label class="col-sm-12 control-label"><b>Combine Lot</b><span class="required">*</span></label>
	<div class="col-sm-12">
		&emsp;
		<input type="radio" id="yesuse" name="usecombine" value="1" <?php if($mprocess['master_process_combine_lot'] == 1){echo "checked";}else{echo "";} ?>> Yes
		&emsp;
		<input type="radio" id="nouse" name="usecombine" value="0" <?php if($mprocess['master_process_combine_lot'] == 0 && $_GET['id']){echo "checked";}else{echo "";} ?>> No
	</div>
</div>

<?php if($_GET['id'] != '') { ?>
	<div class="form-group">
		<label class="col-sm-6 control-label"><b>Status</b></label>
		<div class="col-sm-6">
				<label class="switch">
  					<input type="checkbox" id="status_process" name="status_process" value="1" <?php if($mprocess['master_process_status'] == 1){echo "checked";} ?>  /> 
  					<span class="slider round"></span>
        		</label>
		</div>
	</div> 
<?php } ?>