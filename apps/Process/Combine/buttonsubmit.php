
<?php 
// session_start();
// include "../../../funlibs.php";
// $con = new Database;

if ($role1['master_process_split_lot'] == 1){ ?>
	<div class="form-group"  style="font-size: 12px;" align="center">
	   <a href="javascript:void(0)" class="btn btn-primary" onclick="cancel()">Split Lot</a>
	   <a href="javascript:void(0)" class="btn btn-danger" onclick="cancel()">Not Yet</a>
	</div>
<?php } else if ($role1['master_process_combine_lot'] == 1){  ?>
	<div class="form-group"  style="font-size: 12px;" align="center">
	   <a href="javascript:void(0)" class="btn btn-primary" onclick="cancel()">Split Lot</a>
	   <a href="javascript:void(0)" class="btn btn-danger" onclick="cancel()">Not Yet</a>
	</div>
<?php } else {  ?>
	<div class="form-group"  style="font-size: 12px;" align="center">
	   <a href="javascript:void(0)" class="btn btn-danger" onclick="cancel()">Cancel</a>
	   <a href="javascript:void(0)" class="btn btn-success" id="nextprocess" onclick="correct('<?=$_GET[lot]?>','<?=$_GET[user]?>','<?=$cmt[role_wo_master_id]?>','<?=$_GET[typelot]?>','<?=$role1[master_type_process_id]?>','<?=$role1[master_process_id]?>','<?=$cmt[id]?>','<?=$role1[role_dtl_wo_id]?>')">Correct</a>
	</div>
<?php } ?>