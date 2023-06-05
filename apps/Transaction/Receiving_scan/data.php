<?php
error_reporting(0);
require( '../../../funlibs.php' );
$con=new Database;
session_start();

// $cmt = $con->searchseqcmt($_GET['cm']);
// $cmt2 = trim($cmt);
$colors = $con->searchseq($_GET['co']);
$colors2 = explode('Y|',$colors);
$colors3 = implode('&',$colors2);
$colors4 = trim($colors3);
// $exftydate =  $_GET['xty'];

if ($_GET[cm] != '') {
	$cm = "wo_no = '" . $_GET[cm] . "'";
} else {
	$cm = "";
}

if ($_GET[co] != '') {
	$co = "and trim(color) = '" . $colors4 . "'";
} else {
	$co = "";
}

// if ($exftydate != '') {
// 	$xty = "and DATE(ex_fty_date) = '" . $exftydate . "'";
// } else {
// 	$xty = "";
// }

$table = 'laundry_master_shift';
$primaryKey = 'shift_id';

$joinQuery = "from (select * from (select a.*,c.color_wash,CONCAT(c.wo_master_dtl_proc_id,'|',cutting_qty,'|',gdpbatch) as idcon from (select wo_no,color,DATE(ex_fty_date) exftydate,gdpbatch,count(gdp_id) qty,user_code,DATE(gdp_datetime) scandate
from qrcode_gdp a join qrcode_ticketing_master b using(ticketid) 
where $cm $co and step_id_detail = 4
GROUP BY wo_no,color,ex_fty_date,gdpbatch,user_code,DATE(gdp_datetime)) a 
left join laundry_wo_master_dtl_proc c on a.wo_no=c.wo_no and trim(a.color)=c.garment_colors and a.exftydate=DATE(c.ex_fty_date) and wo_master_dtl_proc_status = 1) a where gdpbatch not in (select qrcodebatch_no from laundry_qrcodebatch where $cm $co)) as asi";  
$extraWhere="";

$columns = array(
	array( "db" => "wo_no", 	"dt" => 0, "field" => "wo_no"),
	array( "db" => "color",     "dt" => 1, "field" => "color" ),
	array( "db" => "color_wash",     "dt" => 2, "field" => "color_wash" ),
	array( "db" => "exftydate", 	"dt" => 3, "field" => "exftydate" ), 
	array( "db" => "gdpbatch", 	"dt" => 4, "field" => "gdpbatch" ), 
	array( "db" => "qty", 	"dt" => 5, "field" => "qty" ),
	array( "db" => "scandate", 	"dt" => 6, "field" => "scandate" ),
	array( "db" => "user_code", 	"dt" => 7, "field" => "user_code" ),
	//array( "db" => "wash_category_id", 	"dt" => 4, "field" => "wash_category_id"),
	array( "db" => "idcon", 	"dt" => 8, "field" => "idcon",
		'formatter' => function( $d, $row ) {
					$expidcon = explode('|', $d);
					$idproc = $expidcon[0];
					$cutqty = $expidcon[1];
					//$testsa = $expidcon[2];
					if ($idproc != '' && $cutqty != '') {
						$isijum = "<a href='javascript:void(0)' data-toggle='modal' data-target='#funModalrec' id='mod' onClick=modelReceiveScan($idproc,'$expidcon[2]') class='label label-warning' style='font-size:12px;'>Create Lot</a>";
					} else if ($idproc == '') {
						$isijum = "<span style='color:red'>Not Sequence Yet</span>";
					} else if ($cutqty == ''){
						$isijum = "<span style='color:blue'>Cut Qty Not Set</span>";
					} 
					
				return "$isijum";
		}
	),
);
//var_dump($columns);
$sql_details = array(	
); 
 
// echo "select * $joinQuery where $extraWhere";
echo json_encode(
	Database::simple( $_GET, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere )
);
