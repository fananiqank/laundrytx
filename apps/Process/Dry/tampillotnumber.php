<?php 
session_start();
include '../../../funlibs.php';
$con = new Database;

$cmt_no = $con->searchseqcmt($_GET['cmt']);
$no=1;
// A : Normal Lot
// B : Break/Separation Lot 
// C : Combine/Marge Lot 
// R : Reject Lot
// S : Scrap Lot
// Q : QA Lot
// P : Pre Bulk Lot
// F : First Bulk Lot
// 19/EP/W/POLO/639/A/1

	//$idgen=$con->nolot('lot_id','laundry_lot_number', $_GET['idc'], $_GET['id'], date('Y-m-d'),$cmt_no);
	//echo $idgen;
	$selectid = $con->select("laundry_lot_number","MAX(lot_id) as max","wo_master_dtl_proc_id = '$_GET[idc]'");
	//echo "select MAX(lot_id) as max from laundry_lot_number where wo_master_dtl_proc = '$_GET[idc]'";
	foreach ($selectid as $idu) {}
		$sequence = $idu['max']+1;
	$expmt = explode('/',$cmt_no);
	$selectseq = $con->select("laundry_lot_number","MAX(lot_id) as max","wo_master_dtl_proc_id = '$_GET[idc]' and lot_jenis = '$_GET[id]'");
	foreach ($selectseq as $seq) {}
		$urut = $seq['max']+1;
	$nolb ='L'.$expmt[0].$expmt[4].$expmt[5].$expmt[6].'Q'.sprintf('%03s', $urut)."-".$sequence;
?>
	<a href='javascript:void(0)' class='btn btn-primary'><?=$nolb?></a>
	<input type="hidden" name="lotnumb" id="lotnumb" value="<?=$nolb?>">

	