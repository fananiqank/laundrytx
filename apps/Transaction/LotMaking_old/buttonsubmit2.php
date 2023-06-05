<div class="form-group"  style="font-size: 14px;">
	<div class="col-md-1" align="left">

	   	<a href="javascript:void(0)" class="btn btn-default" onclick="reset()">Reset</a>
	
  	</div>
  	<div class="col-md-11" align="center">
  	<?php 
  	if ($role1['lot_status'] == 3) {
  		if($role1['dtl_process'] == ''){ 
  	?> <!--Process IN-->
	  		<a href="javascript:void(0)" class="btn btn-danger" style="padding: 3%;background-color: #800000" onclick="process_end(4,'<?=strtoupper($_GET[lot])?>','<?=$cmt[master_process_usemachine]?>','<?=$role1[master_process_id]?>','<?=$cmt[role_dtl_wo_time]?>','<?=$cmt[qty]?>')" <?=$disin?>><b style="font-size: 20px;">&emsp;END&emsp;</b></a>
	<?php } else {
			echo "<a href='javascript:void(0)' class='btn btn-success' style='padding: 3%;'><b style='font-size:14px;'>already receive</b></a>";
		}
	}  else {
		if ($role1['master_type_process_id'] == 3) {
			echo "<a href='javascript:void(0)' class='btn btn-success' style='padding: 3%;'><b style='font-size:14px;'>Go to QC Menu</b></a>";
		} else if ($role1['master_type_process_id'] == 4) {
			echo "<a href='javascript:void(0)' class='btn btn-success' style='padding: 3%;'><b style='font-size:14px;'>Go to Process Menu</b></a>";
		} else if ($role1['master_type_process_id'] == 5) {
			echo "<a href='javascript:void(0)' class='btn btn-success' style='padding: 3%'><b style='font-size:14px;'>Go to Process Menu</b></a>";
		} else if ($role1['master_type_process_id'] == 6){
			echo "<a href='javascript:void(0)' class='btn btn-success' style='padding: 3%'><b style='font-size:14px;'>Go to Despatcn Menu</b></a>";
		} 
	}
	?>
	</div>
</div>