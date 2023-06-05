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
	//$selectid = $con->select("laundry_lot_number","COUNT(lot_id) as max","wo_master_dtl_proc_id = '$_GET[idc]'");
	$selectid = $con->select("laundry_lot_number","COUNT(lot_id) as max","wo_no = '$cmt_no'");
//	echo "select count(lot_id) as max from laundry_lot_number where wo_no = '$cmt_no'";
	foreach ($selectid as $idu) {}
	//spesial case
	if($_GET['idc'] == '331'){
		$sequence = $idu['max']+2;
	} else {
		$sequence = $idu['max']+1;
	}
	
	$expmt = explode('/',$cmt_no);
//	$selectseq = $con->select("laundry_lot_number","COUNT(lot_id) as max","wo_no = '$cmt_no'");
	$selectseq = $con->select("laundry_lot_number","COALESCE(max(lot_no_uniq),0) as max","wo_no = '$cmt_no'");
///	echo "select count(lot_id) as max from laundry_lot_number where wo_no = '$cmt_no'";
	foreach ($selectseq as $seq) {}
	if($_GET['idc'] == '331'){
		$urut = $seq['max']+2;
	}else {
		$urut = $seq['max']+1;
	}
	$expid = explode('_',$_GET['id']);
	$id = $expid[0];
	$initial = $expid[1];

if ($id == '1'){
	if($expmt[1] == 'RECUT'){
		$nolb ='L'.$expmt[0]."R".$expmt[3].$expmt[4].trim($expwo[5]).'Q'.sprintf('%03s', $urut)."-".$sequence;
	} else {
		$nolb ='L'.$expmt[0].$expmt[4].$expmt[5].$expmt[6].'Q'.sprintf('%03s', $urut)."-".$sequence;
	}
?>
	<a href='javascript:void(0)' class='btn btn-primary'><?=$nolb?></a>
	<input type="hidden" name="lotnumb" id="lotnumb" value="<?=$nolb?>">
	<input type="hidden" name="lottype" id="lottype" value="1">
	<input type="hidden" name="uniqseq" id="uniqseq" value="<?=$urut?>">
<?php
} else if ($id == '2'){
	//include "ceklottrial.php";
	if($expmt[1] == 'RECUT'){
		$nolb ='L'.$expmt[0]."R".$expmt[3].$expmt[4].trim($expwo[5]).'P'.sprintf('%03s', $urut)."-".$sequence;
	} else {
		$nolb ='L'.$expmt[0].$expmt[4].$expmt[5].$expmt[6].'P'.sprintf('%03s', $urut)."-".$sequence;
	}
?>
	<a href='javascript:void(0)' class='btn btn-primary'><?=$nolb?></a>
	<input type="hidden" name="lotnumb" id="lotnumb" value="<?=$nolb?>">
	<input type="hidden" name="lottype" id="lottype" value="2">
	<input type="hidden" name="uniqseq" id="uniqseq" value="<?=$urut?>">
<?php

} else if ($id == '3'){
	if($expmt[1] == 'RECUT'){
		$nolb ='L'.$expmt[0]."R".$expmt[3].$expmt[4].trim($expwo[5]).'F'.sprintf('%03s', $urut)."-".$sequence;
	} else {
		$nolb ='L'.$expmt[0].$expmt[4].$expmt[5].$expmt[6].'F'.sprintf('%03s', $urut)."-".$sequence;
	}
?>
	<a href='javascript:void(0)' class='btn btn-primary'><?=$nolb?></a>
	<input type="hidden" name="lotnumb" id="lotnumb" value="<?=$nolb?>">
	<input type="hidden" name="lottype" id="lottype" value="3">
	<input type="hidden" name="uniqseq" id="uniqseq" value="<?=$urut?>">
<?php

} else if ($id == '4'){
	if($expmt[1] == 'RECUT'){
		$nolb ='L'.$expmt[0]."R".$expmt[3].$expmt[4].trim($expwo[5]).'N'.sprintf('%03s', $urut)."-".$sequence;
	} else {
		$nolb ='L'.$expmt[0].$expmt[4].$expmt[5].$expmt[6].'N'.sprintf('%03s', $urut)."-".$sequence;
	}
?>
	<a href='javascript:void(0)' class='btn btn-primary'><?=$nolb?></a>
	<input type="hidden" name="lotnumb" id="lotnumb" value="<?=$nolb?>">
	<input type="hidden" name="lottype" id="lottype" value="4">
	<input type="hidden" name="uniqseq" id="uniqseq" value="<?=$urut?>">
<?php
}
?>

	