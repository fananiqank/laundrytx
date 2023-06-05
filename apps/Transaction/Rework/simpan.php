<?php 

session_start();
include "../../../funlibs.php";
$con = new Database;
//error_reporting(0);
$date = date("Y-m-d H:i:s");
$date2 = date("Y-m-d");
$tabel = "laundry_role_grup";

//shift 
foreach($con->select("laundry_master_shift","shift_id,shift_time_start,shift_time_end,shift_status","TO_CHAR(now(), 'HH24:MI') between TO_CHAR(shift_time_start, 'HH24:MI') and TO_CHAR(shift_time_end, 'HH24:MI') and shift_status = 1") as $shift){}
// end shift
	
if ($_POST['ceklot'] == '2'){ 
// echo "5";
	if ($_POST['lotids'] != ''){
		foreach ($_POST['lotids'] as $key => $value){
			$expval = explode('|',$value);
			$lotid = $expval[0];
			$lotno = $expval[1];
			$lottyperework = $expval[2];
			$lotqty = $expval[3];
			$datatmp = array( 
					 'lot_status' => 0,
					); 
			$dt=$con->update("laundry_lot_number",$datatmp,"lot_id = $lotid");
			
			// $datatmp = array( 
			// 	 'lot_no' => $lotno,
			// 	 'lot_id' => $lotid,
			// 	 'rework_tmp_createdate' => $date,
			// 	 'lot_type_rework' => $lottyperework,
			// 	 'lot_qty' => $lotqty,
			// 	); 
			// $exectmp= $con->insert("laundry_rework_tmp", $datatmp);
				
				// if($exectmp){
				// 	echo "1";
				// } else {
				// 	echo "3";
				// }
		}		
		echo "1";
	} else {
		echo "2";
	}
} 
// end input cmt to plan =========================================================================


