<?php
//error_reporting(0);
require( '../../../funlibs.php' );
$con=new Database;
session_start();
$table = 'laundry_master_shift';
$primaryKey = 'shift_id';
$joinQuery = "";       
$extraWhere="shift_status != 2";
$columns = array(
	array( "db" => "shift_id",     "dt" => 0, "field" => "shift_id" ),
	array( "db" => "shift_name", 	"dt" => 1, "field" => "shift_name" ),
	array( "db" => "shift_time_start", 	"dt" => 2, "field" => "shift_time_start" ),
	array( "db" => "shift_time_end", 	"dt" => 3, "field" => "shift_time_end" ),
	array( "db" => "shift_status", 	"dt" => 4, "field" => "shift_status", 
			'formatter' => function( $d, $row ) {
				if ($d == 1){
					$isijam = "<a href='javascript:void(0)' class='label label-success'>Active</a>";
				} else {
					$isijam = "<a href='javascript:void(0)' class='label label-danger'>Non Active</a>";
				}
			
					
			return "$isijam";

	}),
	array( "db" => "shift_id", 	"dt" => 5, "field" => "shift_id", 
			'formatter' => function( $d, $row ) {
					//$base = base64_encode("&d=".$d);
					$isijam = "
							<a href='javascript:void(0)' class='on-default edit-row' onclick='edit($d)'><i class='fa fa-pencil'></i></a>
							<a href='javascript:void(0)' class='on-default edit-row' onclick='deleted($d)'><i class='fa fa-trash'></i></a>
							";
					
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
// <a href='#' class='on-default remove-row'><i class='fa fa-trash-o'></i></a>