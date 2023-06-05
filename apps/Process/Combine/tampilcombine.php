<?php 
	session_start();
	include '../../../funlibs.php';
	$con = new Database;

//tampilkan data lot yang ada di keranjang
	$tablekeranjang = "(select A.*
from (	
			SELECT 
				A.*,
				C.master_type_lot_name,
				D.ex_fty_date,
				to_char( D.ex_fty_date, 'DD-MM-YYYY' ) AS ex_fty_date_show,
				D.color_wash,
				E.lotmaking_type
			FROM
				laundry_lot_number A 
				JOIN laundry_lot_number_keranjang b ON A.lot_id = b.lot_id
				JOIN laundry_master_type_lot C ON A.master_type_lot_id = C.master_type_lot_id
				JOIN laundry_wo_master_dtl_proc D ON A.wo_master_dtl_proc_id = D.wo_master_dtl_proc_id
				JOIN (select log_lot_tr,lotmaking_type from laundry_log group by log_lot_tr,lotmaking_type)as e ON A.lot_no = e.log_lot_tr
			WHERE
				b.lot_keranjang_status = 1
		UNION 
			select A.*,
				C.master_type_lot_name,
				D.ex_fty_date,
				to_char( D.ex_fty_date, 'DD-MM-YYYY' ) AS ex_fty_date_show,
				D.color_wash,
				E.lotmaking_type 
				from laundry_lot_number A 
				join laundry_master_type_lot C ON A.master_type_lot_id = C.master_type_lot_id
				join laundry_wo_master_dtl_proc D ON A.wo_master_dtl_proc_id = d.wo_master_dtl_proc_id
				join (select log_lot_tr,lotmaking_type from laundry_log group by log_lot_tr,lotmaking_type)as e ON A.lot_no = E.log_lot_tr where A.combine_hold = 1
		ORDER BY combine_hold
		) as A
		JOIN (
					SELECT 
							A.wo_no,A.garment_colors,D.ex_fty_date
						FROM
							laundry_lot_number A 
							JOIN laundry_lot_number_keranjang b ON A.lot_id = b.lot_id
							JOIN laundry_master_type_lot C ON A.master_type_lot_id = C.master_type_lot_id
							JOIN laundry_wo_master_dtl_proc D ON A.wo_master_dtl_proc_id = D.wo_master_dtl_proc_id
							join (select log_lot_tr from laundry_log group by log_lot_tr) as e ON A.lot_no = E.log_lot_tr
						WHERE
						b.lot_keranjang_status = 1
		) as B ON A.wo_no = B.wo_no and A.garment_colors=B.garment_colors and A.ex_fty_date=B.ex_fty_date) as asi";
	$fieldkeranjang = "*";
	$wherekeranjang = "";
//	echo "select $fieldkeranjang from $tablekeranjang where $wherekeranjang";
	$selkeranjang = $con->select($tablekeranjang,$fieldkeranjang,$wherekeranjang);
	foreach ($selkeranjang as $lotnum) {}

	//mendapatkan next process Lot number
	include "cekrole1.php";

	$datasave= $lotnum['wo_master_dtl_proc_id'].'_'.$lotnum['master_type_lot_id'].'_'.$lotnum['role_wo_master_id'].'_'.$lotnum['wo_no'].'_'.$lotnum['garment_colors'];
	
		$sequencecount = $con->selectcount("laundry_lot_number","lot_id","wo_master_dtl_proc_id = '".$lotnum['wo_master_dtl_proc_id']."'");
		$sequence = $sequencecount+1;

		// $urutcount = $con->selectcount("laundry_lot_number","lot_id","wo_no = '".$lotnum['wo_no']."'");
		// $urut = $urutcount+1;

		$selurutcount = $con->select("laundry_lot_number","COALESCE(max(lot_no_uniq),0) as max","wo_no = '$lotnum[wo_no]'");
		foreach($selurutcount as $urutcount){}
		$urut = $urutcount['max']+1;

		$expmt = explode('/',$lotnum['wo_no']);
		$trimexp6 = trim($expmt[6]);
		if($expmt[1] == 'RECUT'){
			$nolb ='L'.$expmt[0]."R".$expmt[3].$expmt[4].trim($expmt[5]).'C'.sprintf('%03s', $urut);
		} else {
			$nolb ='L'.$expmt[0].$expmt[4].$expmt[5].$trimexp6."C".sprintf('%03s', $urut);
		}
		$enknolb = $nolb."|".base64_encode($nolb);
