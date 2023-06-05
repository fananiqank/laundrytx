<?php 
session_start();
include "../../../funlibs.php";
$con = new Database;
//error_reporting(0);
$date = date("Y-m-d H:i:s");
$date2 = date("Y-m-d_G-i-s");
$time = date("H:i:s");

//shift 
foreach($con->select("laundry_master_shift","shift_id,shift_time_start,shift_time_end,shift_status","TO_CHAR(now(), 'HH24:MI') between TO_CHAR(shift_time_start, 'HH24:MI') and TO_CHAR(shift_time_end, 'HH24:MI') and shift_status = 1") as $shift){}
	// echo "select shift_id,shift_time_start,shift_time_end,shift_status from laundry_master_shift where TO_CHAR(now(), 'HH24:MI') between TO_CHAR(shift_time_start, 'HH24:MI') and TO_CHAR(shift_time_end, 'HH24:MI') and shift_status = 1";
//end shift

if ($_POST['type_lot'] == 'A'){
		$jenis = 1;
} else {
		$jenis = 2;
}

$expdata = explode('_',$_POST['data_process']);
$role_wo_master_id = $expdata[0];
$role_wo_id  = $expdata[1];

$wo_master_dtl_proc_id = $expdata[2];
if ($expdata[3] == ''){
$master_process_id = '0';
}else {
$master_process_id = $expdata[3];
}
if ($expdata[4] == ''){
$master_process_usemachine = 0;
} else {
$master_process_usemachine = $expdata[4];	
}

$rolewomasterid = $_POST['role-wo-master-id'];
//cek di laundry process sudah ada apa belum 
$selectprocess = $con->select("laundry_process","count(process_id) jmlprocessid","role_wo_master_id = '$role_wo_master_id' and role_wo_id = '$role_wo_id' and wo_master_dtl_proc_id = '$wo_master_dtl_proc_id'");
foreach ($selectprocess as $process) {}
	
//dapatkan qty reject terakhir di laundry_lot_number
foreach ($con->select("laundry_lot_number","lot_id,lot_qty_reject_upd","lot_no = '$_GET[lot]'") as $qtyrej){}

foreach ($con->select("laundry_role_wo_master","role_wo_master_rev","role_wo_master_id = '$rolewomasterid'") as $rolerev){}

