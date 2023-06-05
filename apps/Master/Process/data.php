<?php
//error_reporting(0);
require( '../../../funlibs.php' );
$con=new Database;
session_start();
$table = 'laundry_master_process';
$primaryKey = 'master_process_id';
$joinQuery = "from (select a.master_process_id,a.master_process_name,a.master_process_status,concat(a.master_process_id,'_',a.master_process_status) as status_process,b.master_type_process_name from laundry_master_process a join laundry_master_type_process b on a.master_type_process = b.master_type_process_id ) as process";       
$extraWhere="master_process_status != 2";
$columns = array(
	array( "db" => "master_process_id",     "dt" => 0, "field" => "master_process_id" ),
	array( "db" => "master_process_name", 	"dt" => 1, "field" => "master_process_name" ),
	array( "db" => "master_type_process_name", 	"dt" => 2, "field" => "master_type_process_name" ),
	array( "db" => "status_process", 	"dt" => 3, "field" => "status_process", 
			'formatter' => function( $d, $row ) {
					$expstatus = explode('_', $d);
					$id = $expstatus[0];
					if ($expstatus[1] == 1){
						$isijum = "<a href='javascript:void(0)' class='label label-warning'  data-toggle='modal' data-target='#funModal' id='mod' onclick='modalproc($id)' style='cursor:pointer'>Detail</a>";
					} else {
						$isijum = "<b><i>Non Active</i></b>";
					}
				return "$isijum";

	}),
	array( "db" => "master_process_id", 	"dt" => 4, "field" => "master_process_id", 
			'formatter' => function( $d, $row ) {
					//$base = base64_encode("&d=".$d);
					$isijam = "<a href='javascript:void(0)' class='on-default edit-row' onclick='edit($d)'><i class='fa fa-pencil'></i></a>";
				
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