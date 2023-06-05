<?php
    if ($_GET['id']){
        session_start();
        include '../../../funlibs.php';
        $con = new Database;

        $masterid = $_GET['id'];
    } else {
        $masterid = $role1['master_process_id'];
    }
    //select pilihan machine baru yang belum ada pada process machine (belum di pilih)
    $selmachine = $con->select("laundry_master_machine a join laundry_master_machine_dtl b on a.machine_id=b.machine_id","a.machine_id,a.machine_name","b.master_process_id = '$masterid' and b.machine_id NOT IN (select machine_id from laundry_process_machine where lot_no = '$_GET[lot]' and master_process_id = '$masterid' and process_machine_status=1)","machine_id");
        echo "select a.machine_id,a.machine_name from laundry_master_machine a join laundry_master_machine_dtl b on a.machine_id=b.machine_id where a.machine_id,a.machine_name","b.master_process_id = '$masterid' and b.machine_id NOT IN (select b.machine_id from laundry_process_machine where lot_no = '$_GET[lot]' and master_process_id = '$masterid' and process_machine_status=1))";
        //jumlah machine pada process machine per lot number
    $selpromachine = $con->select("laundry_process_machine","COUNT(process_machine_id) as jmlprocmachine","master_process_id = '$masterid' and lot_no = '$_GET[lot]' and process_machine_status = 1");
            foreach ($selpromachine as $promachine) {}

    // select machine sesuai pilihan process pada laundry_process_machine (sesuai pilihan)
    $selprocmac = $con->select("laundry_process_machine a join laundry_master_machine b on a.machine_id=b.machine_id","b.machine_name,a.process_machine_id,a.process_machine_time,a.process_machine_onprogress","a.master_process_id = '$masterid' and a.lot_no = '$_GET[lot]' and a.process_machine_status=1","process_machine_sequence"); 
    
    //jumlah time machine yang sudah ada pada process
    $selprocjum = $con->select("laundry_process_machine","SUM(process_machine_time) as jumtime,COUNT(process_machine_id) as countid","master_process_id = '$masterid' and lot_no = '$_GET[lot]' and process_machine_status=1"); 
            foreach ($selprocjum as $procjum) {}

    // pengecekan machine yang sudah process (start / End)
    $selprocess = $con->select("laundry_process a join laundry_process_machine b on a.machine_id=b.machine_id","SUM(b.machine_id) as onprocess","a.master_process_id = '$masterid' and a.lot_no = '$_GET[lot]' and b.process_machine_status=1"); 
    // echo "select SUM(b.machine_id) as onprocess from laundry_process a join laundry_process_machine b on a.machine_id=b.machine_id where a.master_process_id = '$masterid' and a.lot_no = '$_GET[lot]' and b.process_machine_status=1 ";
            foreach ($selprocess as $process) {}

    if ($promachine['jmlprocmachine'] == 4){
        $disable = "disabled";
    } else {
        $disable = "";
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

            <option value="<?=$machine[machine_id]?>" onclick="machine('<?=$masterid?>','<?=$_GET[lot]?>','<?=$machine[machine_time]?>','<?=$machine[machine_time]?>')" ><?=$machine['machine_name'].' - '.$machine['machine_time']?></option>";
        <?php 
            }
        ?>
    </select>
    <input name="master_process_id" id="master_process_id" value="<?=$masterid?>" type="hidden" />
    <input name="machine_seq" id="machine_seq" value="0" type="hidden" />
    <input name="role_wo_id" id="role_wo_id" value="<?=$role1[role_wo_id]?>" type="hidden" />
</div><br><br>
<?php 
    } 
}
?>
<div class="row" id="tampilmachine">
        <?php 
                //cek jika jumlah machine lebih dari 1 (use machine) dan type process lebih dari 3 (Dry & Wet)
                if ($promachine['jmlprocmachine'] > 0 && $cmt['master_type_process_id'] > 3){ ?>
                    <div class="form-group">
                        <table class="table datatable-basic table-bordered table-striped table-hover dataTable no-footer" width="100%">
                            <tr>
                                <th width="10%">No</th>
                                <th width="50%">Machine</th>
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
                                        <input type="text" class="form-control" id="timemac_<?=$no?>" name="timemac[]" placeholder="Minute" onkeyup="savetime(this.value,'<?=$masterid?>','<?=$_GET[lot]?>','<?=$no?>')" onkeydown="return hanyaAngka(this, event);" value="<?=$procmac[process_machine_time]?>" <?php echo $timedisable; ?> required>
                                        <input type="hidden" class="form-control" id="time_id[]" name="time_id_<?=$no?>" value="<?=$procmac[process_machine_id]?>">
                                       
                                    </td>
                                <?php if ($process['onprocess'] == 0){ ?>  <!--jika process sudah start-->
                                    <td>
                                        <a href="javascript:void(0)" onclick="hapusmachine('<?=$procmac[process_machine_id]?>','<?=$masterid?>','<?=$_GET[lot]?>',0)"><i class="fa fa-trash"></i></a>
                                    </td>
                                <?php } ?>
                                </tr>
                            <?php $no ++; } ?>
                            <input type="hidden" class="form-control" id="jumtime" name="jumtime" value="<?=$procjum[jumtime]?>">
                            <input type="hidden" class="form-control" id="jumid" name="jumid" value="<?=$procjum[countid]?>">
                              
                        </table> 
                    </div>      
        <?php   } ?>
</div>

