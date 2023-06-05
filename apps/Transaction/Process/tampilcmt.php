<?php 
session_start();
include '../../../funlibs.php';
$con = new Database;

$cmt_no = $con->searchseqcmt($_GET['id']);
$no=1;

if ($cmt_no != ''){
	$cmtno = "and b.wo_no = '$cmt_no'";
} else {
	$cmtno = "";
}
	if ($cmt_no != ''){
		$selcmt = $con->select("laundry_wo_master_dtl_proc a JOIN work_orders b on a.wo_no=b.wo_no","a.wo_no,a.buyer_po_number,a.wo_master_dtl_proc_qty_md,a.garment_colors,b.buyer_name","wo_master_dtl_proc_status between 1 and 2 $cmtno");
		// echo "select wo_no,buyer_po_number,wo_master_dtl_proc_qty_md,garment_colors from laundry_wo_master_dtl_proc where wo_master_dtl_proc_status between 1 and 2 $cmtno";
		foreach ($selcmt as $cmt) {}
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
			<?php echo $cmt['buyer_po_number'];?>
		</div>
</div>
<div class="form-group"  style="font-size: 12px;">
    <label class="col-md-2 control-label" for="profileLastName"><b>Buyer :</b></label>
		<div class="col-md-4">
			<?php echo $cmt['buyer_name'];?>
		</div>
	<label class="col-md-2 control-label" for="profileLastName"><b>Color :</b></label>
		<div class="col-md-4">
			<?php echo $cmt['garment_colors'];?>
		</div>
</div>
<div class="form-group"  style="font-size: 12px;">
    <label class="col-md-2 control-label" for="profileLastName"><b>CMT Qty :</b></label>
		<div class="col-md-4">
			<?php echo $cmt['wo_master_dtl_proc_qty_md'];?>
		</div>
	<label class="col-md-2 control-label" for="profileLastName"><b>Cut Qty :</b></label>
		<div class="col-md-4">
			
		</div>
</div>
<div class="form-group"  style="font-size: 12px;">
    <label class="col-md-2 control-label" for="profileLastName"><b>Rec. Qty so for:</b></label>
		<div class="col-md-4">
			
		</div>
	<label class="col-md-2 control-label" for="profileLastName"><b>Sisa to Cut :</b></label>
		<div class="col-md-4">
			
		</div>
</div>
<div class="form-group"  style="font-size: 12px;">
    <label class="col-md-2 control-label" for="profileLastName"><b>First Date Rec.:</b></label>
		<div class="col-md-4">
			
		</div>
	<label class="col-md-2 control-label" for="profileLastName"><b>Last Date Rec.:</b></label>
		<div class="col-md-4">
			
		</div>
</div>