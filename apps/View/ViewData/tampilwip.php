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
	$buyerno = "and b.buyer_po_number = '$buyer_no'";
} else {
	$buyerno = "";
}

if ($style_no != ''){
	$styleno = "and a.buyer_style_no = '$style_no'";
} else {
	$styleno = "";
}

if ($cmt_no != ''){
	$cmtno = "and a.wo_no = '$cmt_no'";
} else {
	$cmtno = "";
}

if ($color_no != ''){
	$colorno = "and b.garment_colors = '$color_no'";
} else {
	$colorno = "";
}

if ($tglship1 == 'A' && $tglship2 == 'A'){
	$tgls = "";
} else if ($tglship1 == '' && $tglship2 == ''){
	$tgls = "";
} else {
	$tgls = "and DATE(b.ex_fty_date) BETWEEN '$tglship1' AND '$tglship2'";
}

$selwomas = $con->select("laundry_wo_master a join laundry_wo_master_dtl b on a.wo_master_id=b.wo_master_id","a.wo_no","wo_master_status = 0 and b.wo_master_dtl_status = 0");
    // echo "select a.wo_no from laundry_wo_master a join laundry_wo_master_dtl b on a.wo_master_id=b.wo_master_id where wo_master_status = 0 and b.wo_master_dtl_status = 0";
foreach ($selwomas as $wm) {
?>
    <input type="checkbox" id="cmtno_<?=$no?>" name="cmtno[]" value="<?php echo $wm[wo_no]; ?>"> <?php echo $wm['wo_no']; ?> <br>
<?php 
$no++;} 
$seljmlwo = $con->select("laundry_wo_master a join laundry_wo_master_dtl b on a.wo_master_id=b.wo_master_id","count(wo_no) as jmlwo","wo_master_status = 1 and wo_no NOT IN (select wo_no from laundry_wo_master_keranjang) and b.wo_master_dtl_status = 1 GROUP BY a.wo_no");
foreach ($seljmlwo as $jmlwo) {}
?>
<input value="<?php echo $jmlwo[jmlwo];?>" id="jmlwip" name="jmlwip" type="hidden"  >