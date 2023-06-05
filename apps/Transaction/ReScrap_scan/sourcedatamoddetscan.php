<table class="table datatable-basic table-bordered table-striped table-hover pre-scrollable" width="100%" id="datatable-ajax3" style="font-size: 13px;">
	<thead>
	  <tr>
	    <th width="3%">No</th>
	    <th width="10%">WO No</th>
	    <th width="12%">Qr Code Key</th>
	    <th width="10%">Colors</th>
	    <th width="5%">Ex Fty Date</th>
	    <th width="5%">Sizes</th>
	    <th width="5%">Inseams</th>
	    <th width="5%">QC Date</th>
	    <th width="5%">Login</th>
	    <th width="5%">User</th>
	  </tr>
	</thead>
	<tbody>
	<?php 
		if ($_GET[tyseq] == 1) {
			$jqrcode = 'jqrcode = 1';
		} else {
			$jqrcode = 'jqrcode > 1';
		}
		$num = 1;
		$listtotal = 1;
		$selrechead = $con->select("laundry_scan_qrcode a join qrcode_gdp b on a.ticketid=b.ticketid and step_id_detail = 4
			JOIN (select COUNT(scan_qrcode) as jqrcode,scan_qrcode from laundry_scan_qrcode where scan_type = 3 
			AND scan_status_garment BETWEEN 2 AND 3
			GROUP BY scan_qrcode) as c on a.scan_qrcode=c.scan_qrcode and $jqrcode
			","a.scan_qrcode,wo_no,garment_colors,DATE(ex_fty_date) as ex_fty_date,garment_sizes,garment_inseams,sequence_no,DATE(scan_createdate) as scan_createdate,a.user_code, login_name","a.wo_no = '$hslscan[wo_no]' 
							  and a.garment_colors = '$hslscan[garment_colors]' and DATE(a.ex_fty_date) = '$hslscan[ex_fty_date_show]'
							  and scan_type = '3' and scan_status_garment = '$_GET[scst]' and scan_status = 0");
		// echo "select a.scan_qrcode,wo_no,garment_colors,DATE(ex_fty_date) as ex_fty_date,garment_sizes,garment_inseams,sequence_no,DATE(scan_createdate) as scan_createdate,a.user_code, login_name from 
		// 	laundry_scan_qrcode a join qrcode_gdp b on a.ticketid=b.ticketid and step_id_detail = 4
		// 	JOIN (select COUNT(scan_qrcode) as jqrcode,scan_qrcode from laundry_scan_qrcode where scan_type = 3 
		// 	AND scan_status_garment BETWEEN 2 AND 3
		// 	GROUP BY scan_qrcode) as c on a.scan_qrcode=c.scan_qrcode and $jqrcode
		//     where a.wo_no = '$hslscan[wo_no]' 
		// 					  and a.garment_colors = '$hslscan[garment_colors]' and DATE(a.ex_fty_date) = '$hslscan[ex_fty_date_show]'
		// 					  and scan_type = '3' and scan_status_garment = '$_GET[scst]' and scan_status = 0";
		foreach ($selrechead as $head) {
		$datasave = $head['garment_inseams'] . '|' .$head['garment_sizes'] . '|' .$head['sequence_no'] 
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
		  $totalall += count($listtotal);
		}
		
	?>

		<tr>
			<td  width="5%" colspan="10" style="text-align: center;font-size: 14px;"><b>Total All Qty</b> : <?=$totalall?></td> 
		</tr> 
		<tr style="background-color:  #FFE4E1;">
			<td width="5%" style="text-align: center" rowspan="2">
				<b>Note </b>
			</td>
			<td width="5%" style="text-align: center" rowspan="2" colspan="2">
				<textarea class="form-control" id="remark" name="remark"></textarea>
			</td>
			
			<td width="5%" align="right">
				<b>User :</b>
			</td>
			<td width="5%" colspan="2">
				<input type="text" name="usercode" id="usercode" class="form-control" value="<?= $_GET[usercode] ?>" onkeydown='return hanyaAngka(this, event)' readonly>
			</td>
			<td width="5%" align="right">
				<b>Total <br><?=$bacatypes?> :</b>
			</td>
			<td width="5%">
				<input type="text" name="totalrec" id="totalrec" class="form-control" value="<?= $totalall ?>" onkeydown='return hanyaAngka(this, event)' readonly>
			</td>
			<td width="5%" align="right">
				<b>Rework Now :</b>
			</td>
			<td width="5%" colspan="1">
				<input type="text" name="rwknow" id="rwknow" class="form-control" value="<?= $totalall ?>" onkeydown='return hanyaAngka(this, event)' onkeyup="ceknow(this.value,totalrec.value)">
			</td>
		</tr>
		
	</tbody>
</table>