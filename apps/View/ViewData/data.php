<?php
//error_reporting(1);
require( '../../../funlibs.php' );
$con=new Database;
session_start();

$cmt = $con->searchseqcmt($_GET['cm']);
$colors4 = $con->searchseq($_GET['co']);
$colors2 = explode('Y|',$colors4);
$colors3 = implode('&',$colors2);
$colors = trim($colors3);
$exftydate = $_GET['xty'];
// $inseams = $_GET['in'];
// $sizes = $_GET['si'];
if ($cmt != ''){
	$cm = "and wo_no = '".$cmt."' ";
} else {
	$cm = "and ex_fty_date between (now() - interval '2 months')::date and (now() + interval '1 day')::date";
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

$table = 'laundry_wo_master_dtl_proc';
$primaryKey = 'wo_master_dtl_proc_id';
$joinQuery = "from (select a.wo_master_dtl_proc_id, 
							a.wo_no,
							a.garment_colors,
							a.ex_fty_date,
							to_char(a.ex_fty_date, 'DD-MM-YYYY') as ex_fty_date_show,
							b.wo_master_dtl_proc_qty_rec,
							a.cutting_qty,
							a.wo_master_dtl_proc_status,
							a.color_wash,
							case when wo_master_dtl_proc_status = 1 then 'New' else concat('Rework ',rework_seq) end vstatus
					from laundry_wo_master_dtl_proc a
					LEFT JOIN (select sum(rec_qty) as wo_master_dtl_proc_qty_rec,wo_master_dtl_proc_id 
								from laundry_receive GROUP BY wo_master_dtl_proc_id) as b
								USING(wo_master_dtl_proc_id)
					
					) as asi";  
$extraWhere="wo_master_dtl_proc_status > 0 ".$cm.$co.$xty;

// $selbpo = $con->select("wo_sb","buyer_po_number","wo_no = '$wono' and garment_colors = '$colors' and garment_inseams = '$inseams' and garment_sizes= '$sizes'");
$columns = array(
	array( "db" => "wo_master_dtl_proc_id",     "dt" => 0, "field" => "wo_master_dtl_proc_id" ),
	array( "db" => "wo_no", 	"dt" => 1, "field" => "wo_no"),
	array( "db" => "garment_colors",     "dt" => 2, "field" => "garment_colors" ),
	array( "db" => "color_wash",     "dt" => 3, "field" => "color_wash" ),
	array( "db" => "ex_fty_date_show",     "dt" => 4, "field" => "ex_fty_date_show" ),
	array( "db" => "vstatus",     "dt" => 5, "field" => "vstatus" ),
	array( "db" => "wo_master_dtl_proc_qty_rec", 	"dt" => 6, "field" => "wo_master_dtl_proc_qty_rec" ),
	array( "db" => "cutting_qty", 	"dt" => 7, "field" => "cutting_qty" ),
	array( "db" => "wo_master_dtl_proc_id", 	"dt" => 8, "field" => "wo_master_dtl_proc_id" ,
			'formatter' => function( $d, $row ) {
					
					$isijam = "	
					<a href='javascript:void(0)' class='label label-warning' onClick='detail($d)'>Detail</a>";
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
