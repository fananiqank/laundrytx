
<?php 
session_start();
include '../../../funlibs.php';
$con = new Database;

$cmt_no = $con->searchseqcmt($_GET['id']);
$no=1;

$selcmt = $con->select("laundry_wo_master_dtl_proc a 
                        join laundry_lot_number b on a.wo_master_dtl_proc_id = b.wo_master_dtl_proc_id
                        JOIN laundry_data_wo c on a.wo_no=c.wo_no
                        JOIN laundry_role_wo d on a.role_wo_master_id=d.role_wo_master_id
                        JOIN laundry_role_dtl_wo e ON d.role_wo_id = e.role_wo_id
                        ",
                        "a.wo_master_dtl_proc_id,
                         a.wo_no,
                         a.buyer_id,
                         a.garment_colors,
                         a.role_wo_master_id,
                         a.color_wash,
                         b.lot_id,
                         b.lot_qty,
                         b.lot_no as lotno,
                         b.lot_createdby,
                         b.lot_qty_good_upd,
                         b.lot_status,
                         b.master_type_lot_id,
                         b.lot_type,
                         a.ex_fty_date,
                         to_char(a.ex_fty_date,'DD-MM-YYYY') as ex_fty_date_show,
                         b.lot_kg,
                         e.master_process_id
                        ",
                        "lot_no = '".$_GET['lot']."'
                         ");

foreach ($selcmt as $cmt) {}

//cek apakah process bisa dilakukan split
	foreach ($con->select("laundry_role_child a join laundry_process b on a.lot_no=b.lot_no and a.role_wo_id=b.role_wo_id  and a.role_dtl_wo_id=b.role_dtl_wo_id join laundry_master_process c on b.master_process_id=c.master_process_id",
		                   "b.process_type,c.master_process_split_lot,b.master_process_id",
		                   "a.lot_no ='".$_GET['lot']."' and role_child_status = 0") 
			as $cekrole) {}
	// echo "select b.process_type,c.master_process_split_lot,b.master_process_id from laundry_role_child a join laundry_process b on a.lot_no=b.lot_no and a.role_wo_id=b.role_wo_id  and a.role_dtl_wo_id=b.role_dtl_wo_id join laundry_master_process c on b.master_process_id=c.master_process_id where a.lot_no ='".$_GET['lot']."' and role_child_status = 0";
//cek lot number sudah despatch atau belum
$cekdespatch = $con->selectcount("laundry_process","lot_no","lot_no = '".$_GET['lot']."' and master_type_process_id = 6 and process_type = 4");
	

	if ($cmt['lot_id'] == ''){
		echo "<script>
				swal({
					icon: 'warning',
					title: 'Lot Number Not Found',
					timer: 1000,
				});
				$('#lot_no').val('');
				document.getElementById('lot_no').focus();
			</script>";
	} else if ($cmt['lot_status'] == '0'){
		echo "<script>
				swal({
					icon: 'warning',
					title: 'Lot Number is Break',
					timer: 2000,
				});
				$('#lot_no').val('');
				document.getElementById('lot_no').focus();
			</script>";
	} else if ($cmt['lot_type'] == 'Q' || $cmt['lot_type'] == 'F' || $cmt['lot_type'] == 'P'){
		echo "<script>
				swal({
					icon: 'warning',
					title: 'Lot Number Can not Split',
					text: 'QA Sample | Pre Bulk | First Bulk Can not Split',
					timer: 3000,
				});
				$('#lot_no').val('');
				document.getElementById('lot_no').focus();
			</script>";
	} else if ($cekdespatch > '0'){
		echo "<script>
				swal({
					icon: 'warning',
					title: 'Lot Number is Done',
					text: 'Sequence Lot is Done',
					timer: 2000,
				});
				$('#lot_no').val('');
				document.getElementById('lot_no').focus();
			</script>";
	}  else if ($cekrole['master_process_split_lot'] == '1' && $cekrole['process_type'] == '1'){
?>
		<div class="form-group" >
            <label class="col-sm-12 col-md-12 col-lg-12 control-label" for="profileLastName"><b>WO Details</b></label>
			    <div class="col-sm-12 col-md-12 col-lg-12"  style="background-color: #FFFFFF;" >
			
			    </div>
        </div>
		<div class="form-group"  style="font-size: 12px;">
			    <label class="col-md-2 control-label" for="profileLastName"><b>Date :</b></label>
					<div class="col-md-4">
						<?php echo date('d-m-Y'); ?>
						<input id="datedetail" name="datedetail" value="<?php echo date('Y-m-d'); ?>" type="hidden">
					</div>
				<label class="col-md-2 control-label" for="profileLastName"><b>Lot Number :</b></label>
					<div class="col-md-4">
						<?php echo $cmt['lotno'];?>
						<input type="hidden" id="lot_no_process" name="lot_no_process" value="<?php echo $cmt[lotno]; ?>">
					</div>
		</div>
		<div class="form-group"  style="font-size: 12px;">
			    <label class="col-md-2 control-label" for="profileLastName"><b>WO No :</b></label>
					<div class="col-md-4">
						<?php echo $cmt['wo_no'];?>
						<input type="hidden" id="wo_no_process" name="wo_no_process" value="<?php echo $cmt[wo_no]; ?>">
					</div>
				 <label class="col-md-2 control-label" for="profileLastName"><b>Buyer :</b></label>
					<div class="col-md-4">
						<?php echo $cmt['buyer_id'];?>
					</div>
		</div>
		<div class="form-group"  style="font-size: 12px;">
				<label class="col-md-2 control-label" for="profileLastName"><b>Color QR :</b></label>
					<div class="col-md-4">
						<?php echo $cmt['garment_colors'];?>
						<input type="hidden" id="garment_colors_process" name="garment_colors_process" value="<?php echo $cmt[garment_colors]; ?>">
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
						<input type="hidden" id="qty_process" name="qty_process" value="<?php echo $qtyprocess; ?>">
					</div>
		</div>
		<div class="form-group"  style="font-size: 12px;">
				<label class="col-md-2 control-label" for="profileLastName"><b>Color Wash :</b></label>
					<div class="col-md-4">
						<?php echo $cmt['color_wash'];?></div>
				<label class="col-md-2 control-label" for="profileLastName"><b>&nbsp;</b></label>
					<div class="col-md-4">
						&nbsp;
					</div>
		</div>
		<div class="form-group"  style="font-size: 12px;">
				<label class="col-md-2 control-label" for="profileLastName"><b>Ex Fty Date :</b></label>
					<div class="col-md-4">
						<?php echo $cmt['ex_fty_date_show'];?>
						<input type="hidden" id="ex_fty_date_process" name="ex_fty_date_process" value="<?php echo $cmt[ex_fty_date]; ?>">
					</div>
				<label class="col-md-2 control-label" for="profileLastName"><b>Kg :</b></label>
					<div class="col-md-4">
						<?php echo $cmt['lot_qty']; ?>
						<input type="hidden" id="kg_process" name="kg_process" value="<?php echo $kgprocess; ?>">
					</div>
		</div>
		<hr>
        <div class="form-group">
                <label class="col-sm-2 col-md-2 col-lg-2 control-label" for="profileLastName"><b>Split Lot</b></label>
                    <div class="col-sm-1 col-md-1 col-lg-1"  style="background-color: #FFFFFF;" id="tampilcmt">
                        <input type="text" class="form-control" id="split_no" name="split_no" value="2" onchange="cekpoint(this.value)">
                    </div>
                	<div class="col-sm-9 col-md-9 col-md-9">
                        <a href="javascript:void(0)" class="btn btn-warning" onclick="tampilsplit('<?php echo $cmt[lot_id]; ?>',split_no.value,'<?php echo $cmt[master_type_lot_id]; ?>')">Ready</a>
                    </div>
        		<input id="master_process_id" name="master_process_id" value="<?php echo $cekrole[master_process_id]; ?>" type="hidden">
        </div>
        <hr>
            
        <div class="form-group" id="tampilsplit">
				       
        </div>
<?php
	} 
	else {
		echo "<script>
				swal({
					icon: 'warning',
					title: 'Next Process Can not Split',
					text: 'check your next process and Lot after IN Process',
					timer: 2000,
				});
				$('#lot_no').val('');
				document.getElementById('lot_no').focus();
			</script>";
	}      