<?php
error_reporting(1);
require( '../../../funlibs.php' );
$con=new Database;
session_start();

$cmt = $con->searchseqcmt($_GET['cm']);
$colors4 = $con->searchseq($_GET['co']);
$colors2 = explode('Y|',$colors4);
$colors3 = implode('&',$colors2);
$colors = trim($colors3);

if ($cmt != ''){
	$cm = "and wo_no = '$cmt'";
} else {
	$cm = "";
}

if ($colors != ''){
	$co = "and garment_colors = '$colors'";
} else {
	$co = "";
}

$table = 'laundry_lot_number';
$primaryKey = 'lot_id';
//$joinQuery = "";  
$extraWhere="wo_master_dtl_proc_id =  '".$_GET['id']."'";

$columns = array(
	array( "db" => "lot_id",     "dt" => 0, "field" => "lot_id" ),
	array( "db" => "lot_no",     "dt" => 1, "field" => "lot_no" ),
	array( "db" => "lot_jenis", 	"dt" => 2, "field" => "lot_jenis"),
	array( "db" => "role_wo_master_id",     "dt" => 3, "field" => "role_wo_master_id" ),
	array( "db" => "lot_qty", 	"dt" => 4, "field" => "lot_qty" ),
);
//var_dump($columns);
$sql_details = array(	
); 
 
echo json_encode(
	Database::simple( $_GET, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere )
);
