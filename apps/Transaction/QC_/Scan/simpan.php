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
	//echo "select shift_id,shift_time_start,shift_time_end,shift_status from laundry_master_shift where TO_CHAR(now(), 'HH24:MI') between TO_CHAR(shift_time_start, 'HH24:MI') and TO_CHAR(shift_time_end, 'HH24:MI') and shift_status = 1";
//end shift
	
//data scanner
foreach($con->select("qrcode_ticketing_master","wo_no,color,size,inseam,sequence_no,shade,DATE(ex_fty_date) as ex_fty_date","qrcode_key = '".$_POST['scanner']."'") as $qrcode_key){}

		$cmt = $qrcode_key['wo_no'];
		$colors = $qrcode_key['color'];
		$sizes = $qrcode_key['size'];
		$inseams = $qrcode_key['inseam'];
		$seq_cutting = $qrcode_key['sequence_no'];
		$shade = $qrcode_key['shade'];
		$exftydate = $qrcode_key['ex_fty_date'];

//select step id detail QRCODE ================
foreach ($con->select("qrcode_step_detail","step_id_detail,workcenter_id,workcenter_seq","step_id_detail = '4'") as $stepdtl) {} 
//=============================================

// memulai Process
	//input scan Receive not confirm
	if ($_POST['scan_type'] == '1'){
		$cekproc = $con->selectcount("laundry_wo_master_dtl_proc","wo_no","wo_no = '".$cmt."' and garment_colors = '".$colors."' and DATE(ex_fty_date) = '".$exftydate."'");

		if ($cekproc > 0) {
			include "cekdatagdp.php";
		} else {
			echo "5|".$_POST['scanner'];
		}
	} 

	//input scan Receive confirm
	else if ($_POST['confirm'] == '1'){
		$expwono = explode('_',$_POST['datawono']);
		$wo_no = $expwono[0];
		$colors = $expwono[1];
		$exdate = $expwono[2];

		//jika lastreceive di centang maka status receive 2 jika tidak 1
		if ($_POST['lastrec'][$key] == '1'){
				$statusrec = 2;
		} else {
				$statusrec = 1;
		}

		$selwodtlproc = $con->select("laundry_wo_master_dtl_proc","wo_master_dtl_proc_id,role_wo_master_id","wo_no = '".$wo_no."' and garment_colors = '".$colors."' and DATE(ex_fty_date) = '".$exdate."'");
		//echo "select wo_master_dtl_proc_id,role_wo_master_id from laundry_wo_master_dtl_proc where wo_no = '".$wo_no."' and garment_colors = '".$colors."' and DATE(ex_fty_date) = '".$exdate."'";
			foreach ($selwodtlproc as $wodtlproc) {}

		//cek wo sudah ada pada master wo laundry
		foreach ($selcountwo = $con->select("laundry_wo_master","count(wo_master_id) as countwo,wo_master_id,total_rec_qty","wo_no = '".$wo_no."' GROUP BY wo_master_id") as $countwo){};
		
		// input data ke database receive
		$idreceive = $con->idurut("laundry_receive","rec_id");
		$datarec = array(
						'rec_id' => $idreceive,
						'rec_no' => $_POST['noreceive'],
						'wo_no' => $wo_no,
						'garment_colors' => $colors,
						'rec_qty' => $_POST['totalrec'],
						'rec_remark' => $_POST['remark'],
						'rec_createdate' => $date,
						'rec_createdby' => $_SESSION['ID_LOGIN'],
						'rec_status' => $statusrec,
						'wo_master_dtl_proc_id' => $_POST['womasterdtlprocid'],
						'rec_type' => 2,
						'shift_id' => $shift['shift_id'],
		); 
		//var_dump($datarec);
//		$execrec = $con->insert("laundry_receive", $datarec);
		//var_dump($execrec);

		//input event Lot
			$idevent = $con->idurut("laundry_lot_event","event_id");
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
			); 
