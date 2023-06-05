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

if ($buyer_no != ''){
  $buyerno = "and C.buyer_po_number = '$buyer_no'";
} else {
  $buyerno = "";
}

if ($style_no != ''){
  $styleno = "and B.buyer_style_no = '$style_no'";
} else {
  $styleno = "";
}

if ($cmt_no != ''){
  $cmtno = "and A.wo_no = '$cmt_no'";
} else {
  $cmtno = "";
}

if ($color_no != ''){
  $colorno = "and C.garment_colors = '$color_no'";
} else {
  $colorno = "";
}

if ($tglship1 == 'A' && $tglship2 == 'A'){
  $tgls = "";
} else if ($tglship1 == '' && $tglship2 == ''){
  $tgls = "";
} else {
  $tgls = "and DATE(C.ex_fty_date) BETWEEN '$tglship1' AND '$tglship2'";
}

$no = 1;
$counting = 1;
$awal = 1;
$akhir = 2;
// echo "select * from laundry_role_grup_master a join laundry_role_grup b on a.role_grup_master_id=b.role_grup_master_id where role_grup_master_id = 1";
  $seljenis = $con->select("laundry_role_grup_master a join laundry_role_grup b on a.role_grup_master_id=b.role_grup_master_id join laundry_master_type_process c on b.role_grup_jenis=c.master_type_process_id","*","a.role_grup_master_id = 1");
  
  foreach ($seljenis as $jns) {
  
      $selwoker = $con->select("laundry_wo_master_keranjang A join laundry_wo_master B ON A.wo_no = B.wo_no
      JOIN laundry_wo_master_dtl_proc C ON B.wo_master_id = C.wo_master_id","C.wo_master_dtl_proc_qty,C.garment_colors,C.wo_master_id,A.wo_no","A.wo_master_keranjang_user_id = '$_SESSION[ID_LOGIN]' GROUP BY A.wo_no,C.garment_colors,C.wo_master_id,C.wo_master_dtl_proc_qty");
      //  echo "select C.wo_master_dtl_proc_qty,C.garment_colors,C.wo_master_id from laundry_wo_master_keranjang A join laundry_wo_master B ON A.wo_no = B.wo_no
      // JOIN laundry_wo_master_dtl_proc C ON B.wo_master_id = C.wo_master_id 
      // where A.wo_master_keranjang_user_id = '$_SESSION[ID_LOGIN]' GROUP BY C.garment_colors,C.wo_master_id";
      foreach ($selwoker as $wk) { ?>
        <div class="col-md-6">
          <input type="checkbox" id="appwo_<?=$no?>" name="appwo[]" onchange="cekapp2(this,<?=$no?>)"> <?php echo $jns['master_type_process_name']." - ".$wk['wo_no']."(".$wk[garment_colors]."-".$wk['wo_master_id']."-".$no.")"; ?>
          <input type="hidden" id="appwo2_<?=$no?>" name="appwo2[]" value="0">
          <input type="hidden" id="woid[]" name="woid[]" value="<?php echo $wk[wo_master_id]."_".$wk[garment_colors]."_".$wk[wo_no]?>">
        </div>
    <?php 
      if ($awal == $akhir){
      	echo "<br>";
      }
      $no++; 
      $awal ++;
      $jmlloop += $counting;
     } 
  }
  // $seljmlker = $con->select("laundry_wo_master_keranjang A join laundry_wo_master B ON A.wo_no = B.wo_no
  // JOIN laundry_wo_master_dtl_proc C ON B.wo_master_id = C.wo_master_id","COUNT(C.wo_master_dtl_id) as jmlker","A.wo_master_keranjang_user_id = '$_SESSION[ID_LOGIN]' GROUP BY A.wo_no,C.garment_colors,C.wo_master_id,C.wo_master_dtl_proc_qty");
  // foreach ($seljmlker as $jmlker) {}
?>
<input value="<?php echo $jmlloop;?>" id="jmlappwo" name="jmlappwo" type="hidden" >