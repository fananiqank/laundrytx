<?php 
session_start();
include '../../../funlibs.php';
$con = new Database;

$cmt_no = $con->searchseqcmt($_GET['cm']);
$color_no2 = $con->searchseq($_GET['co']);
$colors2 = explode('Y|',$color_no2);
$colors3 = implode('&',$colors2);
$color_no = trim($colors3);
$exdate = $_GET['exdate'];
$typelot = $_GET['typelot'];

$no=1;

		if ($cmt_no != ''){
			$cmtno = "A.wo_no = '$cmt_no'";
			$cmtno1 = "wo_no = '$cmt_no'";
		} else {
			$cmtno = "";
			$cmtno1 = "";
		}

		if ($color_no != ''){
			$colorno = "and A.garment_colors = '$color_no'";
			$colorno2 = "and garment_colors = '$color_no'";
		} else {
			$colorno = "";
			$colorno2 = "";
		}

	    if ($exdate != ''){
			$exdate = "AND DATE(b.ex_fty_date) =  '$exdate'";
		} else {
			$exdate = "";
		}

		if ($typelot != ''){
			$type_lot = "and lot_type_rework = '$typelot'";
		} else {
			$type_lot = "";
		}

		
	$countwo = 1;
	
	$cek = $con->selectcount("(select a.lot_id,lot_no from (SELECT MAX
	( lot_id ) lot_id,lot_no,wo_no,	garment_colors,	lot_status,	lot_type_rework,	wo_master_dtl_proc_id,lot_type FROM laundry_lot_number where $cmtno1 $colorno2 GROUP BY lot_no,wo_no,garment_colors,lot_status,lot_type_rework,wo_master_dtl_proc_id,lot_type) A 
	JOIN laundry_wo_master_dtl_proc b ON A.wo_master_dtl_proc_id = b.wo_master_dtl_proc_id
	where $cmtno $colorno $exdate AND lot_status = 1 and lot_type = 'W') a","lot_id","");
	
	if ($cek == 0){
		echo "<script>
				swal({
					 icon: 'info',
					 title: 'Data Not Found',
					 text: 'Your Search Not Found',
					 footer: '<a href>Why do I have this issue?</a>'
					});
				$('#ceklot').val(0);
				</script>";
	} else if($cmtno != '') {
		
		echo "<script>$('#ceklot').val(1);</script>";
    	$selwomas = $con->select("(SELECT A.lot_id,A.lot_no, CASE WHEN COALESCE ( A.lot_qty_good_upd, 0 ) != 0 THEN A.lot_qty_good_upd ELSE A.lot_qty END AS lot_qty,
	lot_shade, A.lot_type_rework,	C.master_type_lot_name 
FROM (select max(lot_id) lot_id,lot_no,wo_no,garment_colors,lot_status,lot_type_rework,wo_master_dtl_proc_id,lot_qty_good_upd,lot_qty,lot_shade,lot_type from laundry_lot_number where $cmtno1 $colorno2 group by lot_no,wo_no,garment_colors,lot_status,lot_type_rework,wo_master_dtl_proc_id,lot_qty_good_upd,lot_qty,lot_shade,lot_type) A JOIN laundry_wo_master_dtl_proc b ON A.wo_master_dtl_proc_id = b.wo_master_dtl_proc_id LEFT JOIN laundry_master_type_lot c ON a.lot_type_rework=c.master_type_lot_initial
WHERE $cmtno $colorno $exdate AND lot_status = 1 AND lot_type = 'W') a","*");
    	
?>
	<table width="100%" style="font-size: 12px;">
		<tr>
			<td>&nbsp;</td>
		</tr>
<?php
		$ni = 1;
		foreach ($selwomas as $wm) {
	
?>
		<tr>
			<td width="3%">
	    		<input type="checkbox" id="lotids_<?=$no?>" name="lotids[]" value="<?php echo $wm[lot_id]."|".$wm[lot_no]."|".$wm[lot_type_rework]."|".$wm[lot_qty];?>"> 
	    		<input type="hidden" id="lotapps_<?=$no?>" name="lotapps[]" value="0"> 
	    		<input type="hidden" id="lotnos_<?=$no?>" name="lotnos[]" value="<?php echo $wm[lot_no];?>"> 
	    		<input type="hidden" id="lotqtys_<?=$no?>" name="lotqtys[]" value="<?php echo $wm[lot_qty];?>">
	    		<input type="hidden" id="lotshades_<?=$no?>" name="lotshades[]" value="<?php echo $wm[lot_shade];?>">
	    		<input type="hidden" id="lottyperwks_<?=$no?>" name="lottyperwks[]" value="<?php echo $wm[lot_type_rework];?>">
			</td>
			<td width="23%" align="left">
				<!-- <a href='javascript:void(0)' data-toggle='modal' data-target='#funModal' id='mod' style="color: #000000;"><b> <?php echo $wm['lot_no']." - ".$wm['master_type_lot_name']." (".$wm['lot_qty'].")"?></b></a> -->
				<a href='javascript:void(0)' style="color: #000000;"><b> <?php echo $wm['lot_no']." - ".$wm['master_type_lot_name']." (".$wm['lot_qty'].")"?>&nbsp;</b></a>
			</td>
			
		</tr>
<?php 
			$no++;
			$jmlwos += $countwo;
			$jmlqtys += $wm['lot_qty'];
		} 
		
?>
		<input value="<?php echo $jmlwos;?>" id="jmlswip" name="jmlswip" type="hidden">
		<input value="<?php echo $jmlqtys;?>" id="jmlsqty" name="jmlsqty" type="hidden">
	</table>
<?php 
	}

?>


<!-- <script type="text/javascript">$('#loader-off').click();</script> -->