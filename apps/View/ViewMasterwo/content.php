<section class="panel">
<?php
  //perpindahan menu
  $exppage = explode('_',$_GET['p']);
  $page = $exppage[0];  
  $last = $exppage[1];  
  $selcmt = $con->select("laundry_wo_master_dtl_proc","*","wo_master_dtl_proc_id = '$_GET[idm]'");
  foreach ($selcmt as $cmt) {}

  //mendapatkan jumlah wo rework di keranjang wo master.
  $countrework = $con->selectcount("laundry_wo_master_keranjang","wo_master_keranjang_id","status_seq = '2'");
  
  if($countrework > 0) {
    $statusseq = "<option value='2'>Rework</option>";
  } else {
    $statusseq = "<option value='1'>New</option>
                  <option value='2'>Rework</option>";
  }

  if ($_GET['idm']){
    $model = "onclick='modeladdwo($_GET[idm],$_GET[d],1)'";

    //tombol simpan jika dari approve berbeda dengan dari edit
    if($_GET['l'] == "a"){
    	$funcsimpan = "simpanapprove()";
    } else {
    	$funcsimpan = "simpan()";
    }

  } else {
    $model = "onclick='model(1)'";
  }

       include "view_master.php";

?>
 <input class="form-control" name="getp" id="getp" value="<?=$_GET[p]?>" type="hidden" />
 <input class="form-control" name="getpage" id="getpage" value="<?=$page?>" type="hidden" />
</section>

    
