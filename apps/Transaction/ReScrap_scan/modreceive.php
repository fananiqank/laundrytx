
<?php
session_start();
include "../../../funlibs.php";
$con = new Database();

// select wo master dtl proc
foreach ($seltotscan = $con->select("laundry_wo_master_dtl_proc","DATE(ex_fty_date) as ex_fty_date_show, *","wo_master_dtl_proc_id = '$_GET[id]'") as $hslscan) {
}
//mendapatkan no. urut max per wo
$urutcmt = $con->selectcount("laundry_lot_number", "lot_id", "wo_no = '".$hslscan['wo_no']."'");
$cmtseq = $urutcmt + 1;

$expwo = explode('/', $hslscan['wo_no']);

//mendapatkan no. urut max per wo & color
$urutcmtcolor = $con->selectcount("laundry_lot_number", "lot_id", "wo_no = '".$hslscan['wo_no']."' and garment_colors = '".$hslscan['garment_colors']."'");
$cmtcolseq = $urutcmtcolor + 1;

if($_GET['scst'] == '2') {
	$types = 'S';
	$bacatypes = "Scrap";
} else {
	$types = 'W';
	$bacatypes = "Rework";
}

$nolot = 'L' . $expwo[0] . $expwo[4] . $expwo[5] . trim($expwo[6]) . $types . sprintf('%03s', $cmtseq);

$datawono = $hslscan['wo_no'] . '_' . $hslscan['garment_colors'] . '_' . $hslscan['ex_fty_date'];

?>
<div class="col-lg-12 col-md-12">
		<form class="form-user" id="formku" method="post" enctype="multipart/form-data">
			<input type="hidden" name="getp" id="getp" value="option=Transaction&task=rescrap_scan2&act=ugr_transaction" >
			<input type="hidden" name="getid" id="getid" value="<?=$_GET[id]?>" >
			<input type="hidden" name="scst" id="scst" value="<?=$_GET[scst]?>" >
			<input type="hidden" name="tyseq" id="tyseq" value="<?=$_GET[tyseq]?>" >
			<input type="hidden" name="usercode" id="usercode" value="<?=$_GET[usercode]?>" >
			<input type="hidden" name="idlast" id="idlast" value="">
			<input type="hidden" name="confirm" id="confirm" value="">
			<input type="hidden" name="datawono" id="datawono" value="<?php echo $datawono; ?>">
				<fieldset>
					<div class="form-group">
					 	<label class="col-md-2 col-lg-2 control-label" for="profileAddress" style="text-align: right;"><b>Lot <?=$bacatypes?> :</b></label>
					 	<div class="col-md-9 col-lg-9" >
					 		<b><?php echo $nolot; ?></b>
					 		<input type="hidden" name="nolot" id="nolot" value="<?=$nolot?>">
						</div>
						
					</div>
					<div class="form-group">
						<div class="col-md-12 col-lg-12" style="background-color: #FFFFFF" id="tampilproc">
							<?php include "sourcedatamoddetscan.php"; ?>
						</div>
					</div>
					<div class="form-group">
						<div class="col-md-12 col-lg-12" style="background-color: #FFFFFF;text-align: center" id="proc">
							<a href="javascript:void(0)" name="submit" id="submit" onclick="savetoreceive('<?=$_GET[id]?>','1')" class="btn btn-primary">Submit</a>
						</div>					  			
					</div>
				</fieldset>	
		</form>
</div>
