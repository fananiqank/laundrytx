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
	foreach($con->select("laundry_master_type_lot","master_type_lot_id","master_type_lot_initial = '$_POST[mastertypelotid]'") as $mtlot){}
//end select

//select_lot_id
	foreach($con->select("laundry_lot_number","lot_id","lot_no = '$_POST[noreceive]'") as $lotid){}
// end select

//data scanner
foreach ($con->select("qrcode_ticketing_master", "wo_no,color,size,inseam,sequence_no,shade,DATE(ex_fty_date) as ex_fty_date", "qrcode_key = '" . $_POST['scanner'] . "'") as $qrcode_key) {
}

$cmt = $qrcode_key['wo_no'];
$colors = $qrcode_key['color'];
$sizes = $qrcode_key['size'];
$inseams = $qrcode_key['inseam'];
$seq_cutting = $qrcode_key['sequence_no'];
$shade = $qrcode_key['shade'];
$exftydate = $qrcode_key['ex_fty_date'];
// end data scanner

	//input scan input setelah Dry
	if ($_POST['scan_type'] == '2'){
		include "cekdatagdp.php";
	} 

	//input scan input QC Inspection
	else if ($_POST['scan_type'] == '3'){
		include "cekdatagdpqc.php";
	}
	
	//input scan Dry confirm
	else if ($_POST['confirm'] == '2'){
		
		//select step id detail QRCODE ================
		foreach ($con->select("qrcode_step_detail","step_id_detail,workcenter_id,workcenter_seq","step_id_detail = '5'") as $stepdtl) {} 

		$expwono = explode('_',$_POST['datawono']);
		$wo_no = $expwono[0];
		$colors = $expwono[1];

		foreach ($_POST['datasave'] as $key => $value) {
			$expval = explode('|',$value);
		 	$inseams = $expval[0];
		 	$sizes = $expval[1];
		 	$seq_cutting = $expval[2];
		 	$qty = $expval[3];

		 	//update table scan menjadi status cnfirm dan no. lot
			$selscanzero = $con->select("laundry_scan_qrcode","*","scan_status = 0 and scan_createdby = '$_SESSION[ID_LOGIN]' and wo_no = '$wo_no' and garment_colors = '$colors' and garment_inseams='$inseams' and garment_sizes='$sizes' and scan_type = 1");
			//echo "select scan_id from laundry_scan_qrcode where scan_status = 0 and scan_createdby = '$_SESSION[ID_LOGIN]' and wo_no = '$wo_no' and garment_colors = '$colors' and garment_inseams='$inseams' and garment_sizes='$sizes' and scan_type = 2";
				foreach ($selscanzero as $scanzero) {
					$datamodify = array(
						'scan_status' => 1,
						'wo_master_dtl_id' => $scanzero['wo_master_dtl_id'],
						'lot_no' => $_POST['noreceive'],
					);
					// var_dump($datamodify);
					$execmodify = $con->update("laundry_scan_qrcode",$datamodify,"scan_id = '$scanzero[scan_id]'");
					
					if ($scanzero['scan_status_garment'] == 1){
						$scangood = 1;
						$scanreject = 0;
						$scanrework = 0;
					} if ($scanzero['scan_status_garment'] == 2){
						$scangood = 0;
						$scanreject = 1; 
						$scanrework = 0;
					} else if ($scanzero['scan_status_garment'] == 3){
						$scangood = 0;
						$scanreject = 0; 
						$scanrework = 1;
					} 

				// input on qrcode_GDP
						$idgdp = $con->idurut("qrcode_gdp","gdp_id");
						$datascan = array(
							 //'gdp_id' => $idgdp,
							 'qrcode_key' => $scanzero['scan_qrcode'],
							 'step_id_detail' => 5,
							 'created_date' => $date,
							 'created_by' => $_SESSION['ID_LOGIN'],
							 'gdp_datetime' => $date,
							 'gdp_goods' => $scangood,
							 'gdp_reject' => $scanreject,
							 'gdp_rework' => $scanrework,
							 'gdp_status' => 1,
							 'workcenter_seq' => $stepdtl['workcenter_seq'],
							 'workcenter_id' => $stepdtl['workcenter_id'],
							 'defect_id' => $scanzero['defect_id'],
						);
						//var_dump($datascan);
						$execscan= $con->insert("qrcode_gdp", $datascan);
				//======================

				} 			
		}

		foreach ($con->select("laundry_role_dtl_wo a join laundry_role_wo b on a.role_wo_id=b.role_wo_id join laundry_role_wo_master c on b.role_wo_master_id=c.role_wo_master_id","c.role_wo_master_id,b.role_wo_id,a.role_dtl_wo_id,c.role_wo_master_rev","a.role_dtl_wo_id = '$_POST[role_dtl_wo_id]'") as $roleid){}
		
		$idprocess = $con->idurut("laundry_process","process_id");
		$dataprocess = array(
					 'process_id' => $idprocess,
					 'wo_master_dtl_proc_id' => $_POST['womasterdtlprocid'],
					 'lot_type' => $_POST['typelot'],
					 'master_process_id' => 42,
					 'process_type' => 4,
					 'wo_no' => $wo_no,
					 'garment_colors' => $colors,
					 'lot_no' => $_POST['noreceive'],
					 'master_type_process_id' => 4,
					 'master_type_lot_id' => $mtlot['master_type_lot_id'],
					 'role_wo_master_id' => $roleid['role_wo_master_id'],
					 'role_wo_id' => $roleid['role_wo_id'],
					 'role_dtl_wo_id' => $_POST['role_dtl_wo_id'],
					 'process_createdate' => $date,
					 'process_createdby' => $_SESSION['ID_LOGIN'],
					 'process_status' => 1,
					 'machine_id' => 0,
					 'process_qty_good' => $_POST['supertotalrec'],
					 'process_qty_reject' => 0,
					 'process_qty_total' => $_POST['supertotalrec'],
					 'role_wo_master_rev' => $roleid['role_wo_master_rev'],
					 'shift_id' => $shift['shift_id'],
		); 
		//var_dump($dataprocess);
		$execprocess= $con->insert("laundry_process", $dataprocess);
		//var_dump($execprocess);

		//input event Lot
			$idevent = $con->idurut("laundry_lot_event","event_id");
			$dataevent = array(
							 'event_id' => $idevent,
							 'lot_id' => $idlot,
							 'lot_no' => $_POST['noreceive'],
							 'event_type' => 1,
							 'event_status' => 1,
							 'event_createdby' => $_SESSION['ID_LOGIN'],
							 'event_createdate' => $date,
							 'master_type_process_id' => 4,
							 'master_type_lot_id' => $mtlot['master_type_lot_id'],
							 'shift_id' => $shift['shift_id'], 
							 'lot_type' => $jenis,
							 'master_process_id' => 42,
			); 
			$execevent= $con->insert("laundry_lot_event", $dataevent);
		//end event lot
	}

	//input scan QC Inspection confirm
	else if ($_POST['confirm'] == '3'){

		foreach ($con->select("qrcode_step_detail","step_id_detail,workcenter_id,workcenter_seq","step_id_detail = '6'") as $stepdtl) {} 

		$wo_no = $_POST['wo_no'];
		$colors = $_POST['colors'];
		$ex_fty_date = $_POST['ex_fty_date'];

		//untuk input lot scrap dan lot rework
		foreach ($_POST['datarec'] as $key => $value) {
			
			//create no urut lot number ===================
			foreach ($con->select("laundry_wo_master_dtl_proc","*","wo_no = '$wo_no' and garment_colors = '$colors'") as $lotdtl){}

	//jika memiliki reject QC 
			if($value == '2'){
					$sequencecount = $con->selectcount("laundry_lot_number","lot_id","wo_master_dtl_proc_id = '$lotdtl[wo_master_dtl_proc_id]'");
					$sequence = $sequencecount+1;
					
					$urutcount = $con->selectcount("laundry_lot_number","lot_id","wo_no = '$lotdtl[wo_no]'");
					$urut = $urutcount+1;

					$expmt = explode('/',$wo_no);
					$trimexp6 = trim($expmt[6]);
					$stype = "S";

					$nolb ='L'.$expmt[0].$expmt[4].$expmt[5].$trimexp6.$stype.sprintf('%03s', $urut);

					$idlot = $con->idurut("laundry_lot_number","lot_id");
					$datalot = array(
									 'lot_id' => $idlot,
									 'lot_no' => $nolb, 
									 'wo_no' => $wo_no,
									 'garment_colors' => $colors,
									 'wo_master_dtl_proc_id' => $_POST['womasterdtlprocid'], 
									 'master_type_lot_id' => 9,
									 'role_wo_master_id' => $_POST['role_wo_master_id'],
									 'lot_qty' => $_POST['totalrec_2'],
									 'lot_kg' => 0,
									 'lot_createdate' => $date,
									 'lot_createdby' => $_SESSION['ID_LOGIN'],
									 'lot_status' => 0,
									 'create_type' => 4,
									 'lot_type' => $stype,
									 'reject_from_role' => $_POST['role_wo_id'],
									 'reject_from_lot' => $_POST['noreceive'],
									 'user_code' => $_POST['usercode'],
									 
					); 
					//var_dump($datalot);
					$execlot= $con->insert("laundry_lot_number", $datalot);

					//simpan ke QC untuk qty reject
						$idgood = $con->idurut("laundry_wo_master_dtl_qc","wo_master_dtl_qc_id");
						$datagood = array(
									'wo_master_dtl_qc_id' => $idgood,
								 	'wo_no' => $wo_no,
								 	'garment_colors' => $colors,
									'wo_master_dtl_qc_qty' => $_POST['totalrec_2'], 
									'lot_no' => $_POST['noreceive'],
									'wo_master_dtl_qc_createdate' => $date,
									'wo_master_dtl_qc_createdby' => $_SESSION['ID_LOGIN'],
									'wo_master_dtl_qc_status' => 1,
									'wo_master_dtl_proc_id' => $_POST['womasterdtlprocid'],
									'wo_master_dtl_qc_type' => 2,
									'shift_id' => $shift['shift_id'],,
									'ex_fty_date' => $ex_fty_date,
									);
						$execgood= $con->insert("laundry_wo_master_dtl_qc", $datagood);
					// end simpan ke QC untuk qty reject

					// confirm scan input QC FINAL
					foreach ($_POST['datasave'] as $key2 => $value2) {
						$expval = explode('|',$value2);
					 	$inseams = $expval[0];
					 	$sizes = $expval[1];
					 	$seq_cutting = $expval[2];
					 	$qty = $expval[3];
					
					 	//update table scan menjadi status cnfirm dan no. lot
						$selscanzero = $con->select("laundry_scan_qrcode","*","scan_status = 0 and scan_createdby = '$_SESSION[ID_LOGIN]' and wo_no = '$wo_no' and garment_colors = '$colors' and garment_inseams='$inseams' and garment_sizes='$sizes' and scan_type = 3 and DATE(ex_fty_date)= '$ex_fty_date' and scan_status_garment = '2'");
						//echo "select scan_id from laundry_scan_qrcode where scan_status = 0 and scan_createdby = '$_SESSION[ID_LOGIN]' and wo_no = '$wo_no' and garment_colors = '$colors' and garment_inseams='$inseams' and garment_sizes='$sizes' and scan_type = 2";
							foreach ($selscanzero as $scanzero) {
								$datamodify = array(
									'scan_status' => 1,
									// 'wo_master_dtl_id' => 0,
									// 'lot_no' => $nolb,
									// 'rework_seq' => $_POST['reworkseq'],
								);
								$execmodify = $con->update("laundry_scan_qrcode",$datamodify,"scan_id = '$scanzero[scan_id]'");
								
								if ($scanzero['scan_status_garment'] == 1){
									$scangood = 1;
									$scanreject = 0;
									$scanrework = 0;
								} if ($scanzero['scan_status_garment'] == 2){
									$scangood = 0;
									$scanreject = 1; 
									$scanrework = 0;
								} else if ($scanzero['scan_status_garment'] == 3){
									$scangood = 0;
									$scanreject = 0; 
									$scanrework = 1;
								} 

							// input on qrcode_GDP
									$idgdp = $con->idurut("qrcode_gdp","gdp_id");
									$datascan = array(
										 //'gdp_id' => $idgdp,
										 'qrcode_key' => $scanzero['scan_qrcode'],
										 'step_id_detail' => 6,
										 'created_date' => $date,
										 'created_by' => $_SESSION['ID_LOGIN'],
										 'gdp_datetime' => $date,
										 'gdp_goods' => $scangood,
										 'gdp_reject' => $scanreject,
										 'gdp_rework' => $scanrework,
										 'gdp_status' => 1,
										 'workcenter_seq' => $stepdtl['workcenter_seq'],
										 'workcenter_id' => $stepdtl['workcenter_id'],
										 'defect_id' => 0,
										 'defect_panel' => $_POST['panel_type'],
										 'defect_type' => $_POST['reject_type'],
										 'line_id' => 0,
										 'floor_ipaddress' => $_SESSION['IPAD'],
										 'factory_id' => $_POST['factory'],
										 'user_code' => $_POST['usercode'],
										 'login_name' => $_SESSION['USER_NAME'],
									);
									//var_dump($datascan);
									$execscan= $con->insert("qrcode_gdp", $datascan);
							//======================

							} 			
					}
			} 
	//jika memiliki rework
			else if ($value == '3') {
					$sequencecount = $con->selectcount("laundry_lot_number","lot_id","wo_master_dtl_proc_id = '$lotdtl[wo_master_dtl_proc_id]'");
					$sequence = $sequencecount+1;
					
					$urutcount = $con->selectcount("laundry_lot_number","lot_id","wo_no = '$lotdtl[wo_no]'");
					$urut = $urutcount+1;

					$expmt = explode('/',$wo_no);
					$trimexp6 = trim($expmt[6]);
					$stype = "W";

					//$nolb ='L'.$expmt[0].$expmt[4].$expmt[5].$trimexp6.$stype.sprintf('%03s', $urut);
					$norework ='L'.$expmt[0].$expmt[4].$expmt[5].$trimexp6.$stype.sprintf('%03s', $urut);
	// ===================================
					$idlot = $con->idurut("laundry_lot_number","lot_id");
					$datalot = array(
									 'lot_id' => $idlot,
									 'lot_no' => $norework, 
									 'wo_no' => $wo_no,
									 'garment_colors' => $colors,
									 'wo_master_dtl_proc_id' => $_POST['womasterdtlprocid'], 
									 'master_type_lot_id' => 9,
									 'role_wo_master_id' => $_POST['role_wo_master_id'],
									 'lot_qty' => $_POST['totalrec_3'],
									 'lot_kg' => 0,
									 'lot_createdate' => $date,
									 'lot_createdby' => $_SESSION['ID_LOGIN'],
									 'lot_status' => 1,
									 'create_type' => 4,
									 'lot_type' => $stype,
									 'rework_from_role' => $_POST['role_wo_id'],
									 'reject_from_lot' => $_POST['noreceive'],
									 
					); 
					$execlot= $con->insert("laundry_lot_number", $datalot);

					//simpan ke QC untuk qty rework
						$idgood = $con->idurut("laundry_wo_master_dtl_qc","wo_master_dtl_qc_id");
						$datagood = array(
									'wo_master_dtl_qc_id' => $idgood,
								 	'wo_no' => $wo_no,
								 	'garment_colors' => $colors,
									'wo_master_dtl_qc_qty' => $_POST['totalrec_3'], 
									'lot_no' => $_POST['noreceive'],
									'wo_master_dtl_qc_createdate' => $date,
									'wo_master_dtl_qc_createdby' => $_SESSION['ID_LOGIN'],
									'wo_master_dtl_qc_status' => 1,
									'wo_master_dtl_proc_id' => $_POST['womasterdtlprocid'],
									'wo_master_dtl_qc_type' => 3,
									);
						$execgood= $con->insert("laundry_wo_master_dtl_qc", $datagood);
					// end simpan ke QC untuk qty rework

					// confirm scan input QC FINAL
					foreach ($_POST['datasave'] as $key2 => $value2) {
						$expval = explode('|',$value2);
					 	$inseams = $expval[0];
					 	$sizes = $expval[1];
					 	$seq_cutting = $expval[2];
					 	$qty = $expval[3];	

					 	//update table scan menjadi status cnfirm dan no. lot
						$selscanzero = $con->select("laundry_scan_qrcode","*","scan_status = 0 and scan_createdby = '$_SESSION[ID_LOGIN]' and wo_no = '$wo_no' and garment_colors = '$colors' and garment_inseams='$inseams' and garment_sizes='$sizes' and scan_type = 3 and scan_status_garment = '3' and DATE(ex_fty_date)= '$ex_fty_date'");
						//echo "select scan_id from laundry_scan_qrcode where scan_status = 0 and scan_createdby = '$_SESSION[ID_LOGIN]' and wo_no = '$wo_no' and garment_colors = '$colors' and garment_inseams='$inseams' and garment_sizes='$sizes' and scan_type = 2";
							foreach ($selscanzero as $scanzero) {
								$datamodify = array(
									'scan_status' => 1,
									// 'wo_master_dtl_id' => 0,
									// 'lot_no' => $nolb,
									// 'rework_seq' => $_POST['reworkseq'],
								);
								//var_dump($datamodify);
								$execmodify = $con->update("laundry_scan_qrcode",$datamodify,"scan_id = '$scanzero[scan_id]'");
								
								if ($scanzero['scan_status_garment'] == 1){
									$scangood = 1;
									$scanreject = 0;
									$scanrework = 0;
								} if ($scanzero['scan_status_garment'] == 2){
									$scangood = 0;
									$scanreject = 1; 
									$scanrework = 0;
								} else if ($scanzero['scan_status_garment'] == 3){
									$scangood = 0;
									$scanreject = 0; 
									$scanrework = 1;
								} 

							// input on qrcode_GDP
									$idgdp = $con->idurut("qrcode_gdp","gdp_id");
									$datascan = array(
										 //'gdp_id' => $idgdp,
										 'qrcode_key' => $scanzero['scan_qrcode'],
										 'step_id_detail' => 6,
										 'created_date' => $date,
										 'created_by' => $_SESSION['ID_LOGIN'],
										 'gdp_datetime' => $date,
										 'gdp_goods' => $scangood,
										 'gdp_reject' => $scanreject,
										 'gdp_rework' => $scanrework,
										 'gdp_status' => 1,
										 'workcenter_seq' => $stepdtl['workcenter_seq'],
										 'workcenter_id' => $stepdtl['workcenter_id'],
										 'defect_id' => 0,
										 'defect_panel' => $_POST['panel_type'],
										 'defect_type' => $_POST['reject_type'],
										 'line_id' => 0,
										 'floor_ipaddress' => $_SESSION['IPAD'],
										 'factory_id' => $_POST['factory'],
										 'user_code' => $_POST['usercode'],
										 'login_name' => $_SESSION['USER_NAME'],
									);
									//var_dump($datascan);
									$execscan= $con->insert("qrcode_gdp", $datascan);
							//======================

							} 			
					}
		 	} 
		 	//jika qc good
		 	else if ($value == '1'){
		 		if($_POST['createtype'] == '2'){
		 			$rework = '1';
		 		} else {
		 			$rework = '0';
		 		}

		 		//simpan ke QC untuk qty good
					$idgood = $con->idurut("laundry_wo_master_dtl_qc","wo_master_dtl_qc_id");
					$datagood = array(
								'wo_master_dtl_qc_id' => $idgood,
							 	'wo_no' => $wo_no,
							 	'garment_colors' => $colors,
								'wo_master_dtl_qc_qty' => $_POST['totalrec_1'], 
								'lot_no' => $_POST['noreceive'],
								'wo_master_dtl_qc_createdate' => $date,
								'wo_master_dtl_qc_createdby' => $_SESSION['ID_LOGIN'],
								'wo_master_dtl_qc_status' => 1,
								'wo_master_dtl_proc_id' => $_POST['womasterdtlprocid'],
								'wo_master_dtl_qc_type' => 1,
								);
					$execgood= $con->insert("laundry_wo_master_dtl_qc", $datagood);
				// end simpan ke QC untuk qty good

		 		// confirm scan input QC FINAL
					foreach ($_POST['datasave'] as $key2 => $value2) {
						$expval = explode('|',$value2);
					 	$inseams = $expval[0];
					 	$sizes = $expval[1];
					 	$seq_cutting = $expval[2];
					 	$qty = $expval[3];

					 	//update table scan menjadi status cnfirm dan no. lot
						$selscanzero = $con->select("laundry_scan_qrcode","*","scan_status = 0 and scan_createdby = '$_SESSION[ID_LOGIN]' and wo_no = '$wo_no' and garment_colors = '$colors' and garment_inseams='$inseams' and garment_sizes='$sizes' and scan_type = 3 and DATE(ex_fty_date)= '$ex_fty_date' and scan_status_garment = '1'");
						//	echo "select * from laundry_scan_qrcode where scan_status = 0 and scan_createdby = '$_SESSION[ID_LOGIN]' and wo_no = '$wo_no' and garment_colors = '$colors' and garment_inseams='$inseams' and garment_sizes='$sizes' and scan_type = 3 and scan_status_garment = '1'";
							foreach ($selscanzero as $scanzero) {
								$datamodify = array(
									'scan_status' => 1,
									//'wo_master_dtl_id' => 0,
									//'lot_no' => $_POST['noreceive'],
									//'rework_seq' => $_POST['reworkseq'],
								);
								
								$execmodify = $con->update("laundry_scan_qrcode",$datamodify,"scan_id = '$scanzero[scan_id]'");
								
								if ($scanzero['scan_status_garment'] == 1){
									$scangood = 1;
									$scanreject = 0;
									$scanrework = $rework;
								} if ($scanzero['scan_status_garment'] == 2){
									$scangood = 0;
									$scanreject = 1; 
									$scanrework = $rework;
								} else if ($scanzero['scan_status_garment'] == 3){
									$scangood = 0;
									$scanreject = 0; 
									$scanrework = 1;
								} 

							// input on qrcode_GDP
									$idgdp = $con->idurut("qrcode_gdp","gdp_id");
									$datascan = array(
										 //'gdp_id' => $idgdp,
										 'qrcode_key' => $scanzero['scan_qrcode'],
										 'step_id_detail' => 6,
										 'created_date' => $date,
										 'created_by' => $_SESSION['ID_LOGIN'],
										 'gdp_datetime' => $date,
										 'gdp_goods' => $scangood,
										 'gdp_reject' => $scanreject,
										 'gdp_rework' => $scanrework,
										 'gdp_status' => 1,
										 'workcenter_seq' => $stepdtl['workcenter_seq'],
										 'workcenter_id' => $stepdtl['workcenter_id'],
										 'defect_id' => 0,
										 'line_id' => 0,
										 'floor_ipaddress' => $_SESSION['IPAD'],
										 'factory_id' => $_POST['factory'],
										 'user_code' => $_POST['usercode'],
										 'login_name' => $_SESSION['USER_NAME'],
									);
									//var_dump($datascan);
									$execscan= $con->insert("qrcode_gdp", $datascan);
									//var_dump($execscan);
							//======================

							} 			
					}
		 	}	
		} 
		//mendapatkan roleid
		foreach ($con->select( "laundry_role_wo b join laundry_role_wo_master c on 
								b.role_wo_master_id=c.role_wo_master_id",
							   "c.role_wo_master_id,b.role_wo_id,c.role_wo_master_rev",
							   "b.role_wo_id = '$_POST[role_wo_id]'") as $roleid){}
		
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
		//simpan ke laundry_process
			$idprocess = $con->idurut("laundry_process","process_id");
			$dataprocess = array(
								 'process_id' => $idprocess,
								 'wo_master_dtl_proc_id' => $_POST['womasterdtlprocid'],
								 'lot_type' => $_POST['typelot'],
								 'master_process_id' => 0,
								 'process_type' => 4,
								 'wo_no' => $wo_no,
								 'garment_colors' => $colors,
								 'lot_no' => $_POST['noreceive'],
								 'master_type_process_id' => 3,
								 'master_type_lot_id' => $mtlot['master_type_lot_id'],
								 'role_wo_master_id' => $_POST['role_wo_master_id'],
								 'role_wo_id' => $_POST['role_wo_id'],
								 'role_dtl_wo_id' => 0,
								 'process_createdate' => $date,
								 'process_createdby' => $_SESSION['ID_LOGIN'],
								 'process_status' => 1,
								 'machine_id' => 0,
								 'process_qty_good' => $goodqty,
								 'process_qty_reject' => $rejectqty,
								 'process_qty_repair' => $reworkqty,
								 'process_qty_total' => $_POST['supertotalrec'],
								 'role_wo_master_rev' => $roleid['role_wo_master_rev'],
								 'shift_id' => $shift['shift_id'],
								 'user_code' => $_POST['usercode'],
			); 
			//var_dump($dataprocess);
			$execprocess= $con->insert("laundry_process", $dataprocess);
			//var_dump($execprocess);
		// end simpan laundry_process
		
		//update child menjadi 1 
			$datachild2 = array(
							 'role_child_status' => 1,
			); 
			$execchild2= $con->update("laundry_role_child", $datachild2,"role_child_id = '$_POST[role_child_id]'");
		//
		
		//input event Lot
			$idevent = $con->idurut("laundry_lot_event","event_id");
			$dataevent = array(
							 'event_id' => $idevent,
							 'lot_id' => $lotid['lot_id'],
							 'lot_no' => $_POST['noreceive'],
							 'event_type' => 1,
							 'event_status' => 1,
							 'event_createdby' => $_SESSION['ID_LOGIN'],
							 'event_createdate' => $date,
							 'master_type_process_id' => 3,
							 'master_type_lot_id' => $mtlot['master_type_lot_id'],
							 'shift_id' => $shift['shift_id'], 
							 'lot_type' => 2,
							 'master_process_id' => 0,
							 'user_code' => $_POST['usercode'],
			); 
			$execevent= $con->insert("laundry_lot_event", $dataevent);
		//end event lot
		//	echo $nolb."|".$norework."|".$_POST['noreceive'] ;

	}
