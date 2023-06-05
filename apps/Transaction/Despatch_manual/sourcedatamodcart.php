<?php 
if($_GET['reload'] == 1){
	session_start();
	include "../../../funlibs.php";
	$con = new Database();
}

$no = 1;
$selectuser = $con->select("m_users a join laundry_user_section b on a.user_id=b.user_id join laundry_master_type_process c on b.master_type_process_id = c.master_type_process_id","a.user_id,username,c.master_type_process_name","a.user_id = '$_SESSION[ID_LOGIN]'");
foreach ($selectuser as $user) {}

//mendapatkan jumlah qty di despatch atas lot tersebut
foreach($con->select("laundry_wo_master_dtl_despatch","SUM(wo_master_dtl_desp_qty) as qtydesp","wo_master_dtl_desp_status = '9' and lot_no = '$_GET[lot]'") as $qtydesp);

//mendapatkan jumlah qty good di qc atas lot tersebut 
foreach($con->select("laundry_wo_master_dtl_qc","SUM(wo_master_dtl_qc_qty) as qtyqc","wo_master_dtl_qc_type = '1' and wo_master_dtl_qc_status = 1 and lot_no = '$_GET[lot]'") as $qtyqc);

$selrechead = $con->select("laundry_wo_master_dtl_despatch","wo_no,garment_colors,garment_inseams,garment_sizes,wo_master_dtl_desp_qty,wo_master_dtl_desp_id,wo_master_dtl_desp_status,ex_fty_date,to_char(ex_fty_date,'DD-MM-YYYY') as ex_fty_date_show,lot_no","lot_no = '$_GET[lot]' and wo_master_dtl_desp_status = '9' and wo_master_dtl_desp_createdby = '$_SESSION[ID_LOGIN]'");
foreach ($selrechead as $head) {
?>
	<tr>
		<th width="5%"><?php echo $no?></th>
		<th width="15%"><?php echo $head['wo_no']?></th>
		<th width="15%"><?php echo $head['garment_colors']?></th>
		<th width="10%"><?php echo $head['ex_fty_date_show']?></th>
		<th width="5%"><?php echo $head['garment_inseams']?></th>
		<th width="5%"><?php echo $head['garment_sizes']?></th>
		<th width="5%"><?php echo $head['wo_master_dtl_desp_qty']?></th>
		<th width="5%">
			<a href="javascript:void(0)" class="btn btn-default" onclick="hapuscart('<?=$head[wo_master_dtl_desp_id]?>','<?=$head[lot_no]?>')"><i class="fa fa-trash"></i></a>
		</th>
	</tr>
<?php 
	  $no++; 
	  $total+= $head['wo_master_dtl_desp_qty'];
	}
?>
<tr>
	<th width="5%" colspan="6" style="text-align: center"><b>Total</b></th>
	<th><?=$total?><input name='totalall' id='totalall' value='<?=$total?>' type='hidden'></th>
</tr>
<tr>
<?php if($_GET['lot'] != '' && $qtyqc['qtyqc'] == $qtydesp['qtydesp']) { ?>
	<th width="5%" colspan="7" style="text-align: center">
		<a href="javascript:void(0)" class="btn btn-primary" onclick="savedespatch('<?=$_SESSION[ID_LOGIN]?>')">Submit</a>
	</th>
<?php } ?>
</tr>
<input name='wono' id='wono' value='<?=$head[wo_no]?>' type='hidden'>
<input name='colors' id='colors' value='<?=$head[garment_colors]?>' type='hidden'>
<input name='ex_fty_date' id='ex_fty_date' value='<?=$head[ex_fty_date]?>' type='hidden'>
<input name='lot_no' id='lot_no' value='<?=$head[lot_no]?>' type='hidden'>
