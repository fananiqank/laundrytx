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
	foreach($con->select("laundry_lot_number","lot_id","lot_no = '$_POST[lotnumber]'") as $lotid){}
// end select

	//input qc manual di keranjang
	if ($_POST['confirm'] == '1'){
		if ($_POST['qtygood'] != 0){
			$idkeranjang = $con->idurut("laundry_qc_keranjang","qc_keranjang_id");
			$datakeranjang = array(
						'qc_keranjang_id' => $idkeranjang,
					 	'wo_no' => $_POST['cmt'],
					 	'garment_colors' => $_POST['colors'],
					 	'ex_fty_date' => $_POST['ex_fty_date'],
						'qc_keranjang_qty' => $_POST['qtygood'],
						'qc_keranjang_type' => 1,
						'qc_keranjang_createdate' => $date,
						'qc_keranjang_createdby' => $_SESSION['ID_LOGIN'],
						'lot_no' => $_POST['lotnumber'],
						);
			//var_dump($datakeranjang);
			$execkeranjang= $con->insert("laundry_qc_keranjang", $datakeranjang);
			//var_dump($execkeranjang);
		} 

		if ($_POST['qtyreject'] != 0){
			$idkeranjang = $con->idurut("laundry_qc_keranjang","qc_keranjang_id");
			$datakeranjang = array(
						'qc_keranjang_id' => $idkeranjang,
					 	'wo_no' => $_POST['cmt'],
					 	'garment_colors' => $_POST['colors'],
					 	'ex_fty_date' => $_POST['ex_fty_date'],
						'qc_keranjang_qty' => $_POST['qtyreject'],
						'qc_keranjang_type' => 2,
						'qc_keranjang_createdate' => $date,
						'qc_keranjang_createdby' => $_SESSION['ID_LOGIN'],
						'lot_no' => $_POST['lotnumber'],
						);
			
			$execkeranjang= $con->insert("laundry_qc_keranjang", $datakeranjang);
		} 

		if ($_POST['qtyrework'] != 0){
			$idkeranjang = $con->idurut("laundry_qc_keranjang","qc_keranjang_id");
			$datakeranjang = array(
						'qc_keranjang_id' => $idkeranjang,
					 	'wo_no' => $_POST['cmt'],
					 	'garment_colors' => $_POST['colors'],
					 	'ex_fty_date' => $_POST['ex_fty_date'],
						'qc_keranjang_qty' => $_POST['qtyrework'],
						'qc_keranjang_type' => 3,
						'qc_keranjang_createdate' => $date,
						'qc_keranjang_createdby' => $_SESSION['ID_LOGIN'],
						'lot_no' => $_POST['lotnumber'],
						);
			
			$execkeranjang= $con->insert("laundry_qc_keranjang", $datakeranjang);
		} 

	} 
	// delete dari keranjang
	else if ($_GET['type_qc'] != '') {
		$select = $con->select("laundry_qc_keranjang","qc_keranjang_id","lot_no = '$_GET[lot]' and qc_keranjang_type = '$_GET[type_qc]'");
		//echo "select qc_keranjang_id from laundry_qc_keranjang where lot_no = '$_GET[lot]' and qc_keranjang_type = '$_GET[type_qc]'";
		foreach ($select as $del) {

			$where = array('qc_keranjang_id' => $del['qc_keranjang_id']);
			$con->delete("laundry_qc_keranjang",$where);
		}
		
	} 
	//submit QC manual
	else if ($_POST['conf'] == '2'){
		foreach ($_POST['datarec'] as $key => $value) {
			$expdata = explode('|',$_POST['datasave'][$key]);
			$wo_no = $expdata[0];
			$colors = $expdata[1];
			$exdate = $expdata[2];
			$lotno = $expdata[3]; 
			$qty = $expdata[4];

			//mendapatkan wo_master_dtl_proc_id 
			foreach ($con->select("laundry_wo_master_dtl_proc","wo_master_dtl_proc_id,role_wo_master_id","wo_no = '$wo_no' and garment_colors = '$colors' and ex_fty_date = '$exdate'") as $procid) {}
			// ================================

			//cek lot type 
				$sublot = substr($lotno,-4,1);
				if ($sublot == 'A'){
					$typelot = '1';
				} else {
					$typelot = '2';
				}
			// ========== 
			//jika good akan masuk ke wo_master_dtl_qc
			if($value == 1){
				$idgood = $con->idurut("laundry_wo_master_dtl_qc","wo_master_dtl_qc_id");
				$datagood = array(
						'wo_master_dtl_qc_id' => $idgood,
					 	'wo_no' => $wo_no,
					 	'garment_colors' => $colors,
					 	'ex_fty_date' => $exdate,
						'wo_master_dtl_qc_qty' => $_POST['totalrec_1'], 
						'lot_no' => $lotno,
						'wo_master_dtl_qc_createdate' => $date,
						'wo_master_dtl_qc_createdby' => $_SESSION['ID_LOGIN'],
						'wo_master_dtl_qc_status' => 1,
						'wo_master_dtl_proc_id' => $procid['wo_master_dtl_proc_id'],
						'wo_master_dtl_qc_type' => 1,
						'shift_id' => $shift['shift_id'],
						);
				$execgood= $con->insert("laundry_wo_master_dtl_qc", $datagood);

				//delete dari keranjang
				$where = array("lot_no" => $lotno);
				$execdel = $con->delete("laundry_qc_keranjang",$where,"qc_keranjang_type = 1");
				
			} 
			//jika reject maka akan menjadi lot Scrap dan break
			else if($value == 2){

				$idreject = $con->idurut("laundry_wo_master_dtl_qc","wo_master_dtl_qc_id");
				$datareject = array(
						'wo_master_dtl_qc_id' => $idreject,
					 	'wo_no' => $wo_no,
					 	'garment_colors' => $colors,
					 	'ex_fty_date' => $exdate,
						'wo_master_dtl_qc_qty' => $_POST['totalrec_2'], 
						'lot_no' => $lotno,
						'wo_master_dtl_qc_createdate' => $date,
						'wo_master_dtl_qc_createdby' => $_SESSION['ID_LOGIN'],
						'wo_master_dtl_qc_status' => 1,
						'wo_master_dtl_proc_id' => $procid['wo_master_dtl_proc_id'],
						'wo_master_dtl_qc_type' => 2,
						'shift_id' => $shift['shift_id'],
						);
				$execreject= $con->insert("laundry_wo_master_dtl_qc", $datareject);

				// membuat lot reject 
					//create no urut lot number ===================
						$selectlotnum = $con->select("laundry_lot_number a join laundry_master_type_lot b on a.master_type_lot_id=b.master_type_lot_id","a.*,b.master_type_lot_initial","lot_no = '$lotno'");
						foreach($selectlotnum as $lotnum){}

						//echo "select a.*,b.master_type_lot_initial from laundry_lot_number a join laundry_master_type_lot b on a.master_type_lot_id=b.master_type_lot_id where lot_no = '$lotno'";

						$sequencecount = $con->selectcount("laundry_lot_number","lot_id","wo_master_dtl_proc_id = '$lotnum[wo_master_dtl_proc_id]'");
						//echo "select lot_id from laundry_lot_number where wo_master_dtl_proc_id = '$lotnum[wo_master_dtl_proc_id]'";
						//die();
						$sequence = $sequencecount+1;
						
						$urutcount = $con->selectcount("laundry_lot_number","lot_id","wo_no = '$lotnum[wo_no]'");
						$urut = $urutcount+1;

						$expmt = explode('/',$wo_no);
						$trimexp6 = trim($expmt[6]);

						$nolb ='L'.$expmt[0].$expmt[4].$expmt[5].$trimexp6."S".sprintf('%03s', $urut);
						// end create no urut lot number =============

						$idlot = $con->idurut("laundry_lot_number","lot_id");
						$datalot = array(
										 'lot_id' => $idlot,
										 'lot_no' => $nolb, 
										 'wo_no' => $wo_no,
										 'garment_colors' => $colors,
										 'wo_master_dtl_proc_id' => $lotnum['wo_master_dtl_proc_id'], 
										 'master_type_lot_id' => 3,
										 'role_wo_master_id' => $lotnum['role_wo_master_id'],
										 'lot_qty' => $qty,
										 'lot_kg' => 0,
										 'lot_shade' => $lotnum['lot_shade'],
										 'lot_createdate' => $date,
										 'lot_createdby' => $_SESSION['ID_LOGIN'],
										 'lot_status' => 0,
										 'create_type' => 7,
										 'lot_type' => 'S',
										 'reject_from_role' => $_POST['rolewoidmod'],
										 'reject_from_lot' => $lotno,
										 'shift_id' => $shift['shift_id'],
										 
						); 
						//var_dump($datalot);
						$execlot= $con->insert("laundry_lot_number", $datalot);

						$idlotdtl = $con->idurut("laundry_lot_number_dtl","lot_dtl_id");
						$datalotdtl = array(
										 'lot_dtl_id' => $idlotdtl,
										 'lot_id_parent' => $lotnum['lot_id'], 
										 'lot_id' => $idlot,
										 'lot_dtl_createdby' => $_SESSION['ID_LOGIN'],
										 'lot_dtl_createdate' => $date,
										 'create_type' => 4,
										 'parent_first' => 0,
									);
						//var_dump($datalotdtl);
						$execlotdtl = $con->insert("laundry_lot_number_dtl",$datalotdtl);
						//var_dump($execlotdtl);
				//delete dari keranjang
				$where = array("lot_no" => $lotno);
				$execdel = $con->delete("laundry_qc_keranjang",$where,"qc_keranjang_type = 2");
				

			} 
			//jika rework maka akan mejadi lot W dan lanjut untuk di rework
			else if($value == 3){
				$idgood = $con->idurut("laundry_wo_master_dtl_qc","wo_master_dtl_qc_id");
				$datagood = array(
						'wo_master_dtl_qc_id' => $idgood,
					 	'wo_no' => $wo_no,
					 	'garment_colors' => $colors,
					 	'ex_fty_date' => $exdate,
						'wo_master_dtl_qc_qty' => $_POST['totalrec_3'], 
						'lot_no' => $lotno,
						'wo_master_dtl_qc_createdate' => $date,
						'wo_master_dtl_qc_createdby' => $_SESSION['ID_LOGIN'],
						'wo_master_dtl_qc_status' => 1,
						'wo_master_dtl_proc_id' => $procid['wo_master_dtl_proc_id'],
						'wo_master_dtl_qc_type' => 3,
						'shift_id' => $shift['shift_id'],
						);
				$execgood= $con->insert("laundry_wo_master_dtl_qc", $datagood);

				// membuat lot reject 
					//create no urut lot number ===================
						foreach ($con->select("laundry_lot_number a JOIN laundry_master_type_lot b on a.master_type_lot_id=b.master_type_lot_id","a.*,b.master_type_lot_initial","lot_no = '$lotno'") as $lotnum){}
							//echo "select a.*,b.master_type_lot_initial from laundry_lot_number a join laundry_master_type_lot b on a.master_type_lot_id=b.master_type_lot_id where lot_no = '$_GET[lot]'";

						$sequencecount = $con->selectcount("laundry_lot_number","lot_id","wo_master_dtl_proc_id = '$lotnum[wo_master_dtl_proc_id]'");
						$sequence = $sequencecount+1;
						
						$urutcount = $con->selectcount("laundry_lot_number","lot_id","wo_no = '$lotnum[wo_no]'");
						$urut = $urutcount+1;

						$expmt = explode('/',$wo_no);
						$trimexp6 = trim($expmt[6]);

						$norework ='L'.$expmt[0].$expmt[4].$expmt[5].$trimexp6."W".sprintf('%03s', $urut);
						// end create no urut lot number =============

						$idlot = $con->idurut("laundry_lot_number","lot_id");
						$datalot = array(
										 'lot_id' => $idlot,
										 'lot_no' => $norework, 
										 'wo_no' => $wo_no,
										 'garment_colors' => $colors,
										 'wo_master_dtl_proc_id' => $lotnum['wo_master_dtl_proc_id'], 
										 'master_type_lot_id' => 3,
										 'role_wo_master_id' => 0,
										 'lot_qty' => $qty,
										 'lot_kg' => 0,
										 'lot_shade' => $lotnum['lot_shade'],
										 'lot_createdate' => $date,
										 'lot_createdby' => $_SESSION['ID_LOGIN'],
										 'lot_status' => 1,
										 'create_type' => 5,
										 'lot_type' => 'W',
										 'rework_from_role' => $_POST['rolewoidmod'],
										 'reject_from_lot' => $lotno,
										 'shift_id' => $shift['shift_id'], 
										 
										 
						); 
						$execlot= $con->insert("laundry_lot_number", $datalot);

						$idlotdtl = $con->idurut("laundry_lot_number_dtl","lot_dtl_id");
						$datalotdtl = array(
										 'lot_dtl_id' => $idlotdtl,
										 'lot_id_parent' => $lotnum['lot_id'], 
										 'lot_id' => $idlot,
										 'lot_dtl_createdby' => $_SESSION['ID_LOGIN'],
										 'lot_dtl_createdate' => $date,
										 'create_type' => 5,
										 'parent_first' => 0,
									);
						$execlotdtl = $con->insert("laundry_lot_number_dtl",$datalotdtl);


				//delete dari keranjang
				$where = array("lot_no" => $lotno);
				$execdel = $con->delete("laundry_qc_keranjang",$where,"qc_keranjang_type = 3");
				

			}
		}

		if ($_POST['totalrec_1'] == ''){
			$goodqty = '0';
		} else {
			$goodqty = $_POST['totalrec_1'];
		}

		if ($_POST['totalrec_2'] == ''){
			$rejectqty = '0';
		} else {
			$rejectqty = $_POST['totalrec_2'];
		}

		if ($_POST['totalrec_3'] == ''){
			$reworkqty = '0';
		} else {
			$reworkqty = $_POST['totalrec_3'];
		}

		$idprocess = $con->idurut("laundry_process","process_id");
				$dataprocess = array(
					 'process_id' => $idprocess,
					 'wo_master_dtl_proc_id' => $procid['wo_master_dtl_proc_id'],
					 'lot_type' => $typelot,
					 'master_process_id' => 0,
					 'process_type' => 4,
					 'wo_no' => $wo_no,
					 'garment_colors' => $colors,
					 'lot_no' => $lotno,
					 'master_type_process_id' => 3,
					 'master_type_lot_id' => $mtlot['master_type_lot_id'],
					 'role_wo_master_id' => $procid['role_wo_master_id'],
					 'role_wo_id' => $_POST['rolewoidmod'],
					 'role_dtl_wo_id' => 0,
					 'process_createdate' => $date,
					 'process_createdby' => $_SESSION['ID_LOGIN'],
					 'process_status' => 1,
					 'machine_id' => 0,
					 'role_wo_master_rev' => 0,
					 'process_qty_good' => $goodqty,
					 'process_qty_reject' => $rejectqty,
					 'process_qty_repair' => $reworkqty,
					 'process_qty_total' => $_POST['supertotalrec'],
					 'shift_id' => $shift['shift_id'],
			); 
			$execprocess= $con->insert("laundry_process", $dataprocess);

			//input event Lot
			$idevent = $con->idurut("laundry_lot_event","event_id");
			$dataevent = array(
							 'event_id' => $idevent,
							 'lot_id' => $lotid['lot_id'],
							 'lot_no' => $lotno,
							 'event_type' => 1,
							 'event_status' => 1,
							 'event_createdby' => $_SESSION['ID_LOGIN'],
							 'event_createdate' => $date,
							 'master_type_process_id' => 3,
							 'master_type_lot_id' => $mtlot['master_type_lot_id'],
							 'shift_id' => $shift['shift_id'], 
							 'lot_type' => 2,
							 'master_process_id' => 0,
			); 
			$execevent= $con->insert("laundry_lot_event", $dataevent);
			//end event lot
			echo $nolb."|".$norework."|".$lotno;
	}
	
?>
