<?php
error_reporting(1);
require( '../../../funlibs.php' );
$con=new Database;
session_start();

$cmt = $con->searchseqcmt($_GET['cm']);
$cmt2 = trim($cmt);
$colors = $con->searchseq($_GET['co']);
$colors2 = explode('Y|',$colors);
$colors3 = implode('&',$colors2);
$colors4 = trim($colors3);
$exftydate =  $_GET['xty'];

if ($cmt != '') {
	$cm = "and trim(wo_no) = '" . $cmt2 . "'";
} else {
	$cm = "";
}

if ($colors != '') {
	$co = "and garment_colors = '" . $colors4 . "'";
} else {
	$co = "";
}

if ($exftydate != '') {
	$xty = "and DATE(ex_fty_date) = '" . $exftydate . "'";
} else {
	$xty = "";
}

$table = 'laundry_scan_qrcode';
$primaryKey = 'scan_id';
// $joinQuery = "from (SELECT a.wo_no,
// 					a.garment_colors,
// 					DATE ( a.ex_fty_date ) AS ex_fty_date,
// 					SUM ( a.scan_qty ) AS qty,
// 					CONCAT(wo_master_dtl_proc_id,'_',cutting_qty) as idcon
// 					FROM laundry_scan_qrcode a
// 					 JOIN qrcode_ticketing_master C on UPPER(a.scan_qrcode) = UPPER(c.qrcode_key) and c.washtype = 'Wash' 
// 						 LEFT JOIN laundry_wo_master_dtl_proc b ON a.wo_no = b.wo_no 
// 						 AND a.garment_colors = b.garment_colors 
// 						 AND DATE(a.ex_fty_date) = DATE(b.ex_fty_date) 
// 						 AND b.wo_master_dtl_proc_status = 1 
// 					WHERE a.scan_type = 1 and a.scan_status = 0
// 					GROUP BY a.wo_no,
// 							a.garment_colors,
// 							DATE(a.ex_fty_date),
// 							b.wo_master_dtl_proc_id,
// 							cutting_qty) as asi";  
// $joinQuery = "from (SELECT a.wo_no,
// 					a.garment_colors,
// 					DATE ( a.ex_fty_date ) AS ex_fty_date,
// 					SUM ( a.scan_qty ) AS qty,
// 					CASE 
// 						WHEN d.wash_category_id between 5 and 8 
// 						THEN 'Denim'
// 						ELSE 'Non Denim'
// 					END as wash_category_id,
// 					CONCAT(b.wo_master_dtl_proc_id,'_',cutting_qty) as idcon
// 					FROM laundry_scan_qrcode a
// 					 JOIN qrcode_ticketing_master C on UPPER(a.scan_qrcode) = UPPER(c.qrcode_key) and c.washtype = 'Wash' 
// 					 JOIN (select wo_no, wash_category_id from laundry_data_wo group by wo_no, wash_category_id ) D on A.wo_no=D.wo_no
// 						 LEFT JOIN laundry_wo_master_dtl_proc b ON a.wo_no = b.wo_no 
//  						 AND a.garment_colors = b.garment_colors 
// 						 		 AND DATE(a.ex_fty_date) = DATE(b.ex_fty_date) 
//  						 AND b.wo_master_dtl_proc_status = 1 
// 					WHERE a.scan_type = 1 and a.scan_status = 0
// 					GROUP BY a.wo_no,
// 							a.garment_colors,
// 							DATE(a.ex_fty_date),
// 							b.wo_master_dtl_proc_id,
// 							d.wash_category_id,
// 							cutting_qty) as asi";  
// $joinQuery = "from (SELECT a.wo_no,
// 					a.garment_colors,
// 					DATE ( a.ex_fty_date ) AS ex_fty_date,
// 					SUM ( a.scan_qty ) AS qty,
// 					CASE 
// 						WHEN d.wash_category_id between 5 and 8 
// 						THEN 'Denim'
// 						ELSE 'Non Denim'
// 					END as wash_category_id,
// 					CONCAT(b.wo_master_dtl_proc_id,'_',cutting_qty) as idcon
// 					FROM laundry_scan_qrcode a
// 					 	 JOIN qrcode_ticketing_master C on a.ticketid = c.ticketid and c.washtype = 'Wash' 
// 					 	 JOIN (select wo_no, wash_category_id from laundry_data_wo group by wo_no, wash_category_id ) D on A.wo_no=D.wo_no
// 						 LEFT JOIN laundry_wo_master_dtl_proc b ON a.wo_no = b.wo_no 
// 							 AND a.garment_colors = b.garment_colors 
// 							 AND DATE(a.ex_fty_date) = DATE(b.ex_fty_date) 
// 							 AND b.wo_master_dtl_proc_status = 1 
// 					WHERE a.scan_type = 1 and a.scan_status = 0
// 					GROUP BY a.wo_no,
// 							a.garment_colors,
// 							DATE(a.ex_fty_date),
// 							b.wo_master_dtl_proc_id,
// 							d.wash_category_id,
// 							cutting_qty) as asi";
// $joinQuery = "from (SELECT a.wo_no,
// 					a.garment_colors,
// 					DATE ( a.ex_fty_date ) AS ex_fty_date,
// 					SUM ( a.scan_qty ) AS qty,
// 					CASE 
// 						WHEN d.wash_category_id between 5 and 8 
// 						THEN 'Denim'
// 						ELSE 'Non Denim'
// 					END as wash_category_id,
// 					CONCAT(b.wo_master_dtl_proc_id,'_',cutting_qty) as idcon
// 					FROM laundry_scan_qrcode a
// 					 	 JOIN (select wo_no, wash_category_id from laundry_data_wo group by wo_no, wash_category_id ) D on A.wo_no=D.wo_no
// 						 LEFT JOIN laundry_wo_master_dtl_proc b ON a.wo_no = b.wo_no 
// 							 AND a.garment_colors = b.garment_colors 
// 							 AND DATE(a.ex_fty_date) = DATE(b.ex_fty_date) 
// 							 AND b.wo_master_dtl_proc_status = 1 
// 					WHERE a.scan_type = 1 and a.scan_status = 0
// 					GROUP BY a.wo_no,
// 							a.garment_colors,
// 							DATE(a.ex_fty_date),
// 							b.wo_master_dtl_proc_id,
// 							d.wash_category_id,
// 							cutting_qty) as asi"; 