else if ($_POST['ceklot'] == '1'){
	//create lot number
		$selurutcount = $con->select("laundry_lot_number","COALESCE(max(lot_no_uniq),0) as max","wo_no = '$_POST[wo_no_show]'");
		foreach($selurutcount as $urutcount){}
		$urut = $urutcount['max']+1;

		$expmt = explode('/',trim($_POST['wo_no_show']));
		$trimexp6 = trim($expmt[6]);

		if($expmt[1] == 'RECUT'){
			$nolb ='L'.$expmt[0]."R".$expmt[3].$expmt[4].trim($expmt[5]).'M'.sprintf('%03s', $urut);
		} else {
			$nolb ='L'.$expmt[0].$expmt[4].$expmt[5].$trimexp6."M".sprintf('%03s', $urut);
		}

	$ceklot = $con->selectcount("laundry_lot_number","lot_no","lot_no = '$nolb'");
	if($ceklot == 0){
	//cek lot number agar tidak doble 

	$expseqpro = explode('_',$_POST['seq_pro']);
	$sequence = $expseqpro[1];
	$rolemaster = $expseqpro[0];
	foreach($con->select("laundry_wo_master_dtl_proc","COALESCE(0,wo_master_dtl_proc_qty_rec)","wo_master_dtl_proc_id = '$sequence'") as $cekjumlahproc){}

	//input lot number
		$idlot = $con->idurut("laundry_lot_number","lot_id");
		$datalot = array(
						 'lot_id' => $idlot,
						 'lot_no' => $nolb, 
						 'wo_no' => $_POST['wo_no_show'],
						 'garment_colors' => $_POST['color_no_show'],
						 'wo_master_dtl_proc_id' => $sequence, 
						 'master_type_lot_id' => 10,
						 'role_wo_master_id' => $rolemaster,
						 'lot_qty' => $_POST['totalpcs'],
						 'lot_kg' => $_POST['totalkg'],
						 'lot_shade' => $_POST['shade'],
						 'lot_createdate' => $date,
						 'lot_createdby' => $_SESSION['ID_LOGIN'],
						 'lot_status' => 1,
						 'create_type' => 8,
						 'lot_type' => 'M',
						 'shift_id' => $shift['shift_id'],
						 'user_code' => $_POST['usercode'],
						 'role_wo_name_seq' => 0,
						 'lot_no_uniq' => $urut
						 
		); 
		//var_dump($datalot);
		$execlot= $con->insert("laundry_lot_number", $datalot);

	//insert role child master (fungsi utk master role per lot)
		$datachildmaster = array(
					'lot_no' => $nolb,
					'role_wo_master_id'  => $rolemaster,
					'lot_status' => 1,
					'lot_type' => 4,
					'lot_id' => $idlot
				);
		
		$execchildmaster = $con->insert("laundry_role_child_master", $datachildmaster);
	//=========================================================	
		
	//input data role per lot Rework(M) ke laundry role child
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
									 "role_wo_master_id = '$rolemaster' and a.role_wo_status != 2",
									 "role_wo_seq");
									// and role_wo_seq <= (select role_wo_seq from laundry_role_wo where role_wo_master_id = '$rolemaster' and master_type_process_id = 2)
		
		foreach ($selrolechild as $child) {
			$idchild = $con->idurut("laundry_role_child","role_child_id");
			$datachild = array(
						'role_child_id' => $idchild,
						'role_wo_master_id'  => $rolemaster,
						'role_wo_id'  => $child['role_wo_id'],
						'role_dtl_wo_id'  => $child['role_dtl_wo_id'],
						'lot_type'  => 4,
						'role_child_status'  => 0,
						'role_child_createdate'  => $date,
						'role_child_createdby'  => $_SESSION['ID_LOGIN'],
						'lot_no'  => $nolb,
						'lot_id'  => $idlot,
						'role_wo_seq' => $child['role_wo_seq'],
						'role_dtl_wo_seq' => $child['role_dtl_wo_seq'],
			);
			//var_dump($datachild); 
			$execchild = $con->insert("laundry_role_child", $datachild);
			//var_dump($execchild);
		}
		// end input data role per lot receive ke laundry role child

		foreach($con->select("(select a.*,(select log_lot_receive from laundry_log where log_lot_tr=reject_from_lot GROUP BY log_lot_receive) log_lot_receive from (SELECT wo_no, rec_no, rec_qty, CASE WHEN last_sum >= lot_qty THEN rec_qty ELSE GREATEST (last_sum + rec_qty - lot_qty, 0) END AS BALANCE, REFERENCE_DATE, CASE WHEN last_sum >= lot_qty THEN 0	ELSE LEAST (rec_qty, lot_qty - last_sum) END AS DEPOSITS_BREAKDOWN,reject_from_lot
FROM ( SELECT a.*, COALESCE(sum(a.rec_qty) OVER ( PARTITION BY wo_no ORDER BY a.REFERENCE_DATE ROWS BETWEEN UNBOUNDED PRECEDING AND 1 PRECEDING), 0) AS last_sum, COALESCE(b.lot_qty, 0) AS lot_qty 
			FROM (select rec_no, string_agg(distinct wo_no::TEXT,',') as wo_no , 
		sum(rec_qty) as rec_qty, 
		string_agg(distinct garment_colors::TEXT,',') as garment_colors, 
		string_agg(distinct date(ex_fty_date)::TEXT,',') as ex_fty_date,
		string_agg(distinct rec_createdate::TEXT,',') as REFERENCE_DATE,
		max(reject_from_lot) as reject_from_lot
		
from 
(select * from (select row_number() OVER (PARTITION BY aa.wo_no ORDER BY aa.wo_no, lot_id) as serial,
lot_id as rec_id, lot_no as rec_no, aa.wo_no, aa.garment_colors,bb.ex_fty_date, lot_qty as rec_qty, 1, lot_createdate as rec_createdate,reject_from_lot
from laundry_lot_number aa JOIN laundry_wo_master_dtl_proc bb on aa.wo_master_dtl_proc_id=bb.wo_master_dtl_proc_id where lot_type = 'W' and lot_status = 0) a 
union
	(select 0 as serial, a.log_id, a.log_lot_ref, a.wo_no, a.garment_colors, a.ex_fty_date, a.log_lot_qty*-1 as rec_qty, 2 ,b.log_createdate, '0' as reject_from_lot
	 from laundry_log a JOIN laundry_log b ON a.log_lot_ref=b.log_lot_tr
	 where a.log_lot_ref in (select log_lot_tr from laundry_log where log_lot_ref != '0'))) a
where wo_no = trim('".$_POST[wo_no_show]."') and garment_colors = '".$_POST[color_no_show]."' and DATE(ex_fty_date) = '".$_POST[ex_fty_date]."'
group by rec_no having sum(rec_qty) > 0) a
 LEFT  JOIN (select trim('".$_POST[wo_no_show]."')::TEXT as wo_no , $_POST[totalpcs] as lot_qty) b USING (wo_no) ) sub) a 
 where DEPOSITS_BREAKDOWN > 0) asi","*") as $rwfifo){
		// input data ke log lot
		$datalog = array(
			'log_lot_tr' => $nolb,
			'log_lot_ref' => $rwfifo['rec_no'],
			'log_lot_qty' => $rwfifo['deposits_breakdown'],
			'log_lot_status' => 1,
			'log_lot_event' => 6,
			'wo_no' => $_POST['wo_no_show'],
			'garment_colors' => $_POST['color_no_show'],
			'ex_fty_date' =>$_POST['ex_fty_date'],
			'log_lot_receive' => $rwfifo['log_lot_receive'],
			'log_createdate' => $date,
			'role_wo_name_seq' => 0,
			'lotmaking_type' => 2
		);                                                                                                            
		$execlog = $con->insert("laundry_log", $datalog);
	}
		
	echo $nolb.'_'.base64_encode($nolb);
	}
}

//delete keranjang
else if ($_POST['ceklot'] == '3'){
		$where3 = array('lot_no' => $_GET['lot']);
		$con->delete("laundry_rework_tmp",$where3);

}
	
?>
