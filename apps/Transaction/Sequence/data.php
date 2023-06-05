<?php
//error_reporting(0);
require( '../../../funlibs.php' );
$con=new Database;
session_start();

$view = $_GET['v'];
$table = 'laundry_wo_master_dtl_proc';
$primaryKey = 'wo_master_dtl_proc_id';
$joinQuery = "from (select wo_master_dtl_proc_id, wo_no, garment_colors,color_wash, wo_master_dtl_proc_qty_md, concat(wo_master_dtl_proc_status,'_',rework_seq) as newrework,to_char(ex_fty_date, 'YYYY-MM-DD') as ex_fty_date,to_char(ex_fty_date, 'DD-MM-YYYY') as show_ex_fty_date,concat(wo_master_dtl_proc_id,'_',status_hold) as statushold from laundry_wo_master_dtl_proc) as m";  

$extraWhere="";

$columns = array(
	array( "db" => "wo_master_dtl_proc_id",     "dt" => 0, "field" => "wo_master_dtl_proc_id" ),
	array( "db" => "wo_no",     "dt" => 1, "field" => "wo_no" ),
	array( "db" => "garment_colors", 	"dt" => 2, "field" => "garment_colors"),
	array( "db" => "color_wash", 	"dt" => 3, "field" => "color_wash"),
	array( "db" => "show_ex_fty_date",     "dt" => 4, "field" => "show_ex_fty_date" ),
    array( "db" => "newrework",     "dt" => 5, "field" => "newrework",
			'formatter' => function( $d, $row ) {
					$expnew = explode('_',$d);	
					if ($expnew[0] == '1'){
						$stat = "<span style='color:#4169E1;'><b>New</b></span>";
					} else {
						$stat = "<span style='color:#B22222;'><b>Rework $expnew[1]</b></span>";
					}

					$isijum = $stat;
					
				return "$isijum";

			}),
	array( "db" => "wo_master_dtl_proc_id", 	"dt" => 6, "field" => "wo_master_dtl_proc_id" ,
			'formatter' => function( $d, $row ) {
					$isijam = "<a href='javascript:void(0)' data-toggle='modal' data-target='#funModalrec' id='mod' onclick='modelremarkdate($d)' class='label label-primary' style='font-size:12px;'>Check</a>";
					
					// $isijam = "<a href='javascript:void(0)' data-toggle='modal' data-target='#funModal' id='mod' onclick='mode($d)' class='label label-warning' style='cursor:pointer'>details</a>";
				/*return "
				<a href='javascript:void(0)' data-toggle='modal' id='mod' data-target='#datajaminan' onClick=detiljams('$d') class='label label-success' style='cursor:pointer'>Lihat Jaminan</a>
				";*/
				return $isijam;

			}),
	array( "db" => "wo_master_dtl_proc_id", 	"dt" => 7, "field" => "wo_master_dtl_proc_id" ,
			'formatter' => function( $d, $row ) {
					
					$isijam = "<a href='javascript:void(0)' data-toggle='modal' data-target='#funModal' id='mod' onclick='model($d,3)' class='label label-warning' style='font-size:12px;'>Detail</a>";
					
					// $isijam = "<a href='javascript:void(0)' data-toggle='modal' data-target='#funModal' id='mod' onclick='mode($d)' class='label label-warning' style='cursor:pointer'>details</a>";
				/*return "
				<a href='javascript:void(0)' data-toggle='modal' id='mod' data-target='#datajaminan' onClick=detiljams('$d') class='label label-success' style='cursor:pointer'>Lihat Jaminan</a>
				";*/
				return "$isijam";

			}),
	array( "db" => "statushold", 	"dt" => 8, "field" => "statushold" ,
			'formatter' => function( $d, $row ) {
					$exphold = explode('_',$d);
					$id = $exphold[0];
					$sthold = $exphold[1];
					if ($sthold == 1){
						$isijam = "<a href='javascript:void(0)' data-toggle='modal' data-target='#funModal' id='mod' onclick='hold($id,$sthold)' class='label label-danger' style='font-size:12px;'>Hold</a>";
					} else {
						$isijam = "<a href='javascript:void(0)' data-toggle='modal' data-target='#funModal' id='mod' onclick='hold($id,$sthold)' class='label label-success' style='font-size:12px;'>No</a>";
					}
					
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
