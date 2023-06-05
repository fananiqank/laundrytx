<?php
//error_reporting(0);
require( '../../../funlibs.php' );
$con=new Database;
session_start();
$table = 'laundry_lot_number';
$primaryKey = 'lot_id';
$joinQuery = "from (select a.lot_id,a.lot_no,a.lot_type,a.role_wo_master_id,a.wo_no,a.garment_colors,b.ex_fty_date,to_char(ex_fty_date, 'DD-MM-YYYY') as ex_fty_date_show,lot_qty,lot_status,b.color_wash from laundry_lot_number a JOIN laundry_wo_master_dtl_proc b on a.wo_master_dtl_proc_id=b.wo_master_dtl_proc_id) as ABC";  
$extraWhere="lot_type = 'W' and role_wo_master_id != 0";

$columns = array(
	array( "db" => "lot_id",     "dt" => 0, "field" => "lot_id" ),
	array( "db" => "lot_no",     "dt" => 1, "field" => "lot_no" ),
	array( "db" => "wo_no",     "dt" => 2, "field" => "wo_no" ),
	array( "db" => "garment_colors", 	"dt" => 3, "field" => "garment_colors"),
	array( "db" => "color_wash", 	"dt" => 4, "field" => "color_wash"),
	array( "db" => "ex_fty_date_show", 	"dt" => 5, "field" => "ex_fty_date_show"),
	array( "db" => "lot_qty",     "dt" => 6, "field" => "lot_qty" ),
	array( "db" => "lot_id", 	"dt" => 7, "field" => "lot_id" ,
			'formatter' => function( $d, $row ) {
					
					$isijam = "<a href='javascript:void(0)' data-toggle='modal' data-target='#funModal' id='mod' onclick='modelrework($d,4)' class='label label-warning' style='font-size:12px;'>Detail</a>";
					
					// $isijam = "<a href='javascript:void(0)' data-toggle='modal' data-target='#funModal' id='mod' onclick='mode($d)' class='label label-warning' style='cursor:pointer'>details</a>";
				/*return "
				<a href='javascript:void(0)' data-toggle='modal' id='mod' data-target='#datajaminan' onClick=detiljams('$d') class='label label-success' style='cursor:pointer'>Lihat Jaminan</a>
				";*/
				return "$isijam";

			}),
	// array( "db" => "lot_status", 	"dt" => 7, "field" => "lot_status" ,
	// 		'formatter' => function( $d, $row ) {
	// 				if ($d = '1'){
	// 					$isijam= "Active";
	// 				}
	// 				else {
	// 					$isijam= "Stop";
	// 				}
					
	// 			return "$isijam";

	// 		}),
);
//var_dump($columns);
$sql_details = array(	
); 
 
//echo "select * from $joinQuery where $extraWhere";
echo json_encode(
	Database::simple( $_GET, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere )
);
