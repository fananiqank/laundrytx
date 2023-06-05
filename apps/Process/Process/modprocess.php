<?php
session_start();
include "../../../funlibs.php";
$con = new Database();

if ($_GET['typelot'] == 'A'){
	foreach ($selrec = $con->select("laundry_receive","rec_qty as qty","rec_no = '$_GET[lot]'") as $rec) {}
	$qty = $rec['qty'];
	$displayreject = "none";
	$readonlygood = "readonly";

} else {
	foreach ($selrec = $con->select("laundry_lot_number","concat(lot_qty_good_upd,'_',lot_qty) as qty","lot_no = '$_GET[lot]'") as $rec) {}
	$expqty = explode('_',$rec['qty']);
	if ($expqty[0] != ''){
		$qty = $expqty[0];
	} else {
		$qty = $expqty[1];
	}

	$displayreject = "none";
	$readonlygood = "readonly";
}
	
foreach ($con->select("laundry_process","*","lot_no = '$_GET[lot]' and process_type = 2","process_id DESC","1") as $proc){}
	$good = $proc['process_qty_good'];
	$reject = $proc['process_qty_reject'];
	$total = $proc['process_qty_total'];

	echo "<div class='row'>
			<div class='form-group'>
				<div class='col-md-1'>
					&nbsp;
				</div>
				<div class='col-md-8'>
					<h3><i>End Process / Out From This Section</i></h3>
					<br>
			  	</div>
		  	</div>
		  </div>";
	

?>

<span class="separator"></span>

<form class="form-user" id="formku" method="post" action="content.php?p=<?=$_GET[p]?>_s" enctype="multipart/form-data">
	<div class="row">
       	<div class="col-md-12">
			<input id="machine_isi" name="machine_isi" value="<?php echo $_GET[id].'_'.$_GET[lot].'_'.$_GET[user]?>" type="hidden" >
			<input id="getd" name="getd" value="<?=$_GET[d]?>" type="hidden" >
		<div class="col-md-12 col-lg-12">
		<div class="tabs">
			<div id="overview" class="tab-pane active">
				<div id="edit" class="tab-pane">
					<fieldset>
						
						<div class="form-group">
						 	<label class="col-md-4 col-lg-4 control-label" for="profileAddress">Qty Standart</label>
						 	<div class="col-md-3 col-lg-3">
								<input type="text" id="qtystd" name="qtystd" class="form-control" onblur="hitungqtymach(this.value)" onkeydown="return hanyaAngka(this, event);" value="0" <?=$readonlygood?>>
								
							</div>
							<div class="col-md-5 col-lg-5">
								<input type="checkbox" id="cekstd" name="cekstd" onclick="cstd();"/> Pakai Standart
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-4 col-lg-4 control-label" for="profileAddress">Qty Total</label>
							<div class="col-md-3 col-lg-3">
								<span><?php echo $total; ?></span>
							</div>
							
						</div>
					
					<?php if ($_GET['machine'] != 0) { ?>
						<div class="form-group" style="display: none">
						 	<label class="col-md-4 col-lg-4 control-label" for="profileAddress">Machine Broken</label>
						 	<div class="col-md-3 col-lg-3">
								<label class="switch">
									<input type="checkbox" id="machinebroke" name="machinebroke" value="2">
									<span class="slider round"></span>
								</label>
							</div>
							<div class="col-md-5 col-lg-5">
								&nbsp;
							</div>
						</div>
					<?php } ?>
					
						<div class="form-group">
							<label class="col-md-4 col-lg-4 control-label" for="profileAddress">Remark</label>
							<div class="col-md-8 col-lg-8">
								<textarea class="form-control" id="remarkmachine" name="remarkmachine"></textarea>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-4 col-lg-4 control-label" for="profileAddress">&nbsp;</label>
							<div class="col-md-8 col-lg-8">
							<?php if ($_GET['forwhat'] == 3) { ?>
								<a href="javascript:void(0)" class="btn btn-primary" id="nextprocess" name="nextprocess" onclick="process_out(4,'<?=$_GET[lot]?>','<?=$_GET[user]?>','<?=$proc[master_type_process_id]?>')" data-dismiss="modal"> Submit</a>
							<?php } else { ?>
								<a href="javascript:void(0)" class="btn btn-primary" id="nextprocess" name="nextprocess" onclick="process_end(3,'<?=$_GET[lot]?>','<?=$_GET[machine]?>','<?=$_GET[mtpid]?>','<?=$_GET[mpid]?>','<?=$_GET[qty]?>')" data-dismiss="modal">Submit</a>
							<?php } ?>
								
							</div>
						</div>
					</fieldset>	
				</div>
			</div>
		</div>
	</div>
	
</form>
	<!-- end: page -->
