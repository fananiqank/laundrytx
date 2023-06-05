<?php
session_start();
include "../../../funlibs.php";
$con = new Database();
?>
<span class="separator"></span>

	<div class="row">
       	<div class="col-md-12">
			<input id="machine_isi" name="machine_isi" value="<?php echo $_GET[id].'_'.$_GET[lot].'_'.$_GET[user]?>" type="hidden" >
			<input id="getp" name="getp" value="<?=$_GET[p]?>" type="hidden" >
			<input id="getd" name="getd" value="<?=$_GET[d]?>" type="hidden" >
		<div class="col-md-12 col-lg-12">
		<div class="tabs">
			<div id="overview" class="tab-pane active">
				<div id="edit" class="tab-pane">
					<fieldset>
						
						<div class="form-group">
						 	<label class="col-md-4 col-lg-4 control-label" for="profileAddress">Change Process</label>
						 	<div class="col-md-6 col-lg-6">
								 <select data-plugin-selectTwo class="form-control populate"  placeholder="None Selected" name="ccp" id="ccp">
									<option value='0'>Choose</option>
									<?php 

									foreach($con->select("laundry_master_process","master_process_id,master_process_name","master_type_process = $_GET[mastertypeprocessid]") as $cp) {
										echo "<option value='$cp[master_process_id]|$cp[master_process_name]'>$cp[master_process_name]</option>";
										}
									?>
								</select>
							</div>
							<div class="col-md-5 col-lg-5">
								&nbsp;
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-md-4 col-lg-4 control-label" for="profileAddress">&nbsp;</label>
							<div class="col-md-8 col-lg-8">
								<a href="javascript:void(0)" class="btn btn-primary" id="nextprocess" name="nextprocess" onclick="process_change(ccp.value)" data-dismiss="modal">Submit</a>
							</div>
						</div>
						<input type="hidden" id="qtylot" name="qtylot" value="<?=$qty?>">
					</fieldset>	
				</div>
			</div>
		</div>
	</div>
	