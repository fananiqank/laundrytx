<?php
error_reporting(1);
require('../../../funlibs.php');
$con = new Database;
session_start();

$cmt = $con->searchseqcmt($_GET['cm']);
$colors4 = $con->searchseq($_GET['co']);
$colors2 = explode('Y|',$colors4);
$colors3 = implode('&',$colors2);
$colors = trim($colors3);
$exftydate =  $_GET['xty'];

if ($cmt != '') {
	$cm = "and wo_no = '" . $cmt . "'";
} else {
	$cm = "and ex_fty_date between (now() - interval '1 months')::date and (now() + interval '1 day')::date";
}

if ($colors != '') {
	$co = "and garment_colors = '" . $colors . "'";
} else {
	$co = "";
}

if ($exftydate != '') {
	$xty = "and DATE(ex_fty_date) = '" . $exftydate . "'";
} else {
	$xty = "";
}

$table = 'laundry_master_shift';
$primaryKey = 'shift_id';
$joinQuery = "from (select a.rec_id,a.rec_no,a.rec_status,a.wo_no,a.garment_colors,b.color_wash,
					a.rec_qty as qty,a.rec_createdby,a.user_code,c.username,d.shift_name,rec_break_status,CONCAT(a.rec_no,'_',a.rec_type,'_',a.rec_break_status,'_',process_id) as rec_no_type,b.ex_fty_date, to_char(b.ex_fty_date, 'DD-MM-YYYY') as ex_fty_date_show
					from laundry_receive a
					JOIN laundry_wo_master_dtl_proc b on a.wo_master_dtl_proc_id = b.wo_master_dtl_proc_id
					LEFT JOIN m_users c on a.rec_createdby=c.user_id 
					LEFT JOIN laundry_master_shift d on a.shift_id = d.shift_id
					LEFT JOIN (select max(process_id) as process_id,lot_no from laundry_process GROUP BY lot_no) as e on a.rec_no=e.lot_no
					GROUP BY a.rec_no,a.rec_status,a.wo_no,a.garment_colors,b.ex_fty_date,a.rec_id,c.username,d.shift_name,b.color_wash,process_id) as asi";
$extraWhere = "rec_status > 0 $cm $co $xty";

$columns = array(
	array("db" => "rec_id",     "dt" => 0, "field" => "rec_id"),
	array("db" => "rec_no",     "dt" => 1, "field" => "rec_no"),
	array("db" => "wo_no", 	"dt" => 2, "field" => "wo_no"),
	array("db" => "garment_colors",     "dt" => 3, "field" => "garment_colors"),
	array("db" => "color_wash",     "dt" => 4, "field" => "color_wash"),
	array("db" => "ex_fty_date_show",     "dt" => 5, "field" => "ex_fty_date_show"),
	array("db" => "qty", 	"dt" => 6, "field" => "qty"),
	array("db" => "user_code", 	"dt" => 7, "field" => "user_code"),
	// array("db" => "username", 	"dt" => 8, "field" => "username"),
	// array("db" => "shift_name", 	"dt" => 9, "field" => "shift_name"),
	array( "db" => "rec_break_status", 	"dt" => 8, "field" => "rec_break_status" ,
			'formatter' => function( $d, $row ) {
					if ($d == '1'){
						$isijam= "<span class='label label-success'>Active</span>";
					}
					else {
						$isijam= "<span class='label label-danger'>Break</span>";
					}
					
				return "$isijam";

			}),
	array(
		"db" => "rec_no_type", 	"dt" => 9, "field" => "rec_no_type",
		'formatter' => function ($d, $row) {
			$exprec = explode('_',$d);
			$recno = $exprec[0];
			$type = $exprec[1];
			if ($type == 2) {
				$detail = "<a href='javascript:void(0)' data-toggle='modal' data-target='#funModalrec' id='mod' onclick=modelrecdetqr('".$recno."') class='label label-primary'><i class='fa fa-list' aria-hidden='true' style='font-size:14px;'></i></a>&nbsp;";
			} else {
				$detail = "";
			}

			if($exprec[2] > 0 && $_SESSION['ID_LOGIN'] == '871' && $exprec[3] == '') {
				$cancel = "<a href='javascript:void(0)' class='label label-danger'  data-toggle='modal' data-target='#funModal' id='mod' onclick=modelcancellot('".$recno."',1)><i class='fa fa-close' aria-hidden='true' style='font-size:14px;'></i></a>";
			} else {
				$cancel = "";
			}
			$isijum = $detail."<a href='lib/pdf-qrcode.php?c=".base64_encode($recno)."' class='label label-warning' style='cursor:pointer' target='_blank'><i class='fa fa-print' aria-hidden='true' style='font-size:14px;'></i></a>&nbsp;".$cancel;

			return "$isijum";
		}
	),
);
//var_dump($columns);
$sql_details = array();

echo json_encode(
	Database::simple($_GET, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere)
);
