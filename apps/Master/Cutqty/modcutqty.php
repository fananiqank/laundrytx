
<?php
session_start();
include "../../../funlibs.php";
$con = new Database();

foreach($con->select("laundry_wo_master_dtl_proc a LEFT JOIN (select COUNT(qrcode_key) as cut_qty,wo_no, color,ex_fty_date from qrcode_ticketing_master where washtype = 'Wash' GROUP BY wo_no, color,ex_fty_date) as t on a.wo_no = t.wo_no and a.garment_colors = t.color and a.ex_fty_date=t.ex_fty_date","a.wo_no,garment_colors,a.ex_fty_date,cut_qty as cutting_qty,seq_ex_fty_date,a.color_wash","wo_master_dtl_proc_id = '$_GET[id]'") as $cut){}
	if ($cut['ex_fty_date'] != ''){
		$exftydate = date('d-m-Y',strtotime($cut['ex_fty_date']));
	} else {
		$exftydate = "-";
	}

	if($cut['cutting_qty'] == ''){
		$cutinput = "";
		$subbutton = "";
	} else {
		$cutinput = "readonly";
		$subbutton = "style = 'display:none'";
	}
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
							 	<label class="col-md-4 col-lg-4 control-label" for="profileAddress">WO No</label>
							 	<div class="col-md-5 col-lg-5">
							 		<b>: <?php echo $cut['wo_no']; ?></b>
								</div>
								<label class="col-md-1 col-lg-1 control-label" for="profileAddress">Seq</label>
								<div class="col-md-2 col-lg-2">
									<b>: <?php echo $cut['seq_ex_fty_date']; ?></b>
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-4 col-lg-4 control-label" for="profileAddress">Color QR</label>
								<div class="col-md-8 col-lg-8" style="background-color: #FFFFFF" id="proc">
									<b>: <?php echo $cut['garment_colors']; ?></b>
								</div>
							  			
							</div>
							<div class="form-group">
								<label class="col-md-4 col-lg-4 control-label" for="profileAddress">Color Wash</label>
								<div class="col-md-8 col-lg-8" style="background-color: #FFFFFF" id="cw">
									<b>: <?php echo $cut['color_wash']; ?></b>
								</div>
							  			
							</div>
							<div class="form-group">
								<label class="col-md-4 col-lg-4 control-label" for="profileAddress">Ex Fty Date</label>
								<div class="col-md-8 col-lg-8" style="background-color: #FFFFFF" id="proc">
									<b>: <?php echo $exftydate; ?></b>
								</div>
							  			
							</div>
							<div class="form-group">
								<label class="col-md-4 col-lg-4 control-label" for="profileAddress">Cutting Qty</label>
								<div class="col-md-3 col-lg-3" style="background-color: #FFFFFF" id="proc">
									<input type="type" class="form-control" name="cutting" id="cutting" value="<?php echo $cut[cutting_qty]; ?>" onkeydown="return hanyaAngka(this, event);" <?=$cutinput?>>
									<input type="hidden" class="form-control" name="cuttingnow" id="cuttingnow" value="<?php echo $cut[cutting_qty]; ?>">
								</div>
							  	<div class="col-md-5 col-lg-5">&nbsp;</div>
							</div>
						</fieldset>	
					</div>
				</div>
			</div>
		</div>
		
		<div class="col-md-12" align="center" style="margin-top: 2%;">
			<a href="javascript:void(0)" id="btn-simpan" name="simpan" class="btn btn-primary" onclick="savecutting()" <?=$subbutton?>>Submit</a>
		</div>
</form>
