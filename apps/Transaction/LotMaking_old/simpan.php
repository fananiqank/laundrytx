<?php 
session_start();
include "../../../funlibs.php";
$con = new Database;
//error_reporting(0);
$date = date("Y-m-d H:i:s");
$date2 = date("Y-m-d_G-i-s");
$tabel = "laundry_lot_number";

if($_SESSION['ID_LOGIN'] == ''){
	$userlogin = '831';
} else {
	$userlogin = $_SESSION['ID_LOGIN'];
}

//shift 
foreach($con->select("laundry_master_shift","shift_id,shift_time_start,shift_time_end,shift_status","TO_CHAR(now(), 'HH24:MI') between TO_CHAR(shift_time_start, 'HH24:MI') and TO_CHAR(shift_time_end, 'HH24:MI') and shift_status = 1") as $shift){}
//end shift

//input lot number dari Lot making
if ($_POST['jenis'] == 'input'){
	
	//create lot no
	include "tampillotnumber2.php";

	//pengecekan lot number 
	$selceklot = $con->selectcount("laundry_lot_number","lot_no","lot_no = '$nolb'");
	//end pengecekan lot number

	if($selceklot == 0) {
	
		$exptype = explode('_', $_POST['type']);
		$types = $exptype[0];
		$initial = $exptype[1];
		
		if ($_POST['gettype'] == 1) {
			$createlot = 1;
		} else if ($_POST['gettype'] == 2) {
			$createlot = 6;
		}
		
		$idlot = $con->idurut("laundry_lot_number","lot_id");
		$datalot = array(
						 'lot_id' => $idlot,
						 'lot_no' => $nolb, 
						 'wo_no' => $_POST['showcmt'],
						 'garment_colors' => $_POST['showcolors'],
						 'wo_master_dtl_proc_id' => $_POST['idcmt'], 
						 'master_type_lot_id' => $types,
						 'role_wo_master_id' => $_POST['rolecmt'],
						 'lot_qty' => $_POST['pcs'],
						 'lot_kg' => $_POST['kg'],
						 'lot_shade' => $_POST['shade'],
						 'lot_createdate' => $date,
						 'lot_createdby' => $userlogin,
						 'lot_status' => 3,
						 'lot_qty_good_upd' => $_POST['pcs'],
						 'create_type' => $createlot,
						 'lot_type' => $initial,
						 'shift_id' => $shift['shift_id'],
						 'user_code' => $_POST['usercode_lot'],
						 'role_wo_name_seq' => $_POST['seqlot'],
						 'lot_no_uniq' => $urut
		); 
		//var_dump($datalot);
		$execlot= $con->insert($tabel, $datalot);
		//var_dump($execlot);

		// input data role per lot receive ke laundry role child
		if($_POST['nextseqlot'] != 'A'){
			$whereseqlot = "and role_wo_seq <= (select role_wo_seq from laundry_role_wo where role_wo_master_id = '".$_POST['rolecmt']."' and master_type_process_id = 2 and role_wo_name_seq = '$_POST[nextseqlot]' and role_wo_status != 2)";
			$typelotseq = 2;
			$isinextlot = $_POST['nextseqlot'];
		} else {
			$whereseqlot = "";
			$typelotseq = 5;
			$isinextlot = $_POST['seqlot'];
		}

		//insert role child master (fungsi utk master role per lot)
		$datachildmaster = array(
					'lot_no' => $nolb,
					'role_wo_master_id'  => $_POST['rolecmt'],
					'lot_status' => 1,
					'lot_type' => $typelotseq,
					'lot_id' => $idlot,
					'role_wo_name_seq' => $isinextlot
				);
		//var_dump($datachildmaster);
		$execchildmaster = $con->insert("laundry_role_child_master", $datachildmaster);
		
		//=========================================================	

		//input data role child
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
										 "role_wo_master_id = '".$_POST['rolecmt']."' and a.role_wo_status != 2
										  and role_wo_seq > (select role_wo_seq from laundry_role_wo where role_wo_master_id = '".$_POST['rolecmt']."' and master_type_process_id = 2 and role_wo_name_seq = '$_POST[seqlot]' and role_wo_status != 2)
										  $whereseqlot",
										 "role_wo_seq");
			
			foreach ($selrolechild as $child) {
				$idchild = $con->idurut("laundry_role_child","role_child_id");
				$datachild = array(
							'role_child_id' => $idchild,
							'role_wo_master_id'  => $_POST['rolecmt'],
							'role_wo_id'  => $child['role_wo_id'],
							'role_dtl_wo_id'  => $child['role_dtl_wo_id'],
							'lot_type'  => $typelotseq,
							'role_child_status'  => 0,
							'role_child_createdate'  => $date,
							'role_child_createdby'  => $userlogin,
							'lot_no'  => $nolb,
							'lot_id'  => $idlot,
							'role_wo_seq' => $child['role_wo_seq'],
							'role_dtl_wo_seq' => $child['role_dtl_wo_seq'],
				);
				
				$execchild = $con->insert("laundry_role_child", $datachild);
			}
		// end input data role per lot receive ke laundry role child

		// input data ke log lot
			//select query mendapatkan lot receive secara FIFO
					$sisa_input = "";
					if ($_POST['typework'] == '1') {
						if($_POST['seqlot'] == 1) {
								$fifotable = "(SELECT * FROM (SELECT wo_no, rec_no, rec_qty
											     , CASE WHEN last_sum >= lot_qty THEN rec_qty
											            ELSE GREATEST (last_sum + rec_qty - lot_qty, 0) END AS BALANCE
											     , REFERENCE_DATE
											     , CASE WHEN last_sum >= lot_qty THEN 0
											            ELSE LEAST (rec_qty, lot_qty - last_sum) END AS DEPOSITS_BREAKDOWN
											FROM (
											   SELECT a.*
											        , COALESCE(sum(a.rec_qty) OVER (
											                         PARTITION BY wo_no ORDER BY a.REFERENCE_DATE
											                         ROWS BETWEEN UNBOUNDED PRECEDING
											                                          AND 1 PRECEDING), 0) AS last_sum
											        , COALESCE(b.lot_qty, 0) AS lot_qty
											   FROM   (select rec_no, 
														string_agg(distinct wo_no::TEXT,',') as wo_no , 
														sum(rec_qty) as rec_qty, 
														string_agg(distinct garment_colors::TEXT,',') as garment_colors, 
														string_agg(distinct date(ex_fty_date)::TEXT,',') as ex_fty_date,
														string_agg(distinct rec_createdate::TEXT,',') as REFERENCE_DATE
											from 
												(select * from (select row_number() OVER (PARTITION BY aa.wo_no ORDER BY aa.wo_no, rec_id) as serial,
												rec_id, rec_no, aa.wo_no, aa.garment_colors,bb.ex_fty_date, rec_qty, 1, rec_createdate
												from laundry_receive aa JOIN laundry_wo_master_dtl_proc bb on aa.wo_master_dtl_proc_id=bb.wo_master_dtl_proc_id where aa.rec_break_status = 0) a 
											union
												(select 0 as serial, a.log_id, a.log_lot_ref, a.wo_no, a.garment_colors, a.ex_fty_date, a.log_lot_qty*-1 as rec_qty, 2 ,b.log_createdate
												 from laundry_log a JOIN laundry_log b ON a.log_lot_ref=b.log_lot_tr
												 where a.log_lot_ref in (select log_lot_tr from laundry_log where log_lot_ref='0'))) a 
											where wo_no = trim('$_POST[showcmt]') and garment_colors = '$_POST[showcolors]' and DATE(ex_fty_date) = '$_POST[showexftydateasli]'
											group by rec_no having sum(rec_qty) > 0) a
											   LEFT  JOIN (select trim('$_POST[showcmt]')::TEXT as wo_no , $_POST[pcs] as lot_qty) b USING (wo_no)
											   ) sub)a where DEPOSITS_BREAKDOWN > 0) as asi";
						} else {
							    $seqlotfifo = $_POST['seqlot']-1;
								$fifotable = "(SELECT A.*,Z.log_lot_receive FROM (SELECT wo_no, rec_no, rec_qty
											     , CASE WHEN last_sum >= lot_qty THEN rec_qty
											            ELSE GREATEST (last_sum + rec_qty - lot_qty, 0) END AS BALANCE
											     , REFERENCE_DATE
											     , CASE WHEN last_sum >= lot_qty THEN 0
											            ELSE LEAST (rec_qty, lot_qty - last_sum) END AS DEPOSITS_BREAKDOWN
											FROM (
											   SELECT a.*
											        , COALESCE(sum(a.rec_qty) OVER (
											                         PARTITION BY wo_no ORDER BY a.REFERENCE_DATE
											                         ROWS BETWEEN UNBOUNDED PRECEDING
											                                          AND 1 PRECEDING), 0) AS last_sum
											        , COALESCE(b.lot_qty, 0) AS lot_qty
											   FROM   (select rec_no, 
														string_agg(distinct wo_no::TEXT,',') as wo_no , 
														sum(rec_qty) as rec_qty, 
														string_agg(distinct garment_colors::TEXT,',') as garment_colors, 
														string_agg(distinct date(ex_fty_date)::TEXT,',') as ex_fty_date,
														string_agg(distinct rec_createdate::TEXT,',') as REFERENCE_DATE
											from 
												(select * from (select row_number() OVER (PARTITION BY aa.wo_no ORDER BY aa.wo_no, lot_id) as serial,
												lot_id as rec_id, lot_no as rec_no, aa.wo_no, aa.garment_colors,bb.ex_fty_date, lot_qty as rec_qty, 1, lot_createdate as rec_createdate
												from laundry_lot_number aa JOIN laundry_wo_master_dtl_proc bb on aa.wo_master_dtl_proc_id=bb.wo_master_dtl_proc_id where lot_type != 'M' AND lot_type != 'W' AND lot_type != 'S' and role_wo_name_seq = '$seqlotfifo' AND lot_status = 0) a 
												
											union
												(select 0 as serial, a.log_id, a.log_lot_ref, a.wo_no, a.garment_colors, a.ex_fty_date, a.log_lot_qty*-1 as rec_qty, 2 ,b.log_createdate
												 from laundry_log a JOIN laundry_log b ON a.log_lot_ref=b.log_lot_tr
												 where b.role_wo_name_seq = '$seqlotfifo' and a.log_lot_ref in (select log_lot_tr from laundry_log where log_lot_ref != '0'))) a 
											where wo_no = trim('$_POST[showcmt]') and garment_colors = '$_POST[showcolors]' and DATE(ex_fty_date) = '$_POST[showexftydateasli]'
											group by rec_no having sum(rec_qty) > 0) a
											   LEFT  JOIN (select trim('$_POST[showcmt]')::TEXT as wo_no , $_POST[pcs] as lot_qty) b USING (wo_no)
											   ) sub)a 
											   JOIN (select log_lot_tr,log_lot_receive from laundry_log where lotmaking_type = 1 GROUP BY log_lot_tr,log_lot_receive) as Z ON A.rec_no = Z.log_lot_tr
											   where DEPOSITS_BREAKDOWN > 0) as asi";
						}
					
					}  else {
						if($_POST['seqlot'] == 1) {
							$fifotable = "(SELECT * FROM (SELECT wo_no, rec_no, rec_qty
											     , CASE WHEN last_sum >= lot_qty THEN rec_qty
											            ELSE GREATEST (last_sum + rec_qty - lot_qty, 0) END AS BALANCE
											     , REFERENCE_DATE
											     , CASE WHEN last_sum >= lot_qty THEN 0
											            ELSE LEAST (rec_qty, lot_qty - last_sum) END AS DEPOSITS_BREAKDOWN
											FROM (
											   SELECT a.*
											        , COALESCE(sum(a.rec_qty) OVER (
											                         PARTITION BY wo_no ORDER BY a.REFERENCE_DATE
											                         ROWS BETWEEN UNBOUNDED PRECEDING
											                                          AND 1 PRECEDING), 0) AS last_sum
											        , COALESCE(b.lot_qty, 0) AS lot_qty
											   FROM   (select rec_no, 
														string_agg(distinct wo_no::TEXT,',') as wo_no , 
														sum(rec_qty) as rec_qty, 
														string_agg(distinct garment_colors::TEXT,',') as garment_colors, 
														string_agg(distinct date(ex_fty_date)::TEXT,',') as ex_fty_date,
														string_agg(distinct rec_createdate::TEXT,',') as REFERENCE_DATE
											from 
												(select * from (select row_number() OVER (PARTITION BY aa.wo_no ORDER BY aa.wo_no, lot_id) as serial,
												lot_id as rec_id, lot_no as rec_no, aa.wo_no, aa.garment_colors,bb.ex_fty_date, lot_qty as rec_qty, 1, lot_createdate as rec_createdate
												from laundry_lot_number aa JOIN laundry_wo_master_dtl_proc bb on aa.wo_master_dtl_proc_id=bb.wo_master_dtl_proc_id where lot_type = 'M' and lot_status = 0) a 
												
											union
												(select 0 as serial, a.log_id, a.log_lot_ref, a.wo_no, a.garment_colors, a.ex_fty_date, a.log_lot_qty*-1 as rec_qty, 2 ,b.log_createdate
												 from laundry_log a JOIN laundry_log b ON a.log_lot_ref=b.log_lot_tr
												 where a.log_lot_ref in (select log_lot_tr from laundry_log where log_lot_ref='0'))) a 
											where wo_no = trim('$_POST[showcmt]') and garment_colors = '$_POST[showcolors]' and DATE(ex_fty_date) = '$_POST[showexftydateasli]'
											group by rec_no having sum(rec_qty) > 0) a
											   LEFT  JOIN (select trim('$_POST[showcmt]')::TEXT as wo_no , $_POST[pcs] as lot_qty) b USING (wo_no)
											   ) sub)a where DEPOSITS_BREAKDOWN > 0) as asi";
						} else {

							 	$seqlotfifo = $_POST['seqlot']-1;
								$fifotable = "(SELECT A.*,Z.log_lot_receive FROM (SELECT wo_no, rec_no, rec_qty
											     , CASE WHEN last_sum >= lot_qty THEN rec_qty
											            ELSE GREATEST (last_sum + rec_qty - lot_qty, 0) END AS BALANCE
											     , REFERENCE_DATE
											     , CASE WHEN last_sum >= lot_qty THEN 0
											            ELSE LEAST (rec_qty, lot_qty - last_sum) END AS DEPOSITS_BREAKDOWN
											FROM (
											   SELECT a.*
											        , COALESCE(sum(a.rec_qty) OVER (
											                         PARTITION BY wo_no ORDER BY a.REFERENCE_DATE
											                         ROWS BETWEEN UNBOUNDED PRECEDING
											                                          AND 1 PRECEDING), 0) AS last_sum
											        , COALESCE(b.lot_qty, 0) AS lot_qty
											   FROM   (select rec_no, 
														string_agg(distinct wo_no::TEXT,',') as wo_no , 
														sum(rec_qty) as rec_qty, 
														string_agg(distinct garment_colors::TEXT,',') as garment_colors, 
														string_agg(distinct date(ex_fty_date)::TEXT,',') as ex_fty_date,
														string_agg(distinct rec_createdate::TEXT,',') as REFERENCE_DATE
											from 
												(select * from (select row_number() OVER (PARTITION BY aa.wo_no ORDER BY aa.wo_no, lot_id) as serial,
												lot_id as rec_id, lot_no as rec_no, aa.wo_no, aa.garment_colors,bb.ex_fty_date, lot_qty as rec_qty, 1, lot_createdate as rec_createdate
												from laundry_lot_number aa JOIN laundry_wo_master_dtl_proc bb on aa.wo_master_dtl_proc_id=bb.wo_master_dtl_proc_id where lot_type != 'M' AND lot_type != 'W' AND lot_type != 'S' and role_wo_name_seq = '$seqlotfifo' AND lot_status = 0) a 
												
											union
												(select 0 as serial, a.log_id, a.log_lot_ref, a.wo_no, a.garment_colors, a.ex_fty_date, a.log_lot_qty*-1 as rec_qty, 2 ,b.log_createdate
												 from laundry_log a JOIN laundry_log b ON a.log_lot_ref=b.log_lot_tr
												 where b.role_wo_name_seq = '$seqlotfifo' and a.log_lot_ref in (select log_lot_tr from laundry_log where log_lot_ref != '0'))) a 
											where wo_no = trim('$_POST[showcmt]') and garment_colors = '$_POST[showcolors]' and DATE(ex_fty_date) = '$_POST[showexftydateasli]'
											group by rec_no having sum(rec_qty) > 0) a
											   LEFT  JOIN (select trim('$_POST[showcmt]')::TEXT as wo_no , $_POST[pcs] as lot_qty) b USING (wo_no)
											   ) sub)a 
											   JOIN (select log_lot_tr,log_lot_receive from laundry_log where lotmaking_type = 2 GROUP BY log_lot_tr,log_lot_receive) as Z ON A.rec_no = Z.log_lot_tr
											   where DEPOSITS_BREAKDOWN > 0) as asi";
						}
					}
					
					$selFIFO2 = $con->select($fifotable,"*");
					//echo "select * from $fifotable";
					foreach ($selFIFO2 as $loopfifo) {
						//include "cekdata_fifo.php";
						if($_POST['seqlot'] == 1) {
							$log_lot_rec = $loopfifo['rec_no'];
						} else {
							$log_lot_rec = $loopfifo['log_lot_receive'];
						}
						//inputkan ke laundry_log
							$datalog = array(
								'log_lot_tr' => $nolb,
								'log_lot_ref' => $loopfifo['rec_no'],
								'log_lot_qty' => $loopfifo['deposits_breakdown'],
								'log_lot_status' => 1,
								'log_lot_event' => 2,
								'wo_no' => $_POST['showcmt'],
								'garment_colors' => $_POST['showcolors'],
								'ex_fty_date' =>$_POST['showexftydateasli'],
								'log_createdate' => $date,
								'log_lot_receive' => $log_lot_rec,
								'role_wo_name_seq' => $_POST['seqlot'],
								'lotmaking_type' => $_POST['typework'],
							); 
							//var_dump($datalog);
							$execlog = $con->insert("laundry_log", $datalog);
					}
		if ($selceklot > 0) {
			echo "1";
		} else {
			echo $nolb.'_'.base64_encode($nolb);
		}
	}
} 

