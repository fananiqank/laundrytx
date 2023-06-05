<?php
error_reporting(1);
require( '../../../funlibs.php' );
$con=new Database;
session_start();

$cmt = $con->searchseqcmt($_GET['cm']);
$colors = $con->searchseq($_GET['co']);

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

$table = 'laundry_receive';
$primaryKey = 'rec_id';
$joinQuery = "from (select a.rec_id,a.rec_no,a.rec_status,b.wo_no,b.garment_colors,SUM(b.wo_master_dtl_qty_rec) as qty,a.user_id
from laundry_receive a join laundry_wo_master_dtl b on a.rec_id=b.rec_id GROUP BY a.rec_no,a.rec_status,b.wo_no,b.garment_colors,a.rec_id) as asi";  
$extraWhere="rec_status > 0 $cm $co ";

$columns = array(
	array( "db" => "rec_id",     "dt" => 0, "field" => "rec_id" ),
	array( "db" => "rec_no",     "dt" => 1, "field" => "rec_no" ),
	array( "db" => "wo_no", 	"dt" => 2, "field" => "wo_no"),
	array( "db" => "garment_colors",     "dt" => 3, "field" => "garment_colors" ),
	array( "db" => "qty", 	"dt" => 4, "field" => "qty" ),
	array( "db" => "user_id", 	"dt" => 5, "field" => "user_id" ),
);
//var_dump($columns);
$sql_details = array(	
); 
 
//echo "select * from wip_dtl_id where $extraWhere";
echo json_encode(
	Database::simple( $_GET, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere )
);
