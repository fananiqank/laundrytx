<?php
  
  if ($_GET['machine'] != ''){
    session_start();
    require_once("../../../funlibs.php");
    $con=new Database();
  }
  if($_GET['mpid']) {
    $masterprocessid = $_GET['mpid'];
  } else {
    $masterprocessid = $cmt['master_process_id'];
  }

  $lot = $_GET['lot'];
?>
<div class="row">
  <div class="col-sm-4 col-md-4 col-lg-4">
  <b>IN</b>
  </div>
  <div class="col-sm-6 col-md-6 col-lg-6">
  <?php
    $selectlogin= $con->select("laundry_process","process_type,process_createdate","lot_no = '$lot' and master_process_id = '$masterprocessid' and process_type = 1");
     //echo "select process_type,process_createdate from laundry_process where lot_no = '$lot' and master_process_id = '$cmt[master_process_id]' and process_type = 1";
    foreach ($selectlogin as $slogin) {}
      if($slogin['process_createdate'] == '' ){
       echo "00:00";
      } else {
       echo "<b><i>".date("H:i",strtotime($slogin['process_createdate']))."</i></b>";
      }
  ?>
  </div>
</div>
<div class="row">
  <div class="col-sm-4 col-md-4 col-lg-4">
    <b>START</b>
  </div>
  <div class="col-sm-6 col-md-6 col-lg-6">
  <?php
    $selectlogstart= $con->select("laundry_process","process_type,process_createdate","lot_no = '$lot' and master_process_id = '$masterprocessid' and process_type = 2","process_id","1");
    foreach ($selectlogstart as $slogstart) {}
      if($slogstart['process_createdate'] == '' ){
       echo "00:00";
      } else {  
       echo "<b><i>".date("H:i",strtotime($slogstart['process_createdate']))."</i></b>";
      }
  ?>
  </div>
</div>
<div class="row">
  <div class="col-sm-4 col-md-4 col-lg-4">
    <b>END</b> 
  </div>
  <div class="col-sm-6 col-md-6 col-lg-6">
  <?php
    $selectlogend= $con->select("laundry_process","process_type,process_createdate","lot_no = '$lot' and master_process_id = '$masterprocessid'  and process_type = 3","process_id DESC","1");
    foreach ($selectlogend as $slogend) {}
      if($slogend['process_createdate'] == '' ){
       echo "00:00";
      } else {
       echo "<b><i>".date("H:i",strtotime($slogend['process_createdate']))."</i></b>";
      }
  ?>
  </div>
</div>
<div class="row">
  <div class="col-sm-4 col-md-4 col-lg-4">
    <b>OUT </b>
  </div>
  <div class="col-sm-6 col-md-6 col-lg-6">
  <?php
    $selectlogout= $con->select("laundry_process","process_type,process_createdate","lot_no = '$lot' and master_process_id = '$masterprocessid' and process_type = 4");
    foreach ($selectlogout as $slogout) {}
      if($slogout['process_createdate'] == '' ){
       echo "00:00";
      } else {    
       echo "<b><i>".date("H:i",strtotime($slogout['process_createdate']))."</i></b>";
      }
  ?>
  </div>
</div>