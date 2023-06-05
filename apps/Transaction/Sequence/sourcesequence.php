
<table width="100%" cellpadding="0" cellspacing="0" class="table-wrapper-scroll-y my-custom-scrollbar">
	<tr>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<!-- <a href="javascript:void(0)" class="btn btn-warning" onclick="cekall(<?=$jml['jmlah']?>,)" id="cekal">Check All</a> 
		&nbsp;<a href="javascript:void(0)" class="btn btn-info" onclick="uncekall(<?=$jml['jmlah']?>)" id="uncekal">&ensp;Reset&emsp;</a>
		<br> -->
	</tr>
	<tbody class="row_position">
		<?php 
			$selcatsub = $con->select("laundry_role_dtl_grup a JOIN laundry_master_process b ON A.master_process_id = b.master_process_id and master_process_status = 1","a.master_process_id,b.master_process_name,a.role_dtl_grup_id,a.role_dtl_grup_seq","role_grup_id = '$sqr[role_grup_id]'","role_dtl_grup_seq");
			
			foreach ($selcatsub as $subcat) { 
				
		?>
		<tr id="<?php echo $subcat[role_dtl_grup_id] ?>">
			<td width="70%" style="border-radius: 10px; padding: 5px; margin-bottom: 20px;" >
				<b style=""><?=$subcat['role_dtl_grup_seq']?> &emsp;<?=$subcat['master_process_name']?></b>
			</td>
			<td width="20%">
				
			</td>
		</tr>
		
		<?php }	?>
	</tbody>
</table>
