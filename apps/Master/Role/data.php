<?php
//error_reporting(0);
require( '../../../funlibs.php' );
$con=new Database;
session_start();
$table = 'm_role';
$primaryKey = 'role_id';
$joinQuery = "";       
$extraWhere="role_id between 2000 and 2100";
$columns = array(
	array( "db" => "role_id",     "dt" => 0, "field" => "role_id" ),
	array( "db" => "name", 	"dt" => 1, "field" => "name" ),
	array( "db" => "role_id", 	"dt" => 2, "field" => "role_id", 
			'formatter' => function( $d, $row ) {
					//$base = base64_encode("&d=".$d);
					$isijam = "
					<a href='javascript:void(0)' class='on-default edit-row' onclick=edit('$d')><i class='fa fa-pencil'></i></a>
					
					";
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
// <a href='#' class='on-default remove-row'><i class='fa fa-trash-o'></i></a>