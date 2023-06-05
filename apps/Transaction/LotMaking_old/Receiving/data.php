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

if ($ex_fty_date != ''){
	$exftydate = "and ex = '$colors'";
} else {
	$exftydate = "";
}

$table = 'laundry_receive';
$primaryKey = 'rec_id';
$joinQuery = "from (select 
					A.rec_id,
					A.rec_no,
					A.rec_status,
					B.wo_no,
					B.garment_colors,
					DATE (B.ex_fty_date) as ex_fty_date,
					rec_qty AS qty,
					A.rec_createdby,
					C.username 
					from laundry_receive A 
					JOIN laundry_wo_master_dtl_proc B ON A.wo_master_dtl_proc_id = B.wo_master_dtl_proc_id
					LEFT JOIN m_users C ON A.rec_createdby = C.user_id  
					GROUP BY 
						A.rec_no,
						A.rec_status,
						B.wo_no,
						B.garment_colors,
						B.ex_fty_date,
						A.rec_id,
						C.username ) as asi";  
$extraWhere="rec_status > 0 $cm $co ";

$columns = array(
	array( "db" => "rec_id",     "dt" => 0, "field" => "rec_id" ),
	array( "db" => "rec_no",     "dt" => 1, "field" => "rec_no" ),
	array( "db" => "wo_no", 	"dt" => 2, "field" => "wo_no"),
	array( "db" => "garment_colors",     "dt" => 3, "field" => "garment_colors" ),
	array( "db" => "qty", 	"dt" => 4, "field" => "qty" ),
	array( "db" => "username", 	"dt" => 5, "field" => "username" ),
	array( "db" => "rec_no", 	"dt" => 6, "field" => "rec_no",
		'formatter' => function( $d, $row ) {
					$func = "onClick='modelcetak($d)'";
					$isijum = "<a href='lib/phpqrcode/index.php?no=$d' class='label label-warning' style='cursor:pointer' target='_blank'><i class='fa fa-print' aria-hidden='true' style='font-size:14px;'></i></a>";
	
				return "$isijum";
		}
	),
);
//var_dump($columns);
$sql_details = array(	
); 
 
echo json_encode(
	Database::simple( $_GET, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere )
);