// simpan dari Receive LOt
else if ($_POST['process-status'] == '1'){
	if ($_POST['type_lot'] == 'A'){
			$jenis = 1;
	} else {
			$jenis = 2;
	}

	$expdata = explode('_',$_POST['data_process']);
	$role_wo_master_id = $expdata[0];
	$role_wo_id  = $expdata[1];

	$wo_master_dtl_proc_id = $expdata[2];
	if ($expdata[3] == ''){
	$master_process_id = '0';
	}else {
	$master_process_id = $expdata[3];
	}
	if ($expdata[4] == ''){
	$master_process_usemachine = 0;
	} else {
	$master_process_usemachine = $expdata[4];	
	}

	$rolewomasterid = $_POST['role-wo-master-id'];

	//cek di laundry process sudah ada apa belum 
	$selectprocess = $con->select("laundry_process","count(process_id) jmlprocessid","role_wo_master_id = '$role_wo_master_id' and role_wo_id = '$role_wo_id' and wo_master_dtl_proc_id = '$wo_master_dtl_proc_id'");
	foreach ($selectprocess as $process) {}
		
	//dapatkan qty reject terakhir di laundry_lot_number
	foreach ($con->select("laundry_lot_number","lot_id,lot_qty_reject_upd","lot_no = '".$_POST['lot_no_process']."'") as $qtyrej){}
	foreach ($con->select("laundry_role_wo_master","role_wo_master_rev","role_wo_master_id = '$rolewomasterid'") as $rolerev){}


		$expdata = explode('_',$_POST['data_process']);
		$role_wo_master_id = $expdata[0];
		$role_wo_id  = $expdata[1];
		$wo_master_dtl_proc_id = $expdata[2];
		$seqlot = $expdata[5];
		$rolechildid = $expdata[6];

		if ($_POST['type_lot'] == 'A'){
				$jenis = 1;
				$datarec = array(
							 'rec_break_status' => 0,
							);
				//var_dump($datarec);
				$execrec = $con->update("laundry_receive",$datarec,"rec_no = '".$_POST['lot_no_process']."'");
				//var_dump($execrec);
				foreach($con->select("laundry_receive","rec_id as lotid","rec_no = '".$_POST['lot_no_process']."'") as $lotid){}
				$mastertypelotid = "0";
		} else {
				$jenis = 2;
				$datarec = array(
							 'lot_status' => 0,
							);
				$execrec = $con->update("laundry_lot_number",$datarec,"lot_no = '".$_POST['lot_no_process']."'");
				foreach($con->select("laundry_lot_number","lot_id as lotid,master_type_lot_id","lot_no = '".$_POST['lot_no_process']."'") as $lotid){}
				$mastertypelotid = $lotid['master_type_lot_id'];
		}

		$datarwnd = array(
						 'lot_status' => 0,
					);
		$execrwnd = $con->update("laundry_role_child_master",$datarwnd,"lot_no = '".$_POST['lot_no_process']."'");
		
		$datarchild = array(
						 'role_child_status' => 1,
						 'role_child_modifydate' => $date,
						 'role_child_modifyby' => $userlogin,
						 'role_child_dateprocess' => $date,
					);
		$execrchild = $con->update("laundry_role_child",$datarchild,"role_child_id = '".$rolechildid."'");

		$idprocess = $con->idurut("laundry_process","process_id");
		$dataprocess = array(
						 'process_id' => $idprocess,
						 'wo_master_dtl_proc_id' => $_POST['wo-master-dtl-proc-id'],
						 'lot_type' => $jenis,
						 'master_process_id' => 0,
						 'process_type' => 4,
						 'wo_no' => $_POST['wo_no_process'],
						 'garment_colors' => $_POST['garment_colors_process'],
						 'lot_no' => $_POST['lot_no_process'],
						 'master_type_process_id' => $_POST['mastertypeprocessid'],
						 'master_type_lot_id' => $mastertypelotid,
						 'role_wo_master_id' => $rolewomasterid,
						 'role_wo_id' => $_POST['rolewoid'],
						 'role_dtl_wo_id' => 0,
						 'process_createdate' => $date,
						 'process_createdby' => $userlogin,
						 'process_status' => 1,
						 'machine_id' => 0,
						 'role_wo_master_rev' => $rolerev['role_wo_master_rev'],
						 'process_qty_good' => $_POST['qty_process'],
						 'process_qty_reject' => 0,
						 'process_qty_total' => $_POST['qty_process'],
						 'shift_id' => $shift['shift_id'],
						 'user_code'=> $_POST['usercode'],
						 'role_wo_name_seq' => $_POST['rolewonameseq'],
						 'sender' =>  $_POST['sender'],
						 'receiver' =>  $_POST['receiver'],
						 'change_process' => 0
		);
		//var_dump($dataprocess);
		$execprocess= $con->insert("laundry_process", $dataprocess);

		//input event Lot
			$idevent = $con->idurut("laundry_lot_event","event_id");
			$dataevent = array(
							 'event_id' => $idevent,
							 'lot_id' => $qtyrej['lot_id'],
							 'lot_no' => $_POST['lot_no_process'],
							 'event_type' => 1,
							 'event_status' => 1,
							 'event_createdby' => $userlogin,
							 'event_createdate' => $date,
							 'master_type_process_id' => $_POST['mastertypeprocessid'],
							 'master_type_lot_id' => $mtlot['master_type_lot_id'],
							 'shift_id' => $shift['shift_id'], 
							 'lot_type' => $jenis,
							 'master_process_id' => 0,
							 'user_code'=> $_POST['usercode'],
			); 
			$execevent= $con->insert("laundry_lot_event", $dataevent);
		//end event lot
}

