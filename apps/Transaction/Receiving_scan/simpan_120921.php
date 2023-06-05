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

		//jika lastreceive di centang maka status receive 2 jika tidak 1
		if ($_POST['lastrec'][$key] == '1') {
			$statusrec = 2;
		} else {
			$statusrec = 1;
		}

		$selwodtlproc = $con->select("laundry_wo_master_dtl_proc", "wo_master_dtl_proc_id,role_wo_master_id", "wo_no = '" . $wo_no . "' and garment_colors = '" . $colors . "' and DATE(ex_fty_date) = '" . $exdate . "'");
		foreach ($selwodtlproc as $wodtlproc) {}

		//cek wo sudah ada pada master wo laundry
		foreach ($selcountwo = $con->select("laundry_wo_master", "count(wo_master_id) as countwo,wo_master_id,total_rec_qty", "wo_no = '" . $wo_no . "' GROUP BY wo_master_id") as $countwo) {}

		// input data ke database receive
		$idreceive = $con->idurut("laundry_receive", "rec_id");
		$datarec = array(
			'rec_id' => $idreceive,
			'rec_no' => $_POST['noreceive'],
			'wo_no' => $wo_no,
			'garment_colors' => $colors,
			'rec_qty' => $_POST['recnow'],
			'rec_remark' => $_POST['remark'],
			'rec_createdate' => $date,
			'rec_createdby' => $_SESSION['ID_LOGIN'],
			'rec_status' => $statusrec,
			'wo_master_dtl_proc_id' => $wodtlproc['wo_master_dtl_proc_id'],
			'rec_type' => 2,
			'shift_id' => $shift['shift_id'],
			'ex_fty_date' => $exdate,
			'user_code' => $_POST['usercode'],
		);
		//var_dump($datarec);
		$execrec = $con->insert("laundry_receive", $datarec);
		//$execrec = $con->insertUSDN("laundry_receive", $datarec,"rec_id,rec_no");

		//input event Lot
		$idevent = $con->idurut("laundry_lot_event", "event_id");
		$dataevent = array(
			'event_id' => $idevent,
			'lot_id' => $idreceive,
			'lot_no' => $_POST['noreceive'],
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
			'log_lot_tr' => $_POST['noreceive'],
			'log_lot_ref' => 0,
			'log_lot_qty' => $_POST['recnow'],
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
		//jika wo master belum ada
		if ($countwo['countwo'] == '') {
			//jika belum ada maka di inputkan master wo laundry
			$idwo = $con->idurut("laundry_wo_master", "wo_master_id");
			//mendapatkan buyer style number
			foreach ($selwork = $con->select("laundry_data_wo", "buyer_style_no,wo_qty", "wo_no = '" . $wo_no . "'") as $work) {
			}
			$datawo = array(
				'wo_master_id' => $idwo,
				'buyer_style_no' => $work['buyer_style_no'],
				'wo_no' => $wo_no,
				'wo_qty' => $work['wo_qty'],
				'wo_master_createdate' => $date,
				'wo_master_createdby' => $_SESSION['ID_LOGIN'],
				'total_rec_qty' => $_POST['recnow'],
			);
			$execwo = $con->insert("laundry_wo_master", $datawo);
		}
		//jika sudah ada
		else {
			$idwo = $countwo['wo_master_id'];
			//update jumlah qty pada master wo
			$qty = $countwo['total_rec_qty'] + $_POST['recnow'];
			$datawo = array(
				'total_rec_qty' => $qty,
				'wo_master_modifydate' => $date,
				'wo_master_modifyby' => $_SESSION['ID_LOGIN'],
			);
			$execwo = $con->update("laundry_wo_master", $datawo, "wo_no = '" . $wo_no . "'");
		}
		//end wo master ===========================================================

		//wo master dtl proc ==========================================================
		// update qty dtl proc
		$qtyproc = $proc['wo_master_dtl_proc_qty_rec'] + $_POST['recnow'];
		$dataproc = array(
			'wo_master_dtl_proc_qty_rec' => $_POST['willrec'],
			'cutting_qty' => $_POST['cutqty'],
			'wo_master_dtl_proc_modifydate' => $date,
			'wo_master_dtl_proc_modifyby' => $_SESSION['ID_LOGIN'],
			//'rework_seq' => 1,
		);
		$execproc = $con->update("laundry_wo_master_dtl_proc", $dataproc, "wo_no = '" . $wo_no . "' and garment_colors = '" . $colors . "' and DATE(ex_fty_date) = '" . $exdate . "'");

		//end wo master dtl proc =======================================================

		//input data wo master & wo master detail
		$selwodtl = $con->select("laundry_scan_qrcode", "wo_no,garment_colors,garment_inseams,garment_sizes,SUM(scan_qty) as totqty,DATE(ex_fty_date) as ex_fty_date", "wo_no = '" . $wo_no . "' and garment_colors = '" . $colors . "' and DATE(ex_fty_date) = '" . $exdate . "' and scan_status = '0' and scan_type = 1 GROUP BY wo_no,garment_colors,ex_fty_date,garment_inseams,garment_sizes");
		foreach ($selwodtl as $wodtl) {
			//cek wo sudah ada pada master wo laundry
		$countwodtl = $con->selectcount("laundry_wo_master_dtl", "wo_master_dtl_id", "wo_no = '" . $wo_no . "' and garment_colors = '" . $colors . "' and DATE(ex_fty_date) = '" . $exdate . "' and garment_inseams = '" . $wodtl['garment_inseams'] . "' and garment_sizes= '" . $wodtl['garment_sizes'] . "'");

			if($countwodtl > 0) {
				
				$datawodtl = array(
					'wo_master_dtl_qty_rec' => $wodtl['totqty'],
					'rec_id' => $idreceive,
				);
				$execwodtl = $con->update("laundry_wo_master_dtl", $datawodtl,"wo_no = '" . $wo_no . "' and garment_colors = '" . $colors . "' and DATE(ex_fty_date) = '" . $exdate . "' and garment_inseams = '" . $wodtl['garment_inseams'] . "' and garment_sizes= '" . $wodtl['garment_sizes'] . "'");
			} else {
				
				//input laundry_wo_master_dtl
				$idwodtl = $con->idurut("laundry_wo_master_dtl", "wo_master_dtl_id");
				$datawodtl = array(
					'wo_master_dtl_id' => $idwodtl,
					'wo_no' => $wo_no,
					'garment_colors' => $colors,
					'garment_inseams' => $wodtl['garment_inseams'],
					'garment_sizes' => $wodtl['garment_sizes'],
					'wo_master_dtl_status' => 1,
					'wo_master_id' => $idwo,
					'ex_fty_date' => $wodtl['ex_fty_date'],
					'wo_master_dtl_receive_date' => $date,
					'wo_master_dtl_qty_rec' => $wodtl['totqty'],
					'wo_master_dtl_createdby' => $_SESSION['ID_LOGIN'],
					'rec_id' => $idreceive,
					'wo_master_dtl_proc_id' => $wodtlproc['wo_master_dtl_proc_id'],
				);
				$execwodtl = $con->insert("laundry_wo_master_dtl", $datawodtl);
			}
		}
		//update status dari scan_qrcode
			$selscanzero = $con->select("laundry_scan_qrcode","scan_id","wo_no = '" . $wo_no . "' and garment_colors = '" . $colors . "' and DATE(ex_fty_date) = '" . $exdate . "' and scan_status = '0' and scan_type = 1 limit '".$_POST[recnow]."'");
		//	echo "select scan_id from laundry_scan_qrcode where wo_no = '" . $wo_no . "' and garment_colors = '" . $colors . "' and DATE(ex_fty_date) = '" . $exdate . "' and scan_status = '0' and scan_type = 1 limit '".$_POST[recnow]."'";
			foreach ($selscanzero as $scanzero) {
				$datamodify = array(
					'scan_status' => 1,
					//'wo_master_dtl_id' => $idwodtl,
					'lot_no' => $_POST['noreceive'],
					'wo_master_dtl_proc_id' => $wodtlproc['wo_master_dtl_proc_id'],
				);
			//	var_dump($datamodify);
				$execmodify = $con->update("laundry_scan_qrcode", $datamodify, "scan_id = '" . $scanzero['scan_id'] . "'");

			}


		// input data role per lot receive ke laundry role child
		$selrolechild = $con->select(
			"laundry_role_wo a left join laundry_role_dtl_wo b on a.role_wo_id=b.role_wo_id",
			"a.role_wo_id, role_wo_name, master_type_process_id, role_wo_seq, role_wo_time, role_dtl_wo_id, master_process_id,
			 role_dtl_wo_seq, role_dtl_wo_time","role_wo_master_id = '".$wodtlproc['role_wo_master_id']."' 
			 and role_wo_seq <= (select role_wo_seq from laundry_role_wo where role_wo_master_id = '". $wodtlproc['role_wo_master_id']."' 
			 and master_type_process_id = 2 and role_wo_name_seq = 1)","role_wo_seq");
		// echo "select a.role_wo_id, role_wo_name, master_type_process_id, role_wo_seq, role_wo_time, role_dtl_wo_id, master_process_id,
		// 	 role_dtl_wo_seq, role_dtl_wo_time from laundry_role_wo a left join laundry_role_dtl_wo b on a.role_wo_id=b.role_wo_id where role_wo_master_id = '".$wodtlproc['role_wo_master_id']."' 
		// 	 and role_wo_seq <= (select role_wo_seq from laundry_role_wo where role_wo_master_id = '". $wodtlproc['role_wo_master_id']."' 
		// 	 and master_type_process_id = 2 and role_wo_name_seq = 1)";
		foreach ($selrolechild as $child) {
			$idchild = $con->idurut("laundry_role_child", "role_child_id");
			$datachild = array(
				'role_child_id' => $idchild,
				'role_wo_master_id'  => $wodtlproc['role_wo_master_id'],
				'role_wo_id'  => $child['role_wo_id'],
				'role_dtl_wo_id'  => $child['role_dtl_wo_id'],
				'lot_type'  => 1,
				'role_child_status'  => 0,
				'role_child_createdate'  => $date,
				'role_child_createdby'  => $_SESSION['ID_LOGIN'],
				'lot_no'  => $_POST['noreceive'],
				'lot_id'  => $idreceive,
				'role_wo_seq' => $child['role_wo_seq'],
				'role_dtl_wo_seq' => $child['role_dtl_wo_seq'],
			);
			//var_dump($datachild); 
			$execchild = $con->insert("laundry_role_child", $datachild);
			//var_dump($execchild);
		}
		echo $_POST['noreceive'].'_'.base64_encode($_POST['noreceive']);
	} 	
?>
