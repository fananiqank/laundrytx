	<?php
	session_start();
	include "../../../funlibs.php";
	$con = new Database;
	//error_reporting(0);
	$date = date("Y-m-d H:i:s");
	$date2 = date("Y-m-d_G-i-s");
	$datetok = date('dmy');


	$dt=$con->select("(SELECT string_agg(recdtl_id::TEXT,',') as id,wo_no,garment_colors,ex_fty_date, washcolor, sum(recdtl_qty) qtyrec FROM laundry_receive_manualdtl_tmp where userid='$_SESSION[ID_LOGIN]'  group by wo_no,garment_colors,ex_fty_date,washcolor) a","*");
	if ($_POST['lastrec'][$key] == '1') {
			$statusrec = 2;
		} else {
			$statusrec = 1;
		}
	foreach($dt as $vdt){
	// nomor lot receive

	$expwono = explode('/', $vdt['wo_no']);
	$expwo = explode('/', $vdt['wo_no']);
	$wo_no = $expwono[0];
	$colors = $expwono[1];
	$exdate = $expwono[2];
	//$selecturutcmt = $con->select("laundry_receive", "COUNT(rec_id) as max", "wo_no = '$vdt[wo_no]'");
	$selecturutcmt = $con->select("laundry_receive", "COALESCE(max(rec_no_uniq),0) as max", "wo_no = '$vdt[wo_no]'");
	foreach ($selecturutcmt as $urutcmt) {}
	$cmtseq = $urutcmt['max'] + 1;
	// $urutcmtcolor = $con->selectcount("laundry_receive", "rec_id", "wo_no = '" . $vdt[wo_no] . "' and trim(garment_colors) = '" . trim($vdt[garment_colors]) . "'");
	$urutcmtcolor = $con->selectcount("laundry_receive", "rec_id", "wo_no = '$vdt[wo_no]'");
	$cmtcolseq = $urutcmtcolor + 1;

	if($expwo[1] == 'RECUT'){
		$noreceive = 'L' . $expwo[0]."R".$expwo[3] . $expwo[4] . trim($expwo[5]) . 'A' . sprintf('%03s', $cmtseq) . '-' . $cmtcolseq;
	} else {
		$noreceive = 'L' . $expwo[0] . $expwo[4] . $expwo[5] . trim($expwo[6]) . 'A' . sprintf('%03s', $cmtseq) . '-' . $cmtcolseq;
	}
	
	$idreceive = $con->idurut("laundry_receive", "rec_id");
	$selwodtlproc = $con->select("laundry_wo_master_dtl_proc", "wo_master_dtl_proc_id,role_wo_master_id", "trim(wo_no) = '" . trim($vdt[wo_no]) . "' and trim(garment_colors) = '" . trim($vdt[garment_colors]) . "' and DATE(ex_fty_date) = '" . $vdt[ex_fty_date] . "' and wo_master_dtl_proc_status='1'");
	// echo "select * from laundry_wo_master_dtl_proc where wo_no = '" . $vdt[wo_no] . "' and garment_colors = '" . $vdt[garment_colors] . "' and DATE(ex_fty_date) = '" . $vdt[ex_fty_date] . "'";
	foreach ($selwodtlproc as $wodtlproc) {}
	$datarec = array(
				'rec_id' => $idreceive,
				'rec_no' => $noreceive,
				'rec_createdate' => $date,
				'rec_createdby' => $_SESSION['ID_LOGIN'],
				'rec_status' => $statusrec,
				'rec_remark' => $_POST['note'][0],
				'wo_no' => $vdt['wo_no'],
				'garment_colors' => $vdt[garment_colors],
				'rec_qty' => $vdt['qtyrec'],
				'wo_master_dtl_proc_id' => $wodtlproc['wo_master_dtl_proc_id'],
				'rec_type' => 1,
				'user_code' => $_POST['username'][0],
				'ex_fty_date'=>$vdt[ex_fty_date],
				'rec_no_uniq' => $cmtseq
			);
			// var_dump($datarec);
			$execrec = $con->insertID("laundry_receive", $datarec);
			// echo "execrec data $execrec";

	//insert role child master (fungsi utk master role per lot)
	$datachildmaster = array(
				'lot_no' => $noreceive,
				'role_wo_master_id'  => $wodtlproc['role_wo_master_id'],
				'lot_status' => 1,
				'lot_type' => 1,
				'lot_id' => $idreceive,
				'role_wo_name_seq' => 1,
			);
	
	$execchildmaster = $con->insert("laundry_role_child_master", $datachildmaster);
	//=========================================================

	// input data ke log lot
	$datalog = array(
		'log_lot_tr' => $noreceive,
		'log_lot_ref' => 0,
		'log_lot_qty' => $vdt['qtyrec'],
		'log_lot_status' => 1,
		'log_lot_event' => 1,
		'wo_no' => $vdt['wo_no'],
		'garment_colors' => $vdt[garment_colors],
		'ex_fty_date' =>$vdt[ex_fty_date],
		'log_createdate' => $date,
		'log_lot_receive' => 0,
		'role_wo_name_seq' => 0,
	);
	$execlog = $con->insert("laundry_log", $datalog);

	$pdata=$con->select("laundry_receive_manualdtl_tmp","'$idreceive' as id,wo_no,garment_colors,ex_fty_date,recdtl_inseam,recdtl_size,recdtl_qty,washcolor,recdtl_id","recdtl_id in ($vdt[id])");
	
	foreach($pdata as $vpdata){
		$datachildx = array(
				'rec_id'=>$vpdata[id],
				'wo_no'=>$vpdata[wo_no],
				'garment_colors'=>$vpdata[garment_colors],
				'ex_fty_date'=>$vpdata[ex_fty_date],
				'recdtl_inseam'=>$vpdata[recdtl_inseam],
				'recdtl_size'=>$vpdata[recdtl_size],
				'recdtl_qty'=>$vpdata[recdtl_qty],
				'washcolor'=>$vpdata[washcolor]
			);
			//var_dump($datachild); 
			$execchildx= $con->insert("laundry_receive_manualdtl", $datachildx);

		$datachildxd = array(
				'recdtl_id'=>$vpdata[recdtl_id],
			);
			// var_dump($datachildxd); 
			$execchildxd= $con->delete("laundry_receive_manualdtl_tmp", $datachildxd);



	}

	$con->query("insert into laundry_receive_manualdtl(rec_id,wo_no,garment_colors,ex_fty_date,recdtl_inseam,recdtl_size,recdtl_qty,washcolor) 
			    select '$idreceive',wo_no,garment_colors,ex_fty_date,recdtl_inseam,recdtl_size,recdtl_qty,washcolor from laundry_receive_manualdtl_tmp where recdtl_id in ($vdt[id]);
			    delete from laundry_receive_manualdtl_tmp where  recdtl_id in ($vdt[id])");

	// echo "insert into laundry_receive_manualdtl(rec_id,wo_no,garment_colors,ex_fty_date,recdtl_inseam,recdtl_size,recdtl_qty,washcolor) 
	// 		    select '$idreceive',wo_no,garment_colors,ex_fty_date,recdtl_inseam,recdtl_size,recdtl_qty,washcolor from laundry_receive_manualdtl_tmp where recdtl_id in ($vdt[id]);
	// 		    delete from laundry_receive_manualdtl_tmp where  recdtl_id in ($vdt[id])";
	// input data role per lot receive ke laundry role child
		$selrolechild = $con->select(
			"laundry_role_wo a left join laundry_role_dtl_wo b on a.role_wo_id=b.role_wo_id and role_dtl_wo_status = 1",
			"a.role_wo_id, role_wo_name, master_type_process_id, role_wo_seq, role_wo_time, role_dtl_wo_id, master_process_id,
			 role_dtl_wo_seq, role_dtl_wo_time","role_wo_master_id = '".$wodtlproc['role_wo_master_id']."' and role_wo_status = 1
			 and role_wo_seq <= (select role_wo_seq from laundry_role_wo where role_wo_master_id = '". $wodtlproc['role_wo_master_id']."' 
			 and master_type_process_id = 2 and role_wo_name_seq = 1 and role_wo_status = 1)","role_wo_seq");
		// echo "select a.role_wo_id, role_wo_name, master_type_process_id, role_wo_seq, role_wo_time, role_dtl_wo_id, master_process_id,
		// 	 role_dtl_wo_seq, role_dtl_wo_time from laundry_role_wo a left join laundry_role_dtl_wo b on a.role_wo_id=b.role_wo_id and role_dtl_wo_status = 1 where role_wo_master_id = '".$wodtlproc['role_wo_master_id']."' and role_wo_status = 1
		// 	 and role_wo_seq <= (select role_wo_seq from laundry_role_wo where role_wo_master_id = '". $wodtlproc['role_wo_master_id']."' 
		// 	 and master_type_process_id = 2 and role_wo_name_seq = 1) order by role";
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
				'lot_no'  => $noreceive,
				'lot_id'  => $idreceive,
				'role_wo_seq' => $child['role_wo_seq'],
				'role_dtl_wo_seq' => $child['role_dtl_wo_seq'],
			);
			//var_dump($datachild); 
			$execchild = $con->insert("laundry_role_child", $datachild);
		}

	}
	echo $noreceive.'_'.base64_encode($noreceive);

	?>
