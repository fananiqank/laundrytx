<?php

if($_GET['reload'] == 1) {
	session_start();
	include "../../../funlibs.php";
	$con = new Database();
	//echo "satu";		
}

if ($_GET['wo']){
	$cmt = $con->searchseqcmt($_GET['wo']);
	$colors = $con->searchseq($_GET['color']);

	foreach($con->select("laundry_wo_master_dtl_proc","wo_no,garment_colors,buyer_id,buyer_style_no,color_wash","buyer_style_no = '".$_GET['bso']."' and garment_colors = '".$colors."'") as $wo){}
	//echo "select wo_no,garment_colors,buyer_id from laundry_wo_master_dtl_proc where buyer_style_no = '".$_GET['bso']."' and garment_colors = '".$colors."'";
	$cekapp = $con->selectcount("laundry_approve_bulk","buyer_style_no","buyer_style_no = '".$wo['buyer_style_no']."' and garment_colors = '".$wo['garment_colors']."' and lot_type = 'P'" );
	$cekapf = $con->selectcount("laundry_approve_bulk","buyer_style_no","buyer_style_no = '".$wo['buyer_style_no']."' and garment_colors = '".$wo['garment_colors']."' and lot_type = 'F'" );
	// echo "select count(wo_no) from laundry_approve_bulk wo_no = '".$wo['wo_no']."' and garment_colors = '".$wo['garment_colors']."' and lot_type = 'P' and lot_type = 'F'";
//memecah id_edi dari db menjadi satu-satu
	$expid = explode(";",$wo['id_edi']);
//end memecah id_edi dari db menjadi satu-satu
}

if ($cekapp == 0 || $cekapf == 0){
	$no = 1;
	// $tabelwomas1 = "laundry_role_child a 
	// 			    JOIN (select max(role_wo_seq)-1 as role_wo_seq from laundry_role_child where lot_type = 2) b 
	// 				ON a.role_wo_seq=b.role_wo_seq AND a.lot_type=5 
	// 				JOIN (select lot_id,lot_no,a.wo_no,a.garment_colors,b.ex_fty_date,
	// 							 lot_type,lot_qty_good_upd as lot_qty,lot_kg
	// 					  from laundry_lot_number a 
	// 					       join laundry_wo_master_dtl_proc b 
	// 						   ON a.wo_master_dtl_proc_id=b.wo_master_dtl_proc_id
	// 					  where lot_type = 'Q' OR lot_type = 'P' OR lot_type = 'F') c 
	// 				ON a.lot_no = c.lot_no";
	$tabelwomas1 = "laundry_role_child	A 
					JOIN ( 
						select max(role_wo_id) as role_wo_id from laundry_role_wo a 
						JOIN laundry_wo_master_dtl_proc b on a.role_wo_master_id=b.role_wo_master_id 
						where wo_no = '".$_GET['wo']."' AND garment_colors = '".$wo['garment_colors']."' and master_type_process_id = 3
						and role_wo_name_seq = (select max(role_wo_name_seq) from laundry_role_wo a 
						JOIN laundry_wo_master_dtl_proc b on a.role_wo_master_id=b.role_wo_master_id 
						where wo_no = '".$_GET['wo']."' AND garment_colors = '".$wo['garment_colors']."' and master_type_process_id = 3)
						) b ON A.role_wo_id = b.role_wo_id AND A.lot_type = 5
					JOIN (SELECT lot_id,lot_no,A.wo_no,A.garment_colors,b.ex_fty_date,b.buyer_style_no,lot_type,lot_qty_good_upd AS lot_qty,lot_kg 
						  FROM laundry_lot_number A JOIN laundry_wo_master_dtl_proc b ON A.wo_master_dtl_proc_id = b.wo_master_dtl_proc_id 
						  WHERE lot_type = 'Q' OR lot_type = 'P' OR lot_type = 'F') C ON A.lot_no = C.lot_no ";

	$fieldwomas1 = "a.lot_no,role_child_status, a.role_wo_seq,a.lot_id,
					a.role_dtl_wo_seq,c.lot_type,c.lot_qty,c.lot_kg,c.buyer_style_no,c.wo_no";
	$wherewomas1 = "c.wo_no = '".$_GET['wo']."' and
					c.garment_colors = '".$wo['garment_colors']."' and
					role_child_status = 1 and
					a.lot_id not in (select lot_id from laundry_approve_bulk)
					";
	$selwomas = $con->select($tabelwomas1,$fieldwomas1,$wherewomas1);
	//echo "select $fieldwomas1 from $tabelwomas1 where $wherewomas1";
	foreach ($selwomas as $wm) {
		if($wm['lot_type'] == 'Q') {
			$lottype = "QA Sample";
		} else if($wm['lot_type'] == 'P') {
			$lottype = "Pre Bulk";
		} else if($wm['lot_type'] == 'F') {
			$lottype = "First Bulk";
		}
	?>
	<tr>
		<td><?php echo $wm['wo_no']; ?></td>
		<td><?php echo $wm['lot_no']; ?></td>
		<td ><?php echo $wm['lot_qty']; ?></td>
		<td ><?php echo $wm['lot_kg']; ?></td>
		<td ><?php echo $lottype; ?></td>
		<td >
			<a href='javascript:void(0)' onclick="appr('<?=$wm[lot_id]?>','<?=$wm[lot_type]?>','1','<?=$_GET[wo]?>','<?=$_GET[color]?>','<?=$_GET[bso]?>')" class='label label-primary' style='font-size:12px;'>Yes</a>
			&ensp;
			<a href='javascript:void(0)' onclick="appr('<?=$wm[lot_id]?>','<?=$wm[lot_type]?>','0','<?=$_GET[wo]?>','<?=$_GET[color]?>','<?=$_GET[bso]?>')" class='label label-danger' style='font-size:12px;'>No</a>
		</td>

	</tr>
	<?php 
		$no++;
		$jmlwo += $countwo;
	} 
} else { ?>
	<tr>
		<td colspan="5" align="center">All Approve</td>
	</tr>
<?php } ?>
