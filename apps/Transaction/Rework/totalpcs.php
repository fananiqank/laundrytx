<?php 
	session_start();
	include '../../../funlibs.php';
	$con = new Database;

	foreach($con->select("laundry_rework_tmp","sum(lot_qty) as sumpcs")as $spcs){}
?>

<input type="text" class="form-control" name="totalpcs" id="totalpcs" value="" onkeyup='cekpcs(this.value)' onkeydown='return hanyaAngka(this, event);' readonly>
<input type="hidden" class="form-control" name="totalbalance" id="totalbalance" value="0" readonly>