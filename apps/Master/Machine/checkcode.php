<?php 
session_start();
include '../../../funlibs.php';
$con = new Database;

//cek master machine
// $jmlmachine = $con->selectcount("laundry_master_machine","machine_id","machine_category = '$_GET[id]'");
foreach($con->select("laundry_master_machine","max(machine_id) idm","machine_category = '$_GET[id]'") as $maxmch);

if($_GET['id'] == 'Dry'){
	$headcode = 'MD';
} else if ($_GET['id'] == 'Wet'){
	$headcode = 'MW';
} else if ($_GET['id'] == 'QC'){
	$headcode = 'TQ';
}

$newseq = $maxmch['idm']+1;

$codemachine = $headcode.sprintf('%04s', $newseq);

echo $codemachine;

?>