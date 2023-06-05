<?php
if($_GET['reload'] == 1) {
	session_start();
	include "../../../funlibs.php";
	$con = new Database();
}

if ($_GET['wo']){
	$cmt = $con->searchseqcmt($_GET['wo']);
	$colors = $con->searchseq($_GET['color']);

	foreach($con->select("laundry_wo_master_dtl_proc","wo_no,garment_colors,buyer_id","wo_no = '".$cmt."' and garment_colors = '".$colors."'") as $wo){}
	//echo "select wo_no,garment_colors,buyer_id from laundry_wo_master_dtl_proc where wo_no = '".$cmt."' and garment_colors = '".$colors."'";
//memecah id_edi dari db menjadi satu-satu
	$expid = explode(";",$wo['id_edi']);
//end memecah id_edi dari db menjadi satu-satu
}

$no = 1;
$tabelwomas1 = "laundry_approve_bulk A 
                JOIN laundry_lot_number b ON A.lot_id = b.lot_id ";
$fieldwomas1 = "lot_no,	lot_qty,lot_kg,A.lot_type,A.approve_bulk_status,a.wo_no,
				CASE 
					WHEN A.lot_type = 'Q' 
					THEN 'QA Sample'
					WHEN A.lot_type = 'P'
					THEN 'Pre Bulk'
					ELSE 'First Bulk'
				END as bulk_type
				";
$wherewomas1 = "A.wo_no = '".$wo['wo_no']."' and A.garment_colors = '".$wo['garment_colors']."'";
$selwomas = $con->select($tabelwomas1,$fieldwomas1,$wherewomas1);
				 						//echo "select $fieldwomas1 from $tabelwomas1 where $wherewomas1";
foreach ($selwomas as $wm) {
?>
<tr>
	<td><?php echo $wm['wo_no']; ?></td>
	<td><?php echo $wm['lot_no']; ?></td>
	<td ><?php echo $wm['lot_qty']; ?></td>
	<td ><?php echo $wm['lot_kg']; ?></td>
	<td ><?php echo $wm['bulk_type']; ?></td>
	<td >
		<?php 
		 if($wm['approve_bulk_status'] == 1){
			echo "<span class='label label-success' style='font-size:12px;'>Approved</span>";
		} else {
			echo "<span class='label label-danger' style='font-size:12px;'>Not Approved</span>";
		} 
	?>	
	</td>
</tr>
<?php 
	$no++;
	$jmlwo += $countwo;
	} 
?>