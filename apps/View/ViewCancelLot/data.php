
<?php
//error_reporting(0);
require( '../../../funlibs.php' );
$con=new Database;
session_start();

$cmt = $con->searchseqcmt($_GET['cm']);
$colors4 = $con->searchseq($_GET['co']);
$colors2 = explode('Y|',$colors4);
$colors3 = implode('&',$colors2);
$colors = trim($colors3);
$exftydate =  $_GET['xty'];

if ($cmt != ''){
	$cm = "and wo_no = '".$cmt."'";
} else {
	$cm = "";
}

if ($colors != ''){
	$co = "and garment_colors = '".$colors."'";
} else {
	$co = "and ex_fty_date between (now() - interval '3 months')::date and (now() + interval '1 day')::date";
}

if ($exftydate != ''){
	$xty = "and DATE(ex_fty_date) = '".$exftydate."'";
} else {
	$xty = "";
}

$table = 'laundry_lot_number';
$primaryKey = 'lot_id';
$joinQuery = "from (select a.lot_no,a.cancel_lot_qty,a.cancel_remark,a.cancel_createdate,a,user_code,a.lot_type,b.wo_no,b.garment_colors,b.color_wash,DATE(b.ex_fty_date) as ex_fty_date from laundry_cancel_lot a JOIN laundry_wo_master_dtl_proc b using(wo_master_dtl_proc_id) order by a.cancel_createdate DESC) as asiap";  

$extraWhere="lot_no ilike '%' $cm $co $xty";

$columns = array(
	array( "db" => "lot_no",     "dt" => 0, "field" => "lot_no" ),
	array( "db" => "wo_no",     "dt" => 1, "field" => "wo_no" ),
	array( "db" => "garment_colors", 	"dt" => 2, "field" => "garment_colors"),
	array( "db" => "color_wash", 	"dt" => 3, "field" => "color_wash"),
	array( "db" => "ex_fty_date", 	"dt" => 4, "field" => "ex_fty_date"),
	array( "db" => "cancel_lot_qty", 	"dt" => 5, "field" => "cancel_lot_qty"),
	array( "db" => "cancel_createdate", 	"dt" => 6, "field" => "cancel_createdate"),
	array( "db" => "user_code", 	"dt" => 7, "field" => "user_code"),
	array( "db" => "cancel_remark",     "dt" => 8, "field" => "cancel_remark" ),
	
	// array( "db" => "act", 	"dt" => 12, "field" => "act" ,
	// 		'formatter' => function( $d, $row ) {
	// 			$expact = explode('_', $d);
	// 			$lotno = $expact[0];
	// 			$stat = $expact[1];
				
	// 			if($stat > 0 && $_SESSION['ID_LOGIN'] == '871') {
	// 				$cancel = "<a href='javascript:void(0)' class='label label-danger'  data-toggle='modal' data-target='#funModal' id='mod' onclick=modelcancellot('".$lotno."',2)><i class='fa fa-close' aria-hidden='true' style='font-size:14px;'></i></a>";
	// 			} else {
	// 				$cancel = "";
	// 			}
	// 			//if($stat == 1){
	// 				$isijum = "<a href='lib/pdf-qrcode.php?c=".base64_encode($lotno)."' class='label label-primary' style='cursor:pointer' target='_blank'><i class='fa fa-print' aria-hidden='true' style='font-size:14px;'></i></a>&nbsp;";
	// 			// } else {
	// 			// 	$isijum = "";
	// 			// }
	// 			return "$isijum";

	// 		}),
);
//var_dump($columns);
$sql_details = array(	
); 
 
//echo "select * from $joinQuery where $extraWhere";
echo json_encode(
	Database::simple( $_GET, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere )
);
