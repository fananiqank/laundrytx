<?php 
if ($cutqty['cutting_qty'] != ''){
	$readonly = "readonly";
} else {
	$readonly = "";
}

$no = 1;
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
		<!-- <th width="5%"><?php echo $totscan['sequence_no']?></th> -->
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
	<th width="5%" colspan="5" style="text-align: center"><b>Total</b></th>
	<th width="5%" style="text-align: center"><b><?php echo $totalall; ?></b>
		<input type="hidden" id="totalrec" name="totalrec" value="<?=$totalall?>">
	</th>
	
</tr>
