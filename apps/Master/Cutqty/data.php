<?php
//error_reporting(0);
require( '../../../funlibs.php' );
$con=new Database;
session_start();

$view = $_GET['v'];
$table = 'laundry_wo_master_dtl_proc';
$primaryKey = 'wo_master_dtl_proc_id';
$joinQuery = "from (select wo_master_dtl_proc_id,a.wo_no,a.garment_colors,to_char(a.ex_fty_date,'DD-MM-YYYY') as ex_fty_date,seq_ex_fty_date,cut_qty as cutting_qty,a.color_wash from laundry_wo_master_dtl_proc a LEFT JOIN (select COUNT(qrcode_key) as cut_qty,wo_no, color,ex_fty_date from qrcode_ticketing_master where washtype = 'Wash' GROUP BY wo_no, color,ex_fty_date) as t on a.wo_no = t.wo_no and a.garment_colors = t.color and a.ex_fty_date=t.ex_fty_date) as ab";  

$extraWhere="";

$columns = array(
	array( "db" => "wo_master_dtl_proc_id",     "dt" => 0, "field" => "wo_master_dtl_proc_id" ),
	array( "db" => "wo_no",     "dt" => 1, "field" => "wo_no" ),
	array( "db" => "garment_colors", 	"dt" => 2, "field" => "garment_colors"),
	array( "db" => "color_wash", 	"dt" => 3, "field" => "color_wash"),
	array( "db" => "ex_fty_date", 	"dt" => 4, "field" => "ex_fty_date"),
	array( "db" => "seq_ex_fty_date", 	"dt" => 5, "field" => "seq_ex_fty_date"),
	array( "db" => "cutting_qty",     "dt" => 6, "field" => "cutting_qty" ),
	array( "db" => "wo_master_dtl_proc_id", 	"dt" => 7, "field" => "wo_master_dtl_proc_id" ,
			'formatter' => function( $d, $row ) {
					
					$isijam = "<a href='javascript:void(0)' data-toggle='modal' data-target='#funModal' id='mod' onclick='model($d)' class='label label-warning' style='font-size:12px;'>Detail</a>";
				
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