//			$execevent= $con->insert("laundry_lot_event", $dataevent);
		//end event lot

		//membuat qrcode lot receive
		//include "phpqrcode/index.php";
		// =========================
	//wo master ===================================================
		//jika wo master belum ada
		if ($countwo['countwo'] == '') {	
			//jika belum ada maka di inputkan master wo laundry
			$idwo = $con->idurut("laundry_wo_master","wo_master_id");
				//mendapatkan buyer style number
				foreach($selwork = $con->select("laundry_data_wo","buyer_style_no,wo_qty","wo_no = '".$wo_no."'") as $work){}
					$datawo = array(
							'wo_master_id' => $idwo,
							'buyer_style_no' => $work['buyer_style_no'],
							'wo_no' => $wo_no,
							'wo_qty' => $work['wo_qty'],
							'wo_master_createdate' => $date,
							'wo_master_createdby' => $_SESSION['ID_LOGIN'],
							'total_rec_qty' => $_POST['totalrec'],
					);
//					$execwo= $con->insert("laundry_wo_master", $datawo);
		}  
		//jika sudah ada
		else {
			$idwo = $countwo['wo_master_id'];
			//update jumlah qty pada master wo
			$qty = $countwo['total_rec_qty']+$_POST['totalrec'];
			$datawo = array(
							'total_rec_qty' => $qty,
							'wo_master_modifydate' => $date,
							'wo_master_modifyby' => $_SESSION['ID_LOGIN'],			
			);
//			$execwo= $con->update("laundry_wo_master", $datawo,"wo_no = '".$wo_no."'");
		}
		//end wo master ===========================================================

	//wo master dtl proc ==========================================================
		// update qty dtl proc
			$qtyproc = $proc['wo_master_dtl_proc_qty_rec']+$_POST['totalrec'];
			$dataproc = array(
							'wo_master_dtl_proc_qty_rec' => $_POST['willrec'],	
							'cutting_qty' => $_POST['cutqty'],
							'wo_master_dtl_proc_modifydate' => $date,
							'wo_master_dtl_proc_modifyby' => $_SESSION['ID_LOGIN'],
			);
