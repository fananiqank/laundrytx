<?php
//error_reporting(0);
require( '../../../funlibs.php' );
$con=new Database;
session_start();

$denim_no = $_GET['de'];
$buyer_no = $con->searchseq($_GET['b']);
$style_no = $con->searchseq($_GET['s']);
$cmt_no = $con->searchseqcmt($_GET['cm']);
$color_no = $con->searchseq($_GET['co']);
$tglship1 = $_GET['tgl'];
$tglship2 = $_GET['tgl2'];

$no=1;
if ($denim_no){
		if ($denim_no == '1'){
			$denimno = "and F.wash_category_id between 5 and 8";
		} else if ($denim_no == '2'){
			$denimno = "and F.wash_category_id > 8";
		}

		if ($buyer_no != ''){
			$buyerno = "and A.buyer_id ILIKE '%$buyer_no%'";
		} else {
			$buyerno = "";
		}

		if ($style_no != ''){
			$styleno = "and A.buyer_style_no ILIKE '%$style_no%'";
		} else {
			$styleno = "";
		}

		if ($cmt_no != ''){
			$cmtno = "and A.wo_no = '$cmt_no'";
		} else {
			$cmtno = "";
		}

		if ($color_no != ''){
			$colorno = "and C.garment_colors ILIKE  '%$color_no%'";
		} else {
			$colorno = "";
		}

		if ($tglship1 == 'A' && $tglship2 == 'A'){
			$tgls = "";
		} else if ($tglship1 == '' && $tglship2 == ''){
			$tgls = "";
		} else {
			$tgls = "AND DATE(B.ex_fty_date) BETWEEN '$tglship1' AND '$tglship2'";
			//and DATE(a.ex_fty_date) BETWEEN '$tglship1' AND '$tglship2'";
		}
}

$view = $_GET['v'];
$table = 'wo_no';
$primaryKey = 'work_orders';
$joinQuery = "from (SELECT 
							  A.wo_no,
								C.garment_colors,
								E.buyer_id,
								SUM ( C.quantity ) AS qty 
							FROM
								work_orders
								A JOIN wo_bpo B ON A.wo_no = B.wo_no
								JOIN wo_sb C ON A.wo_no = C.wo_no 
								AND B.buyer_po_number = C.buyer_po_number
								JOIN order_instructions E ON A.order_no = E.order_no
								JOIN quotation_header F ON E.quote_no = F.quote_no 
							WHERE
								A.wo_no ILIKE'%' $denimno $buyerno $styleno $cmtno $colorno $tgls	
								AND concat ( A.wo_no, '_', C.garment_colors) NOT IN ( SELECT concat (wo_no,'_',garment_colors) FROM laundry_wo_master_dtl_proc )
							GROUP BY
								A.wo_no,
								C.garment_colors,
								E.buyer_id 
							ORDER BY
								A.wo_no )
							as ab";  

$extraWhere="";

$columns = array(
	array( "db" => "COUNT(wo_no) as cek",     "dt" => 0, "field" => "wo_master_dtl_proc_id" ),
);
//var_dump($columns);
$sql_details = array(	
); 
 
echo "select * from $joinQuery where $extraWhere";
echo json_encode(
	Database::simple( $_GET, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere )
);
