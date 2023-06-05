<?php
//error_reporting(0);
require( '../../../funlibs.php' );
$con=new Database;
session_start();

$view = $_GET['v'];
$table = 'laundry_wo_master_dtl_proc';
$primaryKey = 'wo_master_dtl_proc_id';
$joinQuery = "from laundry_wo_master_dtl_proc a join (select role_wo_master_id,role_wo_status from laundry_role_wo where role_wo_status = 0 GROUP BY role_wo_master_id,role_wo_status) as b on a.role_wo_master_id=b.role_wo_master_id";  

$extraWhere="b.role_wo_status = 0";

$columns = array(
	array( "db" => "wo_master_dtl_proc_id",     "dt" => 0, "field" => "wo_master_dtl_proc_id" ),
	array( "db" => "wo_no",     "dt" => 1, "field" => "wo_no" ),
	array( "db" => "garment_colors", 	"dt" => 2, "field" => "garment_colors"),
	array( "db" => "color_wash", 	"dt" => 3, "field" => "color_wash"),
	array( "db" => "ex_fty_date",     "dt" => 4, "field" => "ex_fty_date" ,
			'formatter' => function( $d, $row ) {
				if ($d != ''){
					$exdate = date('d-m-Y',strtotime($d));
				} else {
					$exdate = '';
				}
					
				return "$exdate";

			}),
	array( "db" => "wo_master_dtl_proc_id", 	"dt" => 5, "field" => "wo_master_dtl_proc_id" ,
			'formatter' => function( $d, $row ) {
					
					$isijam = "<a href='javascript:void(0)' data-toggle='modal' data-target='#funModal' id='mod' onclick='model($d,3)' class='label label-warning' style='font-size:12px;'>Detail</a>";
					
					// $isijam = "<a href='javascript:void(0)' data-toggle='modal' data-target='#funModal' id='mod' onclick='mode($d)' class='label label-warning' style='cursor:pointer'>details</a>";
				/*return "
				<a href='javascript:void(0)' data-toggle='modal' id='mod' data-target='#datajaminan' onClick=detiljams('$d') class='label label-success' style='cursor:pointer'>Lihat Jaminan</a>
				";*/
				return "$isijam";

			}),
);
//var_dump($columns);
$sql_details = array(	
); 
 
//echo "select * from $joinQuery where $extraWhere";
echo json_encode(
	Database::simple( $_GET, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere )
);
