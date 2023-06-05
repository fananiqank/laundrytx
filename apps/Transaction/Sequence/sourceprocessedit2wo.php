
<?php 
include "../../../funlibs.php";
$con = new Database();
?>
<table width="100%" cellpadding="0" cellspacing="0" class="table-wrapper-scroll-y my-custom-scrollbar">
	<tr>
		<td>Process</td>
	</tr>
	<tr>
		<!-- <a href="javascript:void(0)" class="btn btn-warning" onclick="cekall(<?=$jml['jmlah']?>,)" id="cekal">Check All</a> 
		&nbsp;<a href="javascript:void(0)" class="btn btn-info" onclick="uncekall(<?=$jml['jmlah']?>)" id="uncekal">&ensp;Reset&emsp;</a>
		<br> -->
	</tr>
	<?php 
		$selcatsub = $con->select("(SELECT a.master_process_id,b.master_process_name,a.role_dtl_wo_id,a.role_dtl_wo_seq FROM laundry_role_dtl_wo a JOIN laundry_master_process b ON A.master_process_id = b.master_process_id WHERE role_wo_id = '$_GET[id]' and role_dtl_wo_status = 1
			union
			select master_process_id,master_process_name,NULL,NULL from laundry_master_process where master_process_id NOT IN
			(SELECT a.master_process_id FROM laundry_role_dtl_wo a JOIN laundry_master_process b ON a.master_process_id = b.master_process_id WHERE role_wo_id = '$_GET[id]' and role_dtl_wo_status = 1) and master_type_process = '$_GET[j]' order by role_dtl_wo_seq ASC) as tab","master_process_id,master_process_name,role_dtl_wo_id,role_dtl_wo_seq","");
		// echo "(SELECT a.master_process_id,b.master_process_name,a.role_dtl_wo_id,a.role_dtl_wo_seq FROM laundry_role_dtl_wo a JOIN laundry_master_process b ON A.master_process_id = b.master_process_id WHERE role_wo_id = '$_GET[id]'
		// 	union
		// 	select master_process_id,master_process_name,NULL,NULL from laundry_master_process where master_process_id NOT IN
		// 	(SELECT a.master_process_id FROM laundry_role_dtl_wo a JOIN laundry_master_process b ON a.master_process_id = b.master_process_id WHERE role_wo_id = '$_GET[id]') and master_type_process = '$_GET[j]' order by role_dtl_wo_seq ASC) as tab";
		foreach ($selcatsub as $subcat) { 
			
	?>
	<tr>
		<td width="80%">
			<?php  if ($subcat['role_dtl_wo_id'] != '') { ?>
			<input type="checkbox" class="control-primary" name="process[]" id="process_<?=$subcat['master_process_id']?>" onclick='return false;' checked value="<?=$subcat['master_process_id']?>" onchange="onc(<?=$subcat['master_process_id']?>)" >&ensp;<?=$subcat['master_process_name']?>
			<?php } else {?> 
			<input type="checkbox" class="control-primary" name="process2[]" id="process2_<?=$subcat['master_process_id']?>" value="<?=$subcat['master_process_id']?>" onchange="onc2(<?=$subcat['master_process_id']?>,<?=$_GET[id]?>)" >&ensp;<?=$subcat['master_process_name']?>
			<?php } ?>
		</td>
		<td width="30%">
			<?php if ($subcat['role_dtl_wo_id'] != '') { ?>

				<a href="javascript:void(0)" class="btn btn-default" onclick="hapusroledtl(<?=$subcat['role_dtl_wo_id']?>,<?=$_GET['id']?>,<?=$_GET['j']?>,<?=$subcat['role_dtl_wo_seq']?>,<?=$_GET['jns']?>,<?=$_GET['de']?>)"><i class="fa fa-trash"></i></a>
				
			<?php } else { ?>
				<input id="urseq_<?=$subcat['master_process_id']?>" name="urseq[]" style="width:60%" class="form-control" type="text" disabled="disabled">
			<?php } ?>
		</td>
	</tr>
<?php 

	} 
?>
</table>