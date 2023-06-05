<?php 
session_start();
include "../../../funlibs.php";
$con = new Database;
//error_reporting(0);
$date = date("Y-m-d H:i:s");
$date2 = date("Y-m-d_G-i-s");
$time = date("H:i:s");
$tabel = "laundry_lot_number";

if($_SESSION['ID_LOGIN'] == ''){
	$userlogin = '834';
} else {
	$userlogin = $_SESSION['ID_LOGIN'];
}
//shift 
foreach($con->select("laundry_master_shift","shift_id,shift_time_start,shift_time_end,shift_status","TO_CHAR(now(), 'HH24:MI') between TO_CHAR(shift_time_start, 'HH24:MI') and TO_CHAR(shift_time_end, 'HH24:MI') and shift_status = 1") as $shift){}
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
foreach ($con->select("laundry_lot_number","lot_id,lot_qty_reject_upd,lot_qty_repair_upd,lot_qty_std_upd","lot_no = '$_POST[lot_no_process]'") as $qtyrej){}
foreach ($con->select("laundry_role_wo_master","role_wo_master_rev","role_wo_master_id = '$rolewomasterid'") as $rolerev){}

//select master_type_lot_id	
foreach($con->select("laundry_master_type_lot","master_type_lot_id","master_type_lot_initial = '$_POST[type_lot]'") as $mtlot){}
//select max role wo name seq qc
foreach($con->select("laundry_role_wo","max(role_wo_name_seq) as maxroleseq","role_wo_master_id = '$rolewomasterid' and master_type_process_id = 3") as $maxrole) {}
// simpan dari create lot
//Input Process IN
if ($_POST['process-status'] == '1'){
	$idprocess = $con->idurut("laundry_process","process_id");
	$dataprocess = array(
					 'process_id' => $idprocess,
					 'wo_master_dtl_proc_id' => $_POST['wo-master-dtl-proc-id'],
					 'lot_type' => $jenis,
					 'master_process_id' => 0,
					 'process_type' => 1,
					 'wo_no' => $_POST['wo_no_process'],
					 'garment_colors' => $_POST['garment_colors_process'],
					 'lot_no' => $_POST['lot_no_process'],
					 'master_type_process_id' => $_POST['mastertypeprocessid'],
					 'master_type_lot_id' => $mtlot['master_type_lot_id'],
					 'role_wo_master_id' => $rolewomasterid,
					 'role_wo_id' => $_POST['rolewoid'],
					 'role_dtl_wo_id' => 0,
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
					 'change_process' => 0
	); 
	//var_dump($dataprocess);
	$execprocess= $con->insert("laundry_process", $dataprocess);
	//var_dump($execprocess);
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
								 'master_process_id' => 0,
								 'user_code'=> $_POST['usercode'],
				); 
				//var_dump($dataevent);
				$execevent= $con->insert("laundry_lot_event", $dataevent);
				//var_dump($execevent);
	//end event lot
	$datachild = array(
					 'role_child_status' => 4,
					 'role_child_modifydate' => $date,
					 'role_child_modifyby' => $userlogin,
					 'role_child_dateprocess' => $date,
	); 
	$execchild= $con->update("laundry_role_child", $datachild,"lot_no = '".$_POST['lot_no_process']."' and role_wo_id = '$_POST[rolewoid]' and role_dtl_wo_id = '$_POST[roledtlwoid]'");

