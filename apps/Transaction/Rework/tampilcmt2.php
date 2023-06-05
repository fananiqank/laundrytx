<?php 
session_start();
include '../../../funlibs.php';
$con = new Database;

$cmt_no = $con->searchseqcmt($_GET['cm']);
$color_no2 = $con->searchseq($_GET['co']);
//echo $color_no2;
$colors2 = explode('Y|',$color_no2);
$colors3 = implode('&',$colors2);
$color_no = trim($colors3);
$ex_fty_date = $_GET['exdate'];
$no=1;

// 			$table = "(select *, receive-created as balance from 
// (select a.wo_no,a.garment_colors,wo_ex_fty_date,buyer_id,
// COALESCE((select sum(lot_qty) qty 
// from laundry_lot_number
// where wo_no = a.wo_no and garment_colors = a.garment_colors and lot_type = 'W' and lot_status=0
// GROUP BY wo_no,garment_colors),0) receive,
// coalesce((select sum(lot_qty) qty 
// from laundry_lot_number
// where wo_no = a.wo_no and garment_colors = a.garment_colors and lot_type = 'M'
// GROUP BY wo_no,garment_colors),0) as created
// from laundry_lot_number a 
// left join qrcode_workorders b on trim(a.wo_no)=trim(b.wo_no) 
// where a.wo_no = '".$cmt_no."' and garment_colors = '".$color_no."'
// GROUP BY a.wo_no,garment_colors,wo_ex_fty_date,buyer_id) a ) a";

$table = "(select *, receive-created as balance from 
(select a.wo_no,a.garment_colors,wo_ex_fty_date,buyer_id,
COALESCE((select sum(lot_qty) qty 
from laundry_lot_number
where wo_no = a.wo_no and garment_colors = a.garment_colors and lot_type = 'W' and lot_status=0
GROUP BY wo_no,garment_colors),0) receive,
coalesce((select sum(lot_qty) qty 
from laundry_lot_number
where wo_no = a.wo_no and garment_colors = a.garment_colors and lot_type = 'M'
GROUP BY wo_no,garment_colors),0) as created
from laundry_lot_number a 
left join qrcode_workorders b on trim(a.wo_no)=trim(b.wo_no) 
where a.wo_no = '".$cmt_no."' and garment_colors = '".$color_no."'
GROUP BY a.wo_no,garment_colors,wo_ex_fty_date,buyer_id) a ) a";

			$field = "*";
			$selcmts = $con->select($table,$field);
			//echo "select $field from $table where $where";
			foreach ($selcmts as $cmts) {}
	
	?>

	<div class="form-group"  style="font-size: 12px;">
	    <label class="col-md-2 control-label" for="profileLastName"><b>Date :</b></label>
			<div class="col-md-4">
				<?php echo date('Y-m-d'); ?>
				<input id="datedetail" name="datedetail" value="<?php echo date('Y-m-d'); ?>" type="hidden">
			</div>
		<!-- <label class="col-md-2 control-label" for="profileLastName"><b>PO Number :</b></label>
			<div class="col-md-4">
				<?php echo $cmts['buyer_po_number'];?>
			</div> -->
		<label class="col-md-2 control-label" for="profileLastName"><b>Buyer :</b></label>
			<div class="col-md-4">
				<?php echo $cmts['buyer_id'];?>
			</div>
	</div>
	<div class="form-group"  style="font-size: 12px;">
	    <label class="col-md-2 control-label" for="profileLastName"><b>Wo No :</b></label>
			<div class="col-md-4">
				<?php echo $cmts['wo_no'];?>
			</div>
		<label class="col-md-2 control-label" for="profileLastName"><b>Color QR:</b></label>
			<div class="col-md-4">
				<?php echo $cmts['garment_colors'];?>
			</div>
	</div>
	<hr>
	<div class="form-group"  style="font-size: 12px;">
	    <label class="col-md-2 control-label" for="profileLastName"><b>Total Qty Create Lot Rework (M):</b></label>
			<div class="col-md-2">
				<?php echo number_format($cmts['created']);?>
			</div>
		<label class="col-md-2 control-label" for="profileLastName"><b>Balance Qty Receive Lot (W):</b></label>
			<div class="col-md-2">
				<?php 
					echo "<b>".number_format($cmts['balance'])."</b>";
				?>
				<input type="hidden" id="sisalotout" name="sisalotout" value="<?php echo $cmts['balance']; ?>">
			</div>
		<label class="col-md-2 control-label" for="profileLastName"><b>Total Qty Receive Lot (W):</b></label>
			<div class="col-md-2">
				<?php echo number_format($cmts['receive']);?>
			</div>
	</div>
	<hr>
	<input type="hidden" name="lastrec" id="lastrec" value="<?=$cmts[rec_status]?>">
	<input type="hidden" id="gettype" name="gettype" value="1">

