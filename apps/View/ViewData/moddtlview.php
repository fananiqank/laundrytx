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
    <b>Detail Process</b><br>
    <h5 align="center"><b><?php echo $npro['master_type_process'].$npro['name'] ?></b></h5>
</h4>
    <?php 
        if ($npro['machine'] == '1'){
            echo " ";
            if($_GET['stp'] == 1){
                include "moddtlview_isi.php";
            }
            else {
                include "moddtlview_isi_machine.php";
            }
        } else if ($getqc == 3) {
            echo " ";
            include "moddtlview_isi_machine_qc.php";
        }else {
            if ($_GET['t'] == '6'){
                include "moddtlview_isi_lot_end.php";
            } else {
                include "moddtlview_isi.php";
            }
        }
    ?>