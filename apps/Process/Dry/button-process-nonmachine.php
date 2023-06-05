<?php 
 
if($_GET['mpid']) {
	session_start();
  require_once("../../../funlibs.php");
  $con=new Database();

  //untuk mendapatkan process terakhir
	foreach ($con->select("laundry_process","process_type,process_createdate","lot_no = '$_GET[lot]' and master_process_id = '$_GET[mpid]'","process_type DESC","1") as $typeprocess) {}
	
}



//echo "select * from laundry_process where lot_no = '$_GET[lot]' order by process_type limit 1";
	if($slog['process_type'] == 1 || $_GET['pro'] == 1) {
		$disin = "disabled";
		$disstart = "";
		$disend = "disabled";
		$disout = "";
	} else if ($slog['process_type'] == 2 || $_GET['pro'] == 2 || $typeprocess['process_type'] == 2) {
		$disin = "disabled";
		$disstart = "disabled";
		$disend = "";
		$disout = "";
	} else if ($slog['process_type'] == 3 || $_GET['pro'] == 3 || $typeprocess['process_type'] == 3	) {
		$disin = "disabled";
		$disstart = "disabled";
		$disend = "disabled";
		$disout = "";
	} else if ($slog['process_type'] == 4 || $_GET['pro'] == 4) {
		$disin = "disabled";
		$disstart = "disabled";
		$disend = "disabled";
		$disout = "disabled";
	}
	
	$lot = $_GET['lot'];
	if ($_GET['time']) {
		$time = $_GET['time'];
	} else {
		$time = $cmt['role_dtl_wo_time'];
	}
	
	if ($_GET['mpid']) {
		$masterprocid = $_GET['mpid'];
	} else {
		$masterprocid = $cmt['master_process_id'];
	}
	
	if($_GET['q']){
		$qty = $_GET['q'];
	} else {
		$qty = $cmt['qty'];
	}
?>

<!-- <a href="javascript:void(0)" class="btn btn-warning" style="padding: 3%;" onclick="process_in(1,'<?=$_GET[lot]?>')" <?=$disin?>><b style="font-size:20px">IN Qty</b></a>
  &emsp; -->
<a href="javascript:void(0)" class="btn btn-primary" style="padding: 3%;" onclick="process_start(2,'<?=$_GET[lot]?>','0','<?=$time?>','0','<?=$masterprocid?>','<?=$qty?>')" <?=$disstart?>><b style="font-size:20px;">START</b></a>
  &emsp;
<a href="javascript:void(0)" class="btn btn-success" style="padding: 3%;background-color: #800000"  <?=$disend?> data-toggle='modal' data-target='#funModal' id='mod' onclick="model(0,'<?=$lot?>','<?=$time?>',2,0,'<?=$qty?>','<?=$masterprocid?>','<?=$_GET['typelot']?>')"><b style="font-size:20px;">END</b></a>
  &emsp;
  <span id="tampilkanmenit_0" class="btn btn-default" style="height:60px;width: 15%;border-color: #ffffff;"></span>