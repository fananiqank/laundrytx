<?php 
// include "../../../funlibs.php";
// $con = new Database();
		$selcatsub = $con->select("(SELECT a.master_process_id,b.master_process_name,a.role_dtl_keranjang_id,a.role_dtl_keranjang_seq FROM laundry_role_dtl_keranjang a JOIN laundry_master_process b ON A.master_process_id = b.master_process_id WHERE role_keranjang_id = '$sqr[role_keranjang_id]'
			union
			select master_process_id,master_process_name,NULL,NULL from laundry_master_process where master_process_id NOT IN
			(SELECT a.master_process_id FROM laundry_role_dtl_keranjang a JOIN laundry_master_process b ON a.master_process_id = b.master_process_id WHERE role_keranjang_id = '$sqr[role_keranjang_id]') and master_type_process = '$sqr[role_keranjang_jenis]' order by role_dtl_keranjang_seq ASC) as tab","master_process_id,master_process_name,role_dtl_keranjang_id","");
		// echo "select master_process_id,master_process_name,role_dtl_keranjang_id from (SELECT a.master_process_id,b.master_process_name,a.role_dtl_keranjang_id FROM laundry_role_dtl_keranjang a JOIN laundry_master_process b ON A.master_process_id = b.master_process_id WHERE role_keranjang_id = '$sqr[role_keranjang_id]'
		// 	union
		// 	select master_process_id,master_process_name,NULL from laundry_master_process where master_process_id NOT IN
		// 	(SELECT a.master_process_id FROM laundry_role_dtl_keranjang a JOIN laundry_master_process b ON a.master_process_id = b.master_process_id WHERE role_keranjang_id = '$sqr[role_keranjang_id]') and master_type_process = '$sqr[role_keranjang_jenis]' order by role_dtl_keranjang_id ASC) as tab";
		foreach ($selcatsub as $subcat) { 
			 if ($subcat['role_dtl_keranjang_id'] != '') {
			 	$s = "onclick='return false;' checked";}
			 else { $s = "";}
	?>
	<tr>
		<td width="80%">
			<input type="checkbox" class="control-primary" name="process[]" id="process_<?=$subcat['master_process_id']?>" value="<?=$subcat['master_process_id']?>" onchange="onc(<?=$subcat['master_process_id']?>)" <?=$s?>>&ensp;<?=$subcat['master_process_name']?><br>
		</td>
		<td width="20%">
			<?php if ($subcat['role_dtl_keranjang_id'] != '') { ?>
				<a href="javascript:void(0)" class="btn btn-default" onclick="hapusroledtl(<?=$subcat[role_dtl_keranjang_id]?>)"><i class="fa fa-trash"></i></a>
			<?php } else { ?>
				<input id="urseq_<?=$subcat['master_process_id']?>" name="urseq[]" style="width:50%" class="form-control" type="text" disabled="disabled">
			<?php } ?>
		</td>
	</tr>
<?php 

	} 
?>