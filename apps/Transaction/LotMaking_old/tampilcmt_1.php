<?php 
session_start();
include '../../../funlibs.php';
$con = new Database;

$cmt_no = $con->searchseqcmt($_GET['cmt']);
$color_no = $con->searchseq($_GET['col']);
$ex_fty_date = $_GET['exdate'];
$no=1;

if($_GET['type'] == 1) {
	if ($cmt_no != ''){
		$cmtno = "and b.wo_no = '".$cmt_no."' and a.garment_colors = '".$color_no."' and DATE(a.ex_fty_date) = '".$ex_fty_date."'";
	} else {
		$cmtno = "";
	}
		if ($cmt_no != ''){
			$table = "laundry_wo_master_dtl_proc a 
				JOIN (select wo_no,buyer_id from laundry_data_wo GROUP BY wo_no,buyer_id) b on a.wo_no=b.wo_no
				JOIN (select min(rec_createdate) as firstdate,wo_no,garment_colors,rec_status from 
					  laundry_receive 
					  where wo_no = '".$cmt_no."' and garment_colors = '".$color_no."' 
					  GROUP BY wo_no,garment_colors,rec_status) 
				AS c on a.wo_no = c.wo_no and a.garment_colors = c.garment_colors
				JOIN (select sum(rec_qty) as wo_master_dtl_proc_qty_rec,wo_master_dtl_proc_id 
								from laundry_receive GROUP BY wo_master_dtl_proc_id) as H
								ON a.wo_master_dtl_proc_id=H.wo_master_dtl_proc_id
				LEFT JOIN (select max(rec_createdate) as lastdate,wo_no,garment_colors 
						   from laundry_receive 
						   where wo_no = '$cmt_no' and garment_colors = '$color_no' and rec_status = '2' 
						   GROUP BY wo_no,garment_colors) 
				AS d on a.wo_no = d.wo_no and a.garment_colors = d.garment_colors 
				LEFT JOIN laundry_lot_number e on a.wo_master_dtl_proc_id = e.wo_master_dtl_proc_id and e.create_type = 1 and role_wo_name_seq = '".$_GET[seqlot]."' 
				LEFT JOIN (select sum(process_qty_good) as qty_good,wo_master_dtl_proc_id 
				           from laundry_process 
				           where master_type_process_id = '2' and wo_no = '".$cmt_no."' and 
				           garment_colors = '".$color_no."' 
				           and role_wo_id = '".$_GET[rolewoid]."'  
				           GROUP BY wo_master_dtl_proc_id) 
				as f ON a.wo_master_dtl_proc_id = f.wo_master_dtl_proc_id
				LEFT JOIN (select COUNT(qrcode_key) as cut_qty,wo_no, color,ex_fty_date from qrcode_ticketing_master where wo_no = '".$cmt_no."' and color = '".$color_no."' and DATE(ex_fty_date) = '".$ex_fty_date."' and washtype = 'Wash' GROUP BY wo_no, color,ex_fty_date) as g on a.wo_no = g.wo_no and a.garment_colors = g.color and a.ex_fty_date=g.ex_fty_date
				";
			$field = "a.wo_no,a.buyer_po_number,a.wo_master_dtl_proc_qty_md,a.garment_colors,b.buyer_id,H.wo_master_dtl_proc_qty_rec,firstdate,lastdate,SUM(e.lot_qty) as totallot,f.qty_good,c.rec_status,DATE(a.ex_fty_date) as ex_fty_date, to_char(a.ex_fty_date, 'DD-MM-YYYY') as ex_fty_date_show,cut_qty as cutting_qty,a.color_wash";
			$where = "wo_master_dtl_proc_status = 1 ".$cmtno ."
				GROUP BY A.wo_no,A.buyer_po_number,a.wo_master_dtl_proc_qty_md,A.garment_colors,b.buyer_id,
					cut_qty,H.wo_master_dtl_proc_qty_rec,firstdate,lastdate,f.qty_good,rec_status,a.ex_fty_date,a.color_wash";
			$selcmts = $con->select($table,$field,$where);
			//echo "select $field from $table where $where";
			foreach ($selcmts as $cmts) {}
		}
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
	<div class="form-group"  style="font-size: 12px;">
	    <label class="col-md-2 control-label" for="profileLastName"><b>&nbsp;</b></label>
			<div class="col-md-4">
				&nbsp;
			</div>
		<label class="col-md-2 control-label" for="profileLastName"><b>Color Wash :</b></label>
			<div class="col-md-4">
				<?php echo $cmts['color_wash'];?>
			</div>
	</div>
	<div class="form-group"  style="font-size: 12px;">
	    <label class="col-md-2 control-label" for="profileLastName"><b>Ex Fty Date :</b></label>
			<div class="col-md-4">
				<?php echo $cmts['ex_fty_date'];?>
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
				echo number_format($sisacut);
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
					$sisalotout = $cmts['qty_good']-$cmts['totallot'];
					if($sisalotout < 0) {
						$sisalotout = 0;
					} else {
						$sisalotout = $sisalotout;	
					}
					echo number_format($sisalotout);
				?>
				<input type="hidden" id="sisalotout" name="sisalotout" value="<?php echo $sisalotout; ?>">
			</div>
	</div>
	<input type="hidden" name="lastrec" id="lastrec" value="<?=$cmts[rec_status]?>">
	<input type="hidden" id="gettype" name="gettype" value="1">
<?php 
}  else { 
	if ($cmt_no != ''){
		$where = "and a.wo_no = '".$cmt_no."' and a.garment_colors = '".$color_no."' and DATE(a.ex_fty_date) = '".$ex_fty_date."' ";
	} else {
		$where = "";
	}
		if ($cmt_no != ''){
			$table ="laundry_wo_master_dtl_proc a 
					JOIN (select wo_no,buyer_id from laundry_data_wo GROUP BY wo_no,buyer_id) b on a.wo_no=b.wo_no
					JOIN (select sum(lot_qty) as jmlqty,b.wo_master_dtl_proc_id from laundry_lot_number a join laundry_wo_master_dtl_proc b on a.wo_master_dtl_proc_id = b.wo_master_dtl_proc_id where wo_master_dtl_proc_status = 2 and lot_type = 'M' GROUP BY b.wo_master_dtl_proc_id ORDER BY rework_seq DESC) as c 
						on a.wo_master_dtl_proc_id=c.wo_master_dtl_proc_id
					LEFT JOIN (select sum(process_qty_good) as qty_good,a.wo_master_dtl_proc_id from laundry_process a join laundry_wo_master_dtl_proc b on a.wo_master_dtl_proc_id=b.wo_master_dtl_proc_id where master_type_process_id = '2' and b.wo_no = '$cmt_no' and b.garment_colors = '$color_no' and DATE(b.ex_fty_date) = '$ex_fty_date' and role_wo_id = '".$_GET[rolewoid]."' and a.lot_type = 2 AND b.wo_master_dtl_proc_status = 2 GROUP BY a.wo_master_dtl_proc_id) as f ON a.wo_master_dtl_proc_id = f.wo_master_dtl_proc_id
					LEFT JOIN laundry_lot_number e on a.wo_master_dtl_proc_id = e.wo_master_dtl_proc_id and e.create_type = 6  and role_wo_name_seq = '".$_GET[seqlot]."'
					LEFT JOIN (select process_qty_good,wo_master_dtl_proc_id from laundry_process where master_type_process_id = '3') as d on a.wo_master_dtl_proc_id=d.wo_master_dtl_proc_id
					";
			$field = "a.wo_no,a.garment_colors,b.buyer_id,c.jmlqty,COALESCE (f.qty_good,0) AS qty_good_in,COALESCE (d.process_qty_good,0 ) AS process_qty_good,COALESCE(SUM(e.lot_qty),0) as totallot,a.color_wash";
			$where_rwk = "wo_master_dtl_proc_status = 2 ".$where."
				GROUP BY A.wo_no,A.garment_colors,b.buyer_id,f.qty_good,c.jmlqty,d.process_qty_good,rework_seq,color_wash";
			$selcmts = $con->select($table,$field,$where_rwk,"rework_seq DESC");
			//echo "select $field from $table where $where_rwk ORDER BY rework_seq DESC";
			foreach ($selcmts as $cmts) {}
		}
	?>

	<div class="form-group"  style="font-size: 12px;">
	    <label class="col-md-2 control-label" for="profileLastName"><b>Date :</b></label>
			<div class="col-md-4">
				<?php echo date('Y-m-d'); ?>
				<input id="datedetail" name="datedetail" value="<?php echo date('Y-m-d'); ?>" type="hidden">
			</div>
		<label class="col-md-2 control-label" for="profileLastName"><b>WO No :</b></label>
			<div class="col-md-4">
				<?php echo $cmts['wo_no'];?>
			</div>
	</div>
	<div class="form-group"  style="font-size: 12px;">
	    <label class="col-md-2 control-label" for="profileLastName"><b>Buyer :</b></label>
			<div class="col-md-4">
				<?php echo $cmts['buyer_id'];?>
			</div>
		<label class="col-md-2 control-label" for="profileLastName"><b>Color :</b></label>
			<div class="col-md-4">
				<?php echo $cmts['garment_colors'];?>
			</div>
	</div>
	<div class="form-group"  style="font-size: 12px;">
	    <label class="col-md-2 control-label" for="profileLastName"><b>&nbsp;</b></label>
			<div class="col-md-4">
				&nbsp;
			</div>
		<label class="col-md-2 control-label" for="profileLastName"><b>Color Wash :</b></label>
			<div class="col-md-4">
				<?php echo $cmts['color_wash'];?>
			</div>
	</div>
	<div class="form-group"  style="font-size: 12px;">
		<label class="col-md-2 control-label" for="profileLastName"><b>Qty Rework <br>(Total Rework):</b></label>
			<div class="col-md-4">
				<?php echo number_format($cmts['jmlqty']);?>
			</div>
		<label class="col-md-2 control-label" for="profileLastName"><b>Receive Lot <br> (Lot Making Rework):</b></label>
			<div class="col-md-4">
				<?php echo number_format($cmts['qty_good_in']); ?>
			</div>
	</div>
	<div class="form-group"  style="font-size: 12px;">
		<label class="col-md-2 control-label" for="profileLastName"><b>Total Qty Lot In <br> (after Lot making):</b>:</b></label>
			<div class="col-md-4">
				<?php echo number_format($cmts['totallot']); ?>
			</div>
		<label class="col-md-2 control-label" for="profileLastName"><b>Balance Qty Lot <br> (before Lot making)</b></label>
			<div class="col-md-4">
				<?php 
					$sisalotout = $cmts['qty_good_in']-$cmts['totallot'];
					if($sisalotout < 0) {
						$sisalotout = 0;
					} else {
						$sisalotout = $sisalotout;	
					}
					echo number_format($sisalotout);
				?>
				<input type="hidden" id="sisalotout" name="sisalotout" value="<?php echo $sisalotout; ?>">
			</div>
	</div>
	<input type="hidden" id="gettype" name="gettype" value="2">
<?php } ?> 