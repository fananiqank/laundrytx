<?php 
session_start();
include "../../../funlibs.php";
$con = new Database;
//error_reporting(0);
$date = date("Y-m-d H:i:s");
$date2 = date("Y-m-d_G-i-s");
$tabel = "laundry_lot_number";

//shift 
foreach($con->select("laundry_master_shift","shift_id,shift_time_start,shift_time_end,shift_status","TO_CHAR(now(), 'HH24:MI') between TO_CHAR(shift_time_start, 'HH24:MI') and TO_CHAR(shift_time_end, 'HH24:MI') and shift_status = 1") as $shift){}
//end shift
foreach ($con->select("laundry_log","log_lot_receive,role_wo_name_seq,ex_fty_date,lotmaking_type","log_lot_tr = '$_POST[lot_no]'") as $logrec){}

if ($_POST['parlot'] == 0){
	$parentfirst = $_POST['lotid'];
} else {
	$parentfirst = $_POST['parent1'];
}

foreach ($_POST['lot_num'] as $key => $value) {
	$expdata = explode('_', $_POST['datasave']);

	//input New Lot
	$idlot = $con->idurut("laundry_lot_number","lot_id");
	$datalot = array(
					 'lot_id' => $idlot,
					 'lot_no' => $value, 
					 'wo_master_dtl_proc_id' => $expdata[0], 
					 'master_type_lot_id' => 7,
					 'role_wo_master_id' => $expdata[2],
					 'wo_no' => $expdata[3],
					 'garment_colors' => $expdata[4],
					 'lot_qty' => $_POST['qtysplit'][$key],
					 'lot_kg' => $_POST['kg'][$key],
					 'lot_shade' => $_POST['shade'][$key],
					 'lot_status' => 1,
					 'lot_createdby' => $_SESSION['ID_LOGIN'],
					 'lot_createdate' => $date,
					 'create_type' => 2,
					 'lot_type' => 'B',
					 'shift_id' => $shift['shift_id'],
					 'user_code' => $_POST['usercode'],
					 'role_wo_name_seq' => $logrec['role_wo_name_seq'],
					 'lot_no_uniq' => $_POST['uniqseq'][$key]
	); 
	$execlot= $con->insert($tabel, $datalot);

	//input Lot history ke Lot Number Detail
	$idlotdtl = $con->idurut("laundry_lot_number_dtl","lot_dtl_id");
	$datalotdtl = array(
					 'lot_dtl_id' => $idlotdtl,
					 'lot_id_parent' => $_POST['lotid'], 
					 'lot_id' => $idlot, 
					 'lot_dtl_createdby' => $_SESSION['ID_LOGIN'],
					 'lot_dtl_createdate' => $date,
					 'create_type' => 2,
					 'parent_first' => $parentfirst,
					 'qty' => $_POST['qtysplit'][$key],
					 'kg' => $_POST['kg'][$key]
	); 
	$execlotdtl= $con->insert("laundry_lot_number_dtl", $datalotdtl);

	//input New role child
	//echo "dada";
	$selrolechild = $con->select("laundry_role_child","*","lot_no = '".$value."' and role_child_status = 0");
	foreach ($selrolechild as $forcmaster) {}

	//insert role child master (fungsi utk master role per lot)
	$datachildmaster = array(
				'lot_no' => $value,
				'role_wo_master_id'  => $expdata[2],
				'lot_status' => 1,
				'lot_type' => $forcmaster['lot_type'],
				'lot_id' => $idlot,
				'role_wo_name_seq' => $logrec['role_wo_name_seq'],
			);
	
	$execchildmaster = $con->insert("laundry_role_child_master", $datachildmaster);
	//=========================================================	

	foreach ($selrolechild as $rolechild) {

			$idchild = $con->idurut("laundry_role_child","role_child_id");
			$datachild = array(
						'role_child_id' => $idchild,
						'role_wo_master_id'  => $rolechild['role_wo_master_id'],
						'role_wo_id'  =>$rolechild['role_wo_id'],
						'role_dtl_wo_id'  => $rolechild['role_dtl_wo_id'],
						'lot_type'  => $rolechild['lot_type'],
						'role_child_status'  => 0,
						'role_child_createdate'  => $date,
						'role_child_createdby'  => $_SESSION['ID_LOGIN'],
						'lot_no'  => $value,
						'lot_id'  => $idlot,
						'role_wo_seq' => $rolechild['role_wo_seq'],
						'role_dtl_wo_seq' => $rolechild['role_dtl_wo_seq'],
			);
			//var_dump($datachild);
			$execchild = $con->insert("laundry_role_child", $datachild);
	}
	
	$datalog = array(
				'log_lot_tr' => $value,
				'log_lot_ref' => $_POST['lot_no'],
				'log_lot_qty' => $_POST['qtysplit'][$key],
				'log_lot_status' => 1,
				'log_lot_event' => 3,
				'wo_no' => $expdata[3],
				'garment_colors' => $expdata[4],
				'ex_fty_date' => $logrec['ex_fty_date'],
				'log_createdate' => $date,
				'log_lot_receive' => $logrec['log_lot_receive'],
				'role_wo_name_seq' => $logrec['role_wo_name_seq'],
				'lotmaking_type' => $logrec['lotmaking_type'],
	);
	$execlog = $con->insert("laundry_log", $datalog);

	echo base64_encode($value)."|";
} 

//update parent lot number sebelumnya
	$dataparent= array(
					'lot_parent' => 1,				
					'lot_modifyby' => $_SESSION['ID_LOGIN'],
					'lot_modifydate' => $date,
					'lot_status' => 0,
	);
	$execparent = $con->update($tabel,$dataparent,"lot_id = '$_POST[lotid]'");					

	$datarwnd = array(
					 'lot_status' => 0,
				);
	$execrwnd = $con->update("laundry_role_child_master",$datarwnd,"lot_no = '".$_POST['lot_no_process']."'");
	
//input event Lot
	$idevent = $con->idurut("laundry_lot_event","event_id");
	$dataevent = array(
					 'event_id' => $idevent,
					 'lot_id' => $_POST['lotid'],
					 'lot_no' => $_POST['lot_no_process'],
					 'event_type' => 2,
					 'event_status' => 1,
					 'event_createdby' => $_SESSION['ID_LOGIN'],
					 'event_createdate' => $date,
					 'master_type_lot_id' => $expdata[1],
					 'master_type_process_id' => 5,
					 'shift_id' => $shift['shift_id'],
					 'lot_type' => 2,
					 'master_process_id' => $_POST['master_process_id'],
					 'user_code' => $_POST['usercode']
	); 
	$execevent= $con->insert("laundry_lot_event", $dataevent);

	echo "TN".$_POST['totalno'];
?>
