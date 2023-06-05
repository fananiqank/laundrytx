<?php
//error_reporting(1);
require( '../../../funlibs.php' );
$con=new Database;
session_start();

$cmt = $con->searchseqcmt($_GET['cm']);
$colors = $con->searchseq($_GET['co']);
$inseams = $_GET['in'];
$sizes = $_GET['si'];
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

if ($inseams != ''){
	$in = "and garment_inseams = '$inseams'";
} else {
	$in = "";
}

if ($sizes != ''){
	$si = "and garment_sizes = '$sizes'";
} else {
	$si = "";
}

$table = 'wip_dtl';
$primaryKey = 'wip_dtl_id';
$joinQuery = "from (select wip_dtl_id,wo_no,garment_colors,garment_inseams,garment_sizes,wip_dtl_status,quantity,
CONCAT(quantity,'_',wip_dtl_id) as qty,
CONCAT(wo_no,'_',garment_colors,'_',garment_inseams,'_',garment_sizes) as bpo
from wip_dtl) as ab";  
$extraWhere="wip_dtl_status = 0 $cm $co $in $si";

// $selbpo = $con->select("wo_sb","buyer_po_number","wo_no = '$wono' and garment_colors = '$colors' and garment_inseams = '$inseams' and garment_sizes= '$sizes'");
$columns = array(
	array( "db" => "wip_dtl_id",     "dt" => 0, "field" => "wip_dtl_id" ),
	array( "db" => "wo_no", 	"dt" => 1, "field" => "wo_no"),
	array( "db" => "garment_colors",     "dt" => 2, "field" => "garment_colors" ),
	array( "db" => "garment_inseams",     "dt" => 3, "field" => "garment_inseams" ),
	array( "db" => "garment_sizes", 	"dt" => 4, "field" => "garment_sizes"),
	array( "db" => "quantity", 	"dt" => 5, "field" => "quantity"),
	array( "db" => "qty", 	"dt" => 6, "field" => "qty" ,
			'formatter' => function( $d, $row ) {
					$expd = explode("_", $d);
					$qtysend = $expd[0];
					$idsend = $expd[1];
					$isiqtysend = "					
					 <input name='qtysend_$idsend' id='qtysend[]' value='$qtysend' type='text' class='form-control' style='background-color:#FFE4E1'>
					";
				return "$isiqtysend";

			}),
	array( "db" => "qty", 	"dt" => 7, "field" => "qty" ,
			'formatter' => function( $d, $row ) {
					$expe = explode("_", $d);
					$qtyin = $expe[0];
					$idin = $expe[1];
					$isiqtyin = "					
					 <input name='qtyin_$idin' id='qtysend[]' value='$qtyin' type='text' class='form-control'>
					";
				return "$isiqtyin";

			}),
	array( "db" => "wip_dtl_id", 	"dt" => 8, "field" => "wip_dtl_id" ,
			'formatter' => function( $d, $row ) {
					
					$isijam = "	
					<a href='javascript:void(0)' class='label label-success' onClick='conf($d)'><i class='fa fa-check'></i></a>				
					
					";
					// <a href='javascript:void(0)' class='label label-danger'  data-toggle='modal' data-target='#funModal' id='mod' onclick='modelrec($d,5)'><i class='fa fa-close'></i></a>
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