//select master_type_lot_id	
foreach($con->select("laundry_master_type_lot","master_type_lot_id","master_type_lot_initial = '$_POST[type_lot]'") as $mtlot){}
//Input Process IN
if ($_POST['process-status'] == '1'){
	$idprocess = $con->idurut("laundry_process","process_id");
	$dataprocess = array(
					 'process_id' => $idprocess,
					 'wo_master_dtl_proc_id' => $_POST['wo-master-dtl-proc-id'],
					 'lot_type' => $jenis,
					 'master_process_id' => $_POST['masterprocessid'],
					 'process_type' => 1,
					 'wo_no' => $_POST['wo-no'],
					 'garment_colors' => $_POST['colors'],
					 'lot_no' => $_POST['lot-no'],
					 'master_type_process_id' => $_POST['mastertypeprocessid'],
					 'master_type_lot_id' => $mtlot['master_type_lot_id'],
					 'role_wo_master_id' => $rolewomasterid,
					 'role_wo_id' => $_POST['rolewoid'],
					 'role_dtl_wo_id' => $_POST['roledtlwoid'],
					 'process_createdate' => $date,
					 'process_createdby' => $_POST['user-id'],
					 'process_status' => 1,
					 'machine_id' => 0,
					 'role_wo_master_rev' => $rolerev['role_wo_master_rev'],
					 'process_qty_good' => $_POST['qty'],
					 'process_qty_reject' => 0,
					 'process_qty_total' => $_POST['qty'],
					 'shift_id' => $shift['shift_id'],
					 'user_code'=> $_POST['usercode'],
	); 
	$execprocess= $con->insert("laundry_process", $dataprocess);

	//input event Lot
				$idevent = $con->idurut("laundry_lot_event","event_id");
				$dataevent = array(
								 'event_id' => $idevent,
								 'lot_id' => $qtyrej['lot_id'],
								 'lot_no' => $_POST['lot-no'],
								 'event_type' => 1,
								 'event_status' => 1,
								 'event_createdby' => $_SESSION['ID_LOGIN'],
								 'event_createdate' => $date,
								 'master_type_process_id' => $_POST['mastertypeprocessid'],
								 'master_type_lot_id' => $mtlot['master_type_lot_id'],
								 'shift_id' => $shift['shift_id'], 
								 'lot_type' => $jenis,
								 'master_process_id' => $_POST['masterprocessid'],
				); 
				$execevent= $con->insert("laundry_lot_event", $dataevent);
	//end event lot
//process start
} else if ($_POST['process-status'] == '2'){

	$idprocess = $con->idurut("laundry_process","process_id");
	$dataprocess = array(
					 'process_id' => $idprocess,
					 'wo_master_dtl_proc_id' => $_POST['wo-master-dtl-proc-id'],
					 'lot_type' => $jenis,
					 'master_process_id' => $_POST['masterprocessid'],
					 'process_type' => 2,
					 'wo_no' => $_POST['wo-no'],
					 'garment_colors' => $_POST['colors'],
					 'lot_no' => $_POST['lot-no'],
					 'master_type_process_id' => $_POST['mastertypeprocessid'],
					 'master_type_lot_id' => $mtlot['master_type_lot_id'],
					 'role_wo_master_id' => $rolewomasterid,
					 'role_wo_id' => $_POST['rolewoid'],
					 'role_dtl_wo_id' => $_POST['roledtlwoid'],
					 'process_createdate' => $date,
					 'process_createdby' => $_POST['user-id'],
					 'process_status' => 1,
					 'machine_id' => $_GET['machine'],
					 'process_qty_good' => $_POST['qty'],
					 'process_qty_reject' => 0,
					 'process_qty_total' => $_POST['qty'],
					 'role_wo_master_rev' => $rolerev['role_wo_master_rev'],
					 'shift_id' => $shift['shift_id'],
					 'user_code'=> $_POST['usercode'],
	); 
	$execprocess= $con->insert("laundry_process", $dataprocess);

	$dataprocessmachine = array(
					 'process_machine_onprogress' => 2,
					 );
	$execprocessmachine = $con->update("laundry_process_machine",$dataprocessmachine,"lot_no = '$_GET[lot]' and machine_id = '$_GET[machine]'");

	//input event Lot
				$idevent = $con->idurut("laundry_lot_event","event_id");
				$dataevent = array(
								 'event_id' => $idevent,
								 'lot_id' => $qtyrej['lot_id'],
								 'lot_no' => $_POST['lot-no'],
								 'event_type' => 1,
								 'event_status' => 1,
								 'event_createdby' => $_SESSION['ID_LOGIN'],
								 'event_createdate' => $date,
								 'master_type_process_id' => $_POST['mastertypeprocessid'],
								 'master_type_lot_id' => $mtlot['master_type_lot_id'],
								 'shift_id' => $shift['shift_id'], 
								 'lot_type' => $jenis,
								 'master_process_id' => $_POST['masterprocessid'],
				); 
				$execevent= $con->insert("laundry_lot_event", $dataevent);
	//end event lot

	//update status role child saat sudah mulai start
			$datachild = array(
							 'role_child_status' => 3,
							 'role_child_modifydate' => $date,
							 'role_child_modifyby' => $_SESSION['ID_LOGIN'],
			); 
			$execchild= $con->update("laundry_role_child", $datachild,"lot_no = '".$_POST['lot-no']."' and role_wo_id = '$_POST[rolewoid]' and role_dtl_wo_id = '$_POST[roledtlwoid]'");
	//end update status role child
//process End
} else if ($_POST['process-status'] == '3'){

	// cek machine status
	
	if ($_POST['machinebroke'] == '') {
		$machinestatus = '1';
	} else {
		$machinestatus = $_POST['machinebroke'];
	}
	
	// mendapatkan total qty good
	$qtyreject = $_POST['qtygoodori']-$_POST['qtygood'];
	$totalqty = $qtyreject+$_POST['qtygood'];

	$idprocess = $con->idurut("laundry_process","process_id");
	$dataprocess = array(
					 'process_id' => $idprocess,
					 'wo_master_dtl_proc_id' => $_POST['wo-master-dtl-proc-id'],
					 'lot_type' => $jenis,
					 'master_process_id' => $_POST['masterprocessid'],
					 'process_type' => 3,
					 'wo_no' => $_POST['wo-no'],
					 'garment_colors' => $_POST['colors'],
					 'lot_no' => $_POST['lot-no'],
					 'master_type_process_id' => $_POST['mastertypeprocessid'],
					 'master_type_lot_id' => $mtlot['master_type_lot_id'],
					 'role_wo_master_id' => $rolewomasterid,
					 'role_wo_id' => $_POST['rolewoid'],
					 'role_dtl_wo_id' => $_POST['roledtlwoid'],
					 'process_createdate' => $date,
					 'process_createdby' => $_POST['user-id'],
					 'process_status' => 1,
					 'machine_id' => $_GET['machine'],
					 'process_qty_good' => $_POST['qtygood'],
					 'process_qty_reject' => $qtyreject,
					 'process_qty_total' => $totalqty,
					 'process_remark' => $_POST['remarkmachine'],
					 'role_wo_master_rev' => $rolerev['role_wo_master_rev'],
					 'shift_id' => $shift['shift_id'],
					 'user_code'=> $_POST['usercode'],
	);
	//var_dump($dataprocess);
	$execprocess= $con->insert("laundry_process", $dataprocess);
	//var_dump($execprocess);
	$dataprocessmachine = array(	
					 'process_machine_qty_good' => $_POST['qtygood'],
					 'process_machine_qty_reject' => $qtyreject,
					 'process_machine_qty_total' => $totalqty,
					 'process_machine_remark' => $_POST['remarkmachine'],
					 'process_machine_status' => $machinestatus,
					 'process_machine_onprogress' => 3,
					 );
	//var_dump($dataprocessmachine);
	$execprocessmachine = $con->update("laundry_process_machine",$dataprocessmachine,"lot_no = '$_GET[lot]' and machine_id = '$_GET[machine]'");
	//echo "lot_no = ".$_GET[lot]." and machine_id = ".$_GET[machine];

	$reject = $qtyrej['lot_qty_reject_upd']+$qtyreject;
	$dataqtylot = array(
					 'lot_qty_good_upd' => $_POST['qtygood'],
					 'lot_qty_reject_upd' => $reject,
					 );
	$execqtylot = $con->update("laundry_lot_number",$dataqtylot,"lot_no = '$_GET[lot]'");

	//input event Lot
				$idevent = $con->idurut("laundry_lot_event","event_id");
				$dataevent = array(
								 'event_id' => $idevent,
								 'lot_id' => $qtyrej['lot_id'],
								 'lot_no' => $_POST['lot-no'],
								 'event_type' => 1,
								 'event_status' => 1,
								 'event_createdby' => $_SESSION['ID_LOGIN'],
								 'event_createdate' => $date,
								 'master_type_process_id' => $_POST['mastertypeprocessid'],
								 'master_type_lot_id' => $mtlot['master_type_lot_id'],
								 'shift_id' => $shift['shift_id'], 
								 'lot_type' => $jenis,
								 'master_process_id' => $_POST['masterprocessid'],
				); 
				$execevent= $con->insert("laundry_lot_event", $dataevent);
	//end event lot
//process Out
} else if ($_POST['process-status'] == '4'){
	
	if ($_GET['machine'] == ''){
		$getmachine = 0;
	} else {
		$getmachine = $_GET['machine'];
	}

	if($_POST['qtyreject'] != 0 ){
		$qtyreject = $_POST['qtyreject'];
	} else {
		$qtyreject = $_POST['qtygoodori']-$_POST['qtygood'];
	}

	$totalqty = $qtyreject+$_POST['qtygood'];

	$idprocess = $con->idurut("laundry_process","process_id");
	
	//jika type process Dry 
	if($_GET['mtpid'] == 4) {
		$dataprocess = array(
					 'process_id' => $idprocess,
					 'wo_master_dtl_proc_id' => $_POST['wo-master-dtl-proc-id'],
					 'lot_type' => $jenis,
					 'master_process_id' => $_POST['masterprocessid'],
					 'process_type' => 4,
					 'wo_no' => $_POST['wo-no'],
					 'garment_colors' => $_POST['colors'],
					 'lot_no' => $_POST['lot-no'],
					 'master_type_process_id' => $_POST['mastertypeprocessid'],
					 'master_type_lot_id' => $mtlot['master_type_lot_id'],
					 'role_wo_master_id' => $rolewomasterid,
					 'role_wo_id' => $_POST['rolewoid'],
					 'role_dtl_wo_id' => $_POST['roledtlwoid'],
					 'process_createdate' => $date,
					 'process_createdby' => $_POST['user-id'],
					 'process_status' => 1,
					 'machine_id' => $getmachine,
					 'process_qty_good' => $_POST['qtygoodori'],
					 'process_qty_reject' => 0,
					 'process_qty_repair' => $qtyreject,
					 'process_qty_total' => $totalqty,
					 'process_remark' => $_POST['remarkmachine'],
					 'role_wo_master_rev' => $rolerev['role_wo_master_rev'],
					 'shift_id' => $shift['shift_id'],
					 'user_code'=> $_POST['usercode'],
		);
		$execprocess= $con->insert("laundry_process", $dataprocess); 
	
	} else {
		$dataprocess = array(
					 'process_id' => $idprocess,
					 'wo_master_dtl_proc_id' => $_POST['wo-master-dtl-proc-id'],
					 'lot_type' => $jenis,
					 'master_process_id' => $_POST['masterprocessid'],
					 'process_type' => 4,
					 'wo_no' => $_POST['wo-no'],
					 'garment_colors' => $_POST['colors'],
					 'lot_no' => $_POST['lot-no'],
					 'master_type_process_id' => $_POST['mastertypeprocessid'],
					 'master_type_lot_id' => $mtlot['master_type_lot_id'],
					 'role_wo_master_id' => $rolewomasterid,
					 'role_wo_id' => $_POST['rolewoid'],
					 'role_dtl_wo_id' => $_POST['roledtlwoid'],
					 'process_createdate' => $date,
					 'process_createdby' => $_POST['user-id'],
					 'process_status' => 1,
					 'machine_id' => $getmachine,
					 'process_qty_good' => $_POST['qtygood'],
					 'process_qty_reject' => $qtyreject,
					 'process_qty_total' => $totalqty,
					 'process_remark' => $_POST['remarkmachine'],
					 'role_wo_master_rev' => $rolerev['role_wo_master_rev'],
					 'shift_id' => $shift['shift_id'],
					 'user_code'=> $_POST['usercode'],
		);
		$execprocess= $con->insert("laundry_process", $dataprocess);
		
		//input event Lot
				$idevent = $con->idurut("laundry_lot_event","event_id");
				$dataevent = array(
								 'event_id' => $idevent,
								 'lot_id' => $qtyrej['lot_id'],
								 'lot_no' => $_POST['lot-no'],
								 'event_type' => 1,
								 'event_status' => 1,
								 'event_createdby' => $_SESSION['ID_LOGIN'],
								 'event_createdate' => $date,
								 'master_type_process_id' => $_POST['mastertypeprocessid'],
								 'master_type_lot_id' => $mtlot['master_type_lot_id'],
								 'shift_id' => $shift['shift_id'], 
								 'lot_type' => $jenis,
								 'master_process_id' => $_POST['masterprocessid'],
				); 
				$execevent= $con->insert("laundry_lot_event", $dataevent);
		//end event lot

		//Create Lot Reject Jika Ada Reject saat Out Lot ======================
				

		if($qtyreject > 0) {

			$reject = $qtyrej['lot_qty_reject_upd']+$qtyreject;
		
			$dataqtylot = array(
						 'lot_qty_good_upd' => $_POST['qtygood'],
						 'lot_qty_reject_upd' => $reject,
						 );
			$execqtylot = $con->update("laundry_lot_number",$dataqtylot,"lot_no = '$_GET[lot]'");

			//create no urut lot number ===================
			foreach ($con->select("laundry_lot_number a JOIN laundry_master_type_lot b on a.master_type_lot_id=b.master_type_lot_id","a.*,b.master_type_lot_initial","lot_no = '$_GET[lot]'") as $lotnum){}
				//echo "select a.*,b.master_type_lot_initial from laundry_lot_number a join laundry_master_type_lot b on a.master_type_lot_id=b.master_type_lot_id where lot_no = '$_GET[lot]'";

			$sequencecount = $con->selectcount("laundry_lot_number","lot_id","wo_master_dtl_proc_id = '$lotnum[wo_master_dtl_proc_id]'");
			$sequence = $sequencecount+1;
			
			$urutcount = $con->selectcount("laundry_lot_number","lot_id","wo_no = '$lotnum[wo_no]'");
			$urut = $urutcount+1;

			$expmt = explode('/',$lotnum['wo_no']);
			$trimexp6 = trim($expmt[6]);

			$nolb ='L'.$expmt[0].$expmt[4].$expmt[5].$trimexp6."R".sprintf('%03s', $urut);
			// end create no urut lot number =============

			$idlot = $con->idurut("laundry_lot_number","lot_id");
			$datalot = array(
							 'lot_id' => $idlot,
							 'lot_no' => $nolb, 
							 'wo_no' => $_POST['wo-no'],
							 'garment_colors' => $_POST['colors'],
							 'wo_master_dtl_proc_id' => $lotnum['wo_master_dtl_proc_id'], 
							 'master_type_lot_id' => 5,
							 'role_wo_master_id' => $rolewomasterid,
							 'lot_qty' => $qtyreject,
							 'lot_qty_reject_upd' => $qtyreject,
							 'lot_kg' => 0,
							 'lot_shade' => $lotnum['lot_shade'],
							 'lot_createdate' => $date,
							 'lot_createdby' => $_SESSION['ID_LOGIN'],
							 'lot_status' => 1,
							 'create_type' => 4,
							 'lot_type' => 'R',
							 'role_dtl_reject' => $_POST['roledtlwoid'],
							 'reject_from_lot' => $_POST['lot-no'],
							 'shift_id' => $shift['shift_id'],
							 
			); 
			$execlot= $con->insert("laundry_lot_number", $datalot);

			$idlotdtl = $con->idurut("laundry_lot_number_dtl","lot_dtl_id");
			$datalotdtl = array(
							 'lot_dtl_id' => $idlotdtl,
							 'lot_id_parent' => $_GET['lot'], 
							 'lot_id' => $idlot,
							 'lot_dtl_createdby' => $_SESSION['ID_LOGIN'],
							 'lot_dtl_createdate' => $date,
							 'create_type' => 3,
							 'parent_first' => 0,
						);
			$execlotdtl = $con->insert("laundry_lot_number_dtl",$datalotdtl);

			//input event Lot
				$idevent = $con->idurut("laundry_lot_event","event_id");
				$dataevent = array(
								 'event_id' => $idevent,
								 'lot_id' => $idlot,
								 'lot_no' => $nolb,
								 'event_type' => 5,
								 'event_createdby' => $_SESSION['ID_LOGIN'],
								 'event_createdate' => $date,
								 'master_type_process_id' => $_POST['mastertypeprocessid'],
								 'master_type_lot_id' => 5,
								 'shift_id' => $shift['shift_id'], 
								 'lot_type' => $jenis,
								 'master_process_id' => $_POST['masterprocessid'],
				); 
				$execevent= $con->insert("laundry_lot_event", $dataevent);
			//end event lot
			echo $nolb."_".base64_encode($nolb);
		}
		//=====================================================================	
	}

	//update status role child saat OUT
			$datachild = array(
							 'role_child_status' => 1,
							 'role_child_modifydate' => $date,
							 'role_child_modifyby' => $_SESSION['ID_LOGIN'],
							 'role_child_dateprocess' => $date,
			); 
			$execchild= $con->update("laundry_role_child", $datachild,"lot_no = '".$_POST['lot-no']."' and role_wo_id = '$_POST[rolewoid]' and role_dtl_wo_id = '$_POST[roledtlwoid]'");
	//end update status role child
	
} 
//input machine from first page
else if ($_POST['machine-input'] == '1'){
	
	$idprocmachine = $con->idurut("laundry_process_machine","process_machine_id");
	$idseqmachine = $con->idseq("laundry_process_machine","process_machine_sequence","lot_no","lot_no");
	$dataprocmachine = array(
					 'process_machine_id' => $idprocmachine,
					 'master_process_id' => $_POST['master_process_id'],
					 'machine_id' => $_POST['machineid'],
					 'process_machine_createdby' => $_POST['userid'],
					 'process_machine_status' => 1,
					 'process_machine_createdate' => $date,
					 'lot_no' => $_POST['lot_no'],
					 'lot_type' => $jenis,
					 'process_machine_sequence' => $idseqmachine,
					 'process_machine_onprogress' => 1,
					 'role_wo_id' => $_POST['role_wo_id'],
					 'shift_id' => $shift['shift_id'],
					 'user_code' => $_POST['usercode'],
					); 
	//var_dump($dataprocmachine);
	$execprocmachine= $con->insert("laundry_process_machine", $dataprocmachine);
	//var_dump($execprocmachine);

} 
//hapus machine
else if ($_POST['machine-input'] == '2'){
	//echo $_POST[machine_id];
	$dataprocmachine = array(
					 'process_machine_status' => 0,
					 'process_machine_modifyby' => $_SESSION['ID_LOGIN'], 
					 'process_machine_modifydate' => $date,
					 'shift_id' => $shift['shift_id'],
					 'user_code' => $_POST['usercode'],
					); 
	$execprocmachine= $con->update("laundry_process_machine", $dataprocmachine, "process_machine_id = '$_POST[machine_id]'");

}

