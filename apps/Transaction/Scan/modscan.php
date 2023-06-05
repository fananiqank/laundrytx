<?php
session_start();
include "../../../funlibs.php";
$con = new Database;

//cek status garment
$selstatusgrm = $con->select("laundry_scan_qrcode", "scan_status_garment", "scan_status = '0' and scan_createdby = '" . $_SESSION['ID_LOGIN'] . "' and scan_type = '" . $_GET['type'] . "' GROUP BY scan_status_garment");

// select data on laundry scan qrcode
foreach ($seltotscan = $con->select("laundry_scan_qrcode", "wo_no,garment_colors,garment_inseams,garment_sizes,SUM(scan_qty) as totqty,DATE(ex_fty_date) as ex_fty_date", "scan_status = '0' and scan_createdby = '" . $_SESSION['ID_LOGIN'] . "' and scan_type = '" . $_GET['type'] . "' GROUP BY wo_no,garment_colors,ex_fty_date,garment_inseams,garment_sizes") as $hslscan) {
}

$subs = substr($_GET['lot'], -4, 1);
if ($subs == 'A') {
	$typelot = 1;
} else {
	$typelot = 2;
}

// khusus scan recieve
foreach ($con->select("laundry_receive", "rec_no", "rec_id = '" . $_GET['id'] . "'") as $recno) {
}

//mendapatkan cutting qty
foreach ($selcutqty = $con->select("laundry_wo_master_dtl_proc", "wo_master_dtl_proc_id,cutting_qty,wo_master_dtl_proc_qty_rec", "wo_no = '" . $hslscan['wo_no'] . "' and garment_colors = '" . $hslscan['garment_colors'] . "' and DATE(ex_fty_date) = '" . $hslscan['ex_fty_date'] . "'") as $cutqty) {
}
//echo "select wo_master_dtl_proc_id,cutting_qty,wo_master_dtl_proc_qty_rec from laundry_wo_master_dtl_proc where wo_no = '".$hslscan['wo_no']."' and garment_colors = '".$hslscan['garment_colors']."' and DATE(ex_fty_date) = '".$hslscan['ex_fty_date']."'";

//mendapatkan no. urut max per wo
$urutcmt = $con->selectcount("laundry_receive", "rec_id", "wo_no = '" . $hslscan['wo_no'] . "'");
$cmtseq = $urutcmt + 1;
$expwo = explode('/', $hslscan['wo_no']);

//mendapatkan no. urut max per wo & color
$urutcmtcolor = $con->selectcount("laundry_receive", "rec_id", "wo_no = '" . $hslscan['wo_no'] . "' and garment_colors = '" . $hslscan['garment_colors'] . "'");
$cmtcolseq = $urutcmtcolor + 1;

$noreceive = 'L' . $expwo[0] . $expwo[4] . $expwo[5] . trim($expwo[6]) . 'A' . sprintf('%03s', $cmtseq) . '-' . $cmtcolseq;

$datawono = $hslscan['wo_no'] . '_' . $hslscan['garment_colors'] . '_' . $hslscan['ex_fty_date'];


?>

<!-- Theme CSS -->
<!-- Vendor CSS -->

<span class="separator"></span>
<form class="form-user" id="formku" method="post" enctype="multipart/form-data">
	<div class="row">
		<div class="form-group">
			<div class="col-sm-12 col-md-12 col-lg-12" style="background-color: #FFFFFF; " />

			<div class="row" align="center">
				<h4><b>Cart Data</b></h4>
			</div>
			<?php if ($_GET['type'] == 1) { ?>
				<div class="row" style="margin-left: 4%;">
					<h5><b>No. Lot Receive : <?php echo $noreceive; ?></b></h5>
				</div>
			<?php } ?>
			<!--  <div class="pre-scrollable"> -->
			<table class="table datatable-basic table-bordered table-striped table-hover pre-scrollable" width="100%" id="datatable-ajax3" style="font-size: 13px">
				<thead>
					<tr>
						<th width="5%" style="text-align: center">No</th>
						<th width="15%">WO No</th>
						<th width="10%">Colors</th>
						<th width="10%">Ex Fty Date</th>
						<th width="5%">Sizes</th>
						<th width="5%">Inseams</th>
						<th width="5%">Qty Receive</th>
					</tr>
				</thead>
				<tbody id="tampilmodcart">
					<?php
					if ($_GET['type'] == 1) {
						include "sourcedatamodcart.php";
					} else {
						include "sourcedatamodcart_pro.php";
					}
					?>
				</tbody>
			</table>
			<input type="hidden" name="confirm" id="confirm" value="">
			<input type="hidden" name="getpmod" id="getpmod" value="<?php echo $_GET[p]; ?>">
			<input type="hidden" name="noreceive" id="noreceive" value="<?php echo $noreceive; ?>">
			<input type="hidden" name="datawono" id="datawono" value="<?php echo $datawono; ?>">
			<input type="hidden" name="idlast" id="idlast" value="">
			<input type="hidden" name="womasterdtlprocid" id="womasterdtlprocid" value="<?php echo $cutqty[wo_master_dtl_proc_id]; ?>">
			<input type="hidden" name="types" id="types" value="<?php echo $_GET[type]; ?>">
			<input type="hidden" name="rec_no" id="rec_no" value="<?php echo $recno[rec_no]; ?>">

			<!-- Tambahan QC Scan !-->
			<input type="hidden" name="role_dtl_wo_id" id="role_dtl_wo_id" value="<?php echo $_GET[roledtlid]; ?>">
			<input type="hidden" name="role_wo_id" id="role_wo_id" value="<?php echo $_GET[rolewoid]; ?>">
			<input type="hidden" name="typelot" id="typelot" value="<?php echo $typelot; ?>">
			<input type="hidden" name="createtype" id="createtype" value="<?php echo $cutqty[wo_master_dtl_proc_status]; ?>">
			<input type="hidden" name="reworkseq" id="reworkseq" value="<?php echo $cutqty[rework_seq]; ?>">
			<input type="hidden" name="mastertypelotid" id="mastertypelotid" value="<?php echo $subs; ?>">

			<!-- </div> -->
			<div class="col-sm-12 col-md-12 col-lg-12" align="center" style="display: block">

				<?php if ($totalall != '') { ?>
					<div class="col-sm-12 col-md-12 col-lg-12" id="sumbit"><a href="javascript:void(0)" class="btn btn-primary" onclick="savetoreceive('<?php echo $_GET[type]; ?>')">Submit</a></div>
				<?php } ?>

			</div>
			<div class="col-sm-4 col-md-4 col-lg-4" id="sumbit" style="display: block;vertical-align: middle">

			</div>
		</div>
	</div>
	</div>

	<button type="button" class="btn btn-secondary" id="tutup" data-dismiss="modal" style="display: none"></button>
</form>

<!-- end: page -->