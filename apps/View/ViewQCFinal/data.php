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
	$cm = "and ex_fty_date between (now() - interval '1 months')::date and (now() + interval '1 day')::date";
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
$joinQuery = "from (SELECT
					DISTINCT(wo_no), garment_colors,ex_fty_date,ex_fty_date_show,COALESCE(jumgood,0) as jumgood,COALESCE(jumreject,0) as jumreject,COALESCE(jumrework,0) as jumrework,
					CONCAT(wo_master_dtl_proc_id,'_',type_source,'_',COALESCE(jumgood,0),'_',COALESCE(jumreject,0),'_',COALESCE(jumrework,0),'_',wo_master_dtl_proc_status) 
					as id,wo_master_dtl_proc_status,color_wash
			FROM
					(
					SELECT
						A.wo_no,
						A.garment_colors,
						A.ex_fty_date,
						to_char( A.ex_fty_date, 'DD-MM-YYYY' ) AS ex_fty_date_show,
						B.jumgood,
						C.jumreject,
						D.jumrework,
						E.type_source,
						A.wo_master_dtl_proc_id,
						E.wo_master_dtl_proc_status,
						E.color_wash
					FROM
						laundry_wo_master_dtl_qc A 
						JOIN laundry_wo_master_dtl_proc E ON A.wo_master_dtl_proc_id=E.wo_master_dtl_proc_id 
						and wo_master_dtl_proc_status = 1
						LEFT JOIN ( SELECT SUM ( wo_master_dtl_qc_qty ) AS jumgood,b.wo_no,b.garment_colors,b.ex_fty_date	
									FROM (SELECT wo_master_dtl_qc_qty, P.wo_master_dtl_proc_id, wo_master_dtl_qc_status FROM
											laundry_wo_master_dtl_qc P
											JOIN ( SELECT MAX ( role_wo_name_seq ) AS role_wo_name_seq,wo_master_dtl_proc_id
															FROM laundry_wo_master_dtl_qc WHERE wo_master_dtl_qc_type = 1 AND wo_master_dtl_qc_status = 1 
															GROUP BY wo_master_dtl_proc_id ) AS Q 
											ON P.role_wo_name_seq = Q.role_wo_name_seq and P.wo_master_dtl_proc_id=Q.wo_master_dtl_proc_id
											WHERE wo_master_dtl_qc_type = 1 AND wo_master_dtl_qc_status = 1) A
									JOIN laundry_wo_master_dtl_proc b ON A.wo_master_dtl_proc_id=b.wo_master_dtl_proc_id 
									and b.wo_master_dtl_proc_status = 1
									GROUP BY b.wo_no,b.garment_colors,b.ex_fty_date) as B 
									ON A.wo_no=B.wo_no and A.garment_colors=B.garment_colors and A.ex_fty_date=B.ex_fty_date
						LEFT JOIN ( SELECT SUM ( wo_master_dtl_qc_qty ) AS jumreject,a.wo_no,a.garment_colors,a.ex_fty_date	
									FROM laundry_wo_master_dtl_qc a
									JOIN laundry_wo_master_dtl_proc b ON A.wo_master_dtl_proc_id=b.wo_master_dtl_proc_id 
									and b.wo_master_dtl_proc_status = 1
									WHERE wo_master_dtl_qc_type = 2 and wo_master_dtl_qc_status = 1
									GROUP BY a.wo_no,a.garment_colors,a.ex_fty_date) as C
									ON A.wo_no=C.wo_no and A.garment_colors=C.garment_colors and A.ex_fty_date=C.ex_fty_date
						LEFT JOIN ( SELECT SUM ( wo_master_dtl_qc_qty ) AS jumrework,a.wo_no,a.garment_colors,a.ex_fty_date
									FROM laundry_wo_master_dtl_qc a
									JOIN laundry_wo_master_dtl_proc b ON A.wo_master_dtl_proc_id=b.wo_master_dtl_proc_id 
									and b.wo_master_dtl_proc_status = 1 
									WHERE wo_master_dtl_qc_type = 3 and wo_master_dtl_qc_status = 1
									GROUP BY a.wo_no,a.garment_colors,a.ex_fty_date) as D
									ON A.wo_no=D.wo_no and A.garment_colors=D.garment_colors and A.ex_fty_date=D.ex_fty_date
					
					) AS asi
					UNION
					
					SELECT
					DISTINCT(wo_no), garment_colors,ex_fty_date,ex_fty_date_show,COALESCE(jumgood,0) as jumgood,COALESCE(jumreject,0) as jumreject,COALESCE(jumrework,0) as jumrework,
					CONCAT(wo_master_dtl_proc_id,'_',type_source,'_',COALESCE(jumgood,0),'_',COALESCE(jumreject,0),'_',COALESCE(jumrework,0),'_',wo_master_dtl_proc_status) 
					as id,wo_master_dtl_proc_status,color_wash
			FROM
					(
					SELECT
						A.wo_no,
						A.garment_colors,
						A.ex_fty_date,
						to_char( A.ex_fty_date, 'DD-MM-YYYY' ) AS ex_fty_date_show,
						B.jumgood,
						C.jumreject,
						D.jumrework,
						E.type_source,
						A.wo_master_dtl_proc_id,
						E.wo_master_dtl_proc_status,
						E.color_wash
					FROM
						laundry_wo_master_dtl_qc A 
						JOIN laundry_wo_master_dtl_proc E ON A.wo_master_dtl_proc_id=E.wo_master_dtl_proc_id 
						and wo_master_dtl_proc_status = 2
						LEFT JOIN ( SELECT SUM ( wo_master_dtl_qc_qty ) AS jumgood,b.wo_no,b.garment_colors,b.ex_fty_date	
									FROM (SELECT wo_master_dtl_qc_qty,P.wo_master_dtl_proc_id,wo_master_dtl_qc_status 
											FROM laundry_wo_master_dtl_qc P
												JOIN ( SELECT MAX ( role_wo_name_seq ) AS role_wo_name_seq,wo_master_dtl_proc_id
																FROM laundry_wo_master_dtl_qc WHERE wo_master_dtl_qc_type = 1 AND wo_master_dtl_qc_status = 1 
																GROUP BY wo_master_dtl_proc_id ) AS Q 
												ON P.role_wo_name_seq = Q.role_wo_name_seq and P.wo_master_dtl_proc_id=Q.wo_master_dtl_proc_id
												WHERE wo_master_dtl_qc_type = 1 AND wo_master_dtl_qc_status = 1) A
									JOIN laundry_wo_master_dtl_proc b ON A.wo_master_dtl_proc_id=b.wo_master_dtl_proc_id 
									and b.wo_master_dtl_proc_status = 2
									GROUP BY b.wo_no,b.garment_colors,b.ex_fty_date) as B 
									ON A.wo_no=B.wo_no and A.garment_colors=B.garment_colors and A.ex_fty_date=B.ex_fty_date
						LEFT JOIN ( SELECT SUM ( wo_master_dtl_qc_qty ) AS jumreject,a.wo_no,a.garment_colors,a.ex_fty_date	
									FROM laundry_wo_master_dtl_qc a
									JOIN laundry_wo_master_dtl_proc b ON A.wo_master_dtl_proc_id=b.wo_master_dtl_proc_id 
									and b.wo_master_dtl_proc_status = 2
									WHERE wo_master_dtl_qc_type = 2 and wo_master_dtl_qc_status = 1
									GROUP BY a.wo_no,a.garment_colors,a.ex_fty_date) as C
									ON A.wo_no=C.wo_no and A.garment_colors=C.garment_colors and A.ex_fty_date=C.ex_fty_date
						LEFT JOIN ( SELECT SUM ( wo_master_dtl_qc_qty ) AS jumrework,a.wo_no,a.garment_colors,a.ex_fty_date
									FROM laundry_wo_master_dtl_qc a
									JOIN laundry_wo_master_dtl_proc b ON A.wo_master_dtl_proc_id=b.wo_master_dtl_proc_id 
									and b.wo_master_dtl_proc_status = 2
									WHERE wo_master_dtl_qc_type = 3 and wo_master_dtl_qc_status = 1
									GROUP BY a.wo_no,a.garment_colors,a.ex_fty_date) as D
									ON A.wo_no=D.wo_no and A.garment_colors=D.garment_colors and A.ex_fty_date=D.ex_fty_date
					
					) AS asu
					
					)	as DJ";  
