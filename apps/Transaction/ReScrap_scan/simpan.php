<?php
	session_start();
	include "../../../funlibs.php";
	$con = new Database;
	//error_reporting(0);
	$date = date("Y-m-d H:i:s");
	$date2 = date("Y-m-d_G-i-s");
	$datetok = date('dmy');

	//shift 
	foreach ($con->select("laundry_master_shift", "shift_id,shift_time_start,shift_time_end,shift_status", "TO_CHAR(now(), 'HH24:MI') between TO_CHAR(shift_time_start, 'HH24:MI') and TO_CHAR(shift_time_end, 'HH24:MI') and shift_status = 1") as $shift) {
	}
	//end shift
	
//input scan Receive confirm
	if ($_POST['confirm'] == '1') {
		$expwono = explode('_', $_POST['datawono']);
		$wo_no = $expwono[0];
		$colors = $expwono[1];
		$exdate = $expwono[2];

		$selwodtlproc = $con->select("laundry_wo_master_dtl_proc", "wo_master_dtl_proc_id,role_wo_master_id", "wo_no = '" . $wo_no . "' and garment_colors = '".$colors."' and DATE(ex_fty_date) = '" . $exdate . "' and wo_master_dtl_proc_status = '".$_POST['tyseq']."'","wo_master_dtl_proc_id DESC");
		foreach ($selwodtlproc as $wodtlproc) {}

		if ($_POST['scst']== 2) {
			$type_lot = 8;
			$initial = 'S';
			$createtype = 7;
			$lotstatus = 2;
			$lotstatusevent = 0;
		} else if ($_POST['scst']== 3) {
			$type_lot = 9;
			$initial = 'W';
			$createtype = 5;
			$lotstatus = 1;
			$lotstatusevent = 1;
		}

		//jika rescrap untuk rework
		if($_POST['tyseq'] == 2){
			$intyseq = 1;
		} else {
			$intyseq = 0;
		}
		// input data ke database receive
		$idlot = $con->idurut("laundry_lot_number", "lot_id");
		$datarec = array(
					 'lot_id' => $idlot,
					 'lot_no' => $_POST['nolot'], 
					 'wo_no' => $wo_no,
					 'garment_colors' => $colors,
					 'wo_master_dtl_proc_id' => $wodtlproc['wo_master_dtl_proc_id'], 
					 'master_type_lot_id' => $type_lot,
					 'role_wo_master_id' => $wodtlproc['role_wo_master_id'],
					 'lot_qty' => $_POST['rwknow'],
					 // 'lot_kg' => $_POST['kg'],
					 //'lot_shade' => $_POST['shade'],
					 'lot_createdate' => $date,
					 'lot_createdby' => $_SESSION['ID_LOGIN'],
					 'lot_status' => $lotstatus,
					 'create_type' => $createtype,
					 'lot_type' => $initial,
					 'shift_id' => $shift['shift_id'],
					 'user_code' => $shift['usercode'],
					 'role_wo_name_seq' => 0
		);
		//var_dump($datarec);
		$execrec = $con->insert("laundry_lot_number", $datarec);
		//var_dump($execrec);

		//input event Lot
		$idevent = $con->idurut("laundry_lot_event", "event_id");
		$dataevent = array(
			'event_id' => $idevent,
			'lot_id' => $idlot,
			'lot_no' => $_POST['nolot'],
			'event_type' => 1,
			'event_status' => $lotstatusevent,
			'event_createdby' => $_SESSION['ID_LOGIN'],
			'event_createdate' => $date,
			'master_type_process_id' => 3,
			'master_type_lot_id' => $type_lot,
			'shift_id' => $shift['shift_id'],
			'lot_type' => 2,
			'master_process_id' => 0,
			'user_code' => $_POST['usercode'],
		);
		$execevent = $con->insert("laundry_lot_event", $dataevent);
		//end event lot

	
		//wo master ===================================================
		//input data wo master & wo master detail
		$selwodtl = $con->select("laundry_scan_qrcode", "wo_no,garment_colors,garment_inseams,garment_sizes,SUM(scan_qty) as totqty,DATE(ex_fty_date) as ex_fty_date", "wo_no = '" . $wo_no . "' and garment_colors = '" . $colors . "' and DATE(ex_fty_date) = '" . $exdate . "' and scan_status = '0' and scan_type = 3 and scan_status_garment = '$_POST[scst]' GROUP BY wo_no,garment_colors,ex_fty_date,garment_inseams,garment_sizes");
		
		foreach ($selwodtl as $wodtl) {

			//cari id wo dtl
			foreach($con->select("laundry_wo_master_dtl","wo_master_dtl_id","wo_no = '" . $wo_no . "' and garment_colors = '" . $colors . "' and DATE(ex_fty_date) = '" . $exdate . "' and garment_inseams = '" . $wodtl['garment_inseams'] . "' and garment_sizes = '" . $wodtl['garment_sizes']."'") as $idwodtl){}

			// echo "select wo_master_dtl_id from laundry_wo_master_dtl where wo_no = '" . $wo_no . "' and garment_colors = '" . $colors . "' and DATE(ex_fty_date) = '" . $exdate . "' and garment_inseams = '" . $wodtl['garment_inseams'] . "' and garment_sizes = '" . $wodtl['garment_sizes']."'";
			//update status dari scan_qrcode
			$selscanzero = $con->select("laundry_scan_qrcode","scan_id","wo_no = '" . $wo_no . "' and garment_colors = '" . $colors . "' and DATE(ex_fty_date) = '" . $exdate . "' and garment_inseams = '" . $wodtl['garment_inseams'] . "' and garment_sizes = '" . $wodtl['garment_sizes'] . "' and scan_status = '0' and scan_type = 3 and scan_status_garment = '$_POST[scst]'");
		
			foreach ($selscanzero as $scanzero) {
				$datamodify = array(
					'scan_status' => 1,
					'wo_master_dtl_id' => $idwodtl['wo_master_dtl_id'],
					'lot_no' => $_POST['nolot'],
					'rework_again' => $intyseq
				);
				//var_dump($datamodify);
				$execmodify = $con->update("laundry_scan_qrcode", $datamodify, "scan_id = '" . $scanzero['scan_id'] . "'");

			}
		}

		if ($_POST['scst']== 3) {

			// input data role per lot receive ke laundry role child
			$selrolechild = $con->select(
				"laundry_role_wo a left join laundry_role_dtl_wo b on a.role_wo_id=b.role_wo_id",
				"a.role_wo_id, role_wo_name, master_type_process_id, role_wo_seq, role_wo_time, role_dtl_wo_id, master_process_id,
				 role_dtl_wo_seq, role_dtl_wo_time","role_wo_master_id = '".$wodtlproc['role_wo_master_id']."' 
				 and role_wo_seq <= (select role_wo_seq from laundry_role_wo where role_wo_master_id = '". $wodtlproc['role_wo_master_id']."' 
				 and master_type_process_id = 2)","role_wo_seq");

			foreach ($selrolechild as $child) {
				$idchild = $con->idurut("laundry_role_child", "role_child_id");
				$datachild = array(
					'role_child_id' => $idchild,
					'role_wo_master_id'  => $wodtlproc['role_wo_master_id'],
					'role_wo_id'  => $child['role_wo_id'],
					'role_dtl_wo_id'  => $child['role_dtl_wo_id'],
					'lot_type'  => 3,
					'role_child_status'  => 0,
					'role_child_createdate'  => $date,
					'role_child_createdby'  => $_SESSION['ID_LOGIN'],
					'lot_no'  => $_POST['nolot'],
					'lot_id'  => $idreceive,
					'role_wo_seq' => $child['role_wo_seq'],
					'role_dtl_wo_seq' => $child['role_dtl_wo_seq'],
				);
				//var_dump($datachild); 
				$execchild = $con->insert("laundry_role_child", $datachild);
				//var_dump($execchild);
			}
		}
		echo $_POST['nolot'].'_'.base64_encode($_POST['nolot']);
	} 	
?>
