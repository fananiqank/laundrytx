<?php
session_start();
include "../../../funlibs.php";
$con = new Database();
$selprocess = $con->select("laundry_master_process","master_process_name","master_process_id = '$_GET[id]'");
foreach ($selprocess as $process) {}

$selmasmach = $con->select("laundry_master_machine","machine_id,machine_name","lot_no = '$_GET[lot]'");

?>

<span class="separator"></span>

<form class="form-user" id="formku" method="post" action="content.php?p=<?=$_GET[p]?>_s" enctype="multipart/form-data">
	<div class="row">
       	<div class="col-md-12">
			<input id="machine_isi" name="machine_isi" value="<?php echo $_GET[id].'_'.$_GET[lot].'_'.$_GET[user]?>" type="hidden" >
			<input id="getp" name="getp" value="<?=$_GET[p]?>" type="hidden" >
			<input id="getd" name="getd" value="<?=$_GET[d]?>" type="hidden" >
			<input id="role_wo_id_mod" name="role_wo_id_mod" value="<?=$_GET[rolewoid]?>" type="hidden" >
		<div class="col-md-12 col-lg-12">
		<div class="tabs">
			<div id="overview" class="tab-pane active">
				<div id="edit" class="tab-pane">
					<fieldset>
						<div class="form-group">
						 	<label class="col-md-4 col-lg-4 control-label" for="profileAddress">Process</label>
						 	<div class="col-md-6 col-lg-6">
								<?php echo $process['master_process_name'];?>
							</div>
							<div class="col-md-2 col-lg-2">
								&nbsp;
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-4 col-lg-4 control-label" for="profileAddress">Machine</label>
							<div class="col-md-8 col-lg-8" style="background-color: #FFFFFF" id="proc">
							<?php include "sourcemachine.php" ?>
							</div>
						</div>
						
					</fieldset>	
				</div>
			</div>
		</div>
	</div>
	
</form>
	<!-- end: page -->
