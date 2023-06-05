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

//input keranjang
if ($_POST['action'] == "input") {
	//mendapatkan Lot ID
	foreach ($con->select("laundry_lot_number","lot_id","lot_no = '".$_POST['lot_no']."' and lot_id NOT IN (select lot_id from laundry_lot_number_keranjang)") as $lotid){}
	if ($lotid['lot_id'] != ''){
		//input keranjang
		$idkeranjang = $con->idurut("laundry_lot_number_keranjang","lot_keranjang_id");
		$datakeranjang = array(
						 'lot_keranjang_id' => $idkeranjang,
						 'lot_id' => $lotid['lot_id'], 
						 'lot_keranjang_createdby' => $_SESSION['ID_LOGIN'],
						 'lot_keranjang_createdate' => $date,
						 'lot_keranjang_status' => 1,
		); 
		$execkeranjang= $con->insert("laundry_lot_number_keranjang", $datakeranjang);
	}
} 
//delete keranjang
else if ($_POST['action'] == "delete")  {
	$where = array('lot_id' => $_GET['id'], );
	$con->delete("laundry_lot_number_keranjang",$where);
}
//save combine
else if ($_POST['action'] == "savecombine")  {
	$expdata = explode('_', $_POST['datasave']);

	$idlot = $con->idurut("laundry_lot_number","lot_id");
	$datalot = array(
					 'lot_id' => $idlot,
					 'lot_no' => $_POST['nocombine'], 
					 'wo_master_dtl_proc_id' => $expdata[0], 
					 'master_type_lot_id' => $expdata[1],
					 'role_wo_master_id' => $expdata[2],
					 'wo_no' => $expdata[3],
					 'garment_colors' => $expdata[4],
					 'lot_qty' => $_POST['totalcombine'],
					 'lot_kg' => $_POST['totalkg'],
					 'lot_shade' => 'CO',
					 'lot_status' => 1,
					 'lot_createdby' => $_SESSION['ID_LOGIN'],
					 'lot_createdate' => $date,
					 'create_type' => 3,
					 'lot_type' => 'C',
					 'lot_parent' => 1,
					 'shift_id' => $shift['shift_id'],
					 'user_code' => $_POST['usercode'],
					 'role_wo_name_seq' => $_POST['rolewonameseq'],
					 'lot_no_uniq' => $_POST['uniqseq']
	); 
	$execlot= $con->insert($tabel, $datalot);
	
	//input New role child
	$selrolechild = $con->select("laundry_role_child","*","lot_no = '".$_POST['lotawalno']."' and role_child_status = 0");
	foreach ($selrolechild as $forcmaster) {}
	//insert role child master (fungsi utk master role per lot)
	$datachildmaster = array(
				'lot_no' => $_POST['nocombine'],
				'role_wo_master_id'  => $expdata[2],
				'lot_status' => 1, 
				'lot_type' => $forcmaster['lot_type'],
				'lot_id' => $idlot,
				'role_wo_name_seq' => $_POST['rolewonameseq'],
			);
	
	$execchildmaster = $con->insert("laundry_role_child_master", $datachildmaster);
	//=========================================================	

	foreach ($selrolechild as $rolechild) {

			$idchild = $con->idurut("laundry_role_child","role_child_id");
			$datachild = array(
						'role_child_id' => $idchild,
						'role_wo_master_id'  => $rolechild['role_wo_master_id'],
						'role_wo_id'  => $rolechild['role_wo_id'],
						'role_dtl_wo_id'  => $rolechild['role_dtl_wo_id'],
						'lot_type'  => $rolechild['lot_type'],
						'role_child_status'  => 0,
						'role_child_createdate'  => $date,
						'role_child_createdby'  => $_SESSION['ID_LOGIN'],
						'lot_no'  => $_POST['nocombine'],
						'lot_id'  => $idlot,
						'role_wo_seq' => $rolechild['role_wo_seq'],
						'role_dtl_wo_seq' => $rolechild['role_dtl_wo_seq'],
			);
			
			$execchild = $con->insert("laundry_role_child", $datachild);
	}
	//end input New role child

	foreach ($_POST['lot_num'] as $key => $value) {
	//cek parent first, sudah ada apa belum.
		$selparentfirst = $con->select("laundry_lot_number_dtl","parent_first","lot");

	//input Lot history ke Lot Number Detail
		$idlotdtl = $con->idurut("laundry_lot_number_dtl","lot_dtl_id");
		$datalotdtl = array(
						 'lot_dtl_id' => $idlotdtl,
						 'lot_id_parent' => $idlot, 
						 'lot_id' => $value, 
						 'lot_dtl_createdby' => $_SESSION['ID_LOGIN'],
						 'lot_dtl_createdate' => $date,
						 'create_type' => 3,
						 'qty' => $_POST['qtylot'][$key],
						 'kg' => $_POST['kg'][$key],
		); 
		$execlotdtl= $con->insert("laundry_lot_number_dtl", $datalotdtl);	
	
	//update status lot number sebelumnya
		if ($_POST['balance'][$key] != 0){
			$datastatus= array(				
						'lot_modifyby' => $_SESSION['ID_LOGIN'],
						'lot_modifydate' => $date,
						'lot_qty_good_upd' => $_POST['balance'][$key],
						'combine_hold' => 1,
						'lot_kg' => 0,
			);
		} else {
			$datastatus= array(				
						'lot_modifyby' => $_SESSION['ID_LOGIN'],
						'lot_modifydate' => $date,
						'lot_status' => 0,
						'combine_hold' => 0,
			);
		}
		$execstatus = $con->update($tabel,$datastatus,"lot_id = '$value'");		

		$datarwnd = array(
					 'lot_status' => 0,
				);
		$execrwnd = $con->update("laundry_role_child_master",$datarwnd,"lot_no = '$value'");
					
		$lotnoprocess = $_POST['lot_no_process'][$key];

		foreach ($con->select("laundry_log","log_lot_receive,role_wo_name_seq,ex_fty_date,lotmaking_type","log_lot_tr = '$lotnoprocess'") as $logrec){}
		$datalog = array(
				'log_lot_tr' => $_POST['nocombine'],
				'log_lot_ref' => $_POST['lot_no_process'][$key],
				'log_lot_qty' => $_POST['qtylot'][$key],
				'log_lot_status' => 1,
				'log_lot_event' => 4,
				'wo_no' => $expdata[3],
				'garment_colors' => $expdata[4],
				'ex_fty_date' => $logrec['ex_fty_date'],
				'log_createdate' => $date,
				'log_lot_receive' => $logrec['log_lot_receive'],
				'role_wo_name_seq' => $logrec['role_wo_name_seq'],
				'lotmaking_type' => $logrec['lotmaking_type'],
		);
		//var_dump($datalog);
		$execlog = $con->insert("laundry_log", $datalog);
	//	var_dump($execlog);
	//input event Lot
		$idevent = $con->idurut("laundry_lot_event","event_id");
		$dataevent = array(
						 'event_id' => $idevent,
						 'lot_id' => $value,
						 'lot_no' => $_POST['lot_no_process'][$key],
						 'event_type' => 3,
						 'event_status' => 1,
						 'event_createdby' => $_SESSION['ID_LOGIN'],
						 'event_createdate' => $date,
						 'master_type_process_id' => $_POST['master_type_lot_id'],
						 'master_type_lot_id' => $expdata[1],
						 'master_process_id' => $_POST['master_process_id'],
						 'shift_id' => $shift['shift_id'],
						 'lot_type' => 2,
						 'user_code' => $_POST['usercode'],
		); 
		$execevent= $con->insert("laundry_lot_event", $dataevent);

	//delete keranjang
		$where = array('lot_id' => $value );
		$con->delete("laundry_lot_number_keranjang",$where);
	}


} else if ($_POST['action_mod'] == "editcombinehold"){
	
	if ($_POST['setatus'] == ''){
		$status = 0;
	} else {
		$status = $_POST['setatus'];
	}

	$dataedit = array(
				'last_lot_from_combine' => 1, 
				'combine_hold' => $status,				
	);
	$execedit = $con->update("laundry_lot_number",$dataedit,"lot_id = '".$_POST[lot_id]."'");
	
}
?>
