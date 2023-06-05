
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
	$cm = "and DATE_PART('Y',ex_fty_date) = DATE_PART('Y',now())";
}

if ($colors != ''){
	$co = "and garment_colors = '".$colors."'";
} else {
	$co = "";
}

if ($exftydate != ''){
	$xty = "and DATE(ex_fty_date) = '".$exftydate."'";
} else {
	$xty = "";
}

$table = 'laundry_master_shift';
$primaryKey = 'shift_id';
$joinQuery = "from (select a.lot_id,a.lot_no,a.wo_no,a.garment_colors,a.lot_qty,a.lot_kg,a.lot_status,c.color_wash,lot_type,
					CASE 
						WHEN COALESCE(a.lot_qty_good_upd,0) != 0
						THEN COALESCE(a.lot_qty_good_upd,0)
						ELSE a.lot_qty
					END as qty,
					concat(a.lot_no,'_',a.lot_status,'_',e.process_id) as act,a.lot_shade,
					to_char(c.ex_fty_date, 'DD-MM-YYYY') as ex_fty_date_show,
					DATE(ex_fty_date) as ex_fty_date,
					a.user_code
					from 
					laundry_lot_number a 
					JOIN laundry_wo_master_dtl_proc c on a.wo_master_dtl_proc_id=c.wo_master_dtl_proc_id
					LEFT JOIN laundry_master_shift b on a.shift_id=b.shift_id
					LEFT JOIN (select max(process_id) as process_id,lot_no from laundry_process where process_type != 5 GROUP BY lot_no) as e on 
					a.lot_no=e.lot_no
					GROUP BY a.lot_id,a.lot_no,a.wo_no,a.garment_colors,a.lot_qty,a.lot_kg,a.lot_status,c.color_wash,a.lot_shade,
					c.ex_fty_date,a.user_code,e.process_id
				) as an";  

$extraWhere="lot_type = 'T'  $cm $co $xty";

$columns = array(
	array( "db" => "lot_id",     "dt" => 0, "field" => "lot_id" ),
	array( "db" => "lot_no",     "dt" => 1, "field" => "lot_no" ),
	array( "db" => "wo_no",     "dt" => 2, "field" => "wo_no" ),
	array( "db" => "garment_colors", 	"dt" => 3, "field" => "garment_colors"),
	array( "db" => "color_wash", 	"dt" => 4, "field" => "color_wash"),
	array( "db" => "ex_fty_date_show", 	"dt" => 5, "field" => "ex_fty_date_show"),
	array( "db" => "lot_shade", 	"dt" => 6, "field" => "lot_shade"),
	array( "db" => "qty",     "dt" => 7, "field" => "qty" ),
	array( "db" => "lot_kg",     "dt" => 8, "field" => "lot_kg" ),
	array( "db" => "user_code",     "dt" => 9, "field" => "user_code" ),
	array( "db" => "lot_id", 	"dt" => 10, "field" => "lot_id" ,
			'formatter' => function( $d, $row ) {
					
					$isijam = "<a href='javascript:void(0)' data-toggle='modal' data-target='#funModal' id='mod' onclick='modellotview($d,4)' class='label label-warning' style='font-size:12px;'>Detail</a>";
					
					// $isijam = "<a href='javascript:void(0)' data-toggle='modal' data-target='#funModal' id='mod' onclick='mode($d)' class='label label-warning' style='cursor:pointer'>details</a>";
				/*return "
				<a href='javascript:void(0)' data-toggle='modal' id='mod' data-target='#datajaminan' onClick=detiljams('$d') class='label label-success' style='cursor:pointer'>Lihat Jaminan</a>
				";*/
				return "$isijam";

			}),
	array( "db" => "lot_status", 	"dt" => 11, "field" => "lot_status" ,
			'formatter' => function( $d, $row ) {
					if ($d == '1'){
						$isijam= "<span class='label label-success'>Active</span>";
					} else if ($d == '2'){
						$isijam= "<span class='label label-primary'>Done</span>";
					} else if ($d == '3'){
						$isijam= "<span class='label label-info'>LotMaking</span>";
					}
					else {
						$isijam= "<span class='label label-danger'>Break</span>";
					}
					
				return "$isijam";

			}),
	array( "db" => "act", 	"dt" => 12, "field" => "act" ,
			'formatter' => function( $d, $row ) {
				$expact = explode('_', $d);
				$lotno = $expact[0];
				$stat = $expact[1];
				
				if($stat > 0 && $_SESSION['ID_LOGIN'] == '871' && $expact[2] == '') {
					// $cancel = "<a href='javascript:void(0)' class='label label-danger'  data-toggle='modal' data-target='#funModal' id='mod' onclick=modelcancellot('".$lotno."',2)><i class='fa fa-close' aria-hidden='true' style='font-size:14px;'></i></a>";
				} else {
					$cancel = "";
				}
				//if($stat == 1){
					$isijum = "<a href='lib/pdf-qrcode.php?c=".base64_encode($lotno)."' class='label label-primary' style='cursor:pointer' target='_blank'><i class='fa fa-print' aria-hidden='true' style='font-size:14px;'></i></a>&nbsp;".$cancel;
				// } else {
				// 	$isijum = "";
				// }
				return "$isijum";

			}),
);
//var_dump($columns);
$sql_details = array(	
); 
 
//echo "select * from $joinQuery where $extraWhere";
echo json_encode(
	Database::simple( $_GET, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere )
);
