
<?php 
session_start();
include '../../../funlibs.php';
$con = new Database;

$cmt_no = $con->searchseqcmt($_GET['id']);
$no=1;

$selcmt = $con->select("laundry_wo_master_dtl_proc a 
                        join laundry_lot_number b
                        on a.wo_master_dtl_proc_id = b.wo_master_dtl_proc_id
                        JOIN work_orders c on a.wo_no=c.wo_no
                        JOIN  laundry_role_wo d on a.role_wo_master_id=d.role_wo_master_id
                        LEFT JOIN laundry_role_dtl_wo e ON d.role_wo_id = e.role_wo_id
                        ",
                        "a.wo_master_dtl_proc_id,
                         a.wo_no,
                         a.buyer_id,
                         a.garment_colors,
                         a.role_wo_master_id,
                         b.lot_id,
                         b.lot_qty,
                         b.lot_no as lotno,
                         b.lot_createdby,
                         c.buyer_name,
                         b.lot_qty_good_upd,
                         b.lot_status,
                         b.master_type_lot_id
                        ",
                        "lot_no = '$_GET[lot]'
                         ");
// echo "select a.wo_master_dtl_proc_id,
//                          a.wo_no,
//                          a.buyer_id,
//                          a.garment_colors,
//                          a.role_wo_master_id,
//                          b.lot_qty,
//                          b.lot_no as lotno,
//                          b.lot_createdby,
//                          c.buyer_name
//                          from laundry_wo_master_dtl_proc a 
//                         join laundry_lot_number b
//                         on a.wo_no = b.wo_no and a.garment_colors=b.garment_colors
//                         JOIN work_orders c on a.wo_no=c.wo_no
//                         JOIN  laundry_role_wo d on a.role_wo_master_id=d.role_wo_master_id and d.role_wo_status = 1
//                         LEFT JOIN laundry_role_dtl_wo e ON d.role_wo_id = e.role_wo_id
//                         where rec_no = '$_GET[lot]'
//                       ";
foreach ($selcmt as $cmt) {}
	if ($cmt['lot_status'] == '0'){
		echo "<script>
				swal({
					icon: 'warning',
					title: 'Lot Number is Break',
					timer: 2000,
				});
			</script>";
	} else {

	?>
 		<div class="form-group" >
            <label class="col-sm-12 col-md-12 col-lg-12 control-label" for="profileLastName"><b>CMT Details</b></label>
			    <div class="col-sm-12 col-md-12 col-lg-12"  style="background-color: #FFFFFF;" >
			
			    </div>
        </div>
			<div class="form-group"  style="font-size: 12px;">
			    <label class="col-md-2 control-label" for="profileLastName"><b>Date :</b></label>
					<div class="col-md-4">
						<?php echo date('d-m-Y'); ?>
						<input id="datedetail" name="datedetail" value="<?=date('Y-m-d');?>" type="hidden">
					</div>
				<label class="col-md-2 control-label" for="profileLastName"><b>Lot Number :</b></label>
					<div class="col-md-4">
						<?php echo $cmt['lotno'];?>
						<input type="hidden" id="lot_no_process" name="lot_no_process" value="<?=$cmt[lotno]?>">
					</div>
			</div>
			<div class="form-group"  style="font-size: 12px;">
			    <label class="col-md-2 control-label" for="profileLastName"><b>CMT No :</b></label>
					<div class="col-md-4">
						<?php echo $cmt['wo_no'];?>
						<input type="hidden" id="wo_no_process" name="wo_no_process" value="<?=$cmt[wo_no]?>">
					</div>
				<label class="col-md-2 control-label" for="profileLastName"><b>Colors :</b></label>
					<div class="col-md-4">
						<?php echo $cmt['garment_colors'];?>
						<input type="hidden" id="garment_colors_process" name="garment_colors_process" value="<?=$cmt[garment_colors]?>">
					</div>
			</div>
			<div class="form-group"  style="font-size: 12px;">
			    <label class="col-md-2 control-label" for="profileLastName"><b>Buyer :</b></label>
					<div class="col-md-4">
						<?php echo $cmt['buyer_name'];?>
					</div>
				<label class="col-md-2 control-label" for="profileLastName"><b>Qty :</b></label>
					<div class="col-md-4">
						<?php 
							if ($cmt['lot_qty_good_upd'] != ''){
								echo $cmt['lot_qty_good_upd'];
							} else {
								echo $cmt['lot_qty']; 
							}
						?>
						<input type="hidden" id="qty_process" name="qty_process" value="<?=$qtyprocess?>">
					</div>
			</div>
			<hr>
            <div class="form-group">
                        <label class="col-sm-2 col-md-2 col-lg-2 control-label" for="profileLastName"><b>Split Lot</b></label>
                        <div class="col-sm-1 col-md-1 col-lg-1"  style="background-color: #FFFFFF;" id="tampilcmt">
                            <input type="text" class="form-control" id="split_no" name="split_no" value="2" onchange="cekpoint(this.value)">
                        </div>
                        <div class="col-sm-9 col-md-9 col-md-9">
                            <a href="javascript:void(0)" class="btn btn-warning" onclick="tampilsplit('<?=$cmt[lot_id]?>',split_no.value,'<?=$cmt[master_type_lot_id]?>')">Ready</a>
                        </div>
            </div>
            <hr>
            
            <div class="form-group" id="tampilsplit">
				       
            </div>
<?php } ?>      