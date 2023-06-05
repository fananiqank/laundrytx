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
		$colors = trim($expwono[1]);
		$exdate = $expwono[2];

		//mendapatkan no. urut max per wo
			$selecturutcmt = $con->select("laundry_receive", "COALESCE(max(rec_no_uniq),0) as max", "wo_no = '" . $wo_no . "'");
			foreach ($selecturutcmt as $urutcmt) {}
			$cmtseq = $urutcmt['max'] + 1;

			$expwo = explode('/', $wo_no);

		//mendapatkan no. urut max per wo & color
		$urutcmtcolor = $con->selectcount("laundry_receive", "rec_id", "wo_no = '" . $wo_no . "'");
		$cmtcolseq = $urutcmtcolor + 1;

		if($expwo[1] == 'RECUT'){

			$noreceive = 'L' . $expwo[0]."R".$expwo[3] . $expwo[4] . trim($expwo[5]) . 'A' . sprintf('%03s', $cmtseq) . '-' . $cmtcolseq;
		} else {

			$noreceive = 'L' . $expwo[0] . $expwo[4] . $expwo[5] . trim($expwo[6]) . 'A' . sprintf('%03s', $cmtseq) . '-' . $cmtcolseq;
		}
		
		// cek lot_no sudah ada tau belum, sbg antisipasi double input. 
		$selcountreceive = $con->selectcount("laundry_receive", "rec_no", "rec_no = '" . $noreceive . "'");

		foreach($con->select("(select COALESCE(sum(case when COALESCE(DATE(\"WASH_IN_GOOD\")::TEXT,'x')='x' then 0 else 1 end),0) totalbatch from qrcode_gdp a join qrcode_ticketing_detail b on a.ticketid=b.ticketid join qrcode_ticketing_master c on a.ticketid=c.ticketid where gdpbatch = '$_POST[gdpbatch]' and wo_no = '$wo_no' and trim(color) = '$colors' and step_id_detail = 4) a","*") as $totalbatch){}

		// input data ke database receive
		if($selcountreceive == 0){
			$idreceive = $con->idurut("laundry_receive", "rec_id");
			$datarec = array(
				'rec_id' => $idreceive,
				'rec_no' => $noreceive,
				'wo_no' => $wo_no,
				'garment_colors' => $colors,
				'rec_qty' => $totalbatch['totalbatch'],
				'rec_createdate' => $date,
				'rec_createdby' => $_SESSION['ID_LOGIN'],
				'rec_status' => 1,
				'wo_master_dtl_proc_id' => $_POST['getid'],
				'rec_type' => 2,
				'shift_id' => $shift['shift_id'],
				'ex_fty_date' => $exdate,
				'user_code' => $_POST['usercode'],
				'rec_no_uniq' => $cmtseq
			);
			$execrec = $con->insert("laundry_receive", $datarec);
	
	// insert qrcodebatch
			$idbatch = $con->idurut("laundry_qrcodebatch", "qrcodebatch_id");
			$databatch = array(
					'qrcodebatch_id' => $idbatch,
					'qrcodebatch_no' => $_POST['gdpbatch'],
					'rec_id' => $idreceive,
					'wo_no' => $wo_no,
					'color' => $colors,
					'ex_fty_date' => $exdate,
					'qrcodebatch_qty' => $totalbatch['totalbatch'],
					'qrcodebatch_createdate' => $date,
					'wo_master_dtl_proc_id' => $_POST['getid'],
			);
			$execbatch = $con->insert("laundry_qrcodebatch", $databatch);

	//insert role child master (fungsi utk master role per lot)
			$datachildmaster = array(
						'lot_no' => $noreceive,
						'role_wo_master_id'  => $_POST['rolemaster'],
						'lot_status' => 1,
						'lot_type' => 1,
						'lot_id' => $idreceive,
						'role_wo_name_seq' => 1,
					);
			
			$execchildmaster = $con->insert("laundry_role_child_master", $datachildmaster);
			//=========================================================

			//input event Lot
			$idevent = $con->idurut("laundry_lot_event", "event_id");
			$dataevent = array(
				'event_id' => $idevent,
				'lot_id' => $idreceive,
				'lot_no' => $noreceive,
				'event_type' => 1,
				'event_status' => 1,
				'event_createdby' => $_SESSION['ID_LOGIN'],
				'event_createdate' => $date,
				'master_type_process_id' => 1,
				'master_type_lot_id' => 0,
				'shift_id' => $shift['shift_id'],
				'lot_type' => 1,
				'user_code' => $_POST['usercode'],
			);
			$execevent = $con->insert("laundry_lot_event", $dataevent);
			//end event lot

			// input data ke log lot
			$datalog = array(
				'log_lot_tr' => $noreceive,
				'log_lot_ref' => 0,
				'log_lot_qty' => $totalbatch['totalbatch'],
				'log_lot_status' => 1,
				'log_lot_event' => 1,
				'wo_no' => $wo_no,
				'garment_colors' => $colors,
				'ex_fty_date' =>$exdate,
				'log_createdate' => $date,
				'log_lot_receive' => 0,
				'role_wo_name_seq' => 0,
			);
			$execlog = $con->insert("laundry_log", $datalog);

			//membuat qrcode lot receive
			//include "phpqrcode/index.php";
			// =========================
			//wo master ===================================================
			
			

			// input data role per lot receive ke laundry role child
			$selrolechild = $con->select(
				"laundry_role_wo a left join laundry_role_dtl_wo b on a.role_wo_id=b.role_wo_id and role_dtl_wo_status = 1",
				"a.role_wo_id, role_wo_name, master_type_process_id, role_wo_seq, role_wo_time, role_dtl_wo_id, master_process_id,
				 role_dtl_wo_seq, role_dtl_wo_time","role_wo_master_id = '".$_POST['rolemaster']."' and role_wo_status = 1
				 and role_wo_seq <= (select role_wo_seq from laundry_role_wo where role_wo_master_id = '".$_POST['rolemaster']."' 
				 and master_type_process_id = 2 and role_wo_name_seq = 1 and role_wo_status = 1)","role_wo_seq");
		
			foreach ($selrolechild as $child) {
				$idchild = $con->idurut("laundry_role_child", "role_child_id");
				$datachild = array(
					'role_child_id' => $idchild,
					'role_wo_master_id'  => $_POST['rolemaster'],
					'role_wo_id'  => $child['role_wo_id'],
					'role_dtl_wo_id'  => $child['role_dtl_wo_id'],
					'lot_type'  => 1,
					'role_child_status'  => 0,
					'role_child_createdate'  => $date,
					'role_child_createdby'  => $_SESSION['ID_LOGIN'],
					'lot_no'  => $noreceive,
					'lot_id'  => $idreceive,
					'role_wo_seq' => $child['role_wo_seq'],
					'role_dtl_wo_seq' => $child['role_dtl_wo_seq'],
				);
				//var_dump($datachild); 
				$execchild = $con->insert("laundry_role_child", $datachild);
				//var_dump($execchild);
			}
			echo $noreceive.'_'.base64_encode($noreceive);
		}
	} 	
?>
