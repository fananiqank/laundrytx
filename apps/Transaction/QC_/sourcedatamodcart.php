<?php 

foreach ($selstatusgrm as $grm ) {
	if($grm['scan_status_garment'] == 1){
		$head = "Good";
	} else if ($grm['scan_status_garment'] == 2){
		$head = "Reject";
	} else {
		$head = "Rework";
	}

?>
	<tr>
		<th colspan="6" style="background-color: #87CEFA"><?php echo $head; ?></th>
	</tr>
<?php 
	$no = 1;
	foreach ($con->select("laundry_scan_qrcode","SUM(scan_qty) as totqty","scan_status = '0' and scan_createdby = '$_SESSION[ID_LOGIN]' and scan_type = '$_GET[type]' and scan_status_garment = '$grm[scan_status_garment]' GROUP BY wo_no,garment_colors")as $sumqty){}
	$seltotscan = $con->select("laundry_scan_qrcode","wo_no,garment_colors,garment_inseams,garment_sizes,SUM(scan_qty) as totqty","scan_status = '0' and scan_createdby = '$_SESSION[ID_LOGIN]' and scan_type = '$_GET[type]' and scan_status_garment = '$grm[scan_status_garment]' GROUP BY wo_no,garment_colors,garment_inseams,garment_sizes");
	// echo "select wo_no,garment_colors,garment_inseams,garment_sizes,SUM(scan_qty) as totqty from laundry_scan_qrcode where scan_status = '0' and scan_createdby = '$_SESSION[ID_LOGIN]' and scan_type = '$_GET[type]' and scan_status_garment = '$grm[scan_status_garment]' GROUP BY wo_no,garment_colors,garment_inseams,garment_sizes";
	foreach ($seltotscan as $totscan) 
	{
		$datasave=$totscan['garment_inseams'].'|'.
			  	  $totscan['garment_sizes'].'|'.
			  	  $totscan['sequence_no'].'|'.
			  	  $totscan['totqty'];
		
	?>
		<tr>
			<th width="5%" style="text-align: center"><?php echo $no?></th>
			<th width="15%"><?php echo $totscan['wo_no']?></th>
			<th width="15%"><?php echo $totscan['garment_colors']?></th>
			<th width="5%"><?php echo $totscan['garment_inseams']?></th>
			<th width="5%"><?php echo $totscan['garment_sizes']?></th>
			<th width="5%" style="text-align: center"><?php echo $totscan['totqty']?>
				<input type="hidden" id="datasave[]" name="datasave[]" value="<?=$datasave?>">
			</th>
		</tr>
	<?php 
		  $no++; 
		  $totalall += $totscan['totqty'];
	}

	$willrec = $cutqty['wo_master_dtl_proc_qty_rec']+$totalall;
	$balance = $willrec-$cutqty['cutting_qty'];
	?>
	<tr style="font-size: 16px;">
		<th width="5%" colspan="5" style="text-align: right"><b>Total</b></th>
		<th width="5%" style="text-align: center"><b><?php echo $sumqty['totqty']; ?></b>
			<input type="hidden" id="totalrec[]" name="totalrec[]" value="<?=$sumqty[totqty]?>">
			<input type="hidden" id="datarec[]" name="datarec[]" value="<?=$grm[scan_status_garment]?>">
		</th>
	</tr>
<?php 
	$supertotalall += $sumqty['totqty']; 
} 

?>
<tr style="font-size: 16px;">
	<th width="5%" colspan="5" style="text-align: center"><b>Total</b></th>
	<th width="5%" style="text-align: center"><b><?php echo $supertotalall; ?></b>
		<input type="hidden" id="supertotalrec" name="supertotalrec" value="<?=$supertotalall?>">
	</th>
		
</tr>