// simpan dari End Process LOt
else if ($_POST['process-status'] == '4'){
	if ($_POST['type_lot'] == 'A'){
			$jenis = 1;
	} else {
			$jenis = 2;
	}

	$expdata = explode('_',$_POST['data_process']);
	$role_wo_master_id = $expdata[0];
	$role_wo_id  = $expdata[1];

	$wo_master_dtl_proc_id = $expdata[2];
	if ($expdata[3] == ''){
	$master_process_id = '0';
	}else {
	$master_process_id = $expdata[3];
	}
	if ($expdata[4] == ''){
	$master_process_usemachine = 0;
	} else {
	$master_process_usemachine = $expdata[4];	
	}

	$rolewomasterid = $_POST['role-wo-master-id'];

	//cek di laundry process sudah ada apa belum 
	$selectprocess = $con->select("laundry_process","count(process_id) jmlprocessid","role_wo_master_id = '$role_wo_master_id' and role_wo_id = '$role_wo_id' and wo_master_dtl_proc_id = '$wo_master_dtl_proc_id' and process_type = 5");
	foreach ($selectprocess as $process) {}
		
	//dapatkan qty reject terakhir di laundry_lot_number
	foreach ($con->select("laundry_lot_number","lot_id,lot_qty_reject_upd","lot_no = '".$_POST['lot_no_process']."'") as $qtyrej){}
	foreach ($con->select("laundry_role_wo_master","role_wo_master_rev","role_wo_master_id = '$rolewomasterid'") as $rolerev){}


		$expdata = explode('_',$_POST['data_process']);
		$role_wo_master_id = $expdata[0];
		$role_wo_id  = $expdata[1];
		$wo_master_dtl_proc_id = $expdata[2];
		$seqlot = $expdata[5];
		$rolechildid = $expdata[6];

		$jenis = 2;
		$datarec = array(
					 'lot_status' => 1,
					);
		$execrec = $con->update("laundry_lot_number",$datarec,"lot_no = '".$_POST['lot_no_process']."'");
		foreach($con->select("laundry_lot_number","lot_id as lotid,master_type_lot_id","lot_no = '".$_POST['lot_no_process']."'") as $lotid){}
		$mastertypelotid = $lotid['master_type_lot_id'];

		$idprocess = $con->idurut("laundry_process","process_id");
		$dataprocess = array(
						 'process_id' => $idprocess,
						 'wo_master_dtl_proc_id' => $_POST['wo-master-dtl-proc-id'],
						 'lot_type' => $jenis,
						 'master_process_id' => 0,
						 'process_type' => 5,
						 'wo_no' => $_POST['wo_no_process'],
						 'garment_colors' => $_POST['garment_colors_process'],
						 'lot_no' => $_POST['lot_no_process'],
						 'master_type_process_id' => 2,
						 'master_type_lot_id' => $mastertypelotid,
						 'role_wo_master_id' => $rolewomasterid,
						 'role_wo_id' => $_POST['rolewoid'],
						 'role_dtl_wo_id' => 0,
						 'process_createdate' => $date,
						 'process_createdby' => $userlogin,
						 'process_status' => 1,
						 'machine_id' => 0,
						 'role_wo_master_rev' => $rolerev['role_wo_master_rev'],
						 'process_qty_good' => $_POST['qty_process'],
						 'process_qty_reject' => 0,
						 'process_qty_total' => $_POST['qty_process'],
						 'shift_id' => $shift['shift_id'],
						 'user_code'=> $_POST['usercode'],
						 'role_wo_name_seq' => $_POST['rolewonameseq'],
						 'sender' =>  $_POST['sender'],
						 'receiver' =>  $_POST['receiver'],
						 'change_process' => 0
		);
		//var_dump($dataprocess);
		$execprocess= $con->insert("laundry_process", $dataprocess);

		//input event Lot
			$idevent = $con->idurut("laundry_lot_event","event_id");
			$dataevent = array(
							 'event_id' => $idevent,
							 'lot_id' => $qtyrej['lot_id'],
							 'lot_no' => $_POST['lot_no_process'],
							 'event_type' =>9,
							 'event_status' => 1,
							 'event_createdby' => $userlogin,
							 'event_createdate' => $date,
							 'master_type_process_id' => $_POST['mastertypeprocessid'],
							 'master_type_lot_id' => $mtlot['master_type_lot_id'],
							 'shift_id' => $shift['shift_id'], 
							 'lot_type' => $jenis,
							 'master_process_id' => 0,
							 'user_code'=> $_POST['usercode'],
			); 
			$execevent= $con->insert("laundry_lot_event", $dataevent);
		//end event lot
}
?>
