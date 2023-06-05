<?php 
if ($cutqty['cutting_qty'] != ''){
	$readonly = "readonly";
} else {
	$readonly = "";
}

$no = 1;
foreach ($seltotscan as $totscan) 
{
	$datasave=$totscan['garment_inseams'].'|'.
		  	  $totscan['garment_sizes'].'|'.
		  	  $totscan['sequence_no'].'|'.
		  	  $totscan['totqty'];
	if ($totscan['ex_fty_date'] != '') {
		$exftydate = date('d-m-Y', strtotime($totscan['ex_fty_date']));
	} else {
		$exftydate = '';
	}
?>
	<tr>
		<th style="text-align: center"><?php echo $no?></th>
		<th><?php echo $totscan['wo_no']; ?></th>
		<th><?php echo $totscan['garment_colors']; ?></th>
		<th><?php echo $exftydate; ?></th>
		<th><?php echo $totscan['garment_sizes']; ?></th>
		<th><?php echo $totscan['garment_inseams']; ?></th>
		<th style="text-align: center"><?php echo $totscan['totqty']; ?>
			<input type="hidden" id="datasave[]" name="datasave[]" value="<?=$datasave?>">
		</th>
	</tr>
<?php 
	  $no++; 
	  $totalall += $totscan['totqty'];
}

$willrec = $cutqty['wo_master_dtl_proc_qty_rec']+$totalall;
$balance = $willrec-$cutqty['cutting_qty'];
?>
<tr style="font-size: 16px;">
	<th width="5%" colspan="6" style="text-align: center"><b>Total</b></th>
	<th width="5%" style="text-align: center"><b><?php echo $totalall; ?></b>
		<input type="hidden" id="totalrec" name="totalrec" value="<?=$totalall?>">
	</th>
	
</tr>
<?php if($_GET['type'] == '1') { ?>
	<tr style="background-color:  #FFE4E1;">
		<th width="5%" style="text-align: center" rowspan="3">
			<b>Note </b>
		</th>
		<th width="5%" style="text-align: center"  rowspan="3" colspan="1">
			<textarea class="form-control" id="remark" name="remark" ></textarea>
		</th>
		<th width="5%">
			<input type="checkbox" name="lastrec" id="lastrec" value="1" onclick="tooltip(this)">&nbsp;<b>Last Receive</b>
		</th>
		<th width="5%" align="right">
			Cut Qty:
		</th>
		<th width="5%">
			<input type="text" name="cutqty" id="cutqty" class="form-control" value="<?=$cutqty[cutting_qty]?>" onkeydown='return hanyaAngka(this, event)' onkeyup="hitungcut(this.value)" <?=$readonly?> readonly>
		</th>
		<th width="5%" colspan="2" rowspan="3">
			&nbsp;
		</th>
	</tr>
	<tr style="background-color:  #FFE4E1;">
		<th width="5%" rowspan="2">
			
		</th>
		<th width="5%" colspan="1" align="right">
			Balance :
		</th>
		<th width="5%">
			<input type="text" name="balance" id="balance" class="form-control" value="<?=$balance?>" onkeydown='return hanyaAngka(this, event)' readonly>
		</th>
	</tr>
	<tr style="background-color:  #FFE4E1;">
		<th width="5%" colspan="1" align="right">
			Total <br>Received :
		</th>
		<th width="5%">
			<input type="text" name="willrec" id="willrec" class="form-control" value="<?=$willrec?>" onkeydown='return hanyaAngka(this, event)' readonly>
		</th>
	</tr>
<?php } ?>
