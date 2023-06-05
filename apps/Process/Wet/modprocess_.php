<?php
session_start();
include "../../../funlibs.php";
$con = new Database();
$selrec = $con->select("laundry_receive","rec_qty","rec_no = '$_GET[lot]'");
foreach ($selrec as $rec) {}


if ($_GET['forwhat'] == 3) {
	foreach ($con->select("laundry_process","*","lot_no = '$_GET[lot]' and process_type = 3","process_id DESC","1") as $proc){}
	$good = $proc['process_qty_good'];
	$reject = $proc['process_qty_reject'];
	$total = $proc['process_qty_total'];
	echo "<div class='row'>
			<div class='form-group'>
				<div class='col-md-1'>
					&nbsp;
				</div>
				<div class='col-md-8'>
					<h3><i>Out From This Section</i></h3>
					<br>
			  	</div>
		  	</div>
		  </div>";
} else {
	$good = $_GET['q'];
	$reject = 0;
	$total = $_GET['q'];
}


?>

<span class="separator"></span>

<form class="form-user" id="formku" method="post" action="content.php?p=<?=$_GET[p]?>_s" enctype="multipart/form-data">
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
						 	<label class="col-md-4 col-lg-4 control-label" for="profileAddress">Qty Good</label>
						 	<div class="col-md-3 col-lg-3">
								<input type="text" id="qtygood" name="qtygood" class="form-control" onkeyup="hitungqtymach(this.value,qtyreject.value)" onkeydown="return hanyaAngka(this, event);" value="<?php echo $good; ?>">
								<input type="hidden" id="qtygoodori" name="qtygoodori" value="<?php echo $good; ?>">
							</div>
							<div class="col-md-5 col-lg-5">
								&nbsp;
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-4 col-lg-4 control-label" for="profileAddress">Qty Reject</label>
							<div class="col-md-3 col-lg-3">
								<input type="text" id="qtyreject" name="qtyreject" class="form-control" onkeydown="return hanyaAngka(this, event);" onkeyup="hitungqtymach(qtygood.value,this.value)" value="<?php echo $reject ?>">
							</div>
							<div class="col-md-5 col-lg-5">
								&nbsp;
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-4 col-lg-4 control-label" for="profileAddress">Qty Total</label>
							<div class="col-md-3 col-lg-3">
								<input type="text" id="qtytotal" name="qtytotal" class="form-control" onkeydown="return hanyaAngka(this, event);" value="<?php echo $total; ?>" readonly>
							</div>
							<div class="col-md-5 col-lg-5">
								&nbsp;
							</div>
						</div>
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
								<a href="javascript:void(0)" class="btn btn-primary" id="nextprocess" name="nextprocess" onclick="process_out(4,'<?=$_GET[lot]?>','<?=$_GET[user]?>')" data-dismiss="modal"> Submit</a>
							<?php } else { ?>
								<a href="javascript:void(0)" class="btn btn-primary" id="nextprocess" name="nextprocess" onclick="process_end(3,'<?=$_GET[lot]?>','<?=$_GET[machine]?>')" data-dismiss="modal">Submit</a>
							<?php } ?>
								
							</div>
						</div>
						<input type="hidden" id="qtylot" name="qtylot" value="<?=$rec[rec_qty]?>">
					</fieldset>	
				</div>
			</div>
		</div>
	</div>
	
</form>
	<!-- end: page -->
