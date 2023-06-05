<?php 
session_start();
include "../../../funlibs.php";
$con = new Database;
	//error_reporting(0);
	$date = date("Y-m-d H:i:s");
	$date2 = date("Y-m-d_G-i-s");
	$datetok = date('dmy');

	$expscan = explode('_',$_POST['scanner']);
		$cmt = $expscan[0];
		$colors = $expscan[1];
		$sizes = $expscan[2];
		$inseams = $expscan[3];
		$seq_cutting = $expscan[4];
		$shade = "-";

//shift 
	foreach($con->select("laundry_master_shift","shift_id,shift_time_start,shift_time_end,shift_status","TO_CHAR(now(), 'HH24:MI') between TO_CHAR(shift_time_start, 'HH24:MI') and TO_CHAR(shift_time_end, 'HH24:MI') and shift_status = 1") as $shift){}
//end shift

//select master_type_lot_id
	foreach($con->select("laundry_master_type_lot","master_type_lot_id","master_type_lot_initial = '$_POST[typelot]'") as $mtlot){}
//end select

//select_lot_id
	foreach($con->select("laundry_lot_number","lot_id","lot_no = '$_POST[nolot]'") as $lotid){}
// end select

//select step id detail QRCODE ================
foreach ($con->select("qrcode_step_detail","step_id_detail,workcenter_id,workcenter_seq","step_id_detail = '12'") as $stepdtl) {} 
//=============================================

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

// memulai Process
	//input scan despatch not confirm
	if ($_POST['scan_type'] == '4'){
		//melakukan pengecekan pada database QRCODE
		include "cekdatagdp.php";
		
	} 

	//input scan Receive confirm
	else if ($_POST['confirm'] == '4'){
		$expwono = explode('_',$_POST['datawono']);
		$wo_no = $expwono[0];
		$colors = $expwono[1];

		// confirm scan input desp FINAL
					foreach ($_POST['datasave'] as $key2 => $value2) {
						$expval = explode('|',$value2);
					 	$inseams = $expval[0];
					 	$sizes = $expval[1];
					 	$seq_cutting = $expval[2];
					 	$qty = $expval[3];

						//simpan ke desp untuk qty good
						$idgood = $con->idurut("laundry_wo_master_dtl_despatch","wo_master_dtl_desp_id");
						$datagood = array(
								'wo_master_dtl_desp_id' => $idgood,
							 	'wo_no' => $wo_no,
							 	'garment_colors' => $colors,
							 	'garment_inseams' => $inseams,
							 	'garment_sizes' => $sizes,
								'wo_master_dtl_desp_qty' => $qty, 
								'lot_no' => $_POST['nolot'],
								'wo_master_dtl_desp_createdate' => $date,
								'wo_master_dtl_desp_createdby' => $_SESSION['ID_LOGIN'],
								'wo_master_dtl_desp_status' => 1,
								'wo_master_dtl_proc_id' => $_POST['womasterdtlprocid'],
								);
						//var_dump($datagood);
						$execgood= $con->insert("laundry_wo_master_dtl_despatch", $datagood);
						// end simpan ke desp untuk qty good

						//update done lot number
							$datadone = array(
								'lot_status' => 2,
								);
						
							$execdone= $con->update("laundry_lot_number", $datadone,"lot_id = '$lotid[lot_id]'");
						//end update done lot numnber
						

					 	//update table scan menjadi status cnfirm dan no. lot
						$selscanzero = $con->select("laundry_scan_qrcode","*","scan_status = 0 and scan_createdby = '$_SESSION[ID_LOGIN]' and wo_no = '$wo_no' and garment_colors = '$colors' and garment_inseams='$inseams' and garment_sizes='$sizes' and scan_type = 4 and scan_status_garment = '1' and lot_no = '$_POST[nolot]'");
							
							foreach ($selscanzero as $scanzero) {
								$datamodify = array(
									'scan_status' => 1,
									// 'wo_master_dtl_id' => 0,
									// 'lot_no' => $_POST['nolot'],
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
										 'step_id_detail' => 12,
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
									);
									//var_dump($datascan);
									$execscan= $con->insert("qrcode_gdp", $datascan);
									//var_dump($execscan);
							//======================

							} 			
					}
			foreach ($con->select(
							"laundry_role_wo b join laundry_role_wo_master c on b.role_wo_master_id=c.role_wo_master_id",
							"c.role_wo_master_id,b.role_wo_id,c.role_wo_master_rev",
							"b.role_wo_id = '$_POST[role_wo_id]'") as $roleid){}
						
			$idprocess = $con->idurut("laundry_process","process_id");
			$dataprocess = array(
						 'process_id' => $idprocess,
						 'wo_master_dtl_proc_id' => $_POST['womasterdtlprocid'],
						 'lot_type' => 2,
						 'master_process_id' => 0,
			     		 'process_type' => 4,
						 'wo_no' => $wo_no,
						 'garment_colors' => $colors,
				    	 'lot_no' => $_POST['nolot'],
						 'master_type_process_id' => 6,
						 'master_type_lot_id' => $mtlot['master_type_lot_id'],
						 'role_wo_master_id' => $roleid['role_wo_master_id'],
						 'role_wo_id' => $roleid['role_wo_id'],
						 'role_dtl_wo_id' => 0,
						 'process_createdate' => $date,
						 'process_createdby' => $_SESSION['ID_LOGIN'],
						 'process_status' => 1,
						 'machine_id' => 0,
						 'process_qty_good' => $_POST['totalrec'],
						 'process_qty_reject' => 0,
						 'process_qty_total' => $_POST['totalrec'],
						 'role_wo_master_rev' => $roleid['role_wo_master_rev'],
						 'shift_id' => $shift['shift_id'],
			); 
			$execprocess= $con->insert("laundry_process", $dataprocess);

			//input event Lot
				$idevent = $con->idurut("laundry_lot_event","event_id");
				$dataevent = array(
								 'event_id' => $idevent,
								 'lot_id' => $lotid['lot_id'],
								 'lot_no' => $_POST['nolot'],
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
