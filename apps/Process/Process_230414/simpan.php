<?php 
session_start();
include "../../../funlibs.php";
$con = new Database;
//error_reporting(0);
$date = date("Y-m-d H:i:s");
$date2 = date("Y-m-d_G-i-s");
$time = date("H:i:s");

if($_SESSION['ID_LOGIN'] == ''){
	$userlogin = '0';
} else {
	$userlogin = $_SESSION['ID_LOGIN'];
}

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

//jika role_dtl_wo_id <> ''
if ($_POST[roledtlwoid] <> '') { $whroledtlwoid = "and role_dtl_wo_id = '$_POST[roledtlwoid]'"; }
else { $whroledtlwoid = ""; }

//Input Process IN
if ($_POST['process-status'] == '1'){
	if($_POST['cp'] == '') {
		$copres = 0;
	} else {
		$copres = $_POST['cp'];
	}
	$cekroleproses = $con->selectcount("laundry_role_child","role_child_id","role_dtl_wo_id = $_POST[roledtlwoid] and lot_no = '$_POST[lot_no_process]'");
	if($cekroleproses > 0) {
		$idprocess = $con->idurut("laundry_process","process_id");
		$dataprocess = array(
						 'process_id' => $idprocess,
						 'wo_master_dtl_proc_id' => $_POST['wo-master-dtl-proc-id'],
						 'lot_type' => $jenis,
						 'master_process_id' => $_POST['masterprocessid'],
						 'process_type' => 1,
						 'wo_no' => $_POST['wo_no_process'],
						 'garment_colors' => $_POST['garment_colors_process'],
						 'lot_no' => $_POST['lot_no_process'],
						 'master_type_process_id' => $_POST['mastertypeprocessid'],
						 'master_type_lot_id' => $mtlot['master_type_lot_id'],
						 'role_wo_master_id' => $rolewomasterid,
						 'role_wo_id' => $_POST['rolewoid'],
						 'role_dtl_wo_id' => $_POST['roledtlwoid'],
						 'process_createdate' => $date,
						 'process_createdby' => $userlogin,
						 'process_status' => 1,
						 'machine_id' => 0,
						 'role_wo_master_rev' => $rolerev['role_wo_master_rev'],
						 'process_qty_good' => $_POST['qty_process'],
						 'process_qty_reject' => 0,
						 'process_qty_total' => $_POST['qty_process'],
						 'shift_id' => $shift['shift_id'],
						 'user_code'=> $_POST['usercode'],
						 'role_wo_name_seq' => $_POST['rolewonameseq'],
						 'sender' =>  $_POST['sender'],
						 'receiver' =>  $_POST['receiver'],
						 'change_process' => $copres
		); 
		//var_dump($dataprocess);
		$execprocess= $con->insert("laundry_process", $dataprocess);

		//input event Lot
					$idevent = $con->idurut("laundry_lot_event","event_id");
					$dataevent = array(
									 'event_id' => $idevent,
									 'lot_id' => $qtyrej['lot_id'],
									 'lot_no' => $_POST['lot_no_process'],
									 'event_type' => 1,
									 'event_status' => 1,
									 'event_createdby' => $userlogin,
									 'event_createdate' => $date,
									 'master_type_process_id' => $_POST['mastertypeprocessid'],
									 'master_type_lot_id' => $mtlot['master_type_lot_id'],
									 'shift_id' => $shift['shift_id'], 
									 'lot_type' => $jenis,
									 'master_process_id' => $_POST['masterprocessid'],
									 'user_code'=> $_POST['usercode'],
					); 
					$execevent= $con->insert("laundry_lot_event", $dataevent);
		//end event lot

		$datachild = array(
						 'role_child_status' => 4,
						 'role_child_modifydate' => $date,
						 'role_child_modifyby' => $userlogin,
						 'role_child_dateprocess' => $date,
		); 
		$execchild= $con->update("laundry_role_child", $datachild,"lot_no = '".$_POST['lot_no_process']."' and role_wo_id = '$_POST[rolewoid]' $whroledtlwoid");

		} else {
			echo "none";
		}
//process start
} else if ($_POST['process-status'] == '2'){

	if($_POST['cp'] == '') {
		$copres = 0;
	} else {
		$copres = $_POST['cp'];
	}

	$idprocess = $con->idurut("laundry_process","process_id");
	$dataprocess = array(

					 'process_id' => $idprocess,
					 'wo_master_dtl_proc_id' => $_POST['wo-master-dtl-proc-id'],
					 'lot_type' => $jenis,
					 'master_process_id' => $_POST['masterprocessid'],
					 'process_type' => 2,
					 'wo_no' => $_POST['wo_no_process'],
					 'garment_colors' => $_POST['garment_colors_process'],
					 'lot_no' => $_POST['lot_no_process'],
					 'master_type_process_id' => $_POST['mastertypeprocessid'],
					 'master_type_lot_id' => $mtlot['master_type_lot_id'],
					 'role_wo_master_id' => $rolewomasterid,
					 'role_wo_id' => $_POST['rolewoid'],
					 'role_dtl_wo_id' => $_POST['roledtlwoid'],
					 'process_createdate' => $date,
					 'process_createdby' => $userlogin,
					 'process_status' => 1,
					 'machine_id' => $_POST['machineid'],
					 'process_qty_good' => $_POST['qty_process'],
					 'process_qty_reject' => 0,
					 'process_qty_total' => $_POST['qty_process'],
					 'role_wo_master_rev' => $rolerev['role_wo_master_rev'],
					 'shift_id' => $shift['shift_id'],
					 'user_code'=> $_POST['usercode'],
					 'role_wo_name_seq' => $_POST['rolewonameseq'],
					 'operator' => $_POST['operator'],
					 'change_process' => $copres,
					 'foreman' => $_POST['foreman'],
	); 
	//var_dump($dataprocess);
	$execprocess= $con->insert("laundry_process", $dataprocess);

	if ($_POST['usemachine'] == 1) {
		//input process machine
		$idprocmachine = $con->idurut("laundry_process_machine","process_machine_id");
		$idseqmachine = $con->idseq("laundry_process_machine","process_machine_sequence","lot_no","lot_no");
		$time_machine = $_POST['time']*$_POST['qty_process'];
		$dataprocmachine = array(
						 'process_machine_id' => $idprocmachine,
						 'master_process_id' => $_POST['masterprocessid'],
						 'machine_id' => $_POST['machineid'],
						 'process_machine_createdby' => $userlogin,
						 'process_machine_status' => 1,
						 'process_machine_createdate' => $date,
						 'process_machine_time' => $time_machine,
						 'lot_no' => $_POST['lot_no_process'],
						 'lot_type' => $jenis,
						 'process_machine_sequence' => $idseqmachine,
						 'process_machine_onprogress' => 2,
						 'role_wo_id' => $_POST['rolewoid'],
						 'role_dtl_wo_id' => $_POST['roledtlwoid'],
						 'shift_id' => $shift['shift_id'],
						 'user_code' => $_POST['usercode'],
						 'operator_start' => $_POST['operator'],
						 'master_type_process_id' => $_POST['mastertypeprocessid'],
						 'wo_master_dtl_proc_id' => $_POST['wo-master-dtl-proc-id'],
						 'machine_code' => $_POST['machinecode']
						); 
		//var_dump($dataprocmachine);
		$execprocmachine= $con->insert("laundry_process_machine", $dataprocmachine);
		//var_dump($execprocmachine);
		//end input process machine
	}


	//input event Lot
				$idevent = $con->idurut("laundry_lot_event","event_id");
				$dataevent = array(
								 'event_id' => $idevent,
								 'lot_no' => $_POST['lot_no_process'],
								 'event_type' => 1,
								 'event_status' => 1,
								 'event_createdby' => $userlogin,
								 'event_createdate' => $date,
								 'master_type_process_id' => $_POST['mastertypeprocessid'],
								 'master_type_lot_id' => $mtlot['master_type_lot_id'],
								 'shift_id' => $shift['shift_id'], 
								 'lot_type' => $jenis,
								 'master_process_id' => $_POST['masterprocessid'],
								 'user_code'=> $_POST['usercode'],
				); 
				//var_dump($dataevent);
				$execevent= $con->insert("laundry_lot_event", $dataevent);
	//end event lot

	//update status role child saat sudah mulai start
			$datachild = array(
							 'role_child_status' => 3,
							 'role_child_modifydate' => $date,
							 'role_child_modifyby' => $userlogin,
							 'role_child_dateprocess' => $date,
			); 
			$execchild= $con->update("laundry_role_child", $datachild,"lot_no = '".$_POST['lot_no_process']."' and role_wo_id = '$_POST[rolewoid]' $whroledtlwoid");
	//end update status role child
//process End
} else if ($_POST['process-status'] == '3'){

	// cek machine status
	if ($_POST['usemachine'] == 1) {
		if ($_POST['machinebroke'] == '') {
			$machinestatus = '1';
			$process_type = '4';
			//update status role child saat OUT
				$datachild = array(
								 'role_child_status' => 1,
								 'role_child_modifydate' => $date,
								 'role_child_modifyby' => $userlogin,
								 'role_child_dateprocess' => $date,
				); 
				$execchild= $con->update("laundry_role_child", $datachild,"lot_no = '".$_POST['lot_no_process']."' and role_wo_id = '$_POST[rolewoid]' $whroledtlwoid");
			//end update status role child
		} else {
			$machinestatus = $_POST['machinebroke'];
			$process_type = '3';
		}
	}
	else {
		$process_type = '4';
		$datachild = array(
						 'role_child_status' => 1,
						 'role_child_modifydate' => $date,
						 'role_child_modifyby' => $userlogin,
						 'role_child_dateprocess' => $date,
		); 
		$execchild= $con->update("laundry_role_child", $datachild,"lot_no = '".$_POST['lot_no_process']."' and role_wo_id = '$_POST[rolewoid]' $whroledtlwoid");
	}
	//End cek machine status

	// mendapatkan total qty good
	$qtyreject = $_POST['qtygoodori']-$_POST['qtygood'];
	$totalqty = $qtyreject+$_POST['qtygood'];

	$idprocess = $con->idurut("laundry_process","process_id");
	
		if($_POST['cp'] == '') {
			$copres = 0;
		} else {
			$copres = $_POST['cp'];
		}

	//jika type process Dry 
	if($_GET['mtpid'] == 4) {
		$dataprocess = array(
						 'process_id' => $idprocess,
						 'wo_master_dtl_proc_id' => $_POST['wo-master-dtl-proc-id'],
						 'lot_type' => $jenis,
						 'master_process_id' => $_POST['masterprocessid'],
						 'process_type' => $process_type,
						 'wo_no' => $_POST['wo_no_process'],
						 'garment_colors' => $_POST['garment_colors_process'],
						 'lot_no' => $_POST['lot_no_process'],
						 'master_type_process_id' => $_POST['mastertypeprocessid'],
						 'master_type_lot_id' => $mtlot['master_type_lot_id'],
						 'role_wo_master_id' => $rolewomasterid,
						 'role_wo_id' => $_POST['rolewoid'],
						 'role_dtl_wo_id' => $_POST['roledtlwoid'],
						 'process_createdate' => $date,
						 'process_createdby' => $userlogin,
						 'process_status' => 1,
						 'machine_id' => $_POST['machineid'],
						 'process_qty_good' => $_POST['qtygood'],
						 'process_qty_reject' => 0,
					 	 'process_qty_repair' => $qtyreject,
						 'process_qty_total' => $totalqty,
						 'process_remark' => $_POST['remarkmachine'],
						 'role_wo_master_rev' => $rolerev['role_wo_master_rev'],
						 'shift_id' => $shift['shift_id'],
						 'user_code'=> $_POST['usercode'],
						 'role_wo_name_seq' => $_POST['rolewonameseq'],
						 'operator' => $_POST['operator'],
						 'change_process' => $copres,
						 'process_qty_std' => $_POST['qtystd'],
						 'foreman' => $_POST['foreman'],
		);
	} else {
		$dataprocess = array(
						 'process_id' => $idprocess,
						 'wo_master_dtl_proc_id' => $_POST['wo-master-dtl-proc-id'],
						 'lot_type' => $jenis,
						 'master_process_id' => $_POST['masterprocessid'],
						 'process_type' => $process_type,
						 'wo_no' => $_POST['wo_no_process'],
						 'garment_colors' => $_POST['garment_colors_process'],
						 'lot_no' => $_POST['lot_no_process'],
						 'master_type_process_id' => $_POST['mastertypeprocessid'],
						 'master_type_lot_id' => $mtlot['master_type_lot_id'],
						 'role_wo_master_id' => $rolewomasterid,
						 'role_wo_id' => $_POST['rolewoid'],
						 'role_dtl_wo_id' => $_POST['roledtlwoid'],
						 'process_createdate' => $date,
						 'process_createdby' => $userlogin,
						 'process_status' => 1,
						 'machine_id' => $_POST['machineid'],
						 'process_qty_good' => $_POST['qtygood'],
						 'process_qty_reject' => $qtyreject,
						 'process_qty_total' => $totalqty,
						 'process_remark' => $_POST['remarkmachine'],
						 'role_wo_master_rev' => $rolerev['role_wo_master_rev'],
						 'shift_id' => $shift['shift_id'],
						 'user_code'=> $_POST['usercode'],
						 'role_wo_name_seq' => $_POST['rolewonameseq'],
						 'operator' => $_POST['operator'],
						 'change_process' => $copres,
						 'process_qty_std' => $_POST['qtystd'],
						 'foreman' => $_POST['foreman'],
		);
	}
	//var_dump($dataprocess);
	$execprocess= $con->insert("laundry_process", $dataprocess);
	//var_dump($execprocess);
	if ($_POST['usemachine'] == 1) {
		if($_GET['mtpid'] == 4) {
			$rjk = 0;
			$rwk = $qtyreject;
		} else {
			$rjk = $qtyreject;
			$rwk = 0;
		}
		$dataprocessmachine = array(	
						 'process_machine_qty_good' => $_POST['qtygood'],
						 'process_machine_qty_reject' => $rjk,
						 'process_machine_qty_repair' => $rwk,
						 'process_machine_qty_total' => $totalqty,
						 'process_machine_remark' => $_POST['remarkmachine'],
						 'process_machine_status' => $machinestatus,
						 'process_machine_onprogress' => 3,
						 'process_machine_modifyby' => $userlogin,
						 'process_machine_modifydate' => $date,
						 'operator_end' => $_POST['operator']
						 );
		//var_dump($dataprocessmachine);
		$execprocessmachine = $con->update("laundry_process_machine",$dataprocessmachine,"lot_no = '$_POST[lot_no_process]' and machine_id = '$_POST[machineid]'");
	}

	//input event Lot
		$idevent = $con->idurut("laundry_lot_event","event_id");
		$dataevent = array(
						 'event_id' => $idevent,
						 'lot_id' => $qtyrej['lot_id'],
						 'lot_no' => $_POST['lot_no_process'],
						 'event_type' => 1,
						 'event_status' => 1,
						 'event_createdby' => $userlogin,
						 'event_createdate' => $date,
						 'master_type_process_id' => $_POST['mastertypeprocessid'],
						 'master_type_lot_id' => $mtlot['master_type_lot_id'],
						 'shift_id' => $shift['shift_id'], 
						 'lot_type' => $jenis,
						 'master_process_id' => $_POST['masterprocessid'],
						 'user_code'=> $_POST['usercode'],
		); 
		$execevent= $con->insert("laundry_lot_event", $dataevent);
	//end event lot
	
	if($_POST['qtystd'] > 0){
		
		//update lot number =========
		if($_POST['type_lot'] == 'A') {
			$dataupdlotnum = array(	
								 'rec_qty_good_upd' => $_POST['qtygood'],
								 'rec_qty_std_upd' => $_POST['qtystd'],
								 );
			$execupdlotnum = $con->update("laundry_receive",$dataupdlotnum,"lot_no = '$_POST[lot_no_process]'");
			foreach ($con->select("laundry_receive","*,'A' as master_type_lot_initial","rec_no = '$_GET[lot]'") as $lotnum){}
			$lotnumrolewonameseq = 1;
		} else {
			$dataupdlotnum = array(	
								 'lot_qty_good_upd' => $_POST['qtygood'],
								 'lot_qty_std_upd' => $_POST['qtystd'],
								 'lot_modifyby' => $userlogin,
								 'lot_modifydate' => $date
								 );
			$execupdlotnum = $con->update("laundry_lot_number",$dataupdlotnum,"lot_no = '$_POST[lot_no_process]'");
			foreach ($con->select("laundry_lot_number a JOIN laundry_master_type_lot b on a.master_type_lot_id=b.master_type_lot_id","a.*,b.master_type_lot_initial","lot_no = '$_GET[lot]'") as $lotnum){}
			$lotnumrolewonameseq = $lotnum['role_wo_name_seq'];
		}
		// end update lot number ====
		
		//create new lot standart ======
 		
			$sequencecount = $con->selectcount("laundry_lot_number","lot_id","wo_master_dtl_proc_id = '$lotnum[wo_master_dtl_proc_id]'");
			$sequence = $sequencecount+1;
			
			// $urutcount = $con->selectcount("laundry_lot_number","lot_id","wo_no = '$lotnum[wo_no]'");
			// $urut = $urutcount+1;
			$selurutcount = $con->select("laundry_lot_number","COALESCE(max(lot_no_uniq),0) as max","wo_no = '$lotnum[wo_no]'");
			foreach($selurutcount as $urutcount){}
			$urut = $urutcount['max']+1;
		
			$expmt = explode('/',$lotnum['wo_no']);
			$trimexp6 = trim($expmt[6]);

			if($expmt[1] == 'RECUT'){
				$nolb ='L'.$expmt[0]."R".$expmt[3].$expmt[4].trim($expmt[5]).'T'.sprintf('%03s', $urut);
			} else {
				$nolb ='L'.$expmt[0].$expmt[4].$expmt[5].$trimexp6."T".sprintf('%03s', $urut);
			}
			// end create no urut lot number =============

			$idlot = $con->idurut("laundry_lot_number","lot_id");
			$datalot = array(
							 'lot_id' => $idlot,
							 'lot_no' => $nolb, 
							 'wo_no' => $_POST['wo_no_process'],
						 	 'garment_colors' => $_POST['garment_colors_process'],
							 'wo_master_dtl_proc_id' => $lotnum['wo_master_dtl_proc_id'], 
							 'master_type_lot_id' => 11,
							 'role_wo_master_id' => $rolewomasterid,
							 'lot_qty' => $_POST['qtystd'],
							 'lot_qty_std_upd' => $_POST['qtystd'],
							 'lot_kg' => 0,
							 'lot_shade' => $lotnum['lot_shade'],
							 'lot_createdate' => $date,
							 'lot_createdby' => $userlogin,
							 'lot_status' => 1,
							 'create_type' => 4,
							 'lot_type' => 'T',
							 'role_dtl_reject' => $_POST['roledtlwoid'],
							 'reject_from_lot' => $_POST['lot_no_process'],
							 'shift_id' => $shift['shift_id'],
							 'user_code' => $_POST['usercode'],
					 		 'role_wo_name_seq' => $lotnumrolewonameseq,
					 		 'lot_no_uniq' => $urut							 
			); 
			$execlot= $con->insert("laundry_lot_number", $datalot);

			$idlotdtl = $con->idurut("laundry_lot_number_dtl","lot_dtl_id");
			$datalotdtl = array(
							 'lot_dtl_id' => $idlotdtl,
							 'lot_id_parent' => $_POST['lot_no_process'],
							 'lot_id' => $idlot,
							 'lot_dtl_createdby' => $userlogin,
							 'lot_dtl_createdate' => $date,
							 'create_type' => 7,
							 'parent_first' => 0,
							 'qty' => $_POST['qtystd'],
							 'kg' => 0
						);
			$execlotdtl = $con->insert("laundry_lot_number_dtl",$datalotdtl);
			//end create new lot standart ==

			//input ke lot std info ======
			$datalotstdinfo = array(
							 'lot_no' => $_POST['lot_no_process'],
							 'role_wo_id' => $_POST['rolewoid'],
						 	 'role_dtl_wo_id' => $_POST['roledtlwoid'],
							 'master_process_id' => $_POST['masterprocessid'],
							 'master_type_process_id' => $_POST['mastertypeprocessid'],
							 'lot_std_info_createdate' => $date,
							 'lot_std_info_status' => 1
						);
			$execlotstdinfo = $con->insert("laundry_lot_std_info",$datalotstdinfo);
			//==============================

		//input ke FIFO
			foreach ($con->select("laundry_wo_master_dtl_proc","wo_master_dtl_proc_status","wo_master_dtl_proc_id = '$lotnum[wo_master_dtl_proc_id]'") as $wodtlstatus){}
			foreach ($con->select("laundry_log","log_lot_receive","log_lot_tr = '$_POST[lot_no_process]'") as $logrec){}

			$datalog = array(
							'log_lot_tr' => $nolb,
							'log_lot_ref' => $_POST['lot_no_process'],
							'log_lot_qty' => $_POST['qtystd'],
							'log_lot_status' => 1,
							'log_lot_event' => 9,
							'wo_no' => $_POST['wo_no_process'],
						 	'garment_colors' => $_POST['garment_colors_process'],
							'ex_fty_date' => $_POST['ex_fty_date_process'],
							'log_createdate' => $date,
							'log_lot_receive' => $logrec['log_lot_receive'],
							'role_wo_name_seq' => $lotnumrolewonameseq,
							'lotmaking_type' => $wodtlstatus['wo_master_dtl_proc_status'],
				);
				$execlog = $con->insert("laundry_log", $datalog);
			//end input ke FIFO
				
		//insert role child master (fungsi utk master role per lot)
		foreach ($con->select("laundry_role_child_master","*","lot_no = '$_POST[lot_no_process]'") as $cekrcm){}
		$datachildmaster = array(
					'lot_no' => $nolb,
					'role_wo_master_id'  => $cekrcm['role_wo_master_id'],
					'lot_status' => 1,
					'lot_type' => $cekrcm['lot_type'],
					'lot_id' => $idlot,
					'role_wo_name_seq' => $cekrcm['role_wo_name_seq']
				);
		
		$execchildmaster = $con->insert("laundry_role_child_master", $datachildmaster);
		//=========================================================	

		//input data role child
		$selrolechild = $con->select("laundry_role_child","*","lot_no = '".$_POST['lot_no_process']."' and role_child_status = 0 and role_child_id !=  (select role_child_id from laundry_role_child where lot_no = '".$_POST['lot_no_process']."' and role_wo_id = '$_POST[rolewoid]' $whroledtlwoid) ");
		
		foreach ($selrolechild as $child) {
			$idchild = $con->idurut("laundry_role_child","role_child_id");
			$datachild = array(
						'role_child_id' => $idchild,
						'role_wo_master_id'  => $child['role_wo_master_id'],
						'role_wo_id'  => $child['role_wo_id'],
						'role_dtl_wo_id'  => $child['role_dtl_wo_id'],
						'lot_type'  => $child['lot_type'],
						'role_child_status'  => $child['role_child_status'],
						'role_child_createdate'  => $date,
						'role_child_createdby'  => $userlogin,
						'lot_no'  => $nolb,
						'lot_id'  => $idlot,
						'role_wo_seq' => $child['role_wo_seq'],
						'role_dtl_wo_seq' => $child['role_dtl_wo_seq'],
			);
			
			$execchild = $con->insert("laundry_role_child", $datachild);
		}
		
		echo  $nolb."_".base64_encode($nolb);
	}

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
	
		if($_POST['cp'] == '') {
			$copres = 0;
		} else {
			$copres = $_POST['cp'];
		}

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
					 'role_wo_name_seq' => $_POST['rolewonameseq'],
					 'change_process' => $copres
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
					 'process_qty_good' => $_POST['qtygoodori'],
					 'process_qty_reject' => $qtyreject,
					 'process_qty_total' => $totalqty,
					 'process_remark' => $_POST['remarkmachine'],
					 'role_wo_master_rev' => $rolerev['role_wo_master_rev'],
					 'shift_id' => $shift['shift_id'],
					 'user_code'=> $_POST['usercode'],
					 'role_wo_name_seq' => $_POST['rolewonameseq'],
					 'change_process' => $copres
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
								 'event_createdby' => $userlogin,
								 'event_createdate' => $date,
								 'master_type_process_id' => $_POST['mastertypeprocessid'],
								 'master_type_lot_id' => $mtlot['master_type_lot_id'],
								 'shift_id' => $shift['shift_id'], 
								 'lot_type' => $jenis,
								 'master_process_id' => $_POST['masterprocessid'],
								 'user_code'=> $_POST['usercode'],
				); 
				$execevent= $con->insert("laundry_lot_event", $dataevent);
		//end event lot

		//Create Lot Reject Jika Ada Reject saat Out Lot ======================
				

		// if($qtyreject > 0) {

		// 	$reject = $qtyrej['lot_qty_reject_upd']+$qtyreject;
		
		// 	$dataqtylot = array(
		// 				 'lot_qty_good_upd' => $_POST['qtygood'],
		// 				 'lot_qty_reject_upd' => $reject,
		// 				 );
		// 	$execqtylot = $con->update("laundry_lot_number",$dataqtylot,"lot_no = '$_GET[lot]'");

		// 	//create no urut lot number ===================
		// 	foreach ($con->select("laundry_lot_number a JOIN laundry_master_type_lot b on a.master_type_lot_id=b.master_type_lot_id","a.*,b.master_type_lot_initial","lot_no = '$_GET[lot]'") as $lotnum){}
		// 		//echo "select a.*,b.master_type_lot_initial from laundry_lot_number a join laundry_master_type_lot b on a.master_type_lot_id=b.master_type_lot_id where lot_no = '$_GET[lot]'";

		// 	$sequencecount = $con->selectcount("laundry_lot_number","lot_id","wo_master_dtl_proc_id = '$lotnum[wo_master_dtl_proc_id]'");
		// 	$sequence = $sequencecount+1;
			
		// 	$urutcount = $con->selectcount("laundry_lot_number","lot_id","wo_no = '$lotnum[wo_no]'");
		// 	$urut = $urutcount+1;

		// 	$expmt = explode('/',$lotnum['wo_no']);
		// 	$trimexp6 = trim($expmt[6]);

		// 	$nolb ='L'.$expmt[0].$expmt[4].$expmt[5].$trimexp6."R".sprintf('%03s', $urut);
		// 	// end create no urut lot number =============

		// 	$idlot = $con->idurut("laundry_lot_number","lot_id");
		// 	$datalot = array(
		// 					 'lot_id' => $idlot,
		// 					 'lot_no' => $nolb, 
		// 					 'wo_no' => $_POST['wo-no'],
		// 					 'garment_colors' => $_POST['colors'],
		// 					 'wo_master_dtl_proc_id' => $lotnum['wo_master_dtl_proc_id'], 
		// 					 'master_type_lot_id' => 5,
		// 					 'role_wo_master_id' => $rolewomasterid,
		// 					 'lot_qty' => $qtyreject,
		// 					 'lot_qty_reject_upd' => $qtyreject,
		// 					 'lot_kg' => 0,
		// 					 'lot_shade' => $lotnum['lot_shade'],
		// 					 'lot_createdate' => $date,
		// 					 'lot_createdby' => $userlogin,
		// 					 'lot_status' => 1,
		// 					 'create_type' => 4,
		// 					 'lot_type' => 'R',
		// 					 'role_dtl_reject' => $_POST['roledtlwoid'],
		// 					 'reject_from_lot' => $_POST['lot-no'],
		// 					 'shift_id' => $shift['shift_id'],
							 
		// 	); 
		// 	$execlot= $con->insert("laundry_lot_number", $datalot);

		// 	$idlotdtl = $con->idurut("laundry_lot_number_dtl","lot_dtl_id");
		// 	$datalotdtl = array(
		// 					 'lot_dtl_id' => $idlotdtl,
		// 					 'lot_id_parent' => $_GET['lot'], 
		// 					 'lot_id' => $idlot,
		// 					 'lot_dtl_createdby' => $userlogin,
		// 					 'lot_dtl_createdate' => $date,
		// 					 'create_type' => 3,
		// 					 'parent_first' => 0,
		// 				);
		// 	$execlotdtl = $con->insert("laundry_lot_number_dtl",$datalotdtl);

		// 	//input event Lot
		// 		$idevent = $con->idurut("laundry_lot_event","event_id");
		// 		$dataevent = array(
		// 						 'event_id' => $idevent,
		// 						 'lot_id' => $idlot,
		// 						 'lot_no' => $nolb,
		// 						 'event_type' => 5,
		// 						 'event_createdby' => $userlogin,
		// 						 'event_createdate' => $date,
		// 						 'master_type_process_id' => $_POST['mastertypeprocessid'],
		// 						 'master_type_lot_id' => 5,
		// 						 'shift_id' => $shift['shift_id'], 
		// 						 'lot_type' => $jenis,
		// 						 'master_process_id' => $_POST['masterprocessid'],
		// 		); 
		// 		$execevent= $con->insert("laundry_lot_event", $dataevent);
		// 	//end event lot
		// 	echo $nolb."_".base64_encode($nolb);
		// }
		//=====================================================================	
	}

	//update status role child saat OUT
			$datachild = array(
							 'role_child_status' => 1,
							 'role_child_modifydate' => $date,
							 'role_child_modifyby' => $userlogin,
							 'role_child_dateprocess' => $date,
			); 
			$execchild= $con->update("laundry_role_child", $datachild,"lot_no = '".$_POST['lot_no_process']."' and role_wo_id = '$_POST[rolewoid]' $whroledtlwoid");
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
					 'process_machine_modifyby' => $userlogin, 
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
						 'user_code' => $_POST['usercode'],
						); 
		//var_dump($dataprocmachine);
		$execprocmachine= $con->insert("laundry_process_machine", $dataprocmachine);
		//var_dump($execprocmachine);
	}

} 

?>
