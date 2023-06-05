<?php 
// $explot = explode("_", $_GET['lotno']);
// $wo_no = $explot[0];
// $color = $explot[1];
// $exdate = $explot[2];

foreach ($selqctype as $qtype) {
if($qtype['scan_status_garment'] == 1) {
	$judul = "Good";
} else if($qtype['scan_status_garment'] == 2) {
	$judul = "Reject";
} else if($qtype['scan_status_garment'] == 3) {
	$judul = "Rework";
} 

echo "<tr><td colspan='10'><b>".$judul."</b></td></tr>";

	$num = 1;
	$listtotal = 1;
	$selrechead = $con->select("laundry_scan_qrcode a join qrcode_gdp b on a.scan_qrcode=b.qrcode_key and step_id_detail = 6","scan_qrcode,wo_no,garment_colors,DATE(ex_fty_date) as ex_fty_date,garment_sizes,garment_inseams,DATE(scan_createdate) as scan_createdate,a.user_code, login_name","a.wo_no = '$ck[wo_no]' and a.garment_colors = '$ck[garment_colors]' and a.ex_fty_date = '$ck[ex_fty_date]' and scan_type = 3 and scan_status_garment = $qtype[scan_status_garment]");

	foreach ($selrechead as $head) {
		
	?>
		<tr>
			<td  width="5%"><?php echo $num?></td> 
			<td  width="15%"><?php echo $head['wo_no']?></td> 
			<td  width="15%"><?php echo $head['scan_qrcode']?></td> 
			<td  width="5%"><?php echo $head['garment_colors']?></td> 
			<td  width="5%"><?php echo $head['ex_fty_date']?></td> 
			<td  width="5%"><?php echo $head['garment_sizes']?></td> 
			<td  width="5%"><?php echo $head['garment_inseams']?></td> 
			<td  width="5%"><?php echo $head['scan_createdate']?></td> 
			<td  width="5%"><?php echo $head['login_name']?></td> 
			<td  width="5%"><?php echo $head['user_code']?></td> 
			
		</tr>
	<?php 
		  $num++; 
		  
		}
	?>
	 <tr>
		<td  width="5%" colspan="10" style="text-align: center;"><b>Total Qty</b> : <?=$qtype['jmlscan'];?></td> 
	</tr> 

<?php 
		$total += $qtype['jmlscan']; 
	} 
?>

<tr>
	<td  width="5%" colspan="10" style="text-align: center;font-size: 14px;"><b>Total All Qty</b> : <?=$total;?></td> 
</tr> 