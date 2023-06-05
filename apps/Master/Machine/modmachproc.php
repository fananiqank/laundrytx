
<?php
session_start();
include "../../../funlibs.php";
$con = new Database();

foreach($con->select("laundry_master_machine","machine_name,machine_category","machine_id = '$_GET[id]'") as $pro){}
	if ($pro['machine_category'] == 'Dry'){
		$typepro = '4';
	} else if ($pro['machine_category'] == 'Wet') {
		$typepro = '5';
	}
?>
<div class="col-lg-12 col-md-12">
	<div class="col-lg-6 col-md-6">
		<form class="form-user" id="formku" method="post" enctype="multipart/form-data">
			<input id="getp" name="getp" value="<?=$_GET[p]?>" type="hidden" >
			<input id="getid" name="getid" value="<?=$_GET[id]?>" type="hidden" >
			<input id="confprocess" name="confprocess" value="" type="hidden" >
			<input id="machineid" name="machineid" value="<?=$_GET[id]?>" type="hidden" >
				<fieldset>
					<div class="form-group">
					 	<label class="col-md-4 col-lg-4 control-label" for="profileAddress">Machine</label>
					 	<div class="col-md-8 col-lg-8">
					 		<b><?php echo $pro['machine_name']; ?></b>
						</div>
						
					</div>
					<div class="form-group">
						<label class="col-md-4 col-lg-4 control-label" for="profileAddress">Type</label>
						<div class="col-md-8 col-lg-8" style="background-color: #FFFFFF" id="proc">
							<b><?php echo $pro['machine_category']; ?></b>
						</div>
					  			
					</div>
					<div class="form-group">
						<label class="col-md-4 col-lg-4 control-label" for="profileAddress">Process</label>
						<div class="col-md-8 col-lg-8" style="background-color: #FFFFFF" id="tampilproc">
							<?php include "sourcemodprocess.php"; ?>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-4 col-lg-4 control-label" for="profileAddress">&nbsp;</label>
						<div class="col-md-8 col-lg-8" style="background-color: #FFFFFF" id="proc">
							<a href="javascript:void(0)" name="submit" id="submit" onclick="saveprocess('<?=$_GET[id]?>','<?=$typepro?>')" class="btn btn-primary">Submit</a>
							<a href="javascript:void(0)" name="res" id="res" onclick="resetprocess('<?=$_GET[id]?>','<?=$typepro?>')" class="btn btn-default">Reset</a>
						</div>
					  			
					</div>
				</fieldset>	
		</form>
	</div>
	<div class="col-lg=6 col-md-6" id="tampilmachproc">
		<?php include "sourcemodmachproc.php"; ?>
	</div>
</div>
