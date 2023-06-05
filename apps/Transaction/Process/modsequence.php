<?php
session_start();
//echo $_SESSION['ID_PEG'];
include "../../../funlibs.php";
$con = new Database();
$seltypeseq = $con->select("laundry_master_type_process","master_type_process_id,master_type_process_name","master_type_process_status = '1'");
$selmodseq = $con->select("laundry_master_process","*","master_process_status = '1'");
$selrolegrupmas = $con->select("laundry_role_grup_master","role_grup_master_id","role_grup_master_status = 1");
    foreach ($selrolegrupmas as $grupmas) {}

$seldetail = $con->select("laundry_wo_master_dtl_proc a join laundry_role_grup_master b on a.role_grup_master_id=b.role_grup_master_id 
	join laundry_role_grup c on b.role_grup_master_id=c.role_grup_master_id","c.*","a.wo_master_dtl_proc_id = $_GET[id]");

if ($_GET[id]){
	$selseqker = $con->select("laundry_role_grup","*","role_grup_id = $_GET[id]");
	//echo "select * from laundry_role_grup where role_grup_id = $_GET[id]";
	foreach ($selseqker as $sqr) {}

}

if ($_GET[t] == 1){
	$codep = "input";
	$clicksave = "onClick='return confirm(Apakah Anda yakin menyimpan data??)'";
} else {
	$codep = "";
	$clicksave = "onClick='saveedit()'";
}


?>

<!-- Theme CSS -->
		<!-- Vendor CSS -->
		
		<span class="separator"></span>
		<?php  
		if ($_GET['det'] == 3) { ?>
				<div class="row" style="padding-bottom: 3%;">
                	<div class="col-sm-12 col-md-12 col-lg-12" style="font-size: 14px;">
                		<div class="col-sm-2 col-md-2 col-lg-2">
                			&nbsp;
                		</div>
                		<div class="col-sm-4 col-md-4 col-lg-4">
                			<b>Sequence</b>
                		</div>
                		<div class="col-sm-4 col-md-4 col-lg-4">
                			<b>Time</b>
                		</div>
                		<div class="col-sm-2 col-md-2 col-lg-2">
                			&nbsp;
                		</div>
                	</div>
            	</div>
            	<?php 
            	$hu = 'A';
            	foreach ($seldetail as $detail ) {  
            	?>
            	<div class="row">
                	<div class="col-sm-12 col-md-12 col-lg-12">
                		<div class="col-sm-2 col-md-2 col-lg-2">
                			&nbsp;
                		</div>
                		<div class="col-sm-4 col-md-4 col-lg-4">
                			<b><?=$hu.". ".$detail['role_grup_name']?></b>
                		</div>
                		<div class="col-sm-4 col-md-4 col-lg-4">
                			<b><?=$detail['role_grup_time']?></b>
                		</div>
                		<div class="col-sm-2 col-md-2 col-lg-2">
                			&nbsp;
                		</div>
                	</div>
            	</div>
            		<?php 
            			$no = 1;
            			$seldetdtl = $con->select("laundry_role_dtl_grup a join laundry_master_process b on a.master_process_id = b.master_process_id","master_process_name,role_dtl_grup_time","role_grup_id = '$detail[role_grup_id]'");
            			foreach ($seldetdtl as $detdtl ) {  
            		?>
		            	<div class="row">
		                	<div class="col-sm-12 col-md-12 col-lg-12">
		                		<div class="col-sm-2 col-md-2 col-lg-2">
		                			&nbsp;
		                		</div>
		                		<div class="col-sm-4 col-md-4 col-lg-4">
		                			&emsp;<?=$no.". ".$detdtl['master_process_name']?>
		                			
		                		</div>
		                		<div class="col-sm-4 col-md-4 col-lg-4">
		                			&emsp;<?=$detdtl['role_dtl_grup_time']?>
		                		</div>
		                		<div class="col-sm-2 col-md-2 col-lg-2">
		                			&nbsp;
		                		</div>
		                	</div>
		            	</div>
		<?php 
		            $no++; }
		            $hu++;
		            echo "<br>";
		 	}

		} else { ?>
				
				<form class="form-user" id="formku" method="post" action="content.php?p=<?=$_GET[p]?>_s" enctype="multipart/form-data">
					<div class="row">
                    	<div class="col-md-12">
							
                            <div class="col-md-10" style="padding: 1%;margin-bottom: 2%">
                        	<!-- <h2><strong> Sequence Process</strong> &ensp;
                        		<font style="font-size: 18px;vertical-align: middle;"><?php echo $isijam; ?></font> 
                        	</h2> 
                        	
                            </div>
                            <div class="col-md-2" style="padding: 1%;">
                        		<a href="javascript:void(0)" onClick="window.open('cetak.php?page=request&id=<?=$_GET[id]?>')"  style="cursor:pointer">
                        			<img src="assets/images/print-icon.png" alt="Print PDF" />
                        		</a>
                            </div> -->
                        </div>
						<input id="kode" name="kode" value="<?=$_GET[id]?>" type="hidden" >
						<input id="iddata" name="iddata" value="<?=$mg[id_data]?>" type="hidden" >
						<input id="notiket" name="notiket" value="<?=$mg['no_ticket']?>" type="hidden" >
						<input id="getp" name="getp" value="<?=$_GET[p]?>" type="hidden" >
						<input id="appr" name="appr" value="<?=$_SESSION['ID_PEG'];?>" type="hidden" >
						<input id="hpsdtl" name="hpsdtl" type="hidden">
						<input id="rolemasterid" name="rolemasterid" value="<?=$grupmas[role_grup_master_id]?>" type="hidden">
						<input id="codeproc" name="codeproc" type="hidden" value="<?=$codep?>">
						<div class="col-md-12 col-lg-12">
							<div class="tabs">
									<div id="overview" class="tab-pane active">
										<!-- <h4 class="mb-xlg">Personal Information</h4> -->
										<div id="edit" class="tab-pane">

											<fieldset>
												<div class="form-group">
												 	<label class="col-md-4 col-lg-4 control-label" for="profileAddress">Type</label>
												 	<div class="col-md-6 col-lg-6">
												 		<?php 
												 		if ($sqr['role_grup_jenis']){
												 			echo $sqr['role_grup_name'];
												 		} else {
												 		?>
												  		<select data-plugin-selectTwo class="form-control populate"  placeholder="None Selected" name="type" id="type" onchange="kate(this.value)">
												  			<option value="">Choose Type</option>
												    		<?php 
																  foreach ($seltypeseq as $types) { 
															?>
												    		<option value="<?=$types[master_type_process_id].'_'.$types[master_type_process_name]?>"><?=$types['master_type_process_name']?></option>
												    		<?php } ?>
											    		</select>
											    		<?php } ?>
													</div>
													<div class="col-md-2 col-lg-2">
														&nbsp;
													</div>
												</div>
												<div class="form-group">
													<label class="col-md-4 col-lg-4 control-label" for="profileAddress">Process
														
													</label>
													<div class="col-md-8 col-lg-8" style="background-color: #FFFFFF" id="proc">
														<?php include "sourceprocessedit.php" ?>
													</div>
												  			
											  </div>
											  <div>
											</fieldset>									
										</div>
									</div>
								
							</div>

						</div>
						
						<div class="col-md-12" align="center" style="margin-top: 2%;">
								<button id="btn-simpan" name="simpan" class="btn btn-primary" type="submit" <?=$clicksave?> value="app">
								 Submit
				                </button>
						</div>
						</div>
					</div>
				</form>
			<?php } ?>
					<!-- end: page -->
				