//			$execproc= $con->update("laundry_wo_master_dtl_proc", $dataproc,"wo_no = '".$wo_no."' and garment_colors = '".$colors."' and DATE(ex_fty_date) = '".$exdate."'");
			
	//end wo master dtl proc =======================================================

		//input data wo master & wo master detail
		foreach ($_POST['datasave'] as $key => $value) {
			
		 	$expval = explode('|',$value);
		 	$inseams = $expval[0];
		 	$sizes = $expval[1];
		 	$seq_cutting = $expval[2];
		 	$qty = $expval[3];
				
			//input laundry_wo_master_dtl
			$idwodtl = $con->idurut("laundry_wo_master_dtl","wo_master_dtl_id");
			$datawodtl = array(
							'wo_master_dtl_id' => $idwodtl,
							'wo_no' => $wo_no,
							'garment_colors' => $colors,
							'garment_inseams' => $inseams,
							'garment_sizes' => $sizes, 
							'sequence_no' => $seq_cutting,
							'wo_master_dtl_status' => 1, 
							'wo_master_id' => $idwo,
							'wo_master_dtl_receive_date' => $date,
							'wo_master_dtl_qty_rec' => $qty,
							'wo_master_dtl_createdby' => $_SESSION['ID_LOGIN'],
							'rec_id' => $idreceive,
			); 
//			$execwodtl= $con->insert("laundry_wo_master_dtl", $datawodtl);

			$selscanzero = $con->select("laundry_scan_qrcode","*","scan_status = 0 and scan_createdby = '".$_SESSION['ID_LOGIN']."' and wo_no = '".$wo_no."' and garment_colors = '".$colors."' and garment_inseams='".$inseams."' and garment_sizes='".$sizes."' and ex_fty_date = '". $exdate."' and scan_type = 1");
			 // echo "select * from laundry_scan_qrcode where scan_status = 0 and scan_createdby = '$_SESSION[ID_LOGIN]' and wo_no = '$wo_no' and garment_colors = '$colors' and garment_inseams='$inseams' and garment_sizes='$sizes' and scan_type = 1";
				foreach ($selscanzero as $scanzero) {
					$datamodify = array(
						'scan_status' => 1,
						'wo_master_dtl_id' => $idwodtl,
						'lot_no' => $_POST['noreceive'],
					);
//					$execmodify = $con->update("laundry_scan_qrcode",$datamodify,"scan_id = '".$scanzero['scan_id']."'");

				// input on qrcode_GDP
						$idgdp = $con->idurut("qrcode_gdp","gdp_id");
						$datascan = array(
							 //'gdp_id' => $idgdp,
							 'qrcode_key' => $scanzero['scan_qrcode'],
							 'step_id_detail' => 4,
							 'created_date' => $date,
							 'created_by' => $_SESSION['ID_LOGIN'],
							 'gdp_datetime' => $date,
							 'gdp_goods' => 1,
							 'gdp_reject' => 0,
							 'gdp_rework' => 0,
							 'gdp_status' => 1,
							 'workcenter_seq' => $stepdtl['workcenter_seq'],
							 'workcenter_id' => $stepdtl['workcenter_id'],
							 'defect_id' => 0,
							 'line_id' => 0,
							 'floor_id' => 1,
							 'factory_id' => $scanzero['factory_id'],
							 'user_code' => $scanzero['user_code'] ,

						);
//						$execscan= $con->insert("qrcode_gdp", $datascan);
				//======================

				} 		
		} 

		// input data role per lot receive ke laundry role child
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
									 "role_wo_master_id = '".$wodtlproc['role_wo_master_id']."' and 
									  role_wo_seq <= (select role_wo_seq from laundry_role_wo where role_wo_master_id = '".$wodtlproc['role_wo_master_id']."' and master_type_process_id = 2)",
									 "role_wo_seq");
		
		foreach ($selrolechild as $child) {
			$idchild = $con->idurut("laundry_role_child","role_child_id");
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
//			$execchild = $con->insert("laundry_role_child", $datachild);
			//var_dump($execchild);
		}
		echo $_POST['noreceive'];

	} 
	// //scan input setelah Dry
	// else if ($_POST['scan_type'] == '2'){

	// 	$idscan = $con->idurut("laundry_scan_qrcode","scan_id");
	// 	$datascan = array(
	// 		 'scan_id' => $idscan,
	// 		 'scan_qrcode' => $_POST['scanner'],
	// 		 'buyer_id' => $buyer_id,
	// 		 'wo_no' => $cmt,
	// 		 'garment_colors' => $colors,
	// 		 'garment_inseams' => $inseams,
	// 		 'garment_sizes' => $sizes,
	// 		 'sequence_no' => $seq_cutting,
	// 		 'shade' => $shade,
	// 		 'scan_createdby' => $_SESSION['ID_LOGIN'],
	// 		 'scan_createdate' => $date,
	// 		 'scan_type' => $_POST['scan_type'],
	// 		 'scan_status' => 0,
	// 		 'scan_qty' => 1,
	// 	);
	// 	$execscan= $con->insert("laundry_scan_qrcode", $datascan);
	// } 
	
	// //input scan Dry confirm
	// else if ($_POST['confirm'] == '2'){
	// 	$expwono = explode('_',$_POST['datawono']);
	// 	$wo_no = $expwono[0];
	// 	$colors = $expwono[1];

	// 	foreach ($_POST['datasave'] as $key => $value) {
	// 		$expval = explode('|',$value);
	// 	 	$inseams = $expval[0];
	// 	 	$sizes = $expval[1];
	// 	 	$seq_cutting = $expval[2];
	// 	 	$qty = $expval[3];

	// 	 	//update table scan menjadi status cnfirm dan no. lot
	// 		$selscanzero = $con->select("laundry_scan_qrcode","scan_id","scan_status = 0 and scan_createdby = '$_SESSION[ID_LOGIN]' and wo_no = '$wo_no' and garment_colors = '$colors' and garment_inseams='$inseams' and garment_sizes='$sizes' and sequence_no='$seq_cutting' and scan_type = 2");
			
	// 			foreach ($selscanzero as $scanzero) {
	// 				$datamodify = array(
	// 					'scan_status' => 1,
	// 					'lot_no' => $_POST['rec_no'],
	// 				);
	// 				$execmodify = $con->update("laundry_scan_qrcode",$datamodify,"scan_id = '$scanzero[scan_id]'");
	// 			} 		
	// 	}

	// 	$idprocess = $con->idurut("laundry_process","process_id");
	// 	$dataprocess = array(
	// 				 'process_id' => $idprocess,
	// 				 'wo_master_dtl_proc_id' => $_POST['womasterdtlprocid'],
	// 				 'lot_type' => 1,
	// 				 'master_process_id' => 42,
	// 				 'process_type' => 4,
	// 				 'wo_no' => $wo_no,
	// 				 'garment_colors' => $colors,
	// 				 'lot_no' => $_POST['rec_no'],
	// 				 'master_type_process_id' => 4,
	// 				 'master_type_lot_id' => 0,
	// 				 'role_wo_master_id' => $_POST['role-wo-master-id'],
	// 				 'role_wo_id' => $_POST['rolewoid'],
	// 				 'role_dtl_wo_id' => $_POST['roledtlwoid'],
	// 				 'process_createdate' => $date,
	// 				 'process_createdby' => $_SESSION['ID_LOGIN'],
	// 				 'process_status' => 1,
	// 				 'machine_id' => 0,
	// 				 'process_qty_good' => $_POST['totalrec'],
	// 				 'process_qty_reject' => 0,
	// 				 'process_qty_total' => $_POST['totalrec'],
	// 				 'shift_id' => $shift['shift_id'],
	// 	); 
	// 	$execprocess= $con->insert("laundry_process", $dataprocess);

	// }
?>
