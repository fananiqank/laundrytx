<?php 
//if($_GET['reload'] == 1){
  session_start();
  include "../../../funlibs.php";
  $con = new Database();
//}

$typescan = 1;
//select data di lot number   
foreach ($con->select("laundry_lot_number a join laundry_wo_master_dtl_proc b on a.wo_master_dtl_proc_id=b.wo_master_dtl_proc_id","lot_id,b.wo_no,b.garment_colors,b.ex_fty_date,to_char(b.ex_fty_date,'DD-MM-YYYY') as ex_fty_date_show,lot_qty_good_upd","lot_no = '".$_GET['lot']."'") as $lotno) {}

//cek data dari qc keranjang, apakah sudah di input di keranjang atau belum
$cekqckeranjang= $con->selectcount("laundry_qc_keranjang","qc_keranjang_id","lot_no = '".$_GET['lot']."'");
//jika sudah ada di keranjang maka tidak bisa di input lagi.
   if ($cekqckeranjang > 0){
    foreach($con->select("laundry_qc_keranjang","SUM(qc_keranjang_qty) as totalqty","lot_no = '".$_GET['lot']."'") as $qcqty){}
      if ($lotno['lot_qty_good_upd'] > $qcqty['totalqty']){
          $showinput = "display:block";
          $qtygoods = $lotno['lot_qty_good_upd']-$qcqty['totalqty'];
      } else {
          $showinput = "display:none";
          $qtygoods = "";
      }
   } else {
     $showinput = "display:block";
     $qtygoods = $lotno['lot_qty_good_upd'];
   }
?>
<table class="table datatable-basic table-bordered table-striped table-hover dataTable no-footer" width="100%" id="datatable-ajax4" style="font-size: 13px;">
  <tr>
    <td width="20%"><b>WO No</b></td>
    <td width="20%"><b>Colors</b></td>
    <td width="13%"><b>Ex Fty Date</b></td>  
    <td width="10%"><b>Qty Good</b></td>
    <td width="10%"><b>Qty Reject</b></td>
    <td width="10%"><b>Qty Rework</b></td>
    <td width="7%"><b>Act</b></td>
  </tr>
  <tr>
    <td><input type="text" class="form-control" name="cmt" id="cmt" value="<?=$lotno[wo_no]?>" readonly></td>
    <td><input type="text" class="form-control" name="colors" id="colors" value="<?=$lotno[garment_colors]?>" readonly></td>
    <td><input type="text" class="form-control" name="ex_fty_date_show" id="ex_fty_date_show" value="<?=$lotno[ex_fty_date_show]?>" readonly>
        <input type="hidden" class="form-control" name="ex_fty_date" id="ex_fty_date" value="<?=$lotno[ex_fty_date]?>" readonly>
    </td>
    <?php //if ($cekqckeranjang > 0){ 
       // jika qty process sama dengan qty di keranjang, 
      echo $lotno['lot_qty_good_upd'].'_'.$qcqty['totalqty'];
      if ($lotno['lot_qty_good_upd'] == $qcqty['totalqty']){
    ?>
         <td colspan="5" align="center"><i>This lot has been inputted on cart, Click Confirm</i></td>
    
    <?php   
      } 
      else { 
    ?>
     <td><input type="text" class="form-control" name="qtygood" id="qtygood" value="<?=$qtygoods?>" onkeydown='return hanyaAngka(this, event);'></td>
     <td><input type="text" class="form-control" name="qtyreject" id="qtyreject" value="0" onkeydown='return hanyaAngka(this, event);'></td>
     <td><input type="text" class="form-control" name="qtyrework" id="qtyrework" value="0" onkeydown='return hanyaAngka(this, event);'></td>
     <td>
        <a href="javascript:void(0)" class="btn btn-primary" id="inputtype" name="inputtype" onclick="scaninputmanual('<?=$typescan?>','<?=$_GET[lot]?>')" style="<?=$showinput?>">input</a>
        <input type="hidden" class="form-control" name="qtytotal" id="qtytotal" value="<?=$lotno[lot_qty_good_upd]?>">
     </td>

    <?php } ?>  
  </tr>
</table>