//input machine time
else if ($_POST['machine-input'] == '3'){
	foreach ($_POST['timemac'] as $key => $value) {
		$postmachineid = "time_id_".$_GET['no'];
		$machineid = $_POST[$postmachineid];
		$dataprocmachine = array(
						 'process_machine_time' => $value,
						); 
		$execprocmachine= $con->update("laundry_process_machine", $dataprocmachine, 
						               "process_machine_id = '$machineid'");
	}	
}
//input machine from process
else if ($_POST['machine-input'] == '4'){

	foreach ($_POST['machine'] as $key => $value) {
		$expisi = explode("_", $_POST['machine_isi']);
		$master_process_id = $expisi[0];
		$lotno = $expisi[1];
		$user = $expisi[2];
		$timemachine = "timemachine_".$value;
		$idprocmachine = $con->idurut("laundry_process_machine","process_machine_id");
		$idseqmachine = $con->idseq("laundry_process_machine","process_machine_sequence","lot_no","lot_no");
		
		//cek timemachine;
		include "cekmachine.php"; 

		$dataprocmachine = array(
						 'process_machine_id' => $idprocmachine,
						 'master_process_id' => $master_process_id,
						 'machine_id' => $value,
						 'process_machine_createdby' => $_SESSION[ID_LOGIN],
						 'process_machine_status' => 1,
						 'process_machine_createdate' => $date,
						 'lot_no' => $lotno,
						 'lot_type' => $jenis,
						 'process_machine_sequence' => $idseqmachine,
						 'process_machine_onprogress' => 1,
						 'role_wo_id' => $_POST['role_wo_id_mod'],
						 'process_machine_time' => $_POST[$timemachine],
						 'shift_id' => $shift['shift_id'],
						 'user_code' => $user,
						); 
		//var_dump($dataprocmachine);
		$execprocmachine= $con->insert("laundry_process_machine", $dataprocmachine);
		//var_dump($execprocmachine);
	}

} 

?>
