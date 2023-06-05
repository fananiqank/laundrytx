<?php
error_reporting(1);
require( '../../../funlibs.php' );
$con=new Database;
session_start();

$cmt = $con->searchseqcmt($_GET['cm']);
$cmt2 = trim($cmt);
$colors = $con->searchseq($_GET['co']);
$colors2 = explode('Y|',$colors);
$colors3 = implode('&',$colors2);
$colors4 = trim($colors3);
$exftydate =  $_GET['xty'];

if ($cmt != '') {
	$cm = "trim(wo_no) = '" . $cmt2 . "'";
} else {
	$cm = "";
}

if ($colors != '') {
	$co = "and garment_colors = '" . $colors4 . "'";
} else {
	$co = "";
}

if ($exftydate != '') {
	$xty = "and DATE(ex_fty_date) = '" . $exftydate . "'";
} else {
	$xty = "";
}

$table = 'qrcode_gdp';
$primaryKey = 'gdp_id';
$joinQuery = "from (select a.*,c.color_wash from (select wo_no,color,ex_fty_date,gdpbatch,count(gdp_id) qty
from qrcode_gdp a join qrcode_ticketing_master b using(ticketid) 
where wo_no = '22/EP/W/AFFO/1943/A/1' and color = '6303 AUTHENTIC MID INDIGO WASH' and step_id_detail = 4
GROUP BY wo_no,color,ex_fty_date,gdpbatch) a 
left join laundry_wo_master_dtl_proc c on a.wo_no=c.wo_no and a.color=c.garment_colors and a.ex_fty_date=c.ex_fty_date and wo_master_dtl_proc_status = 1) as asi";  
$extraWhere="";

$columns = array(
	array( "db" => "wo_no", 	"dt" => 0, "field" => "wo_no"),
	array( "db" => "color",     "dt" => 1, "field" => "color" ),
	array( "db" => "color_wash",     "dt" => 2, "field" => "color_wash" ),
	array( "db" => "ex_fty_date", 	"dt" => 3, "field" => "ex_fty_date" ), 
	array( "db" => "qty", 	"dt" => 4, "field" => "qty" ),
	//array( "db" => "wash_category_id", 	"dt" => 4, "field" => "wash_category_id"),
	array( "db" => "wo_no", 	"dt" => 5, "field" => "wo_no",
		'formatter' => function( $d, $row ) {
					$expidcon = explode('_', $d);
					$idproc = $expidcon[0];
					$cutqty = $expidcon[1];
					//$testsa = $expidcon[2];
					if ($idproc != '' && $cutqty != '') {
						$isijum = "<a href='javascript:void(0)' data-toggle='modal' data-target='#funModalrec' id='mod' onClick=modelReceiveScan($idproc) class='label label-warning' style='font-size:12px;'>Create Lot</a>";
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
 
//echo "select * $joinQuery where $extraWhere";
echo json_encode(
	Database::simple( $_GET, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere )
);
