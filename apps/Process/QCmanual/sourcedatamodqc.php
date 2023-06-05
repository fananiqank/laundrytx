<?php 
if($_GET['type_qc'] != '') {
	session_start();
	include "../../../funlibs.php";
	$con = new Database;
} 

$selstatusgrm = $con->select("laundry_qc_keranjang",
							 "qc_keranjang_type,wo_no,garment_colors,ex_fty_date",
							 "lot_no = '$_GET[lot]' 
							  GROUP BY qc_keranjang_type,wo_no,garment_colors,ex_fty_date");

foreach ($selstatusgrm as $grm ) {

	if($grm['qc_keranjang_type'] == 1){
		$head = "Good";
	} else if ($grm['qc_keranjang_type'] == 2){
		$head = "Reject";
	} else {
		$head = "Rework";
	}

?>
	<tr>
		<th colspan="7" style="background-color: #87CEFA"><?php echo $head; ?></th>
	</tr>
<?php 
	$no = 1;
	foreach ($con->select("laundry_qc_keranjang",
		                  "SUM(qc_keranjang_qty) as totqty",
		                  "wo_no = '$grm[wo_no]' and 
		                   garment_colors = '$grm[garment_colors]' and 
		                   ex_fty_date = '$grm[ex_fty_date]' and 
		                   qc_keranjang_type = '$grm[qc_keranjang_type]' 
		                   GROUP BY wo_no,garment_colors")as $sumqty){}

	$seltotqc = $con->select("laundry_qc_keranjang",
							 "wo_no,garment_colors,
							  ex_fty_date,
							  to_char(ex_fty_date,'DD-MM-YYYY') as ex_fty_date_show,
							  lot_no,
							  SUM(qc_keranjang_qty) as jumqty",
							 "wo_no = '$grm[wo_no]' and 
							  garment_colors = '$grm[garment_colors]' and 
							  ex_fty_date = '$grm[ex_fty_date]' and
							  qc_keranjang_type = '$grm[qc_keranjang_type]' 
							  GROUP BY wo_no,garment_colors,ex_fty_date,lot_no");
	foreach ($seltotqc as $totqc) 
	{
		$datasave=  $totqc['wo_no']."|".
					$totqc['garment_colors']."|".
					$totqc['ex_fty_date']."|".
					$totqc['lot_no']."|".
					$totqc['jumqty'];
		
	?>
		<tr>
			<th width="3%" style="text-align: center"><?php echo $no?></th>
			<th width="15%"><?php echo $totqc['wo_no']?></th>
			<th width="15%"><?php echo $totqc['garment_colors']?></th>
			<th width="10%"><?php echo $totqc['ex_fty_date_show']?></th>
			<th width="10%"><?php echo $totqc['lot_no']?></th>
			<th width="7%" style="text-align: center"><?php echo $totqc['jumqty']?>
				<input type="hidden" id="datasave[]" name="datasave[]" value="<?=$datasave?>">
			</th>
			<th width="3%"><a href="javascript:void(0)" onclick="hapusqckeranjang('<?=$_GET[lot]?>','<?=$grm[qc_keranjang_type]?>','<?=$_GET[rolewoid]?>','<?=$_GET[d]?>')"><i class="fa fa-trash"></a></th>
		</tr>
	<?php 
		  $no++; 
		  $totalall += $totqc['jumqty'];
	}

	$willrec = $cutqty['wo_master_dtl_proc_qty_rec']+$totalall;
	$balance = $willrec-$cutqty['cutting_qty'];
	?>
	<tr style="font-size: 16px;">
		<th width="5%" colspan="5" style="text-align: right"><b>Total</b></th>
		<th width="5%" style="text-align: center"><b><?php echo $sumqty['totqty']; ?></b>
			<input type="hidden" id="totalrec_<?=$grm[qc_keranjang_type]?>" name="totalrec_<?=$grm[qc_keranjang_type]?>" value="<?=$sumqty[totqty]?>">
			<input type="hidden" id="datarec[]" name="datarec[]" value="<?=$grm[qc_keranjang_type]?>">
		</th>
	</tr>
<?php 
	$supertotalall += $sumqty['totqty']; 
} 

?>
<tr style="font-size: 16px;">
	<th width="5%" colspan="5" style="text-align: center"><b>Total All</b></th>
	<th width="5%" style="text-align: center"><b><?php echo $supertotalall; ?></b>
		<input type="hidden" id="supertotalrec" name="supertotalrec" value="<?=$supertotalall?>">
		<input type="hidden" id="rolewoidmod" name="rolewoidmod" value="<?=$_GET[rolewoid]?>">
	</th>
		
</tr>
