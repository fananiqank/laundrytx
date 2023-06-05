<?php 
  session_start();
  require_once("../../../funlibs.php");
  $con=new Database();

?>
<div class="form-group" id="button-process">
  <?php 
    if ($cmt['master_process_usemachine'] == 0) {
      include "../Dry/button-process-nonmachine.php";
    } else {
      include "../Dry/button-process.php"; 
    }
  ?>

</div>
<div class="form-group">
  <div class="col-sm-6 col-md-6 col-lg-6" style="font-size: 14px;">
    <h3>History</h3>
    <div id="history">
      <?php include "../Dry/history.php"; ?>
    </div>
  </div>
  <div class="col-sm-6 col-md-6 col-lg-6" style="font-size: 14px;">
    <h3>Out</h3>
    <div class="row">
      <?php 
      if ($cmt['master_process_usemachine'] == 0){
        $cekmachinedone = $con->select("laundry_process","COUNT(process_id) as countcekmach","lot_no = '".$lot."' and master_process_id = '".$cmt['master_process_id']."' and role_wo_id='".$cmt['role_wo_id']."' and process_type = '3'");
        foreach ($cekmachinedone as $machinedone) {}
            if ($machinedone['countcekmach'] > 0){
              $disableout = "";
            } else {
              $disableout = "disabled";
            }
      } else {
        $cekmachinedone = $con->select("laundry_process_machine","COUNT(process_machine_id) as countcekmach","lot_no = '$lot' and master_process_id = '".$cmt['master_process_id']."' and machine_id = '".$mach['machine_id']."' and process_machine_onprogress != 3 and role_wo_id='".$mach['role_wo_id']."'");

        $cekmachinebroken = $con->select("laundry_process_machine","process_machine_status","lot_no = '".$lot."' and master_process_id = '".$cmt['master_process_id']."' and machine_id = '".$mach['machine_id']."' and  role_wo_id='".$mach['role_wo_id']."'","process_machine_id DESC","1");


        foreach ($cekmachinedone as $machinedone) {}
            if ($machinedone['countcekmach'] > 0){
                $disableout = "disabled";
            } else if ($cekmachinebroken['process_machine_status'] == 2) {
                $disableout = "disabled";
            } else {
                  $disableout = "";
            }
      }
          
      ?>
      <a href="javascript:void(0)" data-toggle='modal' data-target='#funModal' id='mod' class="btn btn-primary" style="padding: 3%;background-color: #FF4500" onclick="model('<?=$mach[machine_id]?>','<?=$lot?>','<?=$_GET[user]?>',3)" <?php echo $disableout; ?>><b style="font-size:20px;">OUT</b></a>
    </div>
  </div>
</div>    