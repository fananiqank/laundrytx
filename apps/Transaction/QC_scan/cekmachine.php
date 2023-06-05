<?php 
session_start();
include '../../../funlibs.php';
$con = new Database;

//cek master machine
$cekmastermachine = $con->selectcount("laundry_master_machine","machine_id","machine_code = '$_GET[code]' and machine_status = 1 and machine_category = 'QC'");

//cek process machine
foreach($con->select("(select process_mach_onprogress from laundry_process_machine_progress a join
(select max(process_mach_id) process_mach_id from laundry_process_machine_progress a join laundry_master_machine b on a.machine_id=b.machine_id where machine_code = '$_GET[code]') b on a.process_mach_id=b.process_mach_id) a","*") as $cekonprogress){}

if ($_GET['dtlprocess'] != 2) {
	if ($cekmastermachine > 0 && $cekonprogress[process_mach_onprogress]==2 ) {
		foreach($con->select("laundry_process_machine a LEFT JOIN laundry_wo_master_dtl_proc c ON a.wo_master_dtl_proc_id=c.wo_master_dtl_proc_id ","a.machine_id,lot_no,wo_no,garment_colors,color_wash,ex_fty_date","a.machine_code = '$_GET[code]' and a.process_machine_onprogress = 2 and process_machine_status = 1") as $lotmachine){}
		echo "10000000_".$lotmachine['lot_no']."_".$lotmachine['wo_no']."_".$lotmachine['garment_colors']."_".$lotmachine['color_wash'];
	}
	else if ($cekmastermachine > 0 && ($cekonprogress[process_mach_onprogress]==3 || $cekonprogress[process_mach_onprogress]=='')) {
		//MachineID
		foreach ($con->select("laundry_master_machine","machine_id,machine_name,machine_category","machine_code = '$_GET[code]'") as $cmachine){}
		if($_GET['mtype'] == 4 && $cmachine['machine_category'] == 'Dry'){
			echo $cmachine['machine_name'].'_'.$cmachine['machine_id'];
		} else if($_GET['mtype'] == 5 && $cmachine['machine_category'] == 'Wet'){
			echo $cmachine['machine_name'].'_'.$cmachine['machine_id'];
		} else if($_GET['mtype'] == 3 && $cmachine['machine_category'] == 'QC'){
			echo $cmachine['machine_name'].'_'.$cmachine['machine_id'];
		} else {
			echo "40000000_";
		}
	} else {
		echo "20000000_";
	}
} else {
	if ($cekonprogress[process_mach_onprogress]==2) {
		foreach ($con->select("laundry_master_machine","machine_id,machine_name","machine_code = '$_GET[code]'") as $cmachine){}
		echo $cmachine['machine_name'].'_'.$cmachine['machine_id'];
	} else {
		echo "30000000_";
	}
}

?>