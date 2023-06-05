<?php 
session_start();
include "../../../funlibs.php";
$con = new Database();

if ($_GET['type']){
	$typescan = $_GET['type'];
} else {
	$typescan = 4;
}
//jumlah hasil scan
$cart = $con->selectcount("laundry_scan_qrcode","scan_id","scan_status = 0 and scan_createdby = '$_SESSION[ID_LOGIN]' and scan_type = '$typescan' and lot_no = '$_GET[lot]'");
//echo "select scan_id from laundry_scan_qrcode where scan_status = 0 and scan_createdby = '$_SESSION[ID_LOGIN]' and scan_type = '$typescan' and lot_no = '$_GET[lot]'";
//wo dan colors dari hasil scan
foreach ($selwocolors = $con->select("laundry_scan_qrcode","count(scan_id) as jmlscan,wo_no,garment_colors","scan_status = 0 and scan_createdby = '$_SESSION[ID_LOGIN]' and scan_type = '$typescan' and lot_no = '$_GET[lot]' GROUP BY wo_no,garment_colors") as $wocolors){}

//cutting qty 
foreach ($selcut = $con->select("laundry_wo_master_dtl_proc","wo_master_dtl_proc_id,cutting_qty,wo_master_dtl_proc_qty_rec","wo_no = '$wocolors[wo_no]' and garment_colors = '$wocolors[garment_colors]'") as $cut){}
	
	$totalrec = $cut['wo_master_dtl_proc_qty_rec']+$cart;

//mendapatkan qty terakhir dari lot tersebut
$selcekqtylast = $con->select("laundry_wo_master_dtl_qc","wo_master_dtl_qc_qty","wo_master_dtl_qc_status = 1 and wo_master_dtl_qc_type = 1 and lot_no = '$_GET[lot]'");
foreach ($selcekqtylast as $qtylast) {}

// mendapatkan role wo id
	foreach($con->select("laundry_wo_master_dtl_proc A join laundry_role_wo B on A.role_wo_master_id=B.role_wo_master_id","b.role_wo_id","a.wo_no = '$wocolors[wo_no]' and a.garment_colors = '$wocolors[garment_colors]' and b.master_type_process_id = '6'") as $roleid){}
		
if ($cart == '0'){
	echo "0";
} else {
	echo $cart;
}
?>
<input type="hidden" id="kodeid" name="kodeid" value="<?=$cut[wo_master_dtl_proc_id].'_'.$wocolors[wo_no]?>">
<input type="hidden" id="wo_no_scan" name="wo_no_scan" value="<?php echo $wocolors[wo_no]; ?>">
<input type="hidden" id="garment_colors_scan" name="garment_colors_scan" value="<?php echo $wocolors[garment_colors] ?>">
<input type="hidden" id="jumlah_scan" name="jumlah_scan" value="<?php echo $cart ?>">
<input type="hidden" name="cutqtywo" id="cutqtywo" value="<?=$cut[cutting_qty]?>">
<input type="hidden" name="totalqtyrec" id="totalqtyrec" value="<?=$totalrec?>">
<input type="hidden" name="qtylast" id="qtylast" value="<?=$qtylast[wo_master_dtl_qc_qty]?>">
<input type="hidden" name="rolewoid" id="rolewoid" value="<?=$roleid[role_wo_id]?>">