//process start
} else if ($_POST['process-status'] == '2'){

	$idprocess = $con->idurut("laundry_process","process_id");
	$dataprocess = array(

					 'process_id' => $idprocess,
					 'wo_master_dtl_proc_id' => $_POST['wo-master-dtl-proc-id'],
					 'lot_type' => $jenis,
					 'master_process_id' => 0,
					 'process_type' => 2,
					 'wo_no' => $_POST['wo_no_process'],
					 'garment_colors' => $_POST['garment_colors_process'],
					 'lot_no' => $_POST['lot_no_process'],
					 'master_type_process_id' => $_POST['mastertypeprocessid'],
					 'master_type_lot_id' => $mtlot['master_type_lot_id'],
					 'role_wo_master_id' => $rolewomasterid,
					 'role_wo_id' => $_POST['rolewoid'],
					 'role_dtl_wo_id' => 0,
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
					 'change_process' => 0
	); 
	//var_dump($dataprocess);
	$execprocess= $con->insert("laundry_process", $dataprocess);

	if ($_POST['usemachine'] == 1) {
		$idprocmachine = $con->idurut("laundry_process_machine_progress","process_mach_id");
		$idseqmachine = $con->idseq("laundry_process_machine_progress","process_mach_sequence","lot_no","lot_no");
		$dataprocmachine = array(
						 'process_mach_id' => $idprocmachine,
						 'machine_id' => $_POST['machineid'],
						 'master_type_process_id' => $_POST['mastertypeprocessid'],
						 'master_process_id' => 0,
						 'lot_no' => $_POST['lot_no_process'],
						 'process_mach_qty_good' => $_POST['qty_process'],
						 'process_mach_qty_reject' => 0,
						 'process_mach_qty_repair' => 0,
						 'process_mach_qty_total' => 0,
						 'process_mach_qty_std' => 0, 
						 'operator' => $_POST['operator'],
						 'process_mach_status' => 1,
						 'wo_master_dtl_proc_id' => $_POST['wo-master-dtl-proc-id'],
						 'process_mach_remark' => '',
						 'process_mach_onprogress' => 2,
						 'process_mach_createdate' => $date,
						 'process_mach_sequence' => $idseqmachine,
						 'lot_type' => $jenis,
						  'role_wo_id' => $_POST['rolewoid'],
						  'role_dtl_wo_id' => 0,
						  'user_code' => $_POST['usercode'],
						); 
		
		$execprocmachine= $con->insert("laundry_process_machine_progress", $dataprocmachine);
		//var_dump($execprocmachine);
		//end input process machine
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
								 'master_process_id' => 0,
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
			$execchild= $con->update("laundry_role_child", $datachild,"lot_no = '".$_POST['lot_no_process']."' and role_wo_id = '$_POST[rolewoid]' and role_dtl_wo_id = '$_POST[roledtlwoid]'");
	//end update status role child
//process End
} else if ($_POST['process-status'] == '3') {
	$expdata = explode('_',$_POST['data_process']);
	$role_wo_master_id = $expdata[0];
	$role_wo_id  = $expdata[1];
	$wo_master_dtl_proc_id = $expdata[2];
	$seqlot = $expdata[5];
	$rolechildid = $expdata[6];

	if ($_POST['qtygood'] != ''){
		$sqtygood = $_POST['qtygood'];
	} else {
		$sqtygood = 0;
	}
	if ($_POST['qtyreject'] != ''){
		$sqtyreject = $_POST['qtyreject'];
	} else {
		$sqtyreject = 0;
	}
	if ($_POST['qtyrework'] != ''){
		$sqtyrework = $_POST['qtyrework'];
	} else {
		$sqtyrework = 0;
	}
	if ($_POST['qtystd'] != ''){
		$sqtystd = $_POST['qtystd'];
	} else {
		$sqtystd = 0;
	}

	if ($_POST['type_lot'] == 'A'){
			$jenis = 1;
			
			foreach($con->select("laundry_receive","rec_id as lotid","rec_no = '".$_POST['lot_no_process']."'") as $lotid){}
			$mastertypelotid = "0";
	} else {
			$jenis = 2;
			
			//$execrec = $con->update("laundry_lot_number",$datarec,"lot_no = '".$_POST['lot_no_process']."'");

			foreach($con->select("laundry_lot_number","lot_id as lotid,master_type_lot_id","lot_no = '".$_POST['lot_no_process']."'") as $lotid){}
			$mastertypelotid = $lotid['master_type_lot_id'];
	}

	// khusus max role name seq
		if($maxrole['maxroleseq'] == $_POST['rolewonameseq']){
				$maxrolenameseq = 1;
		} else {
				$maxrolenameseq = 0;
		}
	// end khusus max role name seq

	$idprocess = $con->idurut("laundry_process","process_id");
	$dataprocess = array(
						 'process_id' => $idprocess,
						 'wo_master_dtl_proc_id' => $_POST['wo-master-dtl-proc-id'],
						 'lot_type' => $jenis,
						 'master_process_id' => 0,
						 'process_type' => 4,
						 'wo_no' => $_POST['wo_no_process'],
						 'garment_colors' => $_POST['garment_colors_process'],
						 'lot_no' => $_POST['lot_no_process'],
						 'master_type_process_id' => $_POST['mastertypeprocessid'],
						 'master_type_lot_id' => $mastertypelotid,
						 'role_wo_master_id' => $rolewomasterid,
						 'role_wo_id' => $_POST['rolewoid'],
						 'role_dtl_wo_id' => 0,
						 'process_createdate' => $date,
						 'process_createdby' => $userlogin,
						 'process_status' => 1,
						 'machine_id' => $_POST['machineid'],
						 'process_qty_good' => $sqtygood,
						 'process_qty_reject' => $sqtyreject,
						 'process_qty_repair' => $sqtyrework, 
						 'process_qty_std' => $sqtystd,
						 'process_qty_total' => $_POST['qty_process'],
						 'role_wo_master_rev' => $rolerev['role_wo_master_rev'],
						 'shift_id' => $shift['shift_id'],
						 'user_code'=> $_POST['usercode'],
						 'role_wo_name_seq' => $_POST['rolewonameseq'],
						 'operator' => $_POST['operator'],
						 'max_role_name_seq' => $maxrolenameseq,
						 'change_process' => 0
		);
	//var_dump($dataprocess);
	$execprocess= $con->insert("laundry_process", $dataprocess);

	if ($_POST['usemachine'] == 1) {
		$idprocmachine = $con->idurut("laundry_process_machine_progress","process_mach_id");
		$dataprocmachine = array(
						 'process_mach_id' => $idprocmachine,
						 'machine_id' => $_POST['machineid'],
						 'master_type_process_id' => $_POST['mastertypeprocessid'],
						 'master_process_id' => 0,
						 'lot_no' => $_POST['lot_no_process'],
						 'process_mach_qty_good' => $_POST['qty_process'],
						 'process_mach_qty_reject' => 0,
						 'process_mach_qty_repair' => 0,
						 'process_mach_qty_total' => 0,
						 'process_mach_qty_std' => 0, 
						 'operator' => $_POST['operator'],
						 'process_mach_status' => 1,
						 'wo_master_dtl_proc_id' => $_POST['wo-master-dtl-proc-id'],
						 'process_mach_remark' => '',
						 'process_mach_onprogress' => 3,
						 'process_mach_createdate' => $date,
						 'lot_type' => $jenis,
						  'role_wo_id' => $_POST['rolewoid'],
						  'role_dtl_wo_id' => 0,
						  'user_code' => $_POST['usercode'],
						
						); 
		//var_dump($dataprocmachine);
		$execprocmachine= $con->insert("laundry_process_machine_progress", $dataprocmachine);
	}

	// update data lot_number sAat ini.
		// $rereject = $qtyrej['lot_qty_reject_upd']+$sqtyreject;
		// $rework = $qtyrej['lot_qty_repair_upd']+$sqtyrework;
		// $restd = $qtyrej['lot_qty_std_upd']+$sqtystd;				
		// $dataqtylot = array(
		// 					 'lot_qty_good_upd' => $sqtygood,
		// 					 'lot_qty_reject_upd' => $rereject,
		// 					 'lot_qty_repair_upd' => $rework,
		// 					 'lot_qty_std_upd' => $restd,
		// 					 );
		// $execqtylot = $con->update("laundry_lot_number",$dataqtylot,"lot_no = '$_POST[lot_no_process]'");
	// end update data lot number saat ini

	$qty_1 = $sqtygood;
	$qty_2 = $sqtyreject;
	$qty_3 = $sqtyrework;
	$qty_4 = $sqtystd;

	for ($i=1;$i<=4;$i++){
		
		if ($i == 1){
			$qty = $sqtygood; 
		} else if ($i == 2){
			$qty = $sqtyreject; 
		} else if ($i == 3){
			$qty = $sqtyrework; 
		} else if ($i == 4){
			$qty = $sqtystd; 
		}
	
		if($qty != 0 ) {
			
			// input master data QC
			$idgood = $con->idurut("laundry_wo_master_dtl_qc","wo_master_dtl_qc_id");
			$datagood = array(
								'wo_master_dtl_qc_id' => $idgood,
								'wo_no' => $_POST['wo_no_process'],
							 	'garment_colors' => $_POST['garment_colors_process'],
							 	'ex_fty_date' => $_POST['ex_fty_date_process'],
								'wo_master_dtl_qc_qty' => $qty, 
								'lot_no' => $_POST['lot_no_process'],
								'wo_master_dtl_qc_createdate' => $date,
								'wo_master_dtl_qc_createdby' => $userlogin,
								'wo_master_dtl_qc_status' => 1,
								'wo_master_dtl_proc_id' => $_POST['wo-master-dtl-proc-id'],
								'wo_master_dtl_qc_type' => $i,
								'shift_id' => $shift['shift_id'],
								'user_code' => $_POST['usercode'],
								'operator' => $_POST['operator'],
								'machine_id' => $_POST['machineid'],
								'role_wo_name_seq' => $_POST['rolewonameseq'],
								);
			//var_dump($datagood);	
			$execgood= $con->insert("laundry_wo_master_dtl_qc", $datagood);
			// ==================
			
			if ($i == 1){
				foreach($con->select("(select max(role_wo_name_seq) as maxroleseq from laundry_role_wo where role_wo_master_id = $rolewomasterid and master_type_process_id =3) a","*") as $rwns){}
				if($_POST['rolewonameseq'] == $rwns['maxroleseq']){
					$iddesp = $con->idurut("laundry_wo_master_dtl_despatch","wo_master_dtl_desp_id");
					$datadesp = array(
					'wo_master_dtl_desp_id' => $iddesp,
					'wo_master_dtl_desp_status' => 1,
					'wo_master_dtl_desp_createdate' => $date,
					'wo_master_dtl_desp_qty' => $qty,
					'wo_master_dtl_desp_createdby' => $userlogin,
					'lot_no' => $_POST['lot_no_process'],
					'wo_master_dtl_proc_id' => $_POST['wo-master-dtl-proc-id'],
					'shift_id' => $shift['shift_id'],
					'user_code' => $_POST['usercode'],
					'operator' => $_POST['operator']
					);
					$execdesp= $con->insert("laundry_wo_master_dtl_despatch", $datadesp);

					foreach($con->query("update laundry_lot_number set lot_status = 2 where lot_no = '$_POST[lot_no_process]'") as $endlotnumber){}
				}
			}
		}
	}

	$datainchild = array(
						'role_child_status'  => 1,
						'role_child_modifydate' => $date,
					 	'role_child_modifyby' => $userlogin,
					 	'role_child_dateprocess' => $date,
					);
	//echo $rolechildid;
	$execinchild = $con->update("laundry_role_child",$datainchild,"role_child_id = '".$rolechildid."'");

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
						 'master_type_process_id' => 3,
						 'master_type_lot_id' => $mastertypelotid,
						 'shift_id' => $shift['shift_id'], 
						 'lot_type' => $jenis,
						 'user_code' => $_POST['usercode'],
		);
		//var_dump($dataevent); 
		$execevent= $con->insert("laundry_lot_event", $dataevent);
	//end event lot

//jika QC non final 

	//if($maxrole['maxroleseq'] != $_POST['rolewonameseq']){
	if($sqtyrework > 0 || $sqtyreject > 0 || $sqtystd > 0){	
		foreach ($con->select("laundry_log","log_lot_receive","log_lot_tr = '$_POST[lot_no_process]'") as $logrec){}

		if($sqtyrework > 0) {

		 	//create no urut lot number ===================
				foreach ($con->select("laundry_lot_number a JOIN laundry_master_type_lot b on a.master_type_lot_id=b.master_type_lot_id","a.*,b.master_type_lot_initial","lot_no = '$_POST[lot_no_process]'") as $lotnum){}
					//echo "select a.*,b.master_type_lot_initial from laundry_lot_number a join laundry_master_type_lot b on a.master_type_lot_id=b.master_type_lot_id where lot_no = '$_GET[lot]'";

				$sequencecount = $con->selectcount("laundry_lot_number","lot_id","wo_master_dtl_proc_id = '$lotnum[wo_master_dtl_proc_id]'");
				$sequence = $sequencecount+1;
				
				$selurutcount = $con->select("laundry_lot_number","COALESCE(max(lot_no_uniq),0) as max","wo_no = '$lotnum[wo_no]'");
				foreach($selurutcount as $urutcount){}
				$urut = $urutcount['max']+1;

				$expmt = explode('/',$lotnum['wo_no']);
				$trimexp6 = trim($expmt[6]);

				if($expmt[1] == 'RECUT'){
					$norework ='L'.$expmt[0]."R".$expmt[3].$expmt[4].trim($expmt[5]).'W'.sprintf('%03s', $urut);
				} else {
					$norework ='L'.$expmt[0].$expmt[4].$expmt[5].$trimexp6."W".sprintf('%03s', $urut);
				}
				// end create no urut lot number =============

				$idlot = $con->idurut("laundry_lot_number","lot_id");
				$datalot = array(
								 'lot_id' => $idlot,
								 'lot_no' => $norework, 
								 'wo_no' => $_POST['wo_no_process'],
								 'garment_colors' => $_POST['garment_colors_process'],
								 'wo_master_dtl_proc_id' => $lotnum['wo_master_dtl_proc_id'], 
								 'master_type_lot_id' => 9,
								 'role_wo_master_id' => $rolewomasterid,
								 'lot_qty' => $sqtyrework,
								 'lot_qty_good_upd' => 0,
						 		 'lot_qty_reject_upd' => 0,
								 'lot_qty_repair_upd' => $sqtyrework,
								 'lot_kg' => 0,
								 'lot_shade' => $lotnum['lot_shade'],
								 'lot_createdate' => $date,
								 'lot_createdby' => $userlogin,
								 'lot_status' => 1,
								 'create_type' => 5,
								 'lot_type' => 'W',
								 'role_dtl_reject' => 0,
								 'reject_from_lot' => $_POST['lot_no_process'],
								 'shift_id' => $shift['shift_id'],
								 'user_code'=> $_POST['usercode'],
							 	 'role_wo_name_seq' => $_POST['rolewonameseq'],
							 	 'lot_type_rework' => $lotnum['lot_type'],
							 	 'lot_no_uniq' => $urut
								 
				); 
				//var_dump($datalot);
				$execlot= $con->insert("laundry_lot_number", $datalot);
				//var_dump($execlot);
				$idlotdtl = $con->idurut("laundry_lot_number_dtl","lot_dtl_id");
				$datalotdtl = array(
								 'lot_dtl_id' => $idlotdtl,
								 'lot_id_parent' => $lotnum['lot_id'], 
								 'lot_id' => $idlot,
								 'lot_dtl_createdby' => $userlogin,
								 'lot_dtl_createdate' => $date,
								 'create_type' => 5,
								 'parent_first' => 0,
								 'qty' => $sqtyrework,
								 'kg' => 0
							);
				$execlotdtl = $con->insert("laundry_lot_number_dtl",$datalotdtl);

				$datalog = array(
							'log_lot_tr' => $norework,
							'log_lot_ref' => $_POST['lot_no_process'],
							'log_lot_qty' => $sqtyrework,
							'log_lot_status' => 1,
							'log_lot_event' => 8,
							'wo_no' => $_POST['wo_no_process'],
							'garment_colors' => $_POST['garment_colors_process'],
							'ex_fty_date' => $_POST['ex_fty_date_process'],
							'log_createdate' => $date,
							'log_lot_receive' => $logrec['log_lot_receive'],
							'role_wo_name_seq' => $_POST['rolewonameseq'],
							'lotmaking_type' => 3,
				);
				$execlog = $con->insert("laundry_log", $datalog);
			
			 	//echo $norework."_".base64_encode($norework);
		}

//jika ada reject
		if($sqtyreject > 0) {

		 	//create no urut lot number ===================
				foreach ($con->select("laundry_lot_number a JOIN laundry_master_type_lot b on a.master_type_lot_id=b.master_type_lot_id","a.*,b.master_type_lot_initial","lot_no = '$_POST[lot_no_process]'") as $lotnum){}
					//echo "select a.*,b.master_type_lot_initial from laundry_lot_number a join laundry_master_type_lot b on a.master_type_lot_id=b.master_type_lot_id where lot_no = '$_GET[lot]'";

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
					$noreject ='L'.$expmt[0]."R".$expmt[3].$expmt[4].trim($expmt[5]).'S'.sprintf('%03s', $urut);
				} else {
					$noreject ='L'.$expmt[0].$expmt[4].$expmt[5].$trimexp6."S".sprintf('%03s', $urut);
				}
				// end create no urut lot number =============

				$idlot = $con->idurut("laundry_lot_number","lot_id");
				$datalot = array(
								 'lot_id' => $idlot,
								 'lot_no' => $noreject, 
								 'wo_no' => $_POST['wo_no_process'],
								 'garment_colors' => $_POST['garment_colors_process'],
								 'wo_master_dtl_proc_id' => $lotnum['wo_master_dtl_proc_id'], 
								 'master_type_lot_id' => 8,
								 'role_wo_master_id' => $rolewomasterid,
								 'lot_qty' => $sqtyreject,
								 'lot_qty_good_upd' => 0,
						 		 'lot_qty_reject_upd' => $sqtyreject,
								 'lot_qty_repair_upd' => 0,
								 'lot_kg' => 0,
								 'lot_shade' => $lotnum['lot_shade'],
								 'lot_createdate' => $date,
								 'lot_createdby' => $userlogin,
								 'lot_status' => 0,
								 'create_type' => 7,
								 'lot_type' => 'S',
								 'role_dtl_reject' => 0,
								 'reject_from_lot' => $_POST['lot_no_process'],
								 'shift_id' => $shift['shift_id'],
								 'user_code'=> $_POST['usercode'],
							 	 'role_wo_name_seq' => $_POST['rolewonameseq'],
							 	 'lot_type_rework' => $lotnum['lot_type'],
							 	 'lot_no_uniq' => $urut
								 
				); 
				//var_dump($datalot);
				$execlot= $con->insert("laundry_lot_number", $datalot);
				//var_dump($execlot);
				$idlotdtl = $con->idurut("laundry_lot_number_dtl","lot_dtl_id");
				$datalotdtl = array(
								 'lot_dtl_id' => $idlotdtl,
								 'lot_id_parent' => $lotnum['lot_id'], 
								 'lot_id' => $idlot,
								 'lot_dtl_createdby' => $userlogin,
								 'lot_dtl_createdate' => $date,
								 'create_type' => 4,
								 'parent_first' => 0,
								 'qty' => $sqtyreject,
								 'kg' => 0
							);
				$execlotdtl = $con->insert("laundry_lot_number_dtl",$datalotdtl);

				$datalog = array(
							'log_lot_tr' => $noreject,
							'log_lot_ref' => $_POST['lot_no_process'],
							'log_lot_qty' => $sqtyreject,
							'log_lot_status' => 1,
							'log_lot_event' => 8,
							'wo_no' => $_POST['wo_no_process'],
							'garment_colors' => $_POST['garment_colors_process'],
							'ex_fty_date' => $_POST['ex_fty_date_process'],
							'log_createdate' => $date,
							'log_lot_receive' => $logrec['log_lot_receive'],
							'role_wo_name_seq' => $_POST['rolewonameseq'],
							'lotmaking_type' => 3,
				);
				$execlog = $con->insert("laundry_log", $datalog);
					 	
		}

		if($sqtystd > 0) {

		 	//create no urut lot number ===================
				foreach ($con->select("laundry_lot_number a JOIN laundry_master_type_lot b on a.master_type_lot_id=b.master_type_lot_id","a.*,b.master_type_lot_initial","lot_no = '$_POST[lot_no_process]'") as $lotnum){}
					//echo "select a.*,b.master_type_lot_initial from laundry_lot_number a join laundry_master_type_lot b on a.master_type_lot_id=b.master_type_lot_id where lot_no = '$_GET[lot]'";

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
					$nostd ='L'.$expmt[0]."R".$expmt[3].$expmt[4].trim($expmt[5]).'T'.sprintf('%03s', $urut);
				} else {
					$nostd ='L'.$expmt[0].$expmt[4].$expmt[5].$trimexp6."T".sprintf('%03s', $urut);
				}
				// end create no urut lot number =============

				$idlot = $con->idurut("laundry_lot_number","lot_id");
				$datalot = array(
								 'lot_id' => $idlot,
								 'lot_no' => $nostd, 
								 'wo_no' => $_POST['wo_no_process'],
								 'garment_colors' => $_POST['garment_colors_process'],
								 'wo_master_dtl_proc_id' => $lotnum['wo_master_dtl_proc_id'], 
								 'master_type_lot_id' => 11,
								 'role_wo_master_id' => $rolewomasterid,
								 'lot_qty' => $sqtystd,
								 'lot_qty_good_upd' => 0,
						 		 'lot_qty_reject_upd' => 0,
								 'lot_qty_repair_upd' => 0,
								 'lot_qty_std_upd' => $sqtystd,
								 'lot_kg' => 0,
								 'lot_shade' => $lotnum['lot_shade'],
								 'lot_createdate' => $date,
								 'lot_createdby' => $userlogin,
								 'lot_status' => 1,
								 'create_type' => 9,
								 'lot_type' => 'T',
								 'role_dtl_reject' => 0,
								 'reject_from_lot' => $_POST['lot_no_process'],
								 'shift_id' => $shift['shift_id'],
								 'user_code'=> $_POST['usercode'],
							 	 'role_wo_name_seq' => $_POST['rolewonameseq'],
							 	 'lot_type_rework' => $lotnum['lot_type'],
							 	 'lot_no_uniq' => $urut
								 
				); 
				//var_dump($datalot);
				$execlot= $con->insert("laundry_lot_number", $datalot);
				//var_dump($execlot);
				//die();
				$idlotdtl = $con->idurut("laundry_lot_number_dtl","lot_dtl_id");
				$datalotdtl = array(
								 'lot_dtl_id' => $idlotdtl,
								 'lot_id_parent' => $lotnum['lot_id'], 
								 'lot_id' => $idlot,
								 'lot_dtl_createdby' => $userlogin,
								 'lot_dtl_createdate' => $date,
								 'create_type' => 7,
								 'parent_first' => 0,
								 'qty' => $sqtystd,
								 'kg' => 0
							);
				$execlotdtl = $con->insert("laundry_lot_number_dtl",$datalotdtl);

				//input ke lot std info ======
				$datalotstdinfo = array(
							 'lot_no' => $_POST['lot_no_process'],
							 'role_wo_id' => $_POST['rolewoid'],
						 	 'role_dtl_wo_id' => 0,
							 'master_process_id' => 0,
							 'master_type_process_id' => $_POST['mastertypeprocessid'],
							 'lot_std_info_createdate' => $date,
							 'lot_std_info_status' => 1
						);
				$execlotstdinfo = $con->insert("laundry_lot_std_info",$datalotstdinfo);
				//==============================

				$datalog = array(
							'log_lot_tr' => $nostd,
							'log_lot_ref' => $_POST['lot_no_process'],
							'log_lot_qty' => $sqtystd,
							'log_lot_status' => 1,
							'log_lot_event' => 8,
							'wo_no' => $_POST['wo_no_process'],
							'garment_colors' => $_POST['garment_colors_process'],
							'ex_fty_date' => $_POST['ex_fty_date_process'],
							'log_createdate' => $date,
							'log_lot_receive' => $logrec['log_lot_receive'],
							'role_wo_name_seq' => $_POST['rolewonameseq'],
							'lotmaking_type' => 3,
				);
					   	 //var_dump($datalog);
				$execlog = $con->insert("laundry_log", $datalog);
				//var_dump($execlog);
				
				//insert role child master (fungsi utk master role per lot)
			foreach ($con->select("laundry_role_child_master","*","lot_no = '$_POST[lot_no_process]'") as $cekrcm){}
			$datachildmaster = array(
						'lot_no' => $nostd,
						'role_wo_master_id'  => $cekrcm['role_wo_master_id'],
						'lot_status' => 1,
						'lot_type' => $cekrcm['lot_type'],
						'lot_id' => $idlot,
						'role_wo_name_seq' => $cekrcm['role_wo_name_seq']
					);
			
			$execchildmaster = $con->insert("laundry_role_child_master", $datachildmaster);
			//=========================================================	

			//input data role child
			$selrolechild = $con->select("laundry_role_child","*","lot_no = '".$_POST['lot_no_process']."' and role_child_status = 0 and role_child_id !=  (select role_child_id from laundry_role_child where lot_no = '".$_POST['lot_no_process']."' and role_wo_id = '$_POST[rolewoid]' and COALESCE(role_dtl_wo_id,0) = 0) ");
			
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
							'lot_no'  => $nostd,
							'lot_id'  => $idlot,
							'role_wo_seq' => $child['role_wo_seq'],
							'role_dtl_wo_seq' => $child['role_dtl_wo_seq'],
				);
				
				$execchild = $con->insert("laundry_role_child", $datachild);
			}
		}

		$datalog = array(
					'log_lot_tr' => $_POST['lot_no_process'],
					'log_lot_ref' => $_POST['lot_no_process'],
					'log_lot_qty' => $sqtygood,
					'log_lot_status' => 1,
					'log_lot_event' => 8,
					'wo_no' => $_POST['wo_no_process'],
					'garment_colors' => $_POST['garment_colors_process'],
					'ex_fty_date' => $_POST['ex_fty_date_process'],
					'log_createdate' => $date,
					'log_lot_receive' => $logrec['log_lot_receive'],
					'role_wo_name_seq' => $_POST['rolewonameseq'],
					'lotmaking_type' => 3,
		);
		$execlog = $con->insert("laundry_log", $datalog);

		echo  $_POST['lot_no_process']."_".base64_encode($_POST['lot_no_process'])."|".$norework."_".base64_encode($norework)."|".$noreject."_".base64_encode($noreject)."|".$nostd."_".base64_encode($nostd);
	} else {
		echo  $_POST['lot_no_process']."_".base64_encode($_POST['lot_no_process'])."|_|_|_";
	}
}
?>