//jika ada data di keranjang 
if ($lotnum['wo_master_dtl_proc_id'] != ''){
?>
<div class="form-group" align="left">
	<div class="col-sm-2 col-md-2 col-lg-2" align="right">
		<h6>No. Lot Combine</h6>
		<input type="hidden" id="nocombine" name="nocombine" value="<?php echo $nolb; ?>">
		<input type="hidden" id="uniqseq" name="uniqseq" value="<?php echo $urut ?>">
		<input type="hidden" id="rolewonameseq" name="rolewonameseq" value="<?php echo $lotnum[role_wo_name_seq]; ?>">
		<input type="hidden" id="lotmakingtype" name="lotmakingtype" value="<?php echo $lotnum[lotmaking_type]; ?>">
		<input type="hidden" id="lotshade" name="lotshade" value="<?php echo $lotnum[lot_shade]; ?>">
	</div>
	<div class="col-sm-4 col-md-4 col-lg-4">
		<h5>	: &nbsp; <b><?php echo $nolb; ?></b> </h5>
	</div>
	<div class="col-sm-2 col-md-2 col-lg-2" align="right">
		<h6>WO No</h6>
			<input type="hidden" id="wo_no" name="wo_no" value="<?php echo $lotnum[wo_no]; ?>">
	</div>
	<div class="col-sm-4 col-md-4 col-lg-4">
		<h5>	: &nbsp; <b><?php echo $lotnum['wo_no']; ?></b></h5>
	</div>
	<div class="col-sm-2 col-md-2 col-lg-2" align="right">
		<h6>Type / Next Process</h6>
			<input type="hidden" id="master_type_lot_id" name="master_type_lot_id" value="<?php echo $lotnum[master_type_lot_id]; ?>">
	<!-- didapat dari cekrole1.php next process dari lot number-->
			<input type="hidden" id="master_process_id" name="master_process_id" value="<?php echo $nextstep[master_process_id]; ?>">
	<!-- ==========================-->
	</div>
	<div class="col-sm-4 col-md-4 col-lg-4">
		<h5>	: &nbsp; <b><?php echo $lotnum['master_type_lot_name']; ?></b> / <b><?php echo $nextstep['master_process_name']; ?></b></h5>
	</div>
	<div class="col-sm-2 col-md-2 col-lg-2" align="right">
		<h6>Color QR</h6>
			<input type="hidden" id="garment_colors" name="garment_colors" value="<?php echo $lotnum[garment_colors]; ?>">
	</div>
	<div class="col-sm-4 col-md-4 col-lg-4">
		<h5>	: &nbsp; <b><?php echo $lotnum['garment_colors']; ?></b></h5>
	</div>
	<div class="col-sm-2 col-md-2 col-lg-2" align="right">
		<h6>Color Wash</h6>
	</div>
	<div class="col-sm-4 col-md-4 col-lg-4">
		<h5> : &nbsp; <b><?php echo $lotnum['color_wash']; ?></h5>
	</div>
	<div class="col-sm-2 col-md-2 col-lg-2" align="right">
		<h6>Ex Fty Date</h6>
			<input type="hidden" id="ex_fty_date" name="ex_fty_date" value="<?php echo $lotnum[ex_fty_date]; ?>">
	</div>
	<div class="col-sm-4 col-md-4 col-lg-4">
		<h5>	: &nbsp; <b><?php echo $lotnum['ex_fty_date_show']; ?></b></h5>
	</div>
	

</div>
<div class="form-group" align="left">
</div>
<hr>
<div class="form-group" align="center">
			<div class="col-sm-1 col-md-1 col-lg-1">
				&nbsp;
			</div>
			<div class="col-sm-2 col-md-2 col-lg-2" align="left">
			    &nbsp;
			</div>
			<div class="col-sm-2 col-md-2 col-lg-2">
				<b>Shade</b>
			</div>
			<div class="col-sm-2 col-md-2 col-lg-2">
			   	<b>Pcs</b>
			</div>
			<div class="col-sm-2 col-md-2 col-lg-2">
				<b>Balance</b>
			</div>
			<div class="col-sm-2 col-md-2 col-lg-2">
				<b>Kg</b>
			</div>
			
			<div class="col-sm-1 col-md-1 col-lg-1">
				&nbsp;
			</div>
	</div>
<?php
} 

	$no= 1;
	$count=1;

	foreach($selkeranjang as $keranjang){
		if($keranjang['lot_qty_good_upd'] != ''){
			$qty = $keranjang['lot_qty_good_upd'];
		} else {
			$qty = $keranjang['lot_qty'];
		}

		$qtykg=$keranjang['lot_kg'];
?>

	<div class="form-group" align="center">
			<div class="col-sm-1 col-md-1 col-lg-1">
				<?php echo $no; ?>
			</div>
			<div class="col-sm-2 col-md-2 col-lg-2" align="left">
			    <b><?php echo $keranjang['lot_no']; ?></b>   
			    <input id="lot_num[]" name="lot_num[]" type="hidden" value="<?php echo $keranjang[lot_id]; ?>">
			    <input id="lot_no_process[]" name="lot_no_process[]" type="hidden" value="<?php echo $keranjang[lot_no]; ?>">
			</div>
			<div class="col-sm-2 col-md-2 col-lg-2">
				<input id="shade[]" name="shade[]" type="text" class="form-control" value="<?php echo $keranjang[lot_shade]; ?>" readonly>
			</div>
			<div class="col-sm-2 col-md-2 col-lg-2">
			   	<input id="qtylot_<?=$no?>" name="qtylot[]" type="text" placeholder="qty" class="form-control" onkeyup='cekqtylot(this.value)' value="<?php echo $qty; ?>" onkeydown='return hanyaAngka(this, event);' required readonly> 
			   	<input id="qtylotori_<?php echo $no; ?>" name="qtylotoori[]" type="hidden" value="<?php echo $qty; ?>" required readonly> 
			</div>
			<div class="col-sm-2 col-md-2 col-lg-2" align="left">
				<input id="balance_<?php echo $no; ?>" name="balance[]" type="text" placeholder="balance" class="form-control" onkeyup='cekqtylot(this.value)' value="0" onkeydown='return hanyaAngka(this, event);' style="display: none" required readonly> 
			</div>
			<div class="col-sm-2 col-md-2 col-lg-2">
				<input id="kg_<?php echo $no; ?>" name="kg[]" type="text" placeholder="Kg" class="form-control" onkeyup='cekkg(this.value)' value="<?php echo $qtykg; ?>" onkeydown='return hanyaAngka(this, event);' required>
			</div>
			<?php if($keranjang['combine_hold'] == 0){ ?>
				<div class="col-sm-1 col-md-1 col-lg-1">
					<a href="javascript:void(0)" class="btn btn-default" style="padding: 4%;" onclick="hapuscombine('<?php echo $keranjang[lot_id]; ?>')"><i class="fa fa-close" style="color:#FF0000;"></i></a>
				</div>
			<?php } ?>
	</div>
	<?php 	
		$no ++;
		$totalqty += $qty;
		$totalkg += $qtykg;	
		$totalcount += $count;
		$totalbalance += $balance;	
	} 

