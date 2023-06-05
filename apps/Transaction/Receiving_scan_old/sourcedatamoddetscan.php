<style type="text/css">
table.scroll {width:100%;border:1px #a9c6c9 solid;font:13px verdana,arial,sans-serif;color:#333333;}
table.scroll thead {display:table;width:100%;}
table.scroll tbody {display:block;height:250px;overflow-y:scroll;width:100%;}
table.scroll tbody tr {display:table;width:100%;}
/*table.scroll th, td {width:33%;padding:8px;}
table.scroll th {background-color:#000099;color:#ffffff;}
table.scroll tr:hover td {background:#a0a0a0;color:#ffffff;}
table.scroll tr:nth-child(odd) {background-color:#c0c0c0;}
table.scroll tr:nth-child(even) {background-color:#f0f0f0;}*/
</style>
<table class=" scroll table table-bordered table-striped table-hover" width="100%" id="datatable-receivescan" >
	<thead>
	  <tr>
	    <th width="3%" colspan="10" align="left">Lot No : &emsp; <?php echo $noreceive; ?></th>
	  </tr>
	  <tr>
	    <th width="3%">No</th>
	    <th width="9%">WO No</th>
	    <th width="9%">Qr Code Key</th>
	    <th width="8%">Colors</th>
	    <th width="6%">Ex Fty Date</th>
	    <th width="3%">Sizes</th>
	    <th width="3%">Inseams</th>
	    <th width="5%">Scan Date</th>
	    <th width="5%">Login</th>
	    <th width="5%">User</th>
	  </tr>
	</thead>
	<tbody>
	<?php 

		$num = 1;
		$listtotal = 1;
		$qtyrecscan = $con->selectcount("qrcode_gdp a JOIN qrcode_ticketing_master b on a.ticketid=b.ticketid","a.ticketid",
									"b.wo_no = '$hslscan[wo_no]' 
									and trim(color) = '$hslscan[garment_colors]' 
									and step_id_detail = 4");
		
		foreach($con->select("laundry_receive","SUM(rec_qty) as qtyrec","wo_master_dtl_proc_id = '$_GET[id]' and rec_type = 2") as $qtyreclot){}

		$selrechead = $con->select("(select *, trim(garment_colors) as tgarment_colors from laundry_scan_qrcode) a left join qrcode_gdp b on a.ticketid=b.ticketid and step_id_detail = 4 
									JOIN qrcode_ticketing_master C on a.ticketid=c.ticketid and c.washtype = 'Wash'",
									"scan_qrcode,
									A.wo_no,
									tgarment_colors as garment_colors,
									DATE ( A.ex_fty_date ) AS ex_fty_date,
									garment_sizes,
									garment_inseams,
									DATE ( MAX(scan_createdate) ) AS scan_createdate,
									A.user_code,
									login_name",
									"a.wo_no = '$hslscan[wo_no]' 
									and a.tgarment_colors = '$hslscan[garment_colors]' 
							  		and scan_type = 1 and scan_status = 0
							  		GROUP BY scan_qrcode,a.wo_no,a.ex_fty_date,a.user_code,a.tgarment_colors,c.ticketid,a.garment_sizes,a.garment_inseams,scan_type,scan_status,b.login_name");
		// $selrechead = $con->select("laundry_scan_qrcode	A 
		// 							JOIN qrcode_gdp b ON A.scan_qrcode = b.qrcode_key	AND step_id_detail = 4
		// 						  	JOIN qrcode_ticketing_master C on UPPER(a.scan_qrcode) = UPPER(c.qrcode_key) and c.washtype = 'Wash'",
		// 						  	"scan_qrcode,
		// 							A.wo_no,
		// 							garment_colors,
		// 							DATE ( A.ex_fty_date ) AS ex_fty_date,
		// 							garment_sizes,
		// 							garment_inseams,
		// 							DATE ( scan_createdate ) AS scan_createdate,
		// 							A.user_code,
		// 							login_name ",
		// 							"A.wo_no = '$hslscan[wo_no]' 
		// 					  		and A.garment_colors = '$hslscan[garment_colors]' 
		// 					  		and DATE(A.ex_fty_date) = '$hslscan[ex_fty_date_show]'
		// 					  		and scan_type = 1 and scan_status = 0");

		// echo "select scan_qrcode,A.wo_no,garment_colors,DATE(A.ex_fty_date) as ex_fty_date,garment_sizes,garment_inseams,DATE(scan_createdate) as scan_createdate,a.user_code, login_name 
		// 	from laundry_scan_qrcode a left join qrcode_gdp b on a.ticketid=b.ticketid and step_id_detail = 4 JOIN qrcode_ticketing_master C on UPPER(a.scan_qrcode) = UPPER(c.qrcode_key) and c.washtype = 'Wash' where a.wo_no = '$hslscan[wo_no]'  and a.garment_colors = '$hslscan[garment_colors]' and DATE(a.ex_fty_date) = '$hslscan[ex_fty_date_show]'
		// 					  and scan_type = 1 and scan_status = 0";
		foreach ($selrechead as $head) {
		$datasave = $head['garment_inseams'] . '|' .$head['garment_sizes'] . '|' .$head['sequence_no'] 
	?>
		<tr>
			<td  width="3%"><?php echo $num?></td> 
			<td  width="12%"><?php echo $head['wo_no']?></td> 
			<td  width="12%"><?php echo $head['scan_qrcode']?></td> 
			<td  width="10%"><?php echo $head['garment_colors']?></td> 
			<td  width="5%"><?php echo $head['ex_fty_date']?></td> 
			<td  width="4%"><?php echo $head['garment_sizes']?></td> 
			<td  width="4%"><?php echo $head['garment_inseams']?></td> 
			<td  width="5%"><?php echo $head['scan_createdate']?></td> 
			<td  width="5%"><?php echo $head['login_name']?></td> 
			<td  width="5%"><?php echo $head['user_code']?></td> 
			
		</tr>
	<?php 
		  $num++; 
		  $totalall += count($listtotal);
		}
		// if ($hslscan['wo_master_dtl_proc_qty_rec'] == ''){
		// 	$qtyrec = 0;
		// } else {
			$qtyrec = $qtyreclot['qtyrec'];
		//}
		//$willrec = $hslscan['wo_master_dtl_proc_qty_rec'] + $totalall;
		//$balance = $willrec - $hslscan['cutting_qty'];
	?>

		<tr>
			<td  width="5%" colspan="10" style="text-align: center;font-size: 14px;"><b>Total All Qty</b> : <?=$totalall?></td> 
		</tr> 
	</tbody>
</table>
<table class="table table-bordered table-striped table-hover" width="100%" id="datatable-ajax3" style="font-size: 13px;">
		<tr style="background-color:  #FFE4E1;">
			<td width="10%" style="text-align: center" rowspan="2" colspan="2">
				<b>Note </b>
				<textarea class="form-control" id="remark" name="remark"></textarea>
			</td>
			<td width="5%" style="text-align: center" rowspan="2" >
				<input type="checkbox" name="lastrec" id="lastrec" value="1" onclick="tooltip(this)">&nbsp;<b>Last Receive</b>
			</td>
			<td width="8%" >
				<b>Cut Qty:</b>&ensp;<input type="text" name="cutqty" id="cutqty" value="<?= $jumlahqtycut; ?>" style="width: 65%;border-radius: 5px;height: 34px;background-color: #eee;cursor: not-allowed;font-size: 14px;" readonly>
			</td>
			<td width="5%" align="right">
				<b>Total <br>Scanned Rec:</b>
			</td>
			<td width="5%" colspan="2">
				<input type="text" name="totalrecscan" id="totalrecscan" class="form-control" value="<?= $qtyrecscan ?>" onkeydown='return hanyaAngka(this, event)' readonly>
			</td>
			<td width="5%" align="right">
				<b>Balance <br> Now: </b>
			</td>
			<td width="5%" colspan="2">
				<input type="text" name="balance" id="balance" class="form-control" value="<?= $balance ?>" onkeydown='return hanyaAngka(this, event)' readonly>
			</td>
			
		</tr>
		<tr style="background-color:  #FFE4E1;">
			<td width="8%" align="left">
				<b>User :</b>&emsp;&ensp;<input type="text" name="usercode" id="usercode" style="width: 65%;border-radius: 5px;height: 34px;background-color: #eee;cursor: not-allowed;font-size: 14px;" value="<?= $_GET[usercode] ?>" onkeydown='return hanyaAngka(this, event)' readonly>
			</td>
			<td width="5%" align="right">
				<b>Balance <br>Received:</b>
			</td>
			<td width="5%" colspan="2">
				<input type="text" name="totalrec" id="totalrec" class="form-control" value="<?= $totalall ?>" onkeydown='return hanyaAngka(this, event)' readonly>
				<input type="hidden" name="willrec" id="willrec" class="form-control" value="<?= $willrec ?>">
				<input type="hidden" name="qtyrec" id="qtyrec" class="form-control" value="<?= $qtyrec ?>">
			</td>
			<td width="5%" align="right">
				<b>Receive Now :</b>
			</td>
			<td width="5%" colspan="2">
				<input type="text" name="recnow" id="recnow" class="form-control" value="<?= $totalall ?>" onkeydown='return hanyaAngka(this, event)' onkeyup="ceknow(this.value,totalrec.value)">
			</td>
		</tr>
	</tbody>
</table>