<?php 
session_start();
include '../../../funlibs.php';
$con = new Database;

$buyer_no = $con->searchseq($_GET['b']);
$style_no = $con->searchseq($_GET['s']);
$cmt_no = $con->searchseq($_GET['cm']);
$color_no = $con->searchseq($_GET['co']);
$tglship1 = $_GET['tgl'];
$tglship2 = $_GET['tgl2'];

$no=1;
if ($buyer_no != ''){
	$buyerno = "and a.buyer_po_number = '$buyer_no'";
} else {
	$buyerno = "";
}

if ($style_no != ''){
	$styleno = "and b.buyer_style_no = '$style_no'";
} else {
	$styleno = "";
}

if ($cmt_no != ''){
	$cmtno = "and b.wo_no = '$cmt_no'";
} else {
	$cmtno = "";
}

if ($color_no != ''){
	$colorno = "and a.garment_colors = '$color_no'";
} else {
	$colorno = "";
}

if ($tglship1 == 'A' && $tglship2 == 'A'){
	$tgls = "";
} else if ($tglship1 == '' && $tglship2 == ''){
	$tgls = "";
} else {
	$tgls = "and DATE(a.ex_fty_date) BETWEEN '$tglship1' AND '$tglship2'";
}

    $selwomas = $con->select("laundry_wo_master_dtl a join laundry_wo_master b on a.wo_master_id=b.wo_master_id","b.wo_no,a.garment_colors","a.wo_master_dtl_status = 0 and wo_no NOT IN (select wo_no from laundry_wo_master_keranjang) $buyerno $styleno $cmtno $colorno $tgls GROUP BY a.garment_colors,b.wo_no");
    // echo "select b.wo_no,a.garment_colors,a.wo_master_dtl_id from laundry_wo_master_dtl a join laundry_wo_master b on a.wo_master_id=b.wo_master_id where a.wo_master_dtl_status = 0 and wo_no NOT IN (select wo_no from laundry_wo_master_keranjang) and $buyerno $styleno $cmtno $colorno $tgls GROUP BY a.garment_colors,b.wo_no,a.wo_master_dtl_id";
foreach ($selwomas as $wm) {
?>
    <input type="checkbox" id="cmtno_<?=$no?>" name="cmtno[]" value="<?php echo $wm[wo_no]; ?>"> <?php echo $wm['wo_no']." - ".$wm['garment_colors']; ?> <br>
<?php 
$no++;} 
$seljmlwo = $con->select("(select b.wo_no,a.garment_colors from laundry_wo_master_dtl a join laundry_wo_master b on a.wo_master_id=b.wo_master_id where a.wo_master_dtl_status = 0 and wo_no NOT IN (select wo_no from laundry_wo_master_keranjang) $buyerno $styleno $cmtno $colorno $tgls GROUP BY a.garment_colors,b.wo_no) as hitung","count(wo_no) as jmlwo");
foreach ($seljmlwo as $jmlwo) {}
?>
<input value="<?php echo $jmlwo[jmlwo];?>" id="jmlwip" name="jmlwip" type="hidden"  >