if ($keranjang['lot_no'] != ''){
?>
<div class="form-group">
	<div class="col-sm-3 col-md-3 col-lg-3" align="center">
		<h4>Total</h4>
	</div>
	<div class="col-sm-2 col-md-2 col-lg-2">
	    &nbsp;
	</div>
	<div class="col-sm-2 col-md-2 col-lg-2">
	   	<input id="totalcombine" name="totalcombine" type="text" class="form-control" onkeyup='saveqty(this.value)' onkeydown='return hanyaAngka(this, event);' value="<?php echo $totalqty; ?>" readonly>
	</div>
	<div class="col-sm-2 col-md-2 col-lg-2" align="left">
		<input id="totalbalance" name="totalbalance" type="text" class="form-control" onkeydown='return hanyaAngka(this, event);' value="<?php echo $totalbalance; ?>" readonly>
	</div>
	<div class="col-sm-2 col-md-2 col-lg-2">
		<input id="totalkg" name="totalkg" type="text" class="form-control" onkeyup='saveqty(this.value)' onkeydown='return hanyaAngka(this, event);' value="<?php echo $totalkg; ?>" readonly>
	</div>
	<?php
		$selkeranjangawal = $con->select($tablekeranjang,$fieldkeranjang,$wherekeranjang,"","1");
		foreach ($selkeranjangawal as $ambillotawal) {}
	?>
	<input id="lotawalid" name="lotawalid" type="hidden" value="<?php echo $ambillotawal[lot_id]; ?>" required>
	<input id="lotawalno" name="lotawalno" type="hidden" value="<?php echo $ambillotawal[lot_no]; ?>" required>
	<input id="val" name="val" type="hidden" value="<?php echo $_GET[val]; ?>" required>
	<input id="datasave" name="datasave" type="hidden" value="<?php echo $datasave; ?>" >
	<input id="lotid" name="lotid" type="hidden" value="<?php echo $keranjang[lot_id]; ?>" >
	<input id="totalcount" name="totalcount" type="hidden" value="<?php echo $totalcount; ?>" >
	<input id="totalqtyori" name="totalqtyori" type="hidden" value="<?php echo $totalqty; ?>" >
</div>
<div class="form-group">
	&nbsp;
</div>
<div class="form-group"  style="font-size: 12px;" align="center">
	<a href="javascript:void(0)" class="btn btn-primary" id="nextprocess" onclick="savecombine('<?php echo $enknolb?>')">Submit</a>
			
</div>
<?php } ?>