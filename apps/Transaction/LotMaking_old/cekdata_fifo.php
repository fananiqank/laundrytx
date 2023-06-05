<?php 

// $selFIFO = $con->select("(select rec_no, sum(rec_qty) as sisa, 
// 			                       REPLACE(string_agg(distinct wo_no::TEXT,','),',','') as wo_no , 
// 							       REPLACE(string_agg(distinct garment_colors::TEXT,','),',','') as garment_colors, 
// 								   REPLACE(string_agg(distinct ex_fty_date::TEXT,','),',','') as ex_fty_date
// 									from 
// 										(select * from (select row_number() OVER (PARTITION BY aa.wo_no ORDER BY aa.wo_no, rec_id) as serial,
// 										rec_id, rec_no, aa.wo_no, aa.garment_colors,bb.ex_fty_date, rec_qty, 1
// 										from laundry_receive aa JOIN laundry_wo_master_dtl_proc bb on aa.wo_master_dtl_proc_id=bb.wo_master_dtl_proc_id) a 
// 									union
// 										(select 0 as serial, log_id, log_lot_ref, '', '', NULL, log_lot_qty*-1, 2 
// 										 from laundry_log 
// 										 where log_lot_ref in (select log_lot_tr from laundry_log where log_lot_ref='0'))) a 
// 								group by rec_no having sum(rec_qty) > 0 limit 1) as asi",
// 								"rec_no,sisa,wo_no,garment_colors,ex_fty_date");
// 		foreach ($selFIFO as $FIFO) {}

		if ($sisa_input != ''){
			if ($sisa_input <= $loopfifo['sisa']) {
				$ret = 3;
				$qty_input = $sisa_input;
				$datalog = array(
							'log_lot_tr' => $_POST['lotnumb'],
							'log_lot_ref' => $loopfifo['rec_no'],
							'log_lot_qty' => $qty_input,
							'log_lot_status' => 1,
							'log_lot_event' => 1,
							'wo_no' => $wo_no,
							'garment_colors' => $colors,
							'ex_fty_date' =>$exdate,
							'log_createdate' => $date,
						);
					//	var_dump($datalog);
						$execlog = $con->insert("laundry_log", $datalog);
				break;
			} else {
				$ret= 2;
				$qty_input = $loopfifo['sisa'];
				$sisa_input = $_POST['pcs']-$loopfifo['sisa'];
				$datalog = array(
							'log_lot_tr' => $_POST['lotnumb'],
							'log_lot_ref' => $loopfifo['rec_no'],
							'log_lot_qty' => $qty_input,
							'log_lot_status' => 1,
							'log_lot_event' => 1,
							'wo_no' => $wo_no,
							'garment_colors' => $colors,
							'ex_fty_date' =>$exdate,
							'log_createdate' => $date,
						);
					//	var_dump($datalog);
						$execlog = $con->insert("laundry_log", $datalog);
			}
		} else {
			if ($_POST['pcs'] <= $loopfifo['sisa']) {
				$ret= 1;
				$qty_input = $_POST['pcs'];
				$datalog = array(
							'log_lot_tr' => $_POST['lotnumb'],
							'log_lot_ref' => $loopfifo['rec_no'],
							'log_lot_qty' => $qty_input,
							'log_lot_status' => 1,
							'log_lot_event' => 1,
							'wo_no' => $wo_no,
							'garment_colors' => $colors,
							'ex_fty_date' =>$exdate,
							'log_createdate' => $date,
						);
						var_dump($datalog);
						$execlog = $con->insert("laundry_log", $datalog);
				break;
			} else {
				$ret= 2;
				$qty_input = $loopfifo['sisa'];
				$sisa_input = $_POST['pcs']-$loopfifo['sisa'];
				$datalog = array(
							'log_lot_tr' => $_POST['lotnumb'],
							'log_lot_ref' => $loopfifo['rec_no'],
							'log_lot_qty' => $qty_input,
							'log_lot_status' => 1,
							'log_lot_event' => 1,
							'wo_no' => $wo_no,
							'garment_colors' => $colors,
							'ex_fty_date' =>$exdate,
							'log_createdate' => $date,
						);
						var_dump($datalog);
					//	$execlog = $con->insert("laundry_log", $datalog);
			}
		}
		

?>