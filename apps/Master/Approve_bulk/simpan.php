<?php 

session_start();
include "../../../funlibs.php";
$con = new Database;
//error_reporting(0);
$date = date("Y-m-d H:i:s");
$date2 = date("Y-m-d");

	// $data = array (
	// 	'cutting_qty' => $_POST['cutting'],
	// 	'wo_master_dtl_proc_modifydate' => $date,
	// 	'wo_master_dtl_proc_modifyby' => $_SESSION['ID_LOGIN'],
	// 	);
	// //var_dump($data);
	// $exec = $con->update("laundry_wo_master_dtl_proc",$data,"wo_master_dtl_proc_id = '$_POST[getid]'");
	// //var_dump($exec);

	// $idevent = $con->idurut("laundry_cutqty_event","cutqty_event_id");
	// $dataevent = array(
	// 	'cutqty_event_id' => $idevent,
	// 	'wo_master_dtl_proc_id' => $_POST['getid'],
	// 	'cutting_qty_before' => $_POST['cuttingnow'],
	// 	'cutqty_event_createdate' => $date,
	// 	'cutqty_event_createdby' => $_SESSION['ID_LOGIN'],
	// );
	// //var_dump($dataevent);
	// $execevent = $con->insert("laundry_cutqty_event",$dataevent);
	// //var_dump($execevent);

	$data = array (
		'wo_master_dtl_proc_id' => 0,
		'approve_bulk_status' => $_GET['app'],
		'lot_id' => $_GET['lotid'],
		'lot_type' => $_GET['type'],
		'approve_bulk_createdate' => $date,
		'approve_bulk_createdby' => $_SESSION['ID_LOGIN'],
		'wo_no' => $_POST['wo_no'],
		'garment_colors' => $_POST['garment_colors'],
		'buyer_style_no' => $_POST['buyer_style_no'],
		);
	//var_dump($data);
	$exec = $con->insert("laundry_approve_bulk",$data);
	//var_dump($exec);
?>