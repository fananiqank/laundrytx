<?php 
session_start();
include '../../../funlibs.php';
$con = new Database;
$no = 1;
  $selwoker = $con->select("laundry_wo_master_keranjang","wo_no","wo_master_keranjang_user_id = '$_SESSION[ID_LOGIN]'");
  foreach ($selwoker as $wk) { ?>
      <input type="checkbox" id="wono_<?=$no?>" name="wono[]" value="<?php echo $wk[wo_no]; ?>"> <?php echo $wk['wo_no']; ?> <br>
<?php 
  $no++; } 
  $seljmlker = $con->select("laundry_wo_master_keranjang","count(wo_no) as jmlker","wo_master_keranjang_user_id = '$_SESSION[ID_LOGIN]'");
  foreach ($seljmlker as $jmlker) {}
?>
<input value="<?php echo $jmlker[jmlker];?>" id="jmlwo" name="jmlwo" type="hidden" >