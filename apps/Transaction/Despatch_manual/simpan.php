	<?php 
	session_start();
	include "../../../funlibs.php";
	$con = new Database;
	//error_reporting(0);
	$date = date("Y-m-d H:i:s");
	$date2 = date("Y-m-d_G-i-s");
	$datetok = date('dmy');

//shift 
foreach($con->select("laundry_master_shift","shift_id,shift_time_start,shift_time_end,shift_status","TO_CHAR(now(), 'HH24:MI') between TO_CHAR(shift_time_start, 'HH24:MI') and TO_CHAR(shift_time_end, 'HH24:MI') and shift_status = 1") as $shift){}
//end shift

//select master_type_lot_id
	foreach($con->select("laundry_master_type_lot","master_type_lot_id","master_type_lot_initial = '$_POST[typelot]'") as $mtlot){}
//end select

//select_lot_id
	foreach($con->select("laundry_lot_number","lot_id","lot_no = '$_POST[lot_no]'") as $lotid){}
// end select

	if ($_POST['conf'] == '9'){
		$iddesp = $con->idurut("laundry_wo_master_dtl_despatch","wo_master_dtl_desp_id");
		$datadesp = array(
					 'wo_master_dtl_desp_id' => $iddesp,
					 'wo_no' => $_POST['wono'],
					 'ex_fty_date' => $_POST['ex_fty_date'],
					 'garment_colors' => $_POST['colors'],
					 'garment_inseams' => $_POST['inseams'],
					 'garment_sizes' => $_POST['sizes'],
					 'wo_master_dtl_desp_qty' => $_POST['qty_det'],
					 'wo_master_dtl_desp_status' => 9,
					 'wo_master_dtl_desp_createdate' => $date,
					 'wo_master_dtl_desp_createdby' => $_SESSION['ID_LOGIN'],
					 'lot_no' => $_POST['lotno'],
					 'wo_master_dtl_proc_id' => $_POST['procid'],
					 'shift_id' => $shift['shift_id'],
					 'user_code' => $_POST['username'],
		); 
		//var_dump($datadesp);
		$execdesp= $con->insert("laundry_wo_master_dtl_despatch", $datadesp);
		//var_dump($execdesp);
		echo "<script>window.location='content.php?p=$_POST[getp]'</script>";
	
	} 
	//hapus data cart
	else if ($_POST['hpsmodcart'] == 'hpscart'){

		$where = array( 'wo_master_dtl_desp_id' => $_GET['id']);
		$con->delete("laundry_wo_master_dtl_despatch", $where);
	}
	//simpan cart
	else if ($_POST['confmodcart'] == 'simpan'){

		$datadesp = array(
					 'wo_master_dtl_desp_status' => 1,
		); 
		
		$execdesp= $con->update("laundry_wo_master_dtl_despatch", $datadesp, "wo_master_dtl_desp_status = '9' and wo_master_dtl_desp_createdby = '$_GET[idlog]'");

		//update done lot number
			$datadone = array(
				'lot_status' => 2,
			);
						
			$execdone= $con->update("laundry_lot_number", $datadone,"lot_id = '$lotid[lot_id]'");
		//end update done lot numnber

		foreach($con->select("laundry_process","*","lot_no = '$_POST[lot_no]' and master_type_process_id = '3' and process_type = '4'") as $seprocess);
		//masuk ke laundry process
			$idprocess = $con->idurut("laundry_process","process_id");
			$dataprocess = array(
								 'process_id' => $idprocess,
								 'wo_master_dtl_proc_id' => $seprocess['wo_master_dtl_proc_id'],
								 'lot_type' => 2,
								 'master_process_id' => 0,
								 'process_type' => 4,
								 'wo_no' => $_POST['wono'],
								 'garment_colors' => $_POST['colors'],
								 'lot_no' => $_POST['lot_no'],
								 'master_type_process_id' => 6,
								 'master_type_lot_id' => $mtlot['master_type_lot_id'],
								 'role_wo_master_id' => $seprocess['role_wo_master_id'],
								 'role_wo_id' => $seprocess['role_wo_id'],
								 'role_dtl_wo_id' => 0,
								 'process_createdate' => $date,
								 'process_createdby' => $_SESSION['ID_LOGIN'],
								 'process_status' => 1,
								 'machine_id' => 0,
								 'process_qty_good' => $_POST['totalall'],
								 'process_qty_reject' => 0,
								 'process_qty_repair' => 0,
								 'process_qty_total' => $_POST['totalall'],
								 'role_wo_master_rev' => $seprocess['role_wo_master_rev'],
								 'shift_id' => $shift['shift_id'],
								 
			); 
			//var_dump($dataprocess);
			$execprocess= $con->insert("laundry_process", $dataprocess);

			//input event Lot
				$idevent = $con->idurut("laundry_lot_event","event_id");
				$dataevent = array(
								 'event_id' => $idevent,
								 'lot_id' => $lotid['lot_id'],
								 'lot_no' => $_POST['lot_no'],
								 'event_type' => 1,
								 'event_status' => 1,
								 'event_createdby' => $_SESSION['ID_LOGIN'],
								 'event_createdate' => $date,
								 'master_type_process_id' => 6,
								 'master_type_lot_id' => $mtlot['master_type_lot_id'],
								 'shift_id' => $shift['shift_id'], 
								 'lot_type' => 2,
				); 
				$execevent= $con->insert("laundry_lot_event", $dataevent);
			//end event lot		
	}
	
	?>
