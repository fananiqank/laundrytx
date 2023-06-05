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
$akhir = 1;

$selrwomaster = $con->select("laundry_wo_master_dtl_proc","role_wo_master_id","wo_master_dtl_proc_id = '".$_GET['idm']."'");
foreach ($selrwomaster as $rwm) {}
if ($_GET['idm']){
       // $selrl = $con->select("laundry_role_wo_master a join laundry_role_wo b on a.role_wo_master_id=b.role_wo_master_id join laundry_master_type_process c on b.master_type_process_id=c.master_type_process_id","role_wo_name,a.role_wo_master_id,b.role_wo_id","a.role_wo_master_id = '".$rwm['role_wo_master_id']."' and b.master_type_process_id NOT between 4 and 5 and role_wo_createdby = '".$_SESSION['ID_LOGIN']."'");
  $selrl = $con->select("laundry_role_wo_master a join laundry_role_wo b on a.role_wo_master_id=b.role_wo_master_id join laundry_master_type_process c on b.master_type_process_id=c.master_type_process_id","role_wo_name,a.role_wo_master_id,b.role_wo_id","a.role_wo_master_id = '".$rwm['role_wo_master_id']."' and b.master_type_process_id NOT between 4 and 5");
         

      foreach ($selrl as $rl) { 
          echo "<input type='hidden' value='".$rl['role_wo_id']."' id='role3[]' name='role3[]'>"; 
      }
      
      $selwoker = $con->select("laundry_wo_master_dtl_proc","wo_no,garment_colors,ex_fty_date,role_wo_master_id","wo_master_dtl_proc_id = '".$_GET['idm']."'");
      foreach ($selwoker as $wk) { 
          if ($wk['ex_fty_date'] != ''){
              $exdate = date('d-m-Y', strtotime($wk['ex_fty_date']));
          } else {
              $exdate = '';
          }

          // $seljenis = $con->select("laundry_role_wo_master a join laundry_role_wo b on a.role_wo_master_id=b.role_wo_master_id join laundry_master_type_process c on b.master_type_process_id=c.master_type_process_id","role_wo_name,a.role_wo_master_id,b.role_wo_id","a.role_wo_master_id = '".$wk['role_wo_master_id']."' and b.master_type_process_id between 4 and 5 and role_wo_status = 0 and role_wo_createdby = '".$_SESSION['ID_LOGIN']."'");
          $seljenis = $con->select("laundry_role_wo_master a join laundry_role_wo b on a.role_wo_master_id=b.role_wo_master_id join laundry_master_type_process c on b.master_type_process_id=c.master_type_process_id","role_wo_name,a.role_wo_master_id,b.role_wo_id","a.role_wo_master_id = '".$wk['role_wo_master_id']."' and b.master_type_process_id between 4 and 5 and role_wo_status = 0");


          foreach ($seljenis as $jns) {
?>
                <div class="col-sm-12 col-md-12 col-lg-12">
                  <input type="checkbox" id="appwo_<?=$no?>" name="appwo[]" onchange="cekapp2(this,<?=$no?>)"> <?php echo $jns['role_wo_name']." - ".$wk['wo_no']."(".$wk['garment_colors']."-".$wk['wo_master_id']." | ".$exdate.")"; ?>
                  <input type="hidden" id="appwo2_<?=$no?>" name="appwo2[]" value="0">
                  <input type="hidden" id="woid[]" name="woid[]" value="<?php echo $wk[garment_colors]."_".$wk[wo_no]."_".$jns[role_wo_master_id]."_".$jns[role_wo_id]."_".$wk[ex_fty_date]; ?>">
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
      echo "<input value='editapp' id='editapp' name='editapp' type='hidden'>";
} else {
      // $selrl = $con->select("laundry_role_grup_master a join laundry_role_grup b on a.role_grup_master_id=b.role_grup_master_id join laundry_master_type_process c on b.master_type_process_id=c.master_type_process_id","role_grup_name,a.role_grup_master_id,b.role_grup_id","a.role_grup_master_status = 1 and b.master_type_process_id NOT between 4 and 5 and role_grup_createdby = '".$_SESSION['ID_LOGIN']."'");
  $selrl = $con->select("laundry_role_grup_master a join laundry_role_grup b on a.role_grup_master_id=b.role_grup_master_id join laundry_master_type_process c on b.master_type_process_id=c.master_type_process_id","role_grup_name,a.role_grup_master_id,b.role_grup_id","a.role_grup_master_status = 1 and b.master_type_process_id NOT between 4 and 5");
      
      foreach ($selrl as $rl) { 
          echo "<input type='hidden' value='".$rl['role_grup_id']."' id='role3[]' name='role3[]'>"; 
      }
      
      $selwoker = $con->select("laundry_wo_master_keranjang","wo_no,garment_colors,ex_fty_date","");
      foreach ($selwoker as $wk) { 
          if ($wk['ex_fty_date'] != ''){
              $exdate = date('d-m-Y', strtotime($wk['ex_fty_date']));
          } else {
              $exdate = '';
          }

          // $seljenis = $con->select("laundry_role_grup_master a join laundry_role_grup b on a.role_grup_master_id=b.role_grup_master_id join laundry_master_type_process c on b.master_type_process_id=c.master_type_process_id","role_grup_name,a.role_grup_master_id,b.role_grup_id","a.role_grup_master_status = 1 and b.master_type_process_id between 4 and 5 and role_grup_createdby = '$_SESSION[ID_LOGIN]'");
          $seljenis = $con->select("laundry_role_grup_master a join laundry_role_grup b on a.role_grup_master_id=b.role_grup_master_id join laundry_master_type_process c on b.master_type_process_id=c.master_type_process_id","role_grup_name,a.role_grup_master_id,b.role_grup_id","a.role_grup_master_status = 1 and b.master_type_process_id between 4 and 5");

          foreach ($seljenis as $jns) {
      ?>
        <div class="col-sm-12 col-md-12 col-lg-12">
          <input type="checkbox" id="appwo_<?=$no?>" name="appwo[]" onchange="cekapp2(this,<?=$no?>)"> <?php echo $jns['role_grup_name']." - ".$wk['wo_no']."(".$wk['garment_colors']."-".$wk['wo_master_id']." | ".$exdate.")"; ?>
          <input type="hidden" id="appwo2_<?=$no?>" name="appwo2[]" value="0">
          <input type="hidden" id="woid[]" name="woid[]" value="<?php echo $wk[garment_colors]."_".$wk[wo_no]."_".$jns[role_grup_master_id]."_".$jns[role_grup_id]."_".$wk[ex_fty_date]; ?>">
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
  // JOIN laundry_wo_master_dtl_proc C ON B.wo_master_id = C.wo_master_id","COUNT(C.wo_master_dtl_id) as jmlker","A.wo_master_keranjang_createdby = '$_SESSION[ID_LOGIN]' GROUP BY A.wo_no,C.garment_colors,C.wo_master_id,C.wo_master_dtl_proc_qty");
  // foreach ($seljmlker as $jmlker) {}
}
?>
<input value="<?php echo $jmlloop;?>" id="jmlappwo" name="jmlappwo" type="hidden" >