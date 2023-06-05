
<?php 
session_start();
include "../../../funlibs.php";
$con = new Database();
  $no= 1;
  $selscan = $con->select("laundry_scan_qrcode","*","scan_status = 0 and scan_createdby = '$_SESSION[ID_LOGIN]' and scan_type = 3","scan_id");
  foreach ($selscan as $scan){
?>  
    <tr>
        <th width="5%"><?php echo $no?></th>
        <th width="15%"><?php echo $scan['scan_qrcode']?></th>
        <th width="15%"><?php echo $scan['garment_colors']?></th>
        <th width="5%"><?php echo $scan['garment_inseams']?></th>
        <th width="5%"><?php echo $scan['garment_sizes']?></th>
        <th width="5%"><?php echo $scan['sequence_no']?></th>
    </tr>
<?php 
    $no++; 
    } 
?>
