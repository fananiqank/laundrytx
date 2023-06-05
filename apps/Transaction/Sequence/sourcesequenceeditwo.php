
<table width="100%" cellpadding="0" cellspacing="0" class="table-wrapper-scroll-y my-custom-scrollbar">
	<tr>
		<td>&nbsp;</td>
	</tr>
	<tr>
	</tr>
	<tbody class="row_position">
		<?php 
			$selcatsub = $con->select("laundry_role_dtl_wo a JOIN laundry_master_process b ON A.master_process_id = b.master_process_id and master_process_status = 1 LEFT JOIN (select * from (select role_dtl_wo_id,role_wo_master_id from laundry_process ORDER BY process_id DESC) as m GROUP BY role_wo_master_id,role_dtl_wo_id) as c on a.role_dtl_wo_id = c.role_dtl_wo_id","a.master_process_id,b.master_process_name,a.role_dtl_wo_id,a.role_dtl_wo_seq,c.role_dtl_wo_id as role_on_process","a.role_wo_id = '$sqr[role_wo_id]' and role_dtl_wo_status = 1","role_dtl_wo_seq");
				
				//echo "select a.master_process_id,b.master_process_name,a.role_dtl_wo_id,a.role_dtl_wo_seq from laundry_role_dtl_wo a JOIN laundry_master_process b ON A.master_process_id = b.master_process_id and master_process_status = 1 LEFT JOIN laundry_process c on a.role_dtl_wo_id = c.role_dtl_wo_id where role_wo_id = '$sqr[role_wo_id]'";
			foreach ($selcatsub as $subcat) { 
				if ($subcat['role_on_process']){
					$color = "color:#FF0000";
				} else {
					$color = "";
				}
		?>
		<tr id="<?php echo $subcat[role_dtl_wo_id] ?>">
			<td width="70%" style="border-radius: 10px; padding: 5px; margin-bottom: 20px;">
				<b style="<?=$color?>"><?=$subcat['role_dtl_wo_seq']?> &emsp;<?=$subcat['master_process_name']?></b>
			</td>
			<td width="20%">
				
			</td>
		</tr>
		
		<?php }	?>
	</tbody>
</table>
