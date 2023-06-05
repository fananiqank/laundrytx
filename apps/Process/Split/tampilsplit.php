<?php 
	session_start();
	include '../../../funlibs.php';
	$con = new Database;

	foreach ($con->select("laundry_lot_number a JOIN laundry_master_type_lot b on a.master_type_lot_id=b.master_type_lot_id","a.*,b.master_type_lot_initial","lot_id = '".$_GET['id']."'") as $lotnum){}

	$datasave= $lotnum['wo_master_dtl_proc_id'].'_'.$lotnum['master_type_lot_id'].'_'.$lotnum['role_wo_master_id'].'_'.$lotnum['wo_no'].'_'.$lotnum['garment_colors'];

	if ($lotnum['lot_qty_good_upd'] != ''){
		$totqty = $lotnum['lot_qty_good_upd'];
	} else {
		$totqty = $lotnum['lot_qty'];
	}
?>
<div class="form-group" align="center">
	<div class="col-sm-1 col-md-1 col-lg-1">
	      &nbsp;
	</div>
	<div class="col-sm-2 col-md-2 col-lg-2">
	      &nbsp;
	</div>
	<div class="col-sm-2 col-md-2 col-lg-2">
	    <h4>Shade</h4>
	</div>
	<div class="col-sm-2 col-md-2 col-lg-2">
	    <h4>Pcs</h4>
	</div>
	<div class="col-sm-2 col-md-2 col-lg-2">
	    <h4>Kg</h4>
	</div>
	<div class="col-sm-3 col-md-3 col-lg-3">
	      &nbsp;
	</div>
</div>
<?php 
	$no= 1;
	for ($i=1;$i<=$_GET['val'];$i++) {

		$sequencecount = $con->selectcount("laundry_lot_number","lot_id","wo_master_dtl_proc_id = '".$lotnum['wo_master_dtl_proc_id']."'");
		$sequence = $sequencecount+$i;
		
		// $urutcount = $con->selectcount("laundry_lot_number","lot_id","wo_no = '".$lotnum['wo_no']."'");
		// $urut = $urutcount+$i;
		$selurutcount = $con->select("laundry_lot_number","COALESCE(max(lot_no_uniq),0) as max","wo_no = '$lotnum[wo_no]'");
		foreach($selurutcount as $urutcount){}
		$urut = $urutcount['max']+$i;

		$expmt = explode('/',$lotnum['wo_no']);
		$trimexp6 = trim($expmt[6]);

		if($expmt[1] == 'RECUT'){
			$nolb ='L'.$expmt[0]."R".$expmt[3].$expmt[4].trim($expmt[5]).'B'.sprintf('%03s', $urut);
		} else {
			$nolb ='L'.$expmt[0].$expmt[4].$expmt[5].$trimexp6."B".sprintf('%03s', $urut);
		}

?>
<div class="form-group" align="center">
		<div class="col-sm-1 col-md-1 col-lg-1">
			<?php echo $i; ?>
		</div>
		<div class="col-sm-2 col-md-2 col-lg-2">
		    <b><?php echo trim($nolb); ?></b>   
		    <input id="lot_num[]" name="lot_num[]" type="hidden" value="<?php echo $nolb; ?>">
		    <input id="uniqseq[]" name="uniqseq[]" type="hidden" value="<?php echo $urut ?>">
		    <input id="lot_for_js_<?=$no?>" name="lot_for_js_<?php echo $no; ?>" type="hidden" value="<?php echo $nolb; ?>">
		</div>
		<div class="col-sm-2 col-md-2 col-lg-2">
			<input id="shade[]" name="shade[]" type="text" class="form-control" value="<?php echo $lotnum[lot_shade]; ?>" readonly>
		</div>
		<div class="col-sm-2 col-md-2 col-lg-2">
		   	<input id="qtysplit_<?php echo $i; ?>" name="qtysplit[]" type="text" placeholder="qty" class="form-control" onkeyup='cekqty(this.value)' onkeydown='return hanyaAngka(this, event);' required>	
		</div>
		<div class="col-sm-2 col-md-2 col-lg-2">
			<input id="kg_<?php echo $i; ?>" name="kg[]" type="text" placeholder="Kg" class="form-control" onkeyup='cekkg(this.value)' onkeydown='return hanyaAngka(this, event);' required>
		</div>
		<div class="col-sm-3 col-md-3 col-lg-3">
			&nbsp;
		</div>
</div>
<?php 	
	$totalno+= $no;
	} 
?>
<div class="form-group">
	<div class="col-sm-3 col-md-3 col-lg-3" align="center">
		<h4>Total</h4>
	</div>
	<div class="col-sm-2 col-md-2 col-lg-2">
	    &nbsp;
	</div>
	<div class="col-sm-2 col-md-2 col-lg-2">
	   	<input id="totalsplit" name="totalsplit" type="text" class="form-control" onkeyup='saveqty(this.value)' onkeydown='return hanyaAngka(this, event);' readonly>
	</div>
	<div class="col-sm-2 col-md-2 col-lg-2">
		<input id="totalkg" name="totalkg" type="text" class="form-control" onkeyup='cekkg(this.value)' onkeydown='return hanyaAngka(this, event);' readonly="">
	</div>
	<div class="col-sm-3 col-md-3 col-lg-3">
		&nbsp;
	</div>
	<input id="val" name="val" type="hidden" value="<?php echo $_GET[val]; ?>" required>
	<input id="qtydb" name="qtydb" type="hidden" value="<?php echo $totqty; ?>" required>
	<input id="datasave" name="datasave" type="hidden" value="<?php echo $datasave; ?>" >
	<input id="lotid" name="lotid" type="hidden" value="<?php echo $lotnum[lot_id]; ?>" >
	<input id="totalno" name="totalno" type="hidden" value="<?php echo $totalno; ?>" >
</div>
<div class="form-group">
	&nbsp;
</div>
<div class="form-group"  style="font-size: 12px;" align="center">
	<a href="javascript:void(0)" class="btn btn-primary" id="nextprocess" onclick="savesplit('<?php echo $_GET[lot]; ?>','<?php echo $_GET[user]; ?>','<?php echo $cmt[role_wo_master_id]; ?>','<?php echo $_GET[typelot]; ?>','<?php echo $role1[master_type_process_id]; ?>','<?php echo $role1[master_process_id]; ?>','<?php echo $cmt[id]; ?>','<?php echo $role1[role_dtl_wo_id]; ?>')">Submit</a>
			
</div>
