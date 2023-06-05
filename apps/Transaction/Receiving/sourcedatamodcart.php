<?php
if ($_GET['reload'] == 1) {
	session_start();
	//echo $_SESSION['ID_PEG'];
	include "../../../funlibs.php";
	$con = new Database();
}

$exftydate = $_GET['ex'];

$num = 1;	
	$jmldtl=0;
	$no = 1;
	$selrec = $con->select("laundry_receive_manualdtl_tmp", "*", "userid = '$_SESSION[ID_LOGIN]'");

	foreach ($selrec as $wp) {
?>
		<tr>
			<th width="5%"><?php echo $no ?></th>
			<th width="15%"><?php echo $wp['wo_no'] ?></th>
			<th width="15%"><?php echo $wp['garment_colors'] ?></th>
			<th width="5%"><?php echo $wp['recdtl_inseam'] ?></th>
			<th width="5%"><?php echo $wp['recdtl_size'] ?></th>
			<th width="5%"><?php echo date('d-m-Y', strtotime($wp['ex_fty_date'])) ?></th>
			<th width="5%"><?php echo $wp['recdtl_qty'] ?></th>
			
			<th width="5%">
				<a href="javascript:void(0)" class="btn btn-default" onclick="hapuscart(<?= $wp['recdtl_id'] ?>)"><i class="fa fa-trash"></i></a>
			</th>
		</tr>
	<?php
		$no++;
		$tqty+=$wp['recdtl_qty'];
		$jmldtl++;
		// $jmlsend += $wp['quantity'];
		// $jmlrec += $wp['wip_dtl_receive_qty'];
		// $jmlgood += $wp['wip_dtl_good_qty'];
		// $jmlreject += $wp['wip_dtl_reject_qty'];
		// $jmlqty += $wp['quantity'];
	}
	?>
	<tr>
		<th width="5%" colspan="3" style="text-align: center"><b>Total</b></th>
		<th width="5%"><?php echo $head['recdtl_qty'] ?><input type="hidden" id="jumlahsend[]" name="jumlahsend[]" value="<?= $head[recdtl_qty] ?>"></th>
		<th width="5%"><?php echo $head['recdtl_qty'] ?><input type="hidden" id="jumlahrec[]" name="jumlahrec[]" value="<?= $head[recdtl_qty] ?>"></th>
		<th width="5%"><b style="font-size: 16px;"><?php echo $head['recdtl_qty'] ?></b><input type="hidden" id="jumlahgood[]" name="jumlahgood[]" value="<?= $head[recdtl_qty] ?>"></th>
		<th width="5%"><?php echo $head['totreject'] ?><input type="hidden" id="jumlahreject[]" name="jumlahreject[]" value="<?= $head[totreject] ?>"></th>
		<th width="5%">

		</th>
	</tr>
	<tr style="background-color:  #FFE4E1;">
		<th width="5%" style="text-align: center" rowspan="1">
			<b>Note </b>
		</th>
		<th width="5%" style="text-align: center" rowspan="1" colspan="1">
			<textarea class="form-control" id="note_<?= $num ?>" name="note[]"></textarea>
		</th>
		<th width="5%" colspan="1" style="text-align: right;">
			User:
		</th>
		<th width="5%" colspan="1">
			<input type="text" name="username[]" id="username_<?= $num ?>" class="form-control" value="">
			<input type="hidden" name="userid[]" id="userid_<?= $num ?>" class="form-control" value="<?= $_SESSION[ID_LOGIN] ?>" required>
		</th>
		<th width="5%" colspan="1" style="text-align: right;">
			Total Received :
		</th>
		<th width="5%" colspan="2">
			
			<input type="text" name="willrec[]" id="willrec_<?= $num ?>" class="form-control" value="<?= $tqty ?>" onkeydown='return hanyaAngka(this, event)' readonly>
		</th>
		<th width="5%" rowspan="2" colspan="2">
			<input type="checkbox" name="lastrec[]" id="lastrec_<?= $num ?>" value="1" onclick="tooltip(this,<?= $num ?>)">&nbsp;<b>Last Receive</b>
			<input type="hidden" name="color[]" id="color[]" value="<?= $head[garment_colors] ?>">
			<input type="hidden" name="wono[]" id="wono[]" value="<?= $head[wo_no] ?>">
			<input type="hidden" name="exftydate[]" id="exftydate[]" value="<?= $head[ex_fty_date] ?>">
		</th>
	</tr>
	
	<?php
	$num++;

	$jumlah = $cut['cutting_qty'] - $willrec;
	?>
	<input type="hidden" name="totgood" id="totgood" value="<?= $head[totgood] ?>">
	<input type="hidden" name="idcek" id="idcek" value="<?= $jumlah ?>">
