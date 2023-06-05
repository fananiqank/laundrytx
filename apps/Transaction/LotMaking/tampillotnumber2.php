<?php 

$no=1;
// A : Normal Lot
// B : Break/Separation Lot 
// C : Combine/Marge Lot 
// R : Reject Lot
// S : Scrap Lot
// Q : QA Lot
// P : Pre Bulk Lot
// F : First Bulk Lot
	$selectid = $con->select("laundry_lot_number","COUNT(lot_id) as max","wo_no = '$_POST[showcmt]'");
	foreach ($selectid as $idu) {}
	//spesial case
	if($_GET['idc'] == '331'){
		$sequence = $idu['max']+2;
	} else {
		$sequence = $idu['max']+1;
	}
	
	$expmt = explode('/',trim($_POST['showcmt']));
	$selectseq = $con->select("laundry_lot_number","COALESCE(max(lot_no_uniq),0) as max","wo_no = '$_POST[showcmt]'");
	foreach ($selectseq as $seq) {}
	if($_GET['idc'] == '331'){
		$urut = $seq['max']+2;
	}else {
		$urut = $seq['max']+1;
	}

if ($_POST['lottype'] == '1'){
	if($expmt[1] == 'RECUT'){
		$nolb ='L'.$expmt[0]."R".$expmt[3].$expmt[4].trim($expwo[5]).'Q'.sprintf('%03s', $urut)."-".$sequence;
	} else {
		$nolb ='L'.$expmt[0].$expmt[4].$expmt[5].$expmt[6].'Q'.sprintf('%03s', $urut)."-".$sequence;
	}
} else if ($_POST['lottype'] == '2'){
	//include "ceklottrial.php";
	if($expmt[1] == 'RECUT'){
		$nolb ='L'.$expmt[0]."R".$expmt[3].$expmt[4].trim($expwo[5]).'P'.sprintf('%03s', $urut)."-".$sequence;
	} else {
		$nolb ='L'.$expmt[0].$expmt[4].$expmt[5].$expmt[6].'P'.sprintf('%03s', $urut)."-".$sequence;
	}

} else if ($_POST['lottype'] == '3'){
	if($expmt[1] == 'RECUT'){
		$nolb ='L'.$expmt[0]."R".$expmt[3].$expmt[4].trim($expwo[5]).'F'.sprintf('%03s', $urut)."-".$sequence;
	} else {
		$nolb ='L'.$expmt[0].$expmt[4].$expmt[5].$expmt[6].'F'.sprintf('%03s', $urut)."-".$sequence;
	}

} else if ($_POST['lottype'] == '4'){
	if($expmt[1] == 'RECUT'){
		$nolb ='L'.$expmt[0]."R".$expmt[3].$expmt[4].trim($expwo[5]).'N'.sprintf('%03s', $urut)."-".$sequence;
	} else {
		$nolb ='L'.$expmt[0].$expmt[4].$expmt[5].$expmt[6].'N'.sprintf('%03s', $urut)."-".$sequence;
	}
}
?>

	