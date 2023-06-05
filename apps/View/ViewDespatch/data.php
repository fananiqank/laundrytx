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

// if ($_GET['type'] != ''){
// 	$type = "and wo_master_dtl_qc_type = '$_GET[type]'";
// } else {
// 	$type = "";
// } 

if ($exftydate != ''){
	$xty = "and DATE(ex_fty_date) = '".$exftydate."'";
} else {
	$xty = "";
}


$table = 'laundry_master_shift';
$primaryKey = 'shift_id';
$joinQuery = "FROM
				(	
					SELECT 
						  wo_no,
							garment_colors,
							ex_fty_date,
							to_char( ex_fty_date, 'DD-MM-YYYY' ) AS ex_fty_date_show,
							E.wo_master_dtl_proc_id,
							E.wo_master_dtl_proc_status,
							E.color_wash,
						    SUM(wo_master_dtl_desp_qty) as qty	
						FROM
							laundry_wo_master_dtl_despatch
							A JOIN laundry_wo_master_dtl_proc E ON A.wo_master_dtl_proc_id = E.wo_master_dtl_proc_id 
						WHERE
							wo_master_dtl_desp_status = 1
						GROUP BY 
						 wo_no,
							garment_colors,
							ex_fty_date,
							E.wo_master_dtl_proc_id,
							E.wo_master_dtl_proc_status,
							E.color_wash
				) AS asi";  
$extraWhere="wo_no ilike '%' $cm $co $type $xty";

$columns = array(
	array( "db" => "wo_no",     "dt" => 0, "field" => "wo_no" ),
	array( "db" => "garment_colors", 	"dt" => 1, "field" => "garment_colors"),
	array( "db" => "color_wash", 	"dt" => 2, "field" => "color_wash"),
	array( "db" => "ex_fty_date_show",     "dt" => 3, "field" => "ex_fty_date_show" ),
	array( "db" => "wo_master_dtl_proc_status", 	"dt" => 4, "field" => "wo_master_dtl_proc_status",
		'formatter' => function( $d, $row ) {
				if($d == '1'){
					$detail = "<label class='label label-primary'>New</label>&nbsp;";
				} else {
					$detail = "<label class='label label-danger'>Rework</label>&nbsp;";
				}
				return "$detail";
		}
	),
	array( "db" => "qty",     "dt" => 5, "field" => "qty" ),
	// array( "db" => "wo_master_dtl_proc_id", 	"dt" => 4, "field" => "wo_master_dtl_proc_id",
	// 	'formatter' => function( $d, $row ) {
	// 			$detail = "<a href='javascript:void(0)' data-toggle='modal' data-target='#funModal' id='mod' onclick=modeldespdetqr($d) class='label label-primary'><i class='fa fa-list' aria-hidden='true' style='font-size:14px;'></i></a>&nbsp;";
				
	// 			return "$detail";
	// 	}
	// ),
);

//var_dump($columns);
$sql_details = array(	
); 
 
//echo "select * from $joinQuery where $extraWhere";
echo json_encode(
	Database::simple( $_GET, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere )
);
