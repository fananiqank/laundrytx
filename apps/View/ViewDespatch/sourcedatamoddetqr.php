<?php 
// $explot = explode("_", $_GET['lotno']);
// $wo_no = $explot[0];
// $color = $explot[1];
// $exdate = $explot[2];

	$num = 1;
	$listtotal = 1;
	$selrechead = $con->select("laundry_wo_master_dtl_despatch","*","wo_master_dtl_proc_id = '$_GET[id]'");

	foreach ($selrechead as $head) {
		
	?>
		<tr>
			<td  width="5%"><?php echo $num?></td> 
			<td  width="15%"><?php echo $head['lot_no']?></td> 
			<td  width="15%"><?php echo $head['wo_master_dtl_desp_qty']?></td> 
			
		</tr>
	<?php 
		  $num++; 
		  $total += $head['wo_master_dtl_desp_qty'];
		}
	?>
	 <tr>
		<td  width="5%" colspan="3" style="text-align: center;"><b>Total Qty</b> : <?=$total;?></td> 
	</tr> 