$extraWhere="wo_no ilike '%' $cm $co $type $xty";

$columns = array(
	array( "db" => "wo_no",     "dt" => 0, "field" => "wo_no" ),
	array( "db" => "garment_colors", 	"dt" => 1, "field" => "garment_colors"),
	array( "db" => "color_wash", 	"dt" => 2, "field" => "color_wash"),
	array( "db" => "ex_fty_date_show",     "dt" => 3, "field" => "ex_fty_date_show" ),
	array( "db" => "wo_master_dtl_proc_status", 	"dt" => 4, "field" => "wo_master_dtl_proc_status",
		'formatter' => function( $d, $row ) {
				if ($d == 1) {
					$detail = "<label class='label label-primary'>New</label>&nbsp;";
				} else {
					$detail = "<label class='label label-danger'>Rework</label>&nbsp;";
				}
				return "$detail";
		}
	),
	array( "db" => "jumgood",     "dt" => 5, "field" => "jumgood" ),
	array( "db" => "jumreject",     "dt" => 6, "field" => "jumreject" ),
	array( "db" => "jumrework", 	"dt" => 7, "field" => "jumrework" ),
	// array( "db" => "id", 	"dt" => 7, "field" => "id",
	// 	'formatter' => function( $d, $row ) {
	// 			$expqc = explode('_',$d);
	// 			$dataqc = $expqc[0];
	// 			$type = $expqc[1];
	// 			$good= $expqc[2];
	// 			$reject= $expqc[3];
	// 			$rework= $expqc[4];
	// 			$status = $expqc[5];
	// 			if ($type == 3) {
	// 				$detail = "<a href='javascript:void(0)' data-toggle='modal' data-target='#funModalrec' id='mod' onclick=modelqcdetqr($dataqc,$good,$reject,$rework,$status) class='label label-success'><i class='fa fa-list' aria-hidden='true' style='font-size:14px;'></i></a>&nbsp;";
	// 			} else {
	// 				$detail = "";
	// 			}
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
