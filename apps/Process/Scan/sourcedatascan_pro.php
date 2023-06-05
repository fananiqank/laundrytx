<?php
echo "aduh"; 
if($_GET['type']){
  session_start();
  include "../../../funlibs.php";
  $con = new Database();
}

  $no= 1;
  if($_GET['d'] == '3'){
    $scan_type = '3'; 
  } else if ($_GET['type'] == '3'){
    $scan_type = '3';
  } else {
    $scan_type = '2';
  }

  $selscan = $con->select("laundry_scan_qrcode","*","scan_status = 0 and scan_createdby = '$_SESSION[ID_LOGIN]' and scan_type = '$scan_type'","scan_id");
  foreach ($selscan as $scan){
?>  
    <tr>
        <th><?php echo $no?></th>
        <th><?php echo $scan['scan_qrcode']?></th>
        <th><?php echo $scan['wo_no']?></th>
        <th><?php echo $scan['garment_colors']?></th>
        <th><?php echo $scan['garment_inseams']?></th>
        <th><?php echo $scan['garment_sizes']?></th>
        <th><?php echo $scan['sequence_no']?></th>
        <th><?php echo $scan['sequence_no']?></th>
        <th><?php echo $scan['sequence_no']?></th>
    </tr>
<?php 
    $no++; 
    } 
?>
