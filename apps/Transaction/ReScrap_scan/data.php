<?php
error_reporting(1);
require( '../../../funlibs.php' );
$con=new Database;
session_start();

if($_GET['type'] == 1) {
	$jqrcode = "jqrcode = 1";
} else {
	$jqrcode = "jqrcode > 1";
}
$table = 'laundry_scan_qrcode';
$primaryKey = 'scan_id';
	// $joinQuery = "from (SELECT a.wo_no,
	// 				a.garment_colors,scan_status_garment,
	// 				DATE ( a.ex_fty_date ) AS ex_fty_date,
	// 				SUM ( a.scan_qty ) AS qty,
	// 				CONCAT(b.wo_master_dtl_proc_id,'_',cutting_qty,'_',scan_status_garment) as idcon
	// 				FROM laundry_scan_qrcode a
	// 					 LEFT JOIN laundry_wo_master_dtl_proc b ON a.wo_no = b.wo_no 
	// 					 AND a.garment_colors = b.garment_colors 
	// 					 AND a.ex_fty_date = b.ex_fty_date 
	// 				WHERE a.scan_type = 1 and a.scan_status = 0 
	// 				GROUP BY a.wo_no,
	// 						a.garment_colors,
	// 						a.ex_fty_date,
	// 						b.wo_master_dtl_proc_id,
	// 						cutting_qty,
	// 						scan_status_garment) as asi";  

	$joinQuery = "from (select 
						a.wo_no,a.garment_colors,a.ex_fty_date,scan_status_garment,sum(scan_qty)as qty,
						CONCAT(wo_master_dtl_proc_id,'_',cutting_qty,'_',scan_status_garment) as idcon
						FROM	(
								SELECT
									a.wo_no,
									a.garment_colors,scan_status_garment,
									DATE ( a.ex_fty_date ) AS ex_fty_date,
									a.scan_qrcode,
									a.scan_qty
								FROM
									laundry_scan_qrcode a JOIN
									(select COUNT(scan_qrcode) as jqrcode,scan_qrcode from laundry_scan_qrcode where scan_type = 3 
									AND scan_status_garment BETWEEN 2 
									AND 3
									GROUP BY scan_qrcode) as b on a.scan_qrcode=b.scan_qrcode and $jqrcode
								WHERE
									scan_type = 3 
									AND scan_status = 0 
									AND scan_status_garment BETWEEN 2 
									AND 3
									GROUP BY a.scan_qrcode,a.scan_qty,a.wo_no,
									a.garment_colors,scan_status_garment,
									ex_fty_date
								) as a
								JOIN laundry_wo_master_dtl_proc b ON a.wo_no = b.wo_no 
												 AND a.garment_colors = b.garment_colors 
												 AND a.ex_fty_date = b.ex_fty_date 
												 AND b.wo_master_dtl_proc_status = '$_GET[type]'
								GROUP BY a.wo_no,a.garment_colors,a.ex_fty_date,scan_status_garment,wo_master_dtl_proc_id
						) as asi";

$extraWhere="";

$columns = array(
	array( "db" => "wo_no", 	"dt" => 0, "field" => "wo_no"),
	array( "db" => "garment_colors",     "dt" => 1, "field" => "garment_colors" ),
	array( "db" => "ex_fty_date", 	"dt" => 2, "field" => "ex_fty_date" ),
	array( "db" => "qty", 	"dt" => 3, "field" => "qty" ),
	array( "db" => "scan_status_garment", 	"dt" => 4, "field" => "scan_status_garment",
		'formatter' => function( $d, $row ) {
					if ($d == '2') {
						$isijum = "<span class='label label-danger'>Scrap</span>";
					} else if ($d == '3') {
						$isijum = "<span class='label label-primary'>Rework</span>";
					}
					
				return "$isijum";
		}
	),
	array( "db" => "idcon", 	"dt" => 5, "field" => "idcon",
		'formatter' => function( $d, $row ) {
					$expidcon = explode('_', $d);
					$idproc = $expidcon[0];
					$cutqty = $expidcon[1];
					$scanstatus = $expidcon[2];
					//if ($idproc != '' && $cutqty != '') {
						$isijum = "<a href='javascript:void(0)' data-toggle='modal' data-target='#funModalrec' id='mod' onClick=modelReScrapScan($idproc,$scanstatus) class='label label-warning' style='font-size:12px;'>Create Lot</a>";
					// } else if ($idproc == '') {
					// 	$isijum = "<span style='color:red'>Not Sequence Yet</span>";
					// } else if ($cutqty == ''){
					// 	$isijum = "<span style='color:blue'>Cut Qty Not Set</span>";
					// }
					
				return "$isijum";
		}
	),
);
//var_dump($columns);
$sql_details = array(	
); 
 
//echo "select * from $joinQuery where $extraWhere";
echo json_encode(
	Database::simple( $_GET, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere )
);
