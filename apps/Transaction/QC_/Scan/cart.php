<?php 
session_start();
include "../../../funlibs.php";
$con = new Database();

if ($_GET['type']){
	$typescan = $_GET['type'];
} else {
	$typescan = $typescan;
}
//jumlah hasil scan
foreach($selcountcart = $con->select("laundry_scan_qrcode","count(scan_id) as jmlscan","scan_status = 0 and scan_createdby = '$_SESSION[ID_LOGIN]' and scan_type = '$typescan'") as $cart){}

//wo dan colors dari hasil scan
foreach ($selwocolors = $con->select("laundry_scan_qrcode","count(scan_id) as jmlscan,wo_no,garment_colors","scan_status = 0 and scan_createdby = '$_SESSION[ID_LOGIN]' and scan_type = '$typescan' GROUP BY wo_no,garment_colors") as $wocolors){}

//cutting qty 
foreach ($selcut = $con->select("laundry_wo_master_dtl_proc","wo_master_dtl_proc_id,cutting_qty,wo_master_dtl_proc_qty_rec","wo_no = '$wocolors[wo_no]' and garment_colors = '$wocolors[garment_colors]'") as $cut){}
	
	$totalrec = $cut['wo_master_dtl_proc_qty_rec']+$cart['jmlscan'];

if ($cart['jmlscan'] == '0'){
	echo "0";
} else {
	echo $cart['jmlscan'];
}
?>
<input type="hidden" id="kodeid" name="kodeid" value="<?=$cut[wo_master_dtl_proc_id].'_'.$wocolors[wo_no]?>">
<input type="hidden" id="wo_no_scan" name="wo_no_scan" value="<?php echo $wocolors[wo_no]; ?>">
<input type="hidden" id="garment_colors_scan" name="garment_colors_scan" value="<?php echo $wocolors[garment_colors] ?>">
<input type="hidden" id="jumlah_scan" name="jumlah_scan" value="<?php echo $cart['jmlscan'] ?>">
<input type="hidden" name="cutqtywo" id="cutqtywo" value="<?=$cut[cutting_qty]?>">
<input type="hidden" name="totalqtyrec" id="totalqtyrec" value="<?=$totalrec?>">