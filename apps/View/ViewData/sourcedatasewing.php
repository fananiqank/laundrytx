<?php 
session_start();
include "../../../funlibs.php";
$con = new Database();


$no=1;
if ($_GET['saw']==1){
  $colors = $con->searchseq($_GET['co']);
  $cmt = $con->searchseqcmt($_GET['cm']);   
  
  if ($cmt != ''){
    $cm = "and wo_no = '$cmt'";
  } else {
    $cm = "";
  }

  if ($colors != ''){
    $co = "and garment_colors = '$colors'";
  } else {
    $co = "";
  }

  if ($_GET['in'] != ''){
    $in = "and garment_inseams = '$_GET[in]'";
  } else {
    $in = "";
  }

  if ($_GET['si'] != ''){
    $si = "and garment_sizes = '$_GET[si]'";
  } else {
    $si = "";
  }
}
if ($cmt != '' && $colors != ''){
  $selwip = $con->select("wip_dtl","*","wip_dtl_status = 0 $cm $co $in $si and concat(wo_no,'_',garment_colors) IN (select CONCAT(wo_no,'_',garment_colors) as wocolor from laundry_wo_master_dtl_proc)","wip_dtl_id ASC");
}
//echo "select * from wip_dtl where wip_dtl_status = 0 $cm $co $in $si";
  foreach ($selwip as $wp) {
  // $selbpo = $con->select("wo_sb","buyer_po_number","wo_no = '$wp[wo_no]' and garment_colors = '$wp[garment_colors]' and garment_sizes = '$wp[garment_sizes]' and garment_inseams='$wp[garment_inseams]'");
// echo "select * from wo_sb where wo_no = '$wp[wo_no]' and garment_colors = '$wp[garment_colors]' and garment_sizes = '$wp[garment_sizes]' and garment_inseams='$wp[garment_inseams]'";
?>  
  <tr>
      <th width="5%"><?php echo $no?></th>
      <th width="15%"><?php echo $wp['wo_no']?></th>
      <th width="15%"><?php echo $wp['garment_colors']?></th>
      <th width="5%"><?php echo $wp['garment_inseams']?></th>
      <th width="5%"><?php echo $wp['garment_sizes']?></th>
      <th width="5%"><?php echo $wp['quantity']?></th>
      <th width="5%">
        <input name='qtysend_<?=$wp[wip_dtl_id]?>' id='qtysend_<?=$wp[wip_dtl_id]?>' value='<?=$wp[quantity]?>' type='text' class='form-control' style="background-color: #FFE4E1">
      </th>
      <th width="5%">      
          <input name='qtyin_<?=$wp[wip_dtl_id]?>' id='qtyin_<?=$wp[wip_dtl_id]?>' value='<?=$wp[quantity]?>' type='text' class='form-control' onkeyup='hitungturn2()' onkeydown='return hanyaAngka(this, event);'>
          <input name='qtyout_<?=$wp[wip_dtl_id]?>' id='qtyout<?=$wp[wip_dtl_id]?>' value='' type='hidden' class='form-control'>
      </th>
      <th width="7%">
        <a href='javascript:void(0)' class='label label-success' onClick='conf(<?=$wp[wip_dtl_id]?>)'><i class='fa fa-check'></i></a>
        <input name='wip_id' id='wip_id' value='$d' type='hidden'>
      </th>  
  </tr>
<?php $no++; } ?>