<?php
//error_reporting(0);
require( '../../../funlibs.php' );
$con=new Database;
session_start();
$table = 'laundry_master_machine';
$primaryKey = 'machine_id';
$joinQuery = "";       
$extraWhere="machine_status != 2";
$columns = array(
	array( "db" => "machine_id",     "dt" => 0, "field" => "machine_id" ),
	array( "db" => "machine_code", 	"dt" => 1, "field" => "machine_code" ),
	array( "db" => "machine_name", 	"dt" => 2, "field" => "machine_name" ),
	array( "db" => "machine_category", 	"dt" => 3, "field" => "machine_category" ),
	array( "db" => "machine_type_use", 	"dt" => 4, "field" => "machine_type_use", 
			'formatter' => function( $d, $row ) {
				if($d==1){
					$isijum = "Multiple";
				} else {
					$isijum = "Single";
				}
				return "$isijum";
	}),
	array( "db" => "machine_id", 	"dt" => 5, "field" => "machine_id", 
			'formatter' => function( $d, $row ) {
				$isijum = "<a href='javascript:void(0)' data-toggle='modal' data-target='#funModalrec' id='mod' onclick='modalmachproc($d,4)' class='label label-warning' style='font-size:12px;'>Process</a>";
				return "$isijum";
	}),
	array( "db" => "machine_status", 	"dt" => 6, "field" => "machine_status", 
			'formatter' => function( $d, $row ) {
					if ($d == 1){
						$isijum = "<a href='javascript:void(0)' class='label label-success'>Active</a>";
					} else if ($d == 0){
						$isijum = "<a href='javascript:void(0)' class='label label-danger'>Non Active</a>";
					}
				return "$isijum";

	}),
	array( "db" => "machine_id", 	"dt" => 7, "field" => "machine_id", 
			'formatter' => function( $d, $row ) {
					//$base = base64_encode("&d=".$d);
					$isijam = "
							<a href='javascript:void(0)' class='on-default edit-row' onclick='edit($d)'><i class='fa fa-pencil'></i></a>
							
							";
					
				return "$isijam";

	}),
	array( "db" => "machine_code", 	"dt" => 8, "field" => "machine_code", 
			'formatter' => function( $d, $row ) {
					//$base = base64_encode("&d=".$d);
					$isijam = "
							<a href='lib/pdf-qrcode_machine.php?c=".base64_encode($d)."' class='label label-primary' style='cursor:pointer' target='_blank'><i class='fa fa-print' aria-hidden='true'>
							</i></a>
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