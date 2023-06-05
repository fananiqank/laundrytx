
<?php 
session_start();
include "../../../funlibs.php";
$con = new Database();
  $no= 1;
  $totalqr = $con->selectcount("laundry_scan_qrcode a left join qrcode_factory_master b on a.factory_id=b.factory_id","a.*,b.factory_name","scan_status = 0 and scan_createdby = '".$_SESSION['ID_LOGIN']."' and scan_type = 1","scan_id");
  $selscan = $con->select("laundry_scan_qrcode a left join qrcode_factory_master b on a.factory_id=b.factory_id","a.*,b.factory_name","scan_status = 0 and scan_createdby = '".$_SESSION['ID_LOGIN']."' and scan_type = 1","scan_id");
  foreach ($selscan as $scan){
    if ($scan['status_qrcode'] == 1) {
      $statqr = "GOOD";
    } else if ($scan['status_qrcode'] == 2) {
      $statqr = 'RREJECT';
    } else {
      $statqr = 'REWORK';
    }
   
?>  
    <tr>
        <td><?php echo $scan['wo_no']?></td>
        <td><?php echo $scan['scan_qrcode']?></td>
        <td><?php echo $scan['garment_colors']?></td>
        <td><?php echo $scan['garment_sizes']?></td>
        <td><?php echo $scan['garment_inseams']?></td>
        <td><?php echo $scan['sequence_no']?></td>
        <td><?php echo $statqr; ?></td>
        <td><?php echo $scan['user_code']?></td>
        <td><?php echo $scan['factory_name']?></td>
    </tr>
<?php 
    $no++; 

    } 
?>
    <tr>
      <td colspan="9" align="right"> Total : &ensp; <?php echo $totalqr; ?> &emsp;</td>
    </tr>