<?php 
session_start();
include '../../../funlibs.php';
$con = new Database;

    //jika tipe adalah 2
    if($_GET['t'] == '2'){
        foreach ($con->select("laundry_master_type_process","master_type_process_name as name,NULL as master_type_process","master_type_process_id = '".$_GET['mpid']."'") as $npro) {}
            $getqc = $_GET['mpid'];
    } 
    else {
        if($_GET['cp'] != '0') {
            
            $mpi = $_GET['cp'];
        } else {
            
            $mpi = $_GET['mpid'];
        }
        foreach ($con->select("laundry_master_process","master_process_name as name,master_process_usemachine as machine,
            CASE 
            WHEN master_type_process = 4 
            THEN 'Dry : '
            ELSE 'Wet : '
            END as master_type_process","master_process_id = '".$mpi."'") as $npro) {}
            $getqc = '';
            // echo "select CASE 
            // WHEN master_type_process = 4 
            // THEN 'Dry : '
            // ELSE 'Wet : '
            // END as master_type_process from laundry_master_process where master_process_id = '".$mpi."'";
    }
?>  
<h4 class="col-sm-12 col-md-12 col-lg-12" align="center">
    <b>Detail Lot</b><br>
    <h5 align="center"><b><?php echo $npro['master_type_process'].$npro['name'] ?></b></h5>
</h4>
    <table class="table">
        <thead>
            <tr>
                <td>Lot</td>
                <td>Qty</td>
                <td>Status</td>
            </tr>
        </thead>
        <tbody>
            <?php 
            $selck = $con->select("(select log_lot_receive,log_lot_ref,log_lot_qty,case when log_lot_ref=log_lot_receive then 1 else 2 end as coderef from laundry_log where log_lot_tr = '$_GET[lot]') a join laundry_receive b on a.log_lot_receive=b.rec_no","a.log_lot_receive,a.log_lot_ref,a.coderef,a.log_lot_qty,case when rec_break_status = 0 then 'Break' else 'Active' end as rec_break_status"); 
            foreach($selck as $ck){
            ?>
            <tr>
                <td><?=$ck['log_lot_receive']?></td>
                <td><?=$ck['log_lot_qty']?></td>
                <td><?=$ck['rec_break_status']?></td>
            </tr>
            <?php } 
            if($ck['coderef'] > 1){
            $selck = $con->select("(select log_lot_ref from laundry_log where log_lot_tr = '$_GET[lot]') a join laundry_lot_number b on a.log_lot_ref=b.lot_no","a.log_lot_ref,b.lot_qty,case when lot_status = 0 then 'Break' when lot_status = 2 then 'Done' else 'Active' end as lot_status"); 
            foreach($selck as $ck){
            ?>
            <tr>
                <td><?=$ck['log_lot_ref']?></td>
                <td><?=$ck['lot_qty']?></td>
                <td><?=$ck['lot_status']?></td>
            </tr>
            <?php }
            } 
            ?>
        </tbody>
    </table>