// $joinQuery = "from (SELECT a.wo_no,
// 					a.tgarment_colors as garment_colors,
// 					DATE ( a.ex_fty_date ) AS ex_fty_date,
// 					SUM ( a.scan_qty ) AS qty,
// 					CONCAT(b.wo_master_dtl_proc_id,'_',cutting_qty) as idcon,
// 					color_wash
// 					FROM (select wo_no,ex_fty_date,scan_qty,scan_type,scan_status,trim(garment_colors) as tgarment_colors from laundry_scan_qrcode GROUP BY
// 					scan_qrcode,wo_no,ex_fty_date,scan_qty,scan_type,scan_status,garment_colors) a
// 						 LEFT JOIN laundry_wo_master_dtl_proc b ON a.wo_no = b.wo_no 
// 							 AND a.tgarment_colors = b.garment_colors 
// 							 AND DATE(a.ex_fty_date) = DATE(b.ex_fty_date) 
// 							 AND b.wo_master_dtl_proc_status = 1 
// 					WHERE a.scan_type = 1 and a.scan_status = 0
// 					GROUP BY a.wo_no,
// 							a.tgarment_colors,
// 							DATE(a.ex_fty_date),
// 							b.wo_master_dtl_proc_id,
// 							cutting_qty) as asi";  

$joinQuery = "from (SELECT a.wo_no,
					a.tgarment_colors as garment_colors,
					DATE ( a.ex_fty_date ) AS ex_fty_date,
					SUM ( a.scan_qty ) AS qty,
					CONCAT(b.wo_master_dtl_proc_id,'_',cutting_qty) as idcon,
					color_wash
					FROM (select wo_no,ex_fty_date,scan_qty,scan_type,scan_status,trim(garment_colors) as tgarment_colors from laundry_scan_qrcode GROUP BY
					scan_qrcode,wo_no,ex_fty_date,scan_qty,scan_type,scan_status,garment_colors) a
						 LEFT JOIN laundry_wo_master_dtl_proc b ON trim(a.wo_no) = trim(b.wo_no) 
							 AND a.tgarment_colors = trim(b.garment_colors) 
							 AND b.wo_master_dtl_proc_status = 1 
					WHERE a.scan_type = 1 and a.scan_status = 0
					GROUP BY a.wo_no,
							a.tgarment_colors,
							DATE(a.ex_fty_date),
							b.wo_master_dtl_proc_id,
							cutting_qty) as asi";  
$extraWhere="wo_no like '%' $cm $co";

$columns = array(
	array( "db" => "wo_no", 	"dt" => 0, "field" => "wo_no"),
	array( "db" => "garment_colors",     "dt" => 1, "field" => "garment_colors" ),
	array( "db" => "color_wash",     "dt" => 2, "field" => "color_wash" ),
	array( "db" => "ex_fty_date", 	"dt" => 3, "field" => "ex_fty_date" ), 
	array( "db" => "qty", 	"dt" => 4, "field" => "qty" ),
	//array( "db" => "wash_category_id", 	"dt" => 4, "field" => "wash_category_id"),
	array( "db" => "idcon", 	"dt" => 5, "field" => "idcon",
		'formatter' => function( $d, $row ) {
					$expidcon = explode('_', $d);
					$idproc = $expidcon[0];
					$cutqty = $expidcon[1];
					//$testsa = $expidcon[2];
					if ($idproc != '' && $cutqty != '') {
						$isijum = "<a href='javascript:void(0)' data-toggle='modal' data-target='#funModalrec' id='mod' onClick=modelReceiveScan($idproc) class='label label-warning' style='font-size:12px;'>Create Lot</a>";
					} else if ($idproc == '') {
						$isijum = "<span style='color:red'>Not Sequence Yet</span>";
					} else if ($cutqty == ''){
						$isijum = "<span style='color:blue'>Cut Qty Not Set</span>";
					} 
					
				return "$isijum";
		}
	),
);
//var_dump($columns);
$sql_details = array(	
); 
 
//echo "select * $joinQuery where $extraWhere";
echo json_encode(
	Database::simple( $_GET, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere )
);
