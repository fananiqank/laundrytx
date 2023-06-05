<?php 

if ($_GET['type']){
  session_start();
  include "../../../funlibs.php";
  $con = new Database();
}

if ($_GET['type']){
	$typescan = $_GET['type'];

} else {
	$typescan = $typescan;
}

//jumlah hasil scan
$cart = $con->selectcount("laundry_qc_keranjang","qc_keranjang_id","");
			
//wo dan colors dari hasil scan
foreach ($selwocolors = $con->select("laundry_qc_keranjang","count(qc_keranjang_id) as jmlscan,wo_no,garment_colors","wo_no like '%' GROUP BY wo_no,garment_colors") as $wocolors){}

//cutting qty 
foreach ($selcut = $con->select("laundry_wo_master_dtl_proc","wo_master_dtl_proc_id,cutting_qty,wo_master_dtl_proc_qty_rec","wo_no = '$wocolors[wo_no]' and garment_colors = '$wocolors[garment_colors]'") as $cut){}
	$totalrec = $cut['wo_master_dtl_proc_qty_rec']+$cart['jmlscan'];

if ($cart == '0'){
	echo "0";
} else {
	echo $cart;
}
?>
<input type="hidden" id="wo_no_scan" name="wo_no_scan" value="<?php echo $wocolors[wo_no]; ?>">
<input type="hidden" id="garment_colors_scan" name="garment_colors_scan" value="<?php echo $wocolors[garment_colors] ?>">
<input type="hidden" id="jumlah_scan" name="jumlah_scan" value="<?php echo $cart ?>">
<input type="hidden" name="cutqtywo" id="cutqtywo" value="<?=$cut[cutting_qty]?>">
<input type="hidden" name="totalqtyrec" id="totalqtyrec" value="<?=$totalrec?>">