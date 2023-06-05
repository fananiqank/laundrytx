<?php 
	if($slog['process_type'] == 1 || $_GET['pro'] == 1) {
		$disin = "disabled";
		$disstart = "";
		$disend = "";
		$disout = "";
	} else if ($slog['process_type'] == 2 || $_GET['pro'] == 2) {
		$disin = "disabled";
		$disstart = "disabled";
		$disend = "";
		$disout = "";
	} else if ($slog['process_type'] == 3 || $_GET['pro'] == 3) {
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
?>

<a href="javascript:void(0)" class="btn btn-warning" style="padding: 3%;" onclick="process_in(1,'<?=$_GET[lot]?>','<?=$cmt[master_process_usemachine]?>','<?=$cmt[master_process_id]?>','<?=$cmt[role_dtl_wo_time]?>','<?=$cmt[qty]?>')" <?=$disin?>><b style="font-size:20px">IN Qty</b></a>