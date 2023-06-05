<?php 
session_start();
include '../../../funlibs.php';
$con = new Database;

$cmt_no = $con->searchseqcmt($_GET['cm']);
$color_no = $con->searchseq($_GET['co']);
$exdate = $_GET['exdate'];
$typelot = $_GET['typelot'];

$no=1;
		
	$countwo = 1;
	// $cek = $con->selectcount("laundry_lot_number a join laundry_wo_master_dtl_proc b on a.wo_master_dtl_proc_id=b.wo_master_dtl_proc_id","a.lot_id","A.wo_no ILIKE '%' $cmtno $colorno $tgls and a.lot_no NOT IN (select lot_no from laundry_process) and a.lot_no NOT IN (select lot_no from laundry_lot_event where event_type = 4) and lot_type IN (select lot_type from laundry_lot_number where lot_type = 'W' OR lot_type = 'R' ) $type_lot");
	
	$selwomas = $con->select("laundry_rework_tmp","*","");
    	
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
	    		<input type="hidden" id="lotid_<?=$no?>" name="lotid[]" value="<?php echo $wm[lot_id];?>" onclick="cekqty(this.value)"> 
	    		<input type="hidden" id="lotapp_<?=$no?>" name="lotapp[]" value="0"> 
	    		<input type="hidden" id="lotno_<?=$no?>" name="lotno[]" value="<?php echo $wm[lot_no];?>"> 
	    		<input type="hidden" id="lotshade_<?=$no?>" name="lotshade[]" value="<?php echo $wm[lot_shade];?>">
			</td>
			<td width="30%" align="left">
				<!-- <a href='javascript:void(0)' data-toggle='modal' data-target='#funModal' id='mod' style="color: #000000;"><b> <?php echo $wm['lot_no']." - ".$wm['master_type_lot_name']." (".$wm['lot_qty'].")"?></b></a> -->
				<a href='javascript:void(0)' style="color: #000000;"><b> <?php echo $wm['lot_no']." - ".$wm['master_type_lot_name']?>&nbsp;</b></a>
			</td>
			<td width="15%" align="left">
				<input type="text" class="form-control" name="lot_qty[]" id="lot_qty_<?=$no?>" value="<?=$wm[lot_qty]?>" readonly>
			</td>
			<td width="1%">&nbsp;</td>
			<td width="15%" align="left">
				<input type="text" class="form-control" name="rework_qty[]" id="rework_qty_<?=$no?>" value="<?=$wm[lot_qty]?>" onblur="hitungrework(this.value,'<?=$no?>')">
			</td>
			<td width="10%" align="center">Bal:</td>
			<td width="15%" align="left">
				<input type="text" class="form-control" name="balance_qty[]" id="balance_qty_<?=$no?>" value="0" readonly>
			</td>
			<td width="3%">&nbsp;</td>
			<td width="10%"><a href="javascript:void(0)" class="btn btn-default" style="padding: 4%;" onclick="hapuswip('<?php echo $wm[lot_no];?>','<?=$no?>')"><i class="fa fa-close" style="color:#FF0000;"></i></a></td>
			
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



<!-- <script type="text/javascript">$('#loader-off').click();</script> -->