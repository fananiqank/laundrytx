<?php 
	if ($_GET['reload'] == 1){
		session_start();
		include "../../../funlibs.php";
		$con = new Database;
	
	}
	
	$selcombinehold = $con->select("laundry_lot_number a JOIN laundry_wo_master_dtl_proc b ON a.wo_master_dtl_proc_id=b.wo_master_dtl_proc_id","lot_no,lot_qty_good_upd,lot_id,combine_hold,last_lot_from_combine,a.wo_no,a.garment_colors,DATE(b.ex_fty_date) as ex_fty_date","combine_hold = 1");
?>
<table width="100%" class="table datatable-basic table-bordered table-striped table-hover dataTable no-footer">
	<tr>
		<td>No</td>
		<td>Lot</td>
		<td>Wo No</td>
		<td>Colors</td>
		<td>Ex Fty Date</td>
		<td>Qty (Pcs)</td>
		<td>Status</td>
		<!-- <td>Act</td> -->
	</tr>
		<?php
		$no = 1;
		foreach ($selcombinehold as $cmhold) {
			if ($cmhold['last_lot_from_combine'] == 1){
				$keterangan = "Last Lot";
			} else {
				$keterangan = "Hold";
			}
		?>
	<tr>
		<td width="10%"><?=$no;?></td>
		<td width="10%"><?php echo $cmhold['lot_no']?></td>
		<td width="15%"><?php echo $cmhold['wo_no']?></td>
		<td width="20%"><?php echo $cmhold['garment_colors']?></td>
		<td width="10%"><?php echo $cmhold['ex_fty_date']?></td>
		<td width="10%"><?php echo $cmhold['lot_qty_good_upd']?></td>
		<td width="20%"><?php echo $keterangan; ?> </td>
		<!-- <td width="20%">
			<a href='javascript:void(0)' class="btn btn-primary" onclick="edit('<?php echo $cmhold[lot_no]; ?>')"><i class="fa fa-pencil"></i></a>
			<input type="hidden" id="tempcolaps_<?php echo $no; ?>" name="tempcolaps">
		</td> -->
	</tr>
	<tr id="#kolaps_<?php echo $no; ?>" style="display: none;">
		<td colspan="5"><?=$no;?></td>
	</tr>
		<?php 
			$no++;
			} 
		?>

</table>