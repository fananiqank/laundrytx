<?php 
	session_start();
	include '../../../funlibs.php';
	$con = new Database;

	foreach($con->select("laundry_rework_tmp","sum(lot_qty) as sumpcs")as $spcs){}
?>

<input type="text" class="form-control" name="totalpcs" id="totalpcs" value="<?=$spcs[sumpcs]?>" readonly>
<input type="hidden" class="form-control" name="totalbalance" id="totalbalance" value="0" readonly>