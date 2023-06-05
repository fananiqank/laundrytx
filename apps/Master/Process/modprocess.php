
<?php
session_start();
include "../../../funlibs.php";
$con = new Database();

foreach($con->select("laundry_master_process a join laundry_master_type_process b on a.master_type_process = b.master_type_process_id","a.*,b.master_type_process_name","a.master_process_id = '$_GET[id]'") as $pro){}
//echo "select a.*,b.master_type_process_name from laundry_master_process a join laundry_master_type_process b on a.master_type_process = b.master_type_process_id where a.master_process_id = '$_GET[id]'"; 
?>
	<span class="separator"></span>
	<form class="form-user" id="formku" method="post" enctype="multipart/form-data">
		<div class="row">
           	<div class="col-md-12">
			
            <div class="col-md-12" style="padding: 1%;margin-bottom: 2%">
                    
        </div>
	
		<input id="getp" name="getp" value="<?=$_GET[p]?>" type="hidden" >
		<input id="getid" name="getid" value="<?=$_GET[id]?>" type="hidden" >
		<div class="col-md-12 col-lg-12">
			<div class="tabs">
				<div id="overview" class="tab-pane active">
					<div id="edit" class="tab-pane">
						<fieldset>
							<div class="form-group">
							 	<label class="col-md-4 col-lg-4 control-label" for="profileAddress">Name</label>
							 	<div class="col-md-8 col-lg-8">
							 		<b><?php echo $pro['master_process_name']; ?></b>
								</div>
								
							</div>
							<div class="form-group">
								<label class="col-md-4 col-lg-4 control-label" for="profileAddress">Type Process</label>
								<div class="col-md-8 col-lg-8" style="background-color: #FFFFFF" id="proc">
									<b><?php echo $pro['master_type_process_name']; ?></b>
								</div>
							  			
							</div>
							<div class="form-group">
								<label class="col-md-4 col-lg-4 control-label" for="profileAddress">Use Machine</label>
								<div class="col-md-3 col-lg-3" style="background-color: #FFFFFF" id="proc">
									<b><?php 
										if ($pro['master_process_usemachine'] == 1) {
											echo "Yes";
										} else {
											echo "No";
										}
										?>
									</b>
								</div>
							  	<div class="col-md-5 col-lg-5">&nbsp;</div>
							</div>
							<div class="form-group">
								<label class="col-md-4 col-lg-4 control-label" for="profileAddress">Use Multi Machine</label>
								<div class="col-md-3 col-lg-3" style="background-color: #FFFFFF" id="proc">
									<b><?php 
										if ($pro['master_process_usemultimachine'] == 1) {
											echo "Yes";
										} else {
											echo "No";
										}
										?>
									</b>
								</div>
							  	<div class="col-md-5 col-lg-5">&nbsp;</div>
							</div>
							<div class="form-group">
								<label class="col-md-4 col-lg-4 control-label" for="profileAddress">Split Lot</label>
								<div class="col-md-3 col-lg-3" style="background-color: #FFFFFF" id="proc">
									<b><?php 
										if ($pro['master_process_split_lot'] == 1) {
											echo "Yes";
										} else {
											echo "No";
										}
										?>
									</b>
								</div>
							  	<div class="col-md-5 col-lg-5">&nbsp;</div>
							</div>
							<div class="form-group">
								<label class="col-md-4 col-lg-4 control-label" for="profileAddress">Combine Lot</label>
								<div class="col-md-3 col-lg-3" style="background-color: #FFFFFF" id="proc">
									<b><?php 
										if ($pro['master_process_combine_lot'] == 1) {
											echo "Yes";
										} else {
											echo "No";
										}
										?>
									</b>
								</div>
							  	<div class="col-md-5 col-lg-5">&nbsp;</div>
							</div>

						</fieldset>	
					</div>
				</div>
			</div>
		</div>
		
		<!-- <div class="col-md-12" align="center" style="margin-top: 2%;">
			<a href="javascript:void(0)" id="btn-simpan" name="simpan" class="btn btn-primary" onclick="savecutting()">Submit</a>
		</div> -->
</form>
