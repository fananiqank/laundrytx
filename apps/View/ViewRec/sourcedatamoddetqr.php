<?php 

$num = 1;
$listtotal = 1;

// echo "select scan_qrcode,wo_no,garment_colors,DATE(ex_fty_date) as ex_fty_date,garment_sizes,garment_inseams,DATE(scan_createdate) as scan_createdate,a.user_code, login_name from laundry_scan_qrcode a join qrcode_gdp b on a.scan_qrcode=b.qrcode_key and step_id_detail = 4 
// where lot_no='$_GET[rec]'";
foreach ($selrechead as $head) {
$expkey = explode('_',$head[qrcode_key]);
$qrcode_key = $expkey[0]."_".$expkey[1]."_".$expkey[3];
?>
	<tr>
		<th width="5%"><?php echo $num?></th>
		<th width="15%"><?php echo $head['wo_no']?></th>
		<th width="15%"><?php echo $qrcode_key?></th>
		<th width="5%"><?php echo $head['color']?></th>
		<th width="5%"><?php echo $head['ex_fty_date']?></th>
		<th width="5%"><?php echo $head['size']?></th>
		<th width="5%"><?php echo $head['inseam']?></th>
		<th width="5%"><?php echo $head['gdp_datetime']?></th>
		<th width="5%"><?php echo $head['login_name']?></th>
		<th width="5%"><?php echo $head['user_code']?></th>
		
	</tr>
<?php 
	  $num++; 
	  $total += $listtotal;
	}
?>
 <tr>
	<th width="5%" colspan="10" style="text-align: center"><b>Total Qty</b> : <?=$total;?></th>
</tr> 


