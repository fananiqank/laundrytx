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
	$cmm1 = "";
	$cmm2 = "";
} else {
	$cm="";
	$cmm1 = "and ex_fty_date between (now() - interval '1 months')::date and (now() + interval '1 day')::date";
	// $cmm2 = "and a.ex_fty_date between (now() - interval '1 months')::date and (now() + interval '1 day')::date";
}

if ($colors != ''){
	$co = "and garment_colors = '".$colors."'";
	$cot = "and color = '".$colors."'";
} else {
	$co = "";
	$cot = "";
}

if ($exftydate != ''){
	$xty = "and DATE(ex_fty_date) = '".$exftydate."'";
} else {
	$xty = "";
}

if ($_GET['typeseq'] != '0'){
	$typeq = "and wo_master_dtl_proc_status = '".$_GET['typeseq']."'";
} else {
	$typeq = "";
}

$view = $_GET['v'];
$table = 'laundry_master_shift';
$primaryKey = 'shift_id';
$joinQuery = "from (select wo_master_dtl_proc_id, a.wo_no, a.garment_colors, wo_master_dtl_proc_qty_md,a.color_wash, 
					concat(wo_master_dtl_proc_status,'_',rework_seq,'_',role_wo_master_remark) as newrework,
					to_char(a.ex_fty_date,'DD-MM-YYYY') as ex_fty_date_show,a.ex_fty_date,cut_qty as cutting_qty,wo_master_dtl_proc_status
					from laundry_wo_master_dtl_proc a 
					JOIN laundry_role_wo_master b using(role_wo_master_id)
					LEFT JOIN (select COUNT(qrcode_key) as cut_qty,wo_no, color,ex_fty_date from qrcode_ticketing_master where washtype = 'Wash' $cmm1 $cm $cot $xty GROUP BY wo_no, color,ex_fty_date) as g on a.wo_no = g.wo_no and a.garment_colors = g.color and a.ex_fty_date=g.ex_fty_date) as m";  

$extraWhere="wo_no ilike '%' $cmm1 $cm $co $xty $typeq";

$columns = array(
	array( "db" => "wo_master_dtl_proc_id",     "dt" => 0, "field" => "wo_master_dtl_proc_id" ),
	array( "db" => "wo_no",     "dt" => 1, "field" => "wo_no" ),
	array( "db" => "garment_colors", 	"dt" => 2, "field" => "garment_colors"),
	array( "db" => "color_wash", 	"dt" => 3, "field" => "color_wash"),
	array( "db" => "ex_fty_date_show",     "dt" => 4, "field" => "ex_fty_date_show" ),
	array( "db" => "cutting_qty",     "dt" => 5, "field" => "cutting_qty" ),
    array( "db" => "newrework",     "dt" => 6, "field" => "newrework" ,
			'formatter' => function( $d, $row ) {
					$expnew = explode('_',$d);	
					if ($expnew[0] == '1'){
						$stat = "<span style='color:#4169E1;'><b>New</b></span>";
					} else {
						$stat = "<span style='color:#B22222;'><b>Rework $expnew[1]-$expnew[2]</b></span>";
					}

					$isijum = $stat;
					
				return "$isijum";

			}),
	array( "db" => "wo_master_dtl_proc_id", 	"dt" => 7, "field" => "wo_master_dtl_proc_id" ,
			'formatter' => function( $d, $row ) {
					$isijam = "<a href='javascript:void(0)' data-toggle='modal' data-target='#funModalrec' id='mod' onclick='modelremarkdate($d)' class='label label-primary' style='font-size:12px;'>Check</a>";
					
					// $isijam = "<a href='javascript:void(0)' data-toggle='modal' data-target='#funModal' id='mod' onclick='mode($d)' class='label label-warning' style='cursor:pointer'>details</a>";
				/*return "
				<a href='javascript:void(0)' data-toggle='modal' id='mod' data-target='#datajaminan' onClick=detiljams('$d') class='label label-success' style='cursor:pointer'>Lihat Jaminan</a>
				";*/
				return $isijam;

			}),
	array( "db" => "wo_master_dtl_proc_id", 	"dt" => 8, "field" => "wo_master_dtl_proc_id" ,
			'formatter' => function( $d, $row ) {
					
					$isijam = "<a href='javascript:void(0)' data-toggle='modal' data-target='#funModal' id='mod' onclick='modeldetail($d,3)' class='label label-warning' style='font-size:12px;'>Detail</a>";
					
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
