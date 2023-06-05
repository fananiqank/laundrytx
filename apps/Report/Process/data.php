<?php
error_reporting(1);
require( '../../../funlibs.php' );
$con=new Database;
session_start();

$cmt = $con->searchseqcmt($_GET['cm']);
$colors = $con->searchseq($_GET['co']);
$inseams = $_GET['in'];
$sizes = $_GET['si'];
$exftydate = $_GET['xty'];
$tpro = $_GET['tpro'];
$dpro = $_GET['dpro'];
$tgl1js = $_GET['tgl1'].' 06:00:00';
$tgl2js = $_GET['tgl2'].' 06:00:00';

if ($cmt != ''){
	$cm = "and wo_no = '".$cmt."' ";
} else {
	$cm = "";
}

if ($colors != ''){
	$co = "and garment_colors = '".$colors."' ";
} else {
	$co = "";
}
if ($exftydate != ''){
	$xty = "and DATE(ex_fty_date) = '".$exftydate."'";
} else {
	$xty = "";
}
if ($tpro != ''){
	$tpr = "and master_type_process_id = '".$tpro."'";
} else {
	$tpr = "";
}
if ($dpro != ''){
	$dpr = "and master_process_id = '".$dpro."'";
} else {
	$dpr = "";
}
if ($tgl1js != ''){
	$periode = "and process_createdate between '".$tgl1js."' and '".$tgl2js."'";
} else {
	$periode = "";
}

$table = 'laundry_wo_master_dtl_proc';
$primaryKey = 'wo_master_dtl_proc_id';
$joinQuery = "from (select ae.*,ab.proin,ab.prostart,ab.proend,ac.opstart,ac.opend,ad.machinestart,ad.machineend
					from  (
								SELECT * FROM
											crosstab (
												$$ 
														SELECT
														concat(lot_no:: TEXT,'_',role_wo_id:: TEXT) :: TEXT, 
														process_type :: INT,
														process_createdate :: TIMESTAMP
														FROM
														laundry_process 
														WHERE
														wo_no ilike '%'
														$tpr $dpr $periode
														ORDER BY
														wo_no
												$$
											) AS ct ( lot_no TEXT, proin TIMESTAMP, prostart TIMESTAMP,proend TIMESTAMP )
						) as ab 
						LEFT JOIN (
								SELECT * FROM
											crosstab (
												$$ 
														SELECT
														concat(lot_no:: TEXT,'_',role_wo_id:: TEXT) :: TEXT,  
														process_type :: INT,
														operator :: TEXT
														FROM
														laundry_process 
														WHERE
														wo_no ilike '%'
														AND process_type != 1
														$tpr $dpr $periode
														ORDER BY
														wo_no
												$$
											) AS ct ( lot_no TEXT, opstart TEXT,opend TEXT )	
						) as ac ON ab.lot_no=ac.lot_no
					    LEFT JOIN (	
								SELECT * FROM
											crosstab (
												$$ 
													SELECT
													concat(z.lot_no:: TEXT,'_',role_wo_id:: TEXT) :: TEXT, 
													process_type :: INT,
													machine_name :: TEXT
													FROM
													laundry_process z 
													JOIN laundry_master_machine y
													ON z.machine_id=y.machine_id
													JOIN ( select lot_no,machine_id 
													       from laundry_process_machine 
																 where process_machine_status = 1
													) as w
													ON z.lot_no=w.lot_no and z.machine_id=w.machine_id
													WHERE
														wo_no ilike '%'
														AND process_type != 1
														$tpr $dpr $periode
														ORDER BY
														wo_no
												$$
											) AS ct ( lot_no TEXT, machinestart TEXT,machineend TEXT )
						) as ad ON ab.lot_no=ad.lot_no
						JOIN (
								SELECT 
											a.lot_no,
											concat(A.lot_no,'_',A.role_wo_id) as lotnorole,
											a.process_qty_total as qty_total,
											c.wo_no,
											c.garment_colors,
											DATE(c.ex_fty_date) as ex_fty_date,
											process_type,
											sender,
											receiver,
											b.process_qty_good,
											b.process_qty_reject,
											b.process_qty_repair,
											a.role_wo_name_seq
											FROM
											laundry_process a
											LEFT JOIN (select lot_no,process_qty_good,process_qty_reject,process_qty_repair,
														role_wo_name_seq 
														from laundry_process where process_type = 4
														$tpr $dpr $periode 
														) as b
											ON a.lot_no=b.lot_no and a.role_wo_name_seq=b.role_wo_name_seq
											JOIN laundry_wo_master_dtl_proc c ON a.wo_master_dtl_proc_id=c.wo_master_dtl_proc_id
											WHERE
											a.process_type = 1 
											$cm $co $xty
											$tpr $dpr $periode 
						) as ae ON ab.lot_no=ae.lotnorole
					) as asi"; 
$extraWhere="";

$columns = array(
	array( "db" => "lot_no", 	"dt" => 0, "field" => "lot_no"),
	array( "db" => "wo_no",     "dt" => 1, "field" => "wo_no" ),
	array( "db" => "garment_colors",     "dt" => 2, "field" => "garment_colors" ),
	array( "db" => "ex_fty_date", 	"dt" => 3, "field" => "ex_fty_date" ),
	array( "db" => "role_wo_name_seq", 	"dt" => 4, "field" => "role_wo_name_seq" ),
	array( "db" => "qty_total", 	"dt" => 5, "field" => "qty_total" ),
	array( "db" => "proin", 	"dt" => 6, "field" => "proin" ),
	array( "db" => "sender", 	"dt" => 7, "field" => "sender" ),
	array( "db" => "receiver", 	"dt" => 8, "field" => "receiver" ),
	array( "db" => "prostart", 	"dt" => 9, "field" => "prostart" ),
	array( "db" => "machinestart", 	"dt" => 10, "field" => "machinestart" ),
	array( "db" => "opstart", 	"dt" => 11, "field" => "opstart" ),
	array( "db" => "proend", 	"dt" => 12, "field" => "proend" ),
	array( "db" => "machineend", 	"dt" => 13, "field" => "machineend" ),
	array( "db" => "opend", 	"dt" => 14, "field" => "opend" ),
	array( "db" => "process_qty_good", 	"dt" => 15, "field" => "process_qty_good" ),
	array( "db" => "process_qty_reject", 	"dt" => 16, "field" => "process_qty_reject" ),
	array( "db" => "process_qty_repair", 	"dt" => 17, "field" => "process_qty_repair" ),
	
);
//var_dump($columns);
$sql_details = array(	
); 
 
//echo "select * from $joinQuery";
echo json_encode(
	Database::simple( $_GET, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere )
);
