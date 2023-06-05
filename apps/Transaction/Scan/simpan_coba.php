<?php
session_start();
include "../../../funlibs.php";
$con = new Database;
//error_reporting(0);
$date = date("Y-m-d H:i:s");
$date2 = date("Y-m-d_G-i-s");
$datetok = date('dmy');

//shift 
foreach ($con->select("laundry_master_shift", "shift_id,shift_time_start,shift_time_end,shift_status", "TO_CHAR(now(), 'HH24:MI') between TO_CHAR(shift_time_start, 'HH24:MI') and TO_CHAR(shift_time_end, 'HH24:MI') and shift_status = 1") as $shift) {
}
//echo "select shift_id,shift_time_start,shift_time_end,shift_status from laundry_master_shift where TO_CHAR(now(), 'HH24:MI') between TO_CHAR(shift_time_start, 'HH24:MI') and TO_CHAR(shift_time_end, 'HH24:MI') and shift_status = 1";
//end shift

//data scanner
foreach ($con->select("qrcode_ticketing_master", "wo_no,color,size,inseam,sequence_no,shade,DATE(ex_fty_date) as ex_fty_date", "qrcode_key = '" . $_POST['scanner'] . "'") as $qrcode_key) {
}

$cmt = $qrcode_key['wo_no'];
$colors = $qrcode_key['color'];
$sizes = $qrcode_key['size'];
$inseams = $qrcode_key['inseam'];
$seq_cutting = $qrcode_key['sequence_no'];
$shade = $qrcode_key['shade'];
$exftydate = $qrcode_key['ex_fty_date'];

//select step id detail QRCODE ================
foreach ($con->select("qrcode_step_detail", "step_id_detail,workcenter_id,workcenter_seq", "step_id_detail = '4'") as $stepdtl) {
}
//=============================================

// memulai Process
//input scan Receive not confirm
if ($_POST['scan_type'] == '1') {
	$cekproc = $con->selectcount("laundry_wo_master_dtl_proc", "wo_no", "wo_no = '" . $cmt . "' and garment_colors = '" . $colors . "' and DATE(ex_fty_date) = '" . $exftydate . "'");
		echo "select wo_no from laundry_wo_master_dtl_proc where wo_no = '".$cmt."' and garment_colors = '".$colors."' and DATE(ex_fty_date) = '".$exftydate."'";
	//die();

	if ($cekproc > 0) {
		include "cekdatagdp.php";
	} else {
		echo "5|" . $_POST['scanner'];
	}
}
