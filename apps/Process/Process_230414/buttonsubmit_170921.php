<div class="form-group"  style="font-size: 14px;">
	<div class="col-md-1" align="left">

	   	<a href="javascript:void(0)" class="btn btn-default" onclick="reset()">Reset</a>
	
  	</div>
  	<div class="col-md-11" align="center">
  	<?php 
  	// $_SESSION[ID_LOGIN].$role1[master_process_id];
  	if (($role1['master_type_process_id'] == 4 && $_SESSION[ID_LOGIN] == 832) || ($role1['master_type_process_id'] == 5 && $_SESSION['ID_LOGIN'] == '833')) {
  		if($role1['dtl_process'] == ''){ 
  	?> <!--Process IN-->
	  		<a href="javascript:void(0)" class="btn btn-warning" style="padding: 3%;" onclick="process_in(1,'<?=$_GET[lot]?>','<?=$cmt[master_process_usemachine]?>','<?=$role1[master_process_id]?>','<?=$cmt[role_dtl_wo_time]?>','<?=$cmt[qty]?>')" <?=$disin?>><b style="font-size: 20px;">&emsp;IN&emsp;</b></a>
	<?php } else if ($role1['dtl_process'] == '1' || $role1['dtl_process'] == '3'){ ?> <!--Process Start-->
	   		<a href="javascript:void(0)" class="btn btn-primary" style="padding: 3%;" onclick="process_start(2,'<?=$timeexecute?>',1,'<?=$role1[master_process_id]?>')" <?=$disstart?>><b style="font-size:20px;">START</b></a>
	<?php } else if ($role1['dtl_process'] == '2'){ ?> <!--Process Out-->
			<a href="javascript:void(0)" class="btn btn-success" style="padding: 3%;background-color: #800000"  <?=$disend?> data-toggle='modal' data-target='#funModal' id='mod' onclick="modelend(3,'<?=$_GET[lot]?>','<?=$role1[master_type_process_id]?>','<?=$role1[master_process_id]?>')"><b style="font-size:20px;">END</b></a>
	<?php 
		} 
	}  else {
		if ($role1['master_type_process_id'] == 2) {
			echo "<a href='javascript:void(0)' class='btn btn-success' style='padding: 3%;'><b style='font-size:14px;'>Go to Lot Making Menu</b></a>";
		} else if ($role1['master_type_process_id'] == 3) {
			echo "<a href='javascript:void(0)' class='btn btn-success' style='padding: 3%;'><b style='font-size:14px;'>Go to QC Menu</b></a>";
		} else if ($role1['master_type_process_id'] == 6) {
			echo "<a href='javascript:void(0)' class='btn btn-success' style='padding: 3%'><b style='font-size:14px;'>Go to Despatch Menu</b></a>";
		} else if ($role1['master_type_process_id'] == 4 && $_SESSION['ID_LOGIN'] != '832') {
			echo "<a href='javascript:void(0)' class='btn btn-success' style='padding: 3%'><b style='font-size:14px;'>Go to Dry Process</b></a>";
		} else if ($role1['master_type_process_id'] == 5 && $_SESSION['ID_LOGIN'] != '833') {
			echo "<a href='javascript:void(0)' class='btn btn-success' style='padding: 3%'><b style='font-size:14px;'>Go to Wet Process</b></a>";
		}
	}
	?>
	</div>
</div>