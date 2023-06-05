<?php 
session_start();
include '../../../funlibs.php';
$con = new Database;

$cmt_no = $con->searchseqcmt($_GET['cm']);
$color_no = $con->searchseq($_GET['co']);
$exdate = $_GET['exdate'];
$typelot = $_GET['typelot'];

$no=1;

		if ($cmt_no != ''){
			$cmtno = "and A.wo_no = '$cmt_no'";
		} else {
			$cmtno = "";
		}

		if ($color_no != ''){
			$colorno = "and A.garment_colors = '$color_no'";
		} else {
			$colorno = "";
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
	$cek = $con->selectcount("laundry_lot_number a join laundry_wo_master_dtl_proc b on a.wo_master_dtl_proc_id=b.wo_master_dtl_proc_id","a.lot_id","A.wo_no ILIKE '%' $cmtno $colorno $tgls and a.lot_no NOT IN (select lot_no from laundry_process) and a.lot_no NOT IN (select lot_no from laundry_lot_event where event_type = 4) and lot_type IN (select lot_type from laundry_lot_number where lot_type = 'W' OR lot_type = 'R' ) $type_lot");
	
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
	}else {
		echo "<script>$('#ceklot').val(1);</script>";
    	$selwomas = $con->select("laundry_lot_number a join laundry_wo_master_dtl_proc b on a.wo_master_dtl_proc_id=b.wo_master_dtl_proc_id LEFT JOIN laundry_master_type_lot c ON a.lot_type_rework=c.master_type_lot_initial",
    		"a.lot_id,a.lot_no,a.lot_qty,lot_shade,c.master_type_lot_name",
    		"A.wo_no ILIKE '%'  $cmtno $colorno $tgls 
    		 and a.lot_no NOT IN (select lot_no from laundry_process)
    		 and a.lot_no NOT IN (select lot_no from laundry_lot_event where event_type = 4) 
    		 and lot_type IN (select lot_type from laundry_lot_number where lot_type = 'W' OR lot_type = 'R' )
    		 $type_lot $shade");
    	
?>
	<table width="100%">
		<tr>
			<td>&nbsp;</td>
		</tr>
<?php
		foreach ($selwomas as $wm) {
	
?>
		<tr>
			<td width="3%">
	    		<input type="checkbox" id="lotid_<?=$no?>" name="lotid[]" value="<?php echo $wm[lot_id];?>" onclick="cekqty(<?=$no?>)"> 
	    		<input type="hidden" id="lotapp_<?=$no?>" name="lotapp[]" value="0"> 
	    		<input type="hidden" id="lotno_<?=$no?>" name="lotno[]" value="<?php echo $wm[lot_no];?>"> 
	    		<input type="hidden" id="lotqty_<?=$no?>" name="lotqty[]" value="<?php echo $wm[lot_qty];?>">
	    		<input type="hidden" id="lotshade_<?=$no?>" name="lotshade[]" value="<?php echo $wm[lot_shade];?>">
			</td>
			<td width="95%" align="left">
				<!-- <a href='javascript:void(0)' data-toggle='modal' data-target='#funModal' id='mod' style="color: #000000;"><b> <?php echo $wm['lot_no']." - ".$wm['master_type_lot_name']." (".$wm['lot_qty'].")"?></b></a> -->
				<a href='javascript:void(0)' style="color: #000000;"><b> <?php echo $wm['lot_no']." - ".$wm['master_type_lot_name']." (".$wm['lot_qty'].")"?></b></a>
			</td>
		</tr>
<?php 
			$no++;
			$jmlwo += $countwo;
			$jmlqty += $wm['lot_qty'];
		} 
		
?>
		<input value="<?php echo $jmlwo;?>" id="jmlwip" name="jmlwip" type="hidden">
		<input value="<?php echo $jmlqty;?>" id="jmlqty" name="jmlqty" type="hidden">
	</table>
<?php 
	}

?>


<!-- <script type="text/javascript">$('#loader-off').click();</script> -->