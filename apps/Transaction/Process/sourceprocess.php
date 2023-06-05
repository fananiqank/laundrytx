<?php 

include "../../../funlibs.php";
$con = new Database;

	$exptype = explode('_',$_GET['id']);
	$seljml = $con->select("laundry_master_process","count(master_process_id) as jmlah","master_process_status = '1' and master_type_process = '$exptype[0]'");
	
	foreach($seljml as $jml){}
?>
<table width="100%" cellpadding="0" cellspacing="0">
	<tr>
		<td>Process</td>
		<td>Sequence</td>
	</tr>
	<tr>
		<!-- <a href="javascript:void(0)" class="btn btn-warning" onclick="cekall(<?=$jml['jmlah']?>,)" id="cekal">Check All</a> 
		&nbsp;<a href="javascript:void(0)" class="btn btn-info" onclick="uncekall(<?=$jml['jmlah']?>)" id="uncekal">&ensp;Reset&emsp;</a>
		<br> -->
	</tr>
	<?php 
	// echo "select a.master_process_id,b.master_process_name,a.role_dtl_grup_id from laundry_role_dtl_grup a join laundry_master_process b on a.master_process_id=b.master_process_id where a.role_grup_id = '$seq[role_grup_id]'";
	
		$selcatsub = $con->select("laundry_master_process","master_process_id,master_process_name","master_process_status = '1' and master_type_process = '$exptype[0]'");
	
		foreach ($selcatsub as $subcat) { 
	?>
	<tr>
		<td width="80%">
			<input type="checkbox" class="control-primary" name="process[]" id="process_<?=$subcat['master_process_id']?>" value="<?=$subcat['master_process_id']?>" onchange="onc(<?=$subcat['master_process_id']?>)">&ensp;<?=$subcat['master_process_name']?><br>
		</td>
		<td width="20%">
			<input id="urseq_<?=$subcat['master_process_id']?>" name="urseq[]" style="width:50%" class="form-control" type="text" disabled="disabled">
		</td>
	</tr>
<?php 

	} 
?>
