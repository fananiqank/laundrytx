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
						 'wo_no' => $_POST['cmt_no'],
						 'garment_colors' => $_POST['color_no'],
						 'wo_master_dtl_proc_id' => $sequence, 
						 'master_type_lot_id' => 10,
						 'role_wo_master_id' => $rolemaster,
						 'lot_qty' => $_POST['totalpcs'],
						 'lot_kg' => $_POST['totalkg'],
						 'lot_shade' => $_POST['shade'],
						 'lot_createdate' => $date,
						 'lot_createdby' => $_SESSION['ID_LOGIN'],
						 'lot_status' => 1,
						 'create_type' => 6,
						 'lot_type' => 'M',
						 'shift_id' => $shift['shift_id'],
						 
		); 
		//var_dump($datalot);
		$execlot= $con->insert("laundry_lot_number", $datalot);

	//input data role per lot Rework(M) ke laundry role child
		$selrolechild = $con->select("laundry_role_wo a left join 
									  laundry_role_dtl_wo b on a.role_wo_id=b.role_wo_id",
									 "a.role_wo_id,
									  role_wo_name,
									  master_type_process_id,
									  role_wo_seq,
									  role_wo_time,
									  role_dtl_wo_id,
									  master_process_id,
									  role_dtl_wo_seq,
									  role_dtl_wo_time",
									 "role_wo_master_id = '$rolemaster'",
									 "role_wo_seq");
									// and role_wo_seq <= (select role_wo_seq from laundry_role_wo where role_wo_master_id = '$rolemaster' and master_type_process_id = 2)
		
		foreach ($selrolechild as $child) {
			$idchild = $con->idurut("laundry_role_child","role_child_id");
			$datachild = array(
						'role_child_id' => $idchild,
						'role_wo_master_id'  => $rolemaster,
						'role_wo_id'  => $child['role_wo_id'],
						'role_dtl_wo_id'  => $child['role_dtl_wo_id'],
						'lot_type'  => 1,
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

		$qtyprocrec = $_POST['lotqty'][$key] + $cekjumlahproc['wo_master_dtl_proc_qty_rec']; 

		$data2 = array( 
			         //'wo_master_dtl_proc_id' => $sequence,
			         'lot_status' => 0,
					 // 'role_wo_master_id' => $rolemaster,
					 'lot_modifydate' => $date,
					 'lot_modifyby' => $_SESSION['ID_LOGIN'],
		); 
		//$exec2= $con->update("laundry_lot_number", $data2,"lot_id = '$value'");
		
		//update qty receive wo master dtl proc
		$data3 = array( 
			         'wo_master_dtl_proc_qty_rec' => $qtyprocrec,
					 'wo_master_dtl_proc_modifydate' => $date,
					 'wo_master_dtl_proc_modifyby' => $_SESSION['ID_LOGIN'],
		); 
		//$exec3= $con->update("laundry_wo_master_dtl_proc", $data3,"wo_master_dtl_proc_id = '$sequence'");
		
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
			); 
		//	$execevent= $con->insert("laundry_lot_event", $dataevent);
		//end event lot

		if ($exec2 == 1) {
				echo "1";
		} else {
			echo "2";
		}


	}

	
}

?>
