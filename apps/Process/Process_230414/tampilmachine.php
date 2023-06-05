<?php
    if ($_GET['id']){
        session_start();
        include '../../../funlibs.php';
        $con = new Database;

        $masterid = $_GET['id'];
        $mastertype = $_GET['mastertype'];
        $role_wo_id = $_GET['rolewoid'];
        $usemultimachine = $_GET['usemulti'];
    
    } else {
        $masterid = $role1['master_process_id'];
        $mastertype = $role1['master_type_process_id'];
        $role_wo_id = $role1['role_wo_id'];
        $usemultimachine = $role1['master_process_usemultimachine'];
    
    }
   


//process IN
if($role1['dtl_process'] == ''){
?>
    <div class="row">
        <label class="col-md-4 control-label" for="profileLastName"><b>Sender:</b></label>
        <div class="col-md-8">
            <input type="text" name="sender" id="sender" class="form-control" />
        </div>
    </div>
    <br>
    <div class="row">
        <label class="col-md-4 control-label" for="profileLastName"><b>Receiver:</b></label>
        <div class="col-md-8">
            <input type="text" name="receiver" id="receiver" class="form-control" onkeyup="receiver(this.value)" />
        </div>
    </div>
<?php } else { ?>
<div class="row">
        <label class="col-md-4 control-label" for="profileLastName"><b>Foreman:</b></label>
        <div class="col-md-8">
            <input type="text" name="foreman" id="foreman" class="form-control" />
        </div>
    </div>
    <br>
<div class="row">
    <label class="col-md-4 control-label" for="profileLastName"><b>Operator / Leader:</b></label>
    <div class="col-md-8">
        <input type="text" name="operator" id="operator" class="form-control" />
    </div>
</div>
<br>
    <?php if($role1['master_process_usemachine'] != '0' && $role1['dtl_process'] == '1') { ?>
        <div class="row">
            <label class="col-md-4 control-label" for="profileLastName"><b>Machine / Table:</b></label>
            <div class="col-md-8">
                <input type="text" name="machinecode" id="machinecode" class="form-control" /><br>
                <input type="text" name="machinename" id="machinename" class="form-control" readonly />
                <input type="hidden" name="machineid" id="machineid" class="form-control" />
                <input type="hidden" name="usemachine" id="usemachine" class="form-control" value="1" />
                <input type="hidden" name="cekdtlprocess" id="cekdtlprocess" class="form-control" value="<?=$role1[dtl_process]?>" />
            </div>
        </div>
    <?php } else if($role1['master_process_usemachine'] != '0' && $role1['dtl_process'] == '2') { 
            if($role1[machine_id] <> ''){
                foreach ($con->select("laundry_master_machine","machine_name","machine_id = '$role1[machine_id]'") as $sman){}
            }
    ?>
        <div class="row">
            <label class="col-md-4 control-label" for="profileLastName"><b>Machine / Table:</b></label>
            <div class="col-md-8">
                <?php echo $sman['machine_name'];?>
                <input type="hidden" name="machineid" id="machineid" class="form-control" value="<?=$role1[machine_id]?>" />
                <input type="hidden" name="usemachine" id="usemachine" class="form-control" value="1" />
                <input type="hidden" name="cekdtlprocess" id="cekdtlprocess" class="form-control" value="<?=$role1[dtl_process]?>" />
            </div>
        </div>
<?php 
        }  else {
?>
                <input type="hidden" name="usemachine" id="usemachine" class="form-control" value="0" />
<?php   }
    }
?>

