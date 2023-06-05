<?php 

session_start();
include "../../../funlibs.php";
$con = new Database;
//error_reporting(0);
$date = date("Y-m-d H:i:s");
$date2 = date("Y-m-d");
$tabel = "laundry_role_grup";

//shift 
foreach($con->select("laundry_master_shift","shift_id,shift_time_start,shift_time_end,shift_status","TO_CHAR(now(), 'HH24:MI') between TO_CHAR(shift_time_start, 'HH24:MI') and TO_CHAR(shift_time_end, 'HH24:MI') and shift_status = 1") as $shift){}
// end shift
	
if ($_POST['ceklot'] == '1'){ 
	$expseqpro = explode('_',$_POST['seq_pro']);
	$sequence = $expseqpro[1];
	$rolemaster = $expseqpro[0];
	foreach($con->select("laundry_wo_master_dtl_proc","COALESCE(0,wo_master_dtl_proc_qty_rec)","wo_master_dtl_proc_id = '$sequence'") as $cekjumlahproc){}

	//input lot number
		$idlot = $con->idurut("laundry_lot_number","lot_id");
		$datalot = array(
						 'lot_id' => $idlot,
						 'lot_no' => $_POST['lot_number'], 
						 'wo_no' => $_POST['wo_no_show'],
						 'garment_colors' => $_POST['color_no_show'],
						 'wo_master_dtl_proc_id' => $sequence, 
						 'master_type_lot_id' => 10,
						 'role_wo_master_id' => $rolemaster,
						 'lot_qty' => $_POST['totalpcs'],
						 'lot_kg' => $_POST['totalkg'],
						 'lot_shade' => $_POST['shade'],
						 'lot_createdate' => $date,
						 'lot_createdby' => $_SESSION['ID_LOGIN'],
						 'lot_status' => 1,
						 'create_type' => 8,
						 'lot_type' => 'M',
						 'shift_id' => $shift['shift_id'],
						 'user_code' => $_POST['usercode'],
						 'role_wo_name_seq' => 0,
						 
		); 
		//var_dump($datalot);
		$execlot= $con->insert("laundry_lot_number", $datalot);

	//insert role child master (fungsi utk master role per lot)
		$datachildmaster = array(
					'lot_no' => $_POST['lot_number'],
					'role_wo_master_id'  => $rolemaster,
					'lot_status' => 1,
					'lot_type' => 4,
					'lot_id' => $idlot
				);
		
		$execchildmaster = $con->insert("laundry_role_child_master", $datachildmaster);
	//=========================================================	
		
	//input data role per lot Rework(M) ke laundry role child
		$selrolechild = $con->select("laundry_role_wo a left join 
									  laundry_role_dtl_wo b on a.role_wo_id=b.role_wo_id and b.role_dtl_wo_status = 1",
									 "a.role_wo_id,
									  role_wo_name,
									  master_type_process_id,
									  role_wo_seq,
									  role_wo_time,
									  role_dtl_wo_id,
									  master_process_id,
									  role_dtl_wo_seq,
									  role_dtl_wo_time",
									 "role_wo_master_id = '$rolemaster' and a.role_wo_status != 2",
									 "role_wo_seq");
									// and role_wo_seq <= (select role_wo_seq from laundry_role_wo where role_wo_master_id = '$rolemaster' and master_type_process_id = 2)
		
		foreach ($selrolechild as $child) {
			$idchild = $con->idurut("laundry_role_child","role_child_id");
			$datachild = array(
						'role_child_id' => $idchild,
						'role_wo_master_id'  => $rolemaster,
						'role_wo_id'  => $child['role_wo_id'],
						'role_dtl_wo_id'  => $child['role_dtl_wo_id'],
						'lot_type'  => 4,
						'role_child_status'  => 0,
						'role_child_createdate'  => $date,
						'role_child_createdby'  => $_SESSION['ID_LOGIN'],
						'lot_no'  => $_POST['lot_number'],
						'lot_id'  => $idlot,
						'role_wo_seq' => $child['role_wo_seq'],
						'role_dtl_wo_seq' => $child['role_dtl_wo_seq'],
			);
			//var_dump($datachild); 
			$execchild = $con->insert("laundry_role_child", $datachild);
			//var_dump($execchild);
		}
		// end input data role per lot receive ke laundry role child

	foreach ($_POST['lotid'] as $key => $value){

		$data2 = array( 
			         //'wo_master_dtl_proc_id' => $sequence,
			         'lot_status' => 0,
					 // 'role_wo_master_id' => $rolemaster,
					 'lot_modifydate' => $date,
					 'lot_modifyby' => $_SESSION['ID_LOGIN'],
		); 
		//var_dump($data2);
		$exec2= $con->update("laundry_lot_number", $data2,"lot_id = '$value'");
		
		// input data ke log lot
		$datalog = array(
			'log_lot_tr' => $_POST['lot_number'],
			'log_lot_ref' => $_POST['lotno'][$key],
			'log_lot_qty' => $_POST['lotqty'][$key],
			'log_lot_status' => 1,
			'log_lot_event' => 6,
			'wo_no' => $_POST['wo_no_show'],
			'garment_colors' => $_POST['color_no_show'],
			'ex_fty_date' =>$_POST['ex_fty_date_asli'],
			'log_lot_receive' => 0,
			'log_createdate' => $date,
			'role_wo_name_seq' => 0,
			'lotmaking_type' => 2
		);
		$execlog = $con->insert("laundry_log", $datalog);

		//input event Lot
			$idevent = $con->idurut("laundry_lot_event","event_id");
			$dataevent = array(
							 'event_id' => $idevent,
							 'lot_id' => $value,
							 'lot_no' => $_POST['lotno'][$key],
							 'event_type' => 4,
							 'event_status' => 1,
							 'event_createdby' => $_SESSION['ID_LOGIN'],
							 'event_createdate' => $date,
							 'master_type_process_id' => 0,
							 'master_type_lot_id' => 9,
							 'shift_id' => $shift['shift_id'], 
							 'lot_type' => 3,
							 'user_code' => $_POST['usercode'],
			); 
			$execevent= $con->insert("laundry_lot_event", $dataevent);
		//end event lot

	}
		$qtyprocrec = $_POST['totalpcs'] + $cekjumlahproc['wo_master_dtl_proc_qty_rec'];
		//echo $qtyprocrec;
	//update qty receive wo master dtl proc
		$data3 = array( 
			         'wo_master_dtl_proc_qty_rec' => $qtyprocrec,
					 'wo_master_dtl_proc_modifydate' => $date,
					 'wo_master_dtl_proc_modifyby' => $_SESSION['ID_LOGIN'],
		); 
		//var_dump($data3);
		$exec3= $con->update("laundry_wo_master_dtl_proc", $data3,"wo_master_dtl_proc_id = '$sequence'");

	echo $_POST['lot_number'].'_'.base64_encode($_POST['lot_number']);
	
}

?>
