<?php
//error_reporting(0);
require( '../../../funlibs.php' );
$con=new Database;
session_start();
$table = 'laundry_lot_number';
$primaryKey = 'lot_id';
$joinQuery = "from (select lot_id,lot_no,wo_no,garment_colors,lot_qty,lot_kg,lot_status,concat(lot_no,'_',lot_status) as act,coalesce(shift_name, '-') AS shift_name,lot_shade from laundry_lot_number a left join laundry_master_shift b on a.shift_id=b.shift_id) as an";  

$extraWhere="";

$columns = array(
	array( "db" => "lot_id",     "dt" => 0, "field" => "lot_id" ),
	array( "db" => "lot_no",     "dt" => 1, "field" => "lot_no" ),
	array( "db" => "wo_no",     "dt" => 2, "field" => "wo_no" ),
	array( "db" => "garment_colors", 	"dt" => 3, "field" => "garment_colors"),
	array( "db" => "lot_shade", 	"dt" => 4, "field" => "lot_shade"),
	array( "db" => "lot_qty",     "dt" => 5, "field" => "lot_qty" ),
	array( "db" => "lot_kg",     "dt" => 6, "field" => "lot_kg" ),
	array( "db" => "shift_name",     "dt" => 7, "field" => "shift_name" ),
	array( "db" => "lot_id", 	"dt" => 8, "field" => "lot_id" ,
			'formatter' => function( $d, $row ) {
					
					$isijam = "<a href='javascript:void(0)' data-toggle='modal' data-target='#funModal' id='mod' onclick='modellot($d,4)' class='label label-warning' style='font-size:12px;'>Detail</a>";
					
					// $isijam = "<a href='javascript:void(0)' data-toggle='modal' data-target='#funModal' id='mod' onclick='mode($d)' class='label label-warning' style='cursor:pointer'>details</a>";
				/*return "
				<a href='javascript:void(0)' data-toggle='modal' id='mod' data-target='#datajaminan' onClick=detiljams('$d') class='label label-success' style='cursor:pointer'>Lihat Jaminan</a>
				";*/
				return "$isijam";

			}),
	array( "db" => "lot_status", 	"dt" => 9, "field" => "lot_status" ,
			'formatter' => function( $d, $row ) {
					if ($d = '1'){
						$isijam= "Active";
					}
					else {
						$isijam= "Break";
					}
					
				return "$isijam";

			}),
	array( "db" => "act", 	"dt" => 10, "field" => "act" ,
			'formatter' => function( $d, $row ) {
				$expact = explode('_', $d);
				$lotno = $expact[0];
				$stat = $expact[1];
				
				if($stat == 1){
					$isijum = "<a href='lib/phpqrcode/index.php?no=$lotno' class='label label-primary' style='cursor:pointer' target='_blank'><i class='fa fa-print' aria-hidden='true' style='font-size:14px;'></i></a>";
				} else {
					$isijum = "";
				}
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
