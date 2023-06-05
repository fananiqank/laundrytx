<?php 
session_start();
include '../../../funlibs.php';
$con = new Database;

$cmt_no = $con->searchseqcmt($_GET['cmt']);
$color_no = $con->searchseq($_GET['col']);
$no=1;

if ($cmt_no != ''){
	$cmtno = "and b.wo_no = '$cmt_no' and a.garment_colors = '$color_no'";
} else {
	$cmtno = "";
}
	if ($cmt_no != ''){
		$selcmts = $con->select("
			laundry_wo_master_dtl_proc a 
			JOIN work_orders b on a.wo_no=b.wo_no
			JOIN (select min(rec_createdate) as firstdate,wo_no,garment_colors from laundry_receive 
			where wo_no = '$cmt_no' and garment_colors = '$color_no' GROUP BY wo_no,garment_colors) 
			as c on a.wo_no = c.wo_no and a.garment_colors = c.garment_colors
			LEFT JOIN (select max(rec_createdate) as lastdate,wo_no,garment_colors from laundry_receive 
			where wo_no = '$cmt_no' and garment_colors = '$color_no' and rec_status = '2' GROUP BY wo_no,garment_colors) 
			as d on a.wo_no = d.wo_no and a.garment_colors = d.garment_colors 
			LEFT JOIN laundry_lot_number e on a.wo_master_dtl_proc_id = e.wo_master_dtl_proc_id",
			"a.wo_no,a.buyer_po_number,a.wo_master_dtl_proc_qty_md,a.garment_colors,b.buyer_name,a.cutting_qty,a.wo_master_dtl_proc_qty_rec,firstdate,lastdate,SUM(e.lot_qty) as totallot","wo_master_dtl_proc_status between 1 and 2 $cmtno 
			GROUP BY A.wo_no,A.buyer_po_number,A.wo_master_dtl_proc_qty_md,A.garment_colors,b.buyer_name,
				A.cutting_qty,A.wo_master_dtl_proc_qty_rec,firstdate,lastdate
			");
		//  echo "SELECT a.wo_no,a.buyer_po_number,a.wo_master_dtl_proc_qty_md,a.garment_colors,b.buyer_name,
		// a.cutting_qty,a.wo_master_dtl_proc_qty_rec,firstdate,lastdate,SUM(e.lot_qty) as totallot FROM	laundry_wo_master_dtl_proc a 
		// JOIN work_orders b on a.wo_no=b.wo_no 
		// JOIN (select min(rec_createdate) as firstdate,wo_no,garment_colors from laundry_receive where wo_no = '$cmt_no' and garment_colors = '$color_no' GROUP BY wo_no,garment_colors) as c on a.wo_no = c.wo_no and a.garment_colors = c.garment_colors
		// LEFT JOIN (select max(rec_createdate) as lastdate,wo_no,garment_colors from laundry_receive 
		// 	  where wo_no = '$cmt_no' and garment_colors = '$color_no' and 
		// 	  rec_status = '2' GROUP BY wo_no,garment_colors) 
		// 	  as d on a.wo_no = d.wo_no and a.garment_colors = d.garment_colors 
		// LEFT JOIN laundry_lot_number e on a.wo_master_dtl_proc_id = e.wo_master_dtl_proc_id
		// WHERE wo_master_dtl_proc_status between 1 and 2 $cmtno 
		// GROUP BY A.wo_no,A.buyer_po_number,A.wo_master_dtl_proc_qty_md,A.garment_colors,b.buyer_name,
		// 		A.cutting_qty,A.wo_master_dtl_proc_qty_rec,firstdate,lastdate";
		foreach ($selcmts as $cmts) {}
	}
?>

<div class="form-group"  style="font-size: 12px;">
    <label class="col-md-2 control-label" for="profileLastName"><b>Date :</b></label>
		<div class="col-md-4">
			<?php echo date('Y-m-d'); ?>
			<input id="datedetail" name="datedetail" value="<?=date('Y-m-d');?>" type="hidden">
		</div>
	<label class="col-md-2 control-label" for="profileLastName"><b>PO Number :</b></label>
		<div class="col-md-4">
			<?php echo $cmts['buyer_po_number'];?>
		</div>
</div>
<div class="form-group"  style="font-size: 12px;">
    <label class="col-md-2 control-label" for="profileLastName"><b>Buyer :</b></label>
		<div class="col-md-4">
			<?php echo $cmts['buyer_name'];?>
		</div>
	<label class="col-md-2 control-label" for="profileLastName"><b>Color :</b></label>
		<div class="col-md-4">
			<?php echo $cmts['garment_colors'];?>
		</div>
</div>
<div class="form-group"  style="font-size: 12px;">
    <label class="col-md-2 control-label" for="profileLastName"><b>CMT Qty :</b></label>
		<div class="col-md-4">
			<?php echo number_format($cmts['wo_master_dtl_proc_qty_md']);?>
		</div>
	<label class="col-md-2 control-label" for="profileLastName"><b>Cut Qty :</b></label>
		<div class="col-md-4">
			<?php echo number_format($cmts['cutting_qty']);?>
		</div>
</div>
<div class="form-group"  style="font-size: 12px;">
    <label class="col-md-2 control-label" for="profileLastName"><b>Rec. Qty so far:</b></label>
		<div class="col-md-4">
			<?php echo number_format($cmts['wo_master_dtl_proc_qty_rec']);?>
		</div>
	<label class="col-md-2 control-label" for="profileLastName"><b>Balance to Cut :</b></label>
		<div class="col-md-4">
			<?php 
			$sisacut = ($cmts['cutting_qty'])-($cmts['wo_master_dtl_proc_qty_rec']);
			echo $sisacut;
			 ?>
		</div>
</div>
<div class="form-group"  style="font-size: 12px;">
    <label class="col-md-2 control-label" for="profileLastName"><b>First Date Rec.:</b></label>
		<div class="col-md-4">
			<?php echo $cmts['firstdate']; ?>
		</div>
	<label class="col-md-2 control-label" for="profileLastName"><b>Last Date Rec.:</b></label>
		<div class="col-md-4">
			<?php echo $cmts['lastdate']; ?>
		</div>
</div>
<div class="form-group"  style="font-size: 12px;">
    <label class="col-md-2 control-label" for="profileLastName"><b>Total Qty Lot In <br> (after Lot making):</b></label>
		<div class="col-md-4">
			<?php echo number_format($cmts['totallot']);?>
		</div>
	<label class="col-md-2 control-label" for="profileLastName"><b>Balance Qty Lot <br> (before Lot making):</b></label>
		<div class="col-md-4">
			<?php 
				$sisalotout = $cmts['wo_master_dtl_proc_qty_rec']-$cmts['totallot'];
				//$sisacut = ($cmts['cutting_qty'])-($cmts['wo_master_dtl_proc_qty_rec']);
				echo $sisalotout;
			?>
			<input type="hidden" id="sisalotout" name="sisalotout" value="<?=$sisalotout?>">
		</div>
</div>