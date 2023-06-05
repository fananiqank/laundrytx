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
    
    //select pilihan machine baru yang belum ada pada process machine (belum di pilih)
    $selmachine = $con->select("laundry_master_machine a join laundry_master_machine_dtl b on a.machine_id=b.machine_id","a.machine_id,a.machine_name","b.master_process_id = '$masterid' and b.machine_id NOT IN (select machine_id from laundry_process_machine where lot_no = '".$_GET['lot']."' and master_process_id = '".$masterid."' and process_machine_status != 0)","machine_id");
         // echo "select a.machine_id,a.machine_name from laundry_master_machine a join laundry_master_machine_dtl b on a.machine_id=b.machine_id where b.master_process_id = '$masterid' and b.machine_id NOT IN (select machine_id from laundry_process_machine where lot_no = '$_GET[lot]' and master_process_id = '$masterid' and process_machine_status=1))";
    
    //jumlah machine pada process machine per lot number
    $promachine = $con->selectcount("laundry_process_machine","process_machine_id as jmlprocmachine","master_process_id = '".$masterid."' and lot_no = '".$_GET['lot']."' and process_machine_status != 0");
    
    // select machine sesuai pilihan process pada laundry_process_machine (sesuai pilihan)
    $selprocmac = $con->select("laundry_process_machine a join laundry_master_machine b on a.machine_id=b.machine_id","b.machine_name,a.process_machine_id,a.process_machine_time,a.process_machine_onprogress,a.process_machine_status","a.master_process_id = '".$masterid."' and a.lot_no = '".$_GET['lot']."' and a.process_machine_status != 0","process_machine_sequence"); 
    
    //jumlah time machine yang sudah ada pada process
    $selprocjum = $con->select("laundry_process_machine","SUM(process_machine_time) as jumtime,COUNT(process_machine_id) as countid","master_process_id = '".$masterid."' and lot_no = '".$_GET['lot']."' and process_machine_status != 0"); 
            foreach ($selprocjum as $procjum) {}

    // pengecekan machine yang sudah process (start / End)
    $selprocess = $con->select("laundry_process a join laundry_process_machine b on a.machine_id=b.machine_id","SUM(b.machine_id) as onprocess","a.master_process_id = '".$masterid."' and a.lot_no = '".$_GET['lot']."' and b.process_machine_status != 0"); 
    // echo "select SUM(b.machine_id) as onprocess from laundry_process a join laundry_process_machine b on a.machine_id=b.machine_id where a.master_process_id = '$masterid' and a.lot_no = '$_GET[lot]' and b.process_machine_status=1 ";
            foreach ($selprocess as $process) {}

    if ($usemultimachine == 0){ //jika usemachine tp tidak multi machine
        if ($promachine >= 1){
            $disable = "disabled";
        } else {
            $disable = "";
        }

    } else if ($usemultimachine == 1){ //jika multimachine
        if ($promachine >= 1){
            $disable = "disabled";
        } else {
            $disable = "";
        }
    } else {
        $disable= "";
    }

?>

