<?php
session_start();
//echo $_SESSION['ID_PEG'];
include "../../../funlibs.php";
$con = new Database();
$selrec = $con->select("wip_dtl","quantity,wo_no","wip_dtl_id = '$_GET[id]'");
//echo "select wo_master_dtl_qty from laundry_wo_master_dtl where wo_master_dtl_id = '$_GET[id]'";
foreach ($selrec as $re) {}
	if ($_GET['t'] == 5){
		$code = "return";
	} else {
		$code = "save";
	}
$selcut = $con->select("laundry_wo_master_dtl_proc","cutting_qty","wo_no = '$re[wo_no]'");
foreach ($selcut as $cut) {}
?>

<!-- Theme CSS -->
		<!-- Vendor CSS -->
		
		<span class="separator"></span>
				<form class="form-user" id="formku" method="post" action="content.php?p=<?=$_GET[p]?>_s" enctype="multipart/form-data">
					<div class="row">
                    	<div class="col-md-12">
							
                            <div class="col-md-10" style="padding: 1%;margin-bottom: 2%">
                        	
                        </div>
						<input id="kode" name="kode" value="<?=$_GET[id]?>" type="hidden" >
						<input id="getp" name="getp" value="<?=$_GET[p]?>" type="hidden" >
						<input id="appr" name="appr" value="<?=$_SESSION['ID_PEG'];?>" type="hidden" >
						<input id="codeproc" name="codeproc" type="hidden" value="<?=$code?>">
						<div class="col-md-12 col-lg-12">
							<div class="tabs">
									<div id="overview" class="tab-pane active">
										<!-- <h4 class="mb-xlg">Personal Information</h4> -->
										<div id="edit" class="tab-pane">
										<?php if ($_GET['t'] == 5) {?>
											<fieldset>
												<div class="form-group">
												 	<label class="col-md-4 col-lg-4 control-label" for="profileAddress">Qty Sewing</label>
												 	<div class="col-md-6 col-lg-6">
												 		<?php echo $re['quantity']; ?>
													</div>
													<div class="col-md-2 col-lg-2">
														&nbsp;
													</div>
												</div>
												<div class="form-group">
													<label class="col-md-4 col-lg-4 control-label" for="profileAddress">Qty Good
													</label>
													<div class="col-md-3 col-lg-3" style="background-color: #FFFFFF" id="proc">
														<input type="text" name="good" id="good" class="form-control" onkeyup="hitungturn()" >
													</div>
												  			
											  	</div>
											  	<div class="form-group">
													<label class="col-md-4 col-lg-4 control-label" for="profileAddress">Qty Reject
													</label>
													<div class="col-md-3 col-lg-3" style="background-color: #FFFFFF" id="proc">
														<input type="text" name="reject" id="reject" class="form-control" onkeyup="hitungturn()" onkeydown="return hanyaAngka(this, event);">
													</div>
											  	</div>
											  	<div class="form-group">
													<label class="col-md-4 col-lg-4 control-label" for="profileAddress">Qty Return
													</label>
													<div class="col-md-3 col-lg-3" style="background-color: #FFFFFF" id="proc">
														<input type="text" name="return" id="return" class="form-control" readonly>
													</div>
												  			
											  	</div>
											  	<div class="form-group">
													<label class="col-md-4 col-lg-4 control-label" for="profileAddress">Remark
													</label>
													<div class="col-md-3 col-lg-5" style="background-color: #FFFFFF" id="proc">
														<textarea class="form-control" id="remarkwip" name="remarkwip" onkeypress="return allvalscript(event)"></textarea>
													</div>
												  			
											  	</div>
											</fieldset>	
										<?php } else { ?>
											<fieldset>
												<div class="form-group">
												 	<label class="col-md-4 col-lg-4 control-label" for="profileAddress">Qty Sewing</label>
												 	<div class="col-md-4 col-lg-4">
												 		<?php echo $re['quantity']; ?>
													</div>
													<div class="col-md-4 col-lg-4">
														<input type="checkbox" name="lastrec" id="lastrec" value="1">&nbsp; Last Receive
													</div>
												</div>
												<div class="form-group">
													<label class="col-md-4 col-lg-4 control-label" for="profileAddress">Qty Good
													</label>
													<div class="col-md-3 col-lg-3" style="background-color: #FFFFFF" id="proc">
														<input type="text" name="good" id="good" class="form-control" onkeyup="hitungturn()" value="<?=$re[quantity]?>">
													</div>
												  			
											  	</div>
											  	<div class="form-group">
													<label class="col-md-4 col-lg-4 control-label" for="profileAddress">Qty Cutting
													</label>
													<div class="col-md-3 col-lg-3" style="background-color: #FFFFFF" id="proc">
														<input type="text" name="cutting" id="cutting" class="form-control" value="<?=$cut[cutting_qty]?>">
													</div>
											  	</div>
											  	<div class="form-group">
													<label class="col-md-4 col-lg-4 control-label" for="profileAddress">Remark
													</label>
													<div class="col-md-3 col-lg-5" style="background-color: #FFFFFF" id="proc">
														<textarea class="form-control" id="remarkwip" name="remarkwip" onkeypress="return allvalscript(event)"></textarea>
													</div>
												  			
											  	</div>
											</fieldset>	
										<?php } ?>								
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
			
					<!-- end: page -->
				