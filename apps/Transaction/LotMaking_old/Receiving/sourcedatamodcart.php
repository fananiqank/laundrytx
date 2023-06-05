<?php 
if ($_GET['reload'] == 1){
	session_start();
	//echo $_SESSION['ID_PEG'];
	include "../../../funlibs.php";
	$con = new Database();
}

$exftydate = $_GET['ex'];

$num = 1;

//cek jumlah wip pada wip_dtl
$jmldtl = $con->selectcount("wip_dtl","wip_dtl_id","wip_dtl_status = '9'");

$selrechead = $con->select("wip_dtl","wo_no,garment_colors,ex_fty_date,SUM(quantity) as totquantity, SUM(wip_dtl_receive_qty) as totreceive, SUM(wip_dtl_good_qty) as totgood, SUM(wip_dtl_reject_qty) as totreject","wip_dtl_status = '9' and wip_dtl_createdby = '".$_SESSION['ID_LOGIN']."' GROUP BY wo_no,garment_colors,ex_fty_date");
//echo "select wo_no,garment_colors,ex_fty_date,SUM(quantity) as totquantity, SUM(wip_dtl_receive_qty) as totreceive, SUM(wip_dtl_good_qty) as totgood, SUM(wip_dtl_reject_qty) as totreject from wip_dtl where wip_dtl_status = '9' and wip_dtl_createdby = '$_SESSION[ID_LOGIN]' GROUP BY wo_no,garment_colors,ex_fty_date";
foreach ($selrechead as $head) {
	$selcut = $con->select("laundry_wo_master_dtl_proc","*","wo_no = '".$head['wo_no']."' and garment_colors = '".$head['garment_colors']."' and ex_fty_date = '".$head['ex_fty_date']."'");
	foreach ($selcut as $cut) {}

	$no = 1;
	$selrec = $con->select("wip_dtl","*","wip_dtl_status = '9' and wo_no = '".$head['wo_no']."' and garment_colors = '".$head['garment_colors']."' and ex_fty_date = '".$head['ex_fty_date']."' and wip_dtl_createdby = '".$_SESSION['ID_LOGIN']."'");
	
	foreach ($selrec as $wp) {
?>
	<tr>
		<th width="5%"><?php echo $no; ?></th>
		<th width="15%"><?php echo $wp['wo_no']; ?></th>
		<th width="15%"><?php echo $wp['garment_colors'];  ?></th>
		<th width="5%"><?php echo $wp['garment_inseams']; ?></th>
		<th width="5%"><?php echo $wp['garment_sizes']; ?></th>
		<th width="5%"><?php echo date('d-m-Y',strtotime($wp['ex_fty_date'])); ?></th>
		<th width="5%"><?php echo $wp['quantity']; ?></th>
		<th width="5%"><?php echo $wp['wip_dtl_receive_qty']; ?></th>
		<th width="5%"><?php echo $wp['wip_dtl_good_qty']; ?></th>
		<th width="5%"><?php echo $wp['wip_dtl_reject_qty']; ?></th>
		<th width="5%">
			<a href="javascript:void(0)" class="btn btn-default" onclick="hapuscart('<?php echo $wp[wip_dtl_id]; ?>','<?php echo $_GET[cm]; ?>','<?php echo $_GET[co]; ?>','<?php echo $_GET[in]; ?>','<?php echo $_GET[si]; ?>','<?php echo $_GET[ex]; ?>')"><i class="fa fa-trash"></i></a>
		</th>
	</tr>
<?php 
	  $no++; 
	  // $jmlsend += $wp['quantity'];
	  // $jmlrec += $wp['wip_dtl_receive_qty'];
	  // $jmlgood += $wp['wip_dtl_good_qty'];
	  // $jmlreject += $wp['wip_dtl_reject_qty'];
	  // $jmlqty += $wp['quantity'];
	}
?>
<tr>
	<th width="5%" colspan="6" style="text-align: center"><b>Total</b></th>
	<th width="5%"><?php echo $head['totquantity']?><input type="hidden" id="jumlahsend[]" name="jumlahsend[]" value="<?php echo $head[totquantity]; ?>"></th>
	<th width="5%"><?php echo $head['totreceive']?><input type="hidden" id="jumlahrec[]" name="jumlahrec[]" value="<?php echo $head[totreceive]; ?>"></th>
	<th width="5%"><b style="font-size: 16px;"><?php echo $head['totgood']?></b><input type="hidden" id="jumlahgood[]" name="jumlahgood[]" value="<?php echo $head[totgood]; ?>"></th>
	<th width="5%"><?php echo $head['totreject']?><input type="hidden" id="jumlahreject[]" name="jumlahreject[]" value="<?php echo $head[totreject]; ?>"></th>
	<th width="5%">
		
	</th>
</tr>
<tr style="background-color:  #FFE4E1;">
	<th width="5%" style="text-align: center" rowspan="2">
		<b>Note </b>
	</th>
	<th width="5%" style="text-align: center"  rowspan="2" colspan="2">
		<textarea class="form-control" id="note_<?php echo $num; ?>" name="note[]" ></textarea>
	</th>
	<th width="5%" colspan="1" align="right">
		Cut Qty:
	</th>
	<th width="5%" colspan="2">
		<input type="text" name="cutqty[]" id="cutqty_<?php echo $num; ?>" class="form-control" value="<?php echo $cut[cutting_qty]; ?>" onkeydown='return hanyaAngka(this, event)' onkeyup="hitungcut(this.value,<?=$num?>)" readonly>
	</th>
	<th width="5%" colspan="1" align="right">
		Total Received :
	</th>
	<th width="5%" colspan="2">
		<?php $willrec = $cut['wo_master_dtl_proc_qty_rec']+$head['totgood']; ?>
		<input type="text" name="willrec[]" id="willrec_<?php echo $num; ?>" class="form-control" value="<?php echo $willrec; ?>" onkeydown='return hanyaAngka(this, event)' readonly>
	</th>
	<th width="5%" rowspan="2" colspan="2">
		<input type="checkbox" name="lastrec[]" id="lastrec_<?php echo $num; ?>" value="1" onclick="tooltip(this,<?php echo $num; ?>)">&nbsp;<b>Last Receive</b>
		<input type="hidden" name="color[]" id="color[]" value="<?php echo $head[garment_colors]; ?>">
		<input type="hidden" name="wono[]" id="wono[]" value="<?php echo $head[wo_no]; ?>">
		<input type="hidden" name="exftydate[]" id="exftydate[]" value="<?php echo $head[ex_fty_date]; ?>">
	</th>
</tr>
<tr style="background-color:  #FFE4E1;">
	<th width="5%" colspan="1" align="right">
		User:
	</th>
	<th width="5%" colspan="2">
		<input type="text" name="username[]" id="username_<?php echo $num; ?>" class="form-control" value="">
		<input type="hidden" name="userid[]" id="userid_<?php echo $num; ?>" class="form-control" value="<?php echo $_SESSION[ID_LOGIN];?>" required>
	</th>
	<th width="5%" colspan="1" align="right">
		Balance :
	</th>
	<th width="5%" colspan="2">
		<?php $balance = $willrec-$cut['cutting_qty']; ?>
		<input type="text" name="balance[]" id="balance_<?php echo $num; ?>" class="form-control" value="<?php echo $balance; ?>" onkeydown='return hanyaAngka(this, event)' readonly>
	</th>
</tr>
<?php 
$num ++;

	$jumlah = $cut['cutting_qty']-$willrec;
?> 	
	<input type="hidden" name="totgood" id="totgood" value="<?php echo $head[totgood]; ?>">
	<input type="hidden" name="idcek" id="idcek" value="<?php echo $jumlah; ?>">
<?php 
} 
?>