<?php
if ($role1['master_process_usemachine'] != '0'){  // hanya process yang use machine.
    if ($process['onprocess'] == 0){ ?> <!--jika process sudah start-->
        <div class="row">
            <select data-plugin-selectTwo class="form-control populate" placeholder="None Selected" id="machineid" name="machineid" <?php echo $disable; ?> required>
                <option value="">Choose Machine</option>
                <?php 
                    foreach ($selmachine as $machine) { ?>

                    <option value="<?php echo $machine[machine_id]; ?>" onclick="machine('<?php echo $masterid; ?>','<?php echo $_GET[lot]; ?>','<?php echo $machine[machine_time]; ?>','<?php echo $role_wo_id; ?>','<?php echo $mastertype; ?>','<?php echo $usemultimachine; ?>')" ><?php echo $machine['machine_name'].' - '.$machine['machine_time']; ?></option>";
                <?php 
                    }
                ?>
            </select>
            <input name="master_process_id" id="master_process_id" value="<?php echo $masterid; ?>" type="hidden" />
            <input name="machine_seq" id="machine_seq" value="0" type="hidden" />
            <input name="role_wo_id" id="role_wo_id" value="<?php echo $role_wo_id; ?>" type="hidden" />

        </div><br><br>
<?php 
    } 
}
?>
<div class="row" id="tampilmachine">
        <?php 
                //cek jika jumlah machine lebih dari 1 (use machine) dan type process lebih dari 3 (Dry & Wet)
                if ($promachine > 0 && $mastertype > 3){ ?>
                    <div class="form-group">
                        <table class="table datatable-basic table-bordered table-striped table-hover dataTable no-footer" width="100%">
                            <tr>
                                <th width="5%">No</th>
                                <th width="40%">Machine</th>
                                <th width="30%">Time</th>
                                <th width="10%">Act</th>
                            </tr>
                            <?php $no = 1; 
                                  foreach ($selprocmac as $procmac) { 
                                    if ($procmac['process_machine_onprogress'] != '1'){
                                        $timedisable = "disabled";
                                    } else {
                                        $timedisable = "";
                                    }
                            ?>
                                <tr>
                                    <td><?=$no;?></td>
                                    <td><?=$procmac['machine_name']?></td>
                                    <td>
                                        <input type="text" class="form-control" id="timemac_<?php echo $no; ?>" name="timemac[]" placeholder="Minute" onkeyup="savetime(this.value,'<?php echo $masterid; ?>','<?php echo $_GET[lot]; ?>','<?php echo $no; ?>')" onkeydown="return hanyaAngka(this, event);" value="<?php echo $procmac[process_machine_time]; ?>" <?php echo $timedisable; ?> required>
                                        <input type="hidden" class="form-control" id="time_id[]" name="time_id_<?php echo $no; ?>" value="<?php echo $procmac[process_machine_id]; ?>">
                                       
                                    </td>
                                <?php if ($process['onprocess'] == 0){ ?>  <!--jika process sudah start-->
                                    <td>
                                        <a href="javascript:void(0)" onclick="hapusmachine('<?php echo $procmac[process_machine_id]; ?>','<?php echo $masterid; ?>','<?php echo $_GET[lot]; ?>','<?php echo $mastertype; ?>','<?php echo $role_wo_id; ?>')"><i class="fa fa-trash"></i></a>
                                    </td>
                                <?php } ?>
                                </tr>
                                <tr>
                                	<td colspan="4"><b>Status : 
                                					<?php 
                                						if ($procmac['process_machine_status'] == 0){
                                							echo "<span style='color:#ed9c28'>Waiting</span>";
                                						} else if ($procmac['process_machine_status'] == 1  && $procmac['process_machine_onprogress'] == 1){
                                							echo "<span style='color:#ed9c28'>Waiting</span>";
                                						} else if ($procmac['process_machine_status'] == 1  && $procmac['process_machine_onprogress'] == 2){
                                							echo "<span style='color:#0088cc'>Process</span>"; 
                                						} else if ($procmac['process_machine_status'] == 1  && $procmac['process_machine_onprogress'] == 3){
                                							echo "<span style='color:#47a447'>Done</span>"; 
                                						} else if ($procmac['process_machine_status'] == 2){
                                							echo "<span style='color:#d2322d'>Machine Broken</span>";
                                						} 
                                					?>
                                					</b>
                                	</td>
                                </tr>
                            <?php 
                                $no ++; 
                                $jumtime += $procmac['process_machine_time'];
                                $jumid += $no;
                            } 
                            ?>
                           
                            <input type="hidden" class="form-control" id="jumtime" name="jumtime" value="<?php echo $procjum[jumtime]; ?>">
                            <input type="hidden" class="form-control" id="jumid" name="jumid" value="<?php echo $procjum[countid]; ?>">
                              
                        </table> 
                    </div>      
        <?php   }  ?>
        <input name="machinetime" id="machinetime" value="<?php echo $jumtime; ?>" type="hidden">
        <input name="machineplan" id="machineplan" value="<?php echo $jumid; ?>" type="hidden">
</div>

