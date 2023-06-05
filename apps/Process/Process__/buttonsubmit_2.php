
<div class="form-group"  style="font-size: 12px;" align="center">

<?php 

//if ($role1['master_process_split_lot'] == 1 ){  ?>
<!-- 	   <a href="javascript:void(0)" class="btn btn-primary" onclick="split('<?=$_GET[lot]?>','<?=$parlot?>','<?=$parentfirst?>')">Split Lot</a> -->
<?php //}

//if ($role1['master_process_combine_lot'] == 1){ ?>
<!-- 	   <a href="javascript:void(0)" class="btn btn-primary" onclick="combine('<?=$_GET[lot]?>','<?=$parlot?>','<?=$parentfirst?>')">Combine Lot</a> -->
<?php 
//} 
if ($role1['master_type_process_id'] == 3) { 
		$cekqcker = $con->selectcount("laundry_qc_keranjang","qc_keranjang_id","lot_no = '".$_GET['lot']."'");
		if($cekqcker > 0) {
			$displayman = "";
			$displayscan = "display:none";
		} else {
			$displayman = "";
			$displayscan = "";
		}
?>

	<a href="javascript:void(0)" class="btn btn-danger" onclick="cancel()">Cancel</a>
<?php 
//jika receive menggunakan manual 
 	if($cmt['type_receive'] == 1){ 
 ?>
		<a href="javascript:void(0)" class="btn btn-success" id="nextprocess" style="<?php echo $displayman; ?>" onclick="correct('<?php echo $_GET[lot]; ?>','<?php echo $_GET[user]; ?>','<?php echo $cmt[role_wo_master_id]; ?>','<?php echo $_GET[typelot]; ?>','<?php echo $role1[master_type_process_id]; ?>','<?php echo $role1[master_process_id]; ?>','<?php echo $cmt[id]; ?>','<?php echo $role1[role_dtl_wo_id]; ?>','<?php echo $parlot."_".$cekpar; ?>','<?php echo $role1[master_process_usemachine]; ?>','<?php echo $role1[role_wo_id]; ?>','M','<?php echo $qtyprocess; ?>','<?php echo $cmt[wo_master_dtl_proc_status]; ?>')">QC Manual</a>
<?php 
	}
// jika receive menggunakan scan qrcode	
	else { ?>
		<a href="javascript:void(0)" class="btn btn-primary" id="nextprocess" style="<?php echo $displayscan; ?>" onclick="correct('<?php echo $_GET[lot]; ?>','<?php echo $_GET[user]; ?>','<?php echo $cmt[role_wo_master_id]; ?>','<?php echo $_GET[typelot]; ?>','<?php echo $role1[master_type_process_id]; ?>','<?php echo $role1[master_process_id]; ?>','<?php echo $cmt[id]; ?>','<?php echo $role1[role_dtl_wo_id]; ?>','<?php echo $parlot."_".$cekpar; ?>','<?php echo $role1[master_process_usemachine]; ?>','<?php echo $role1[role_wo_id]; ?>','S','<?php echo $qtyprocess; ?>','<?php echo $cmt[wo_master_dtl_proc_status]; ?>')">QC Scan</a>
<?php 
	}
} else if ($role1['master_type_process_id'] == 6) { 
	if ($cmt['type_receive'] == 1){
		$typedesp = "M";
	} else {
		$typedesp = "S";
	}
	
?>

	<a href="javascript:void(0)" class="btn btn-danger" onclick="cancel()">Cancel</a>

	<a href="javascript:void(0)" class="btn btn-success" id="nextprocess" style="<?php echo $displayman; ?>" onclick="correct('<?php echo $_GET[lot]; ?>','<?php echo $_GET[user]; ?>','<?php echo $cmt[role_wo_master_id]; ?>','<?php echo $_GET[typelot]; ?>','<?php echo $role1[master_type_process_id]; ?>','<?php echo $role1[master_process_id]; ?>','<?php echo $cmt[id]; ?>','<?php echo $role1[role_dtl_wo_id]; ?>','<?php echo $parlot."_".$cekpar; ?>','<?php echo $role1[master_process_usemachine]; ?>','<?php echo $role1[role_wo_id]; ?>','<?php echo $typedesp; ?>','<?php echo $qtyprocess; ?>','<?php echo $cmt[wo_master_dtl_proc_status]; ?>')">Correct</a>
<?php
} else { ?>	   

	   <a href="javascript:void(0)" class="btn btn-danger" onclick="cancel()">Cancel</a>
	   <a href="javascript:void(0)" class="btn btn-success" id="nextprocess" onclick="correct('<?php echo $_GET[lot]; ?>','<?php echo $_GET[user]; ?>','<?php echo $cmt[role_wo_master_id]; ?>','<?php echo $_GET[typelot]; ?>','<?php echo $role1[master_type_process_id]; ?>','<?php echo $role1[master_process_id]; ?>','<?php echo $cmt[id]; ?>','<?php echo $role1[role_dtl_wo_id]; ?>','<?php echo $parlot."_".$cekpar; ?>','<?php echo $role1[master_process_usemachine]; ?>','<?php echo $role1[role_wo_id]; ?>','G','<?php echo $qtyprocess; ?>','<?php echo $cmt[wo_master_dtl_proc_status]; ?>','<?php echo $cmt[wo_master_dtl_proc_status]; ?>')">Correct</a>

<?php } ?>

</div>