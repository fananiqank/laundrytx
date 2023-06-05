<?php 
session_start();
include '../../../funlibs.php';
$con = new Database;

$denim_no = $_GET['de'];
$buyer_no = $con->searchseq($_GET['b']);
$style_no = $con->searchseq($_GET['s']);
$cmt_no = $con->searchseqcmt($_GET['cm']);
$color_no = $con->searchseq($_GET['co']);
$tglship1 = $_GET['tgl'];
$tglship2 = $_GET['tgl2'];
$typelot = $_GET['typelot'];
/*A.buyer_style_no = 'PT40'*/
				/*A.buyer_id = 'VANS'*/
				/*C.garment_colors = 'BLACK (ACCENT FOOTWEAR LIMITED)'*/
				/*A.wo_no = '07/E/JK/W/VANS/1226/1 '*/
				/*CASE
					WHEN D.ex_fty_date IS NULL THEN
					to_char( B.ex_fty_date, 'YYYY-mm-dd' ) ELSE to_char( D.ex_fty_date, 'YYYY-mm-dd' ) 
				END BETWEEN '2007-12-04' AND '2007-12-26'*/
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

		if ($tglship1 == 'A' && $tglship2 == 'A'){
			$tgls = "";
		} else if ($tglship1 == '' && $tglship2 == ''){
			$tgls = "";
		} else {
			$tgls = "AND DATE(B.ex_fty_date) BETWEEN '$tglship1' AND '$tglship2'";
			//and DATE(a.ex_fty_date) BETWEEN '$tglship1' AND '$tglship2'";
		}

		if ($typelot != ''){
			$type_lot = "and lot_type = '$typelot'";
		} else {
			$type_lot = "";
		}

		if ($_GET['shade'] != ''){
			$shade = "and lot_shade = '$_GET[shade]'";
		} else {
			$shade = "";
		}

	$countwo = 1;
	$cek = $con->selectcount("laundry_lot_number a join laundry_wo_master_dtl_proc b on a.wo_master_dtl_proc_id=b.wo_master_dtl_proc_id","a.lot_id","A.wo_no ILIKE '%' $cmtno $colorno $tgls and a.lot_no NOT IN (select lot_no from laundry_process) and a.lot_no NOT IN (select lot_no from laundry_lot_event where event_type = 4) and lot_type IN (select lot_type from laundry_lot_number where lot_type = 'W' OR lot_type = 'R' ) $type_lot $shade");
	
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
    	$selwomas = $con->select("laundry_lot_number a join laundry_wo_master_dtl_proc b on a.wo_master_dtl_proc_id=b.wo_master_dtl_proc_id",
    		"a.lot_id,a.lot_no,a.lot_qty,lot_shade",
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
	    		<input type="checkbox" id="lotid_<?=$no?>" name="lotid[]" value="<?php echo $wm[lot_id].'_'.$wm[lot_qty];?>" onClick="cekqty(<?=$no?>)"> 
	    		<input type="hidden" id="lotapp_<?=$no?>" name="lotapp[]" value="0"> 
	    		<input type="hidden" id="lotno_<?=$no?>" name="lotno[]" value="<?php echo $wm[lot_no];?>"> 
	    		<input type="hidden" id="lotqty_<?=$no?>" name="lotqty[]" value="<?php echo $wm[lot_qty];?>">
	    		<input type="hidden" id="lotshade_<?=$no?>" name="lotshade[]" value="<?php echo $wm[lot_shade];?>">
			</td>
			<td width="95%" align="left"><a href='javascript:void(0)' data-toggle='modal' data-target='#funModal' id='mod' style="color: #000000;"><b> <?php echo $wm['lot_no']." - (".$wm['lot_qty'].")"?></b></a></td>
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