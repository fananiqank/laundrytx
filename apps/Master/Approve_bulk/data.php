<?php
//error_reporting(0);
require( '../../../funlibs.php' );
$con=new Database;
session_start();

$cmt = $con->searchseqcmt($_GET['cm']);
$colors = $con->searchseq($_GET['co']);
$exftydate =  $_GET['xty'];

if ($cmt != ''){
	$cm = "and wo_no = '".$cmt."'";
} else {
	$cm = "";
}

if ($colors != ''){
	$co = "and garment_colors = '".$colors."'";
} else {
	$co = "";
}

// if ($exftydate != ''){
// 	$xty = "and DATE(ex_fty_date) = '".$exftydate."'";
// } else {
// 	$xty = "";
// }

$view = $_GET['v'];
$table = 'laundry_wo_master_dtl_proc';
$primaryKey = 'wo_no';
$joinQuery = "from (select wo_no, garment_colors, buyer_style_no,concat(wo_no,'_',garment_colors,'_',buyer_style_no) as conc,color_wash from laundry_wo_master_dtl_proc GROUP BY wo_no,garment_colors,buyer_style_no,color_wash) as m";  

$extraWhere="wo_no ilike '%' $cm $co";

$columns = array(
	array( "db" => "wo_no",     "dt" => 0, "field" => "wo_no" ),
	array( "db" => "wo_no",     "dt" => 1, "field" => "wo_no" ),
	array( "db" => "garment_colors", 	"dt" => 2, "field" => "garment_colors"),
	array( "db" => "color_wash", 	"dt" => 3, "field" => "color_wash"),
	array( "db" => "buyer_style_no", 	"dt" => 4, "field" => "buyer_style_no"),
	array( "db" => "conc", 	"dt" => 5, "field" => "conc" ,
			'formatter' => function( $d, $row ) {
					$expd = explode("_", $d);
					$trimcmt = trim($expd[0]);
					$expcmt = explode('/',$trimcmt);
			 		$cmts = implode('-',$expcmt);
				 	
				 	//Color
			 		$trimcolor = trim($expd[1]);
					$expcolor = explode('#',$trimcolor);
						if (count($expcolor) > 1) {
							$expcolor1 = implode('K-',$expcolor);
						} else {
							$expcolor1 = $trimcolor;
						}
			 		$expcolor2 = explode(' ',$expcolor1);
				 		if (count($expcolor2) > 1){
				 			$expcolor3 = implode('_',$expcolor2);  
				 		} else {
				 			$expcolor3 = $expcolor1;
				 		}
			 		$expcolor4 = explode('/',$expcolor3);
				 		if (count($expcolor4) > 1){
				 			$color = implode('G-',$expcolor4);  
				 		} else {
				 			$color = $expcolor3;
				 		}

				 //	$expbso = explode(' ',$expd[2]);

					$isijam = '<a href="javascript:void(0)" data-toggle="modal" data-target="#funModalrec" id="mod" onclick=modeldetail("'.$trimcmt.'","'.$color.'","'.str_replace(" ","_",$expd[2]).'",3) class="label label-warning" style="font-size:12px;">Detail</a>';
					
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
