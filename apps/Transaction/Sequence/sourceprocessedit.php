
<table width="100%" cellpadding="0" cellspacing="0"  class="table-wrapper-scroll-y my-custom-scrollbar">
	<tr>
		<td>Process</td>
	</tr>
	<tr>
		<!-- <a href="javascript:void(0)" class="btn btn-warning" onclick="cekall(<?=$jml['jmlah']?>,)" id="cekal">Check All</a> 
		&nbsp;<a href="javascript:void(0)" class="btn btn-info" onclick="uncekall(<?=$jml['jmlah']?>)" id="uncekal">&ensp;Reset&emsp;</a>
		<br> -->
	</tr>
		<?php 
			$selcatsub = $con->select("(SELECT a.master_process_id,b.master_process_name,a.role_dtl_grup_id,a.role_dtl_grup_seq FROM laundry_role_dtl_grup a JOIN laundry_master_process b ON A.master_process_id = b.master_process_id and master_process_status = 1 WHERE role_grup_id = '".$sqr['role_grup_id']."' 
				union
				select master_process_id,master_process_name,NULL,NULL from laundry_master_process where master_process_id NOT IN
				(SELECT a.master_process_id FROM laundry_role_dtl_grup a JOIN laundry_master_process b ON a.master_process_id = b.master_process_id and master_process_status = 1 WHERE role_grup_id = '".$sqr['role_grup_id']."') and master_process_id != '42' and master_type_process = '".$sqr['master_type_process_id']."' order by role_dtl_grup_seq ASC) as tab","master_process_id,master_process_name,role_dtl_grup_id,role_dtl_grup_seq");
			// echo "select master_process_id,master_process_name,role_dtl_grup_id from (SELECT a.master_process_id,b.master_process_name,a.role_dtl_grup_id FROM laundry_role_dtl_grup a JOIN laundry_master_process b ON A.master_process_id = b.master_process_id WHERE role_grup_id = '$sqr[role_grup_id]'
			// 	union
			// 	select master_process_id,master_process_name,NULL from laundry_master_process where master_process_id NOT IN
			// 	(SELECT a.master_process_id FROM laundry_role_dtl_grup a JOIN laundry_master_process b ON a.master_process_id = b.master_process_id WHERE role_grup_id = '$sqr[role_grup_id]') and master_type_process = '$sqr[master_type_process_id]' order by role_dtl_grup_id ASC) as tab";
			foreach ($selcatsub as $subcat) {
		?>
		<tr >
			<td width="80%">
				<?php  if ($subcat['role_dtl_grup_id'] != '') { ?>
				<input type="checkbox" class="control-primary" name="process[]" id="process_<?php echo $subcat[master_process_id]; ?>" onclick='return false;' checked value="<?php echo $subcat[master_process_id]; ?>" onchange="onc(<?php echo $subcat['master_process_id']; ?>)" >&ensp;<?php echo $subcat['master_process_name']; ?>
				<?php } else {?> 
				<input type="checkbox" class="control-primary" name="process2[]" id="process2_<?php echo $subcat[master_process_id]; ?>" value="<?php echo $subcat[master_process_id]; ?>" onchange="onc2(<?php echo $subcat['master_process_id']; ?>,<?php echo $sqr['role_grup_id']; ?>)" >&ensp;<?php echo $subcat['master_process_name']; ?>
				<?php } ?>
			</td>
			<td width="20%">
				<?php if ($subcat['role_dtl_grup_id'] != '') { ?>
					<a href="javascript:void(0)" class="btn btn-default" onclick="hapusroledtl(<?php echo $subcat['role_dtl_grup_id']; ?>,<?php echo $sqr['role_grup_id']; ?>,<?php echo $sqr['master_type_process_id']; ?>,<?php echo $subcat['role_dtl_grup_seq']; ?>,0,0)"><i class="fa fa-trash"></i></a>
				<?php } else { ?>
					<input id="urseq_<?php echo $subcat[master_process_id]; ?>" name="urseq[]" style="width:60%" class="form-control" type="text" disabled="disabled">
				<?php } ?>
			</td>
		</tr>
		<?php 
			} 
		?>
</table>