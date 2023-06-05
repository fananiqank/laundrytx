<?php 
session_start();
include '../../../funlibs.php';
$con = new Database;

$denim_no = $_GET['de'];
$buyer_no = $con->searchseq($_GET['b']);
$style_no = $con->searchseq($_GET['s']);
$cmt_no = $con->searchseqcmt($_GET['cm']);
$color_no = $con->searchseq($_GET['co']);
$tglship1 = $_GET['tgl'];
$tglship2 = $_GET['tgl2'];
$color_wash = $con->searchseq($_GET['colorwash']);
/*A.buyer_style_no = 'PT40'*/
				/*A.buyer_id = 'VANS'*/
				/*C.garment_colors = 'BLACK (ACCENT FOOTWEAR LIMITED)'*/
				/*A.wo_no = '07/E/JK/W/VANS/1226/1 '*/
				/*CASE
					WHEN D.ex_fty_date IS NULL THEN
					to_char( B.ex_fty_date, 'YYYY-mm-dd' ) ELSE to_char( D.ex_fty_date, 'YYYY-mm-dd' ) 
				END BETWEEN '2007-12-04' AND '2007-12-26'*/
$no=1;
if ($denim_no){
		if ($denim_no == '1'){
			$denimno = "and A.wash_category_id between 5 and 8";
		} else if ($denim_no == '2'){
			$denimno = "and A.wash_category_id not between 5 and 8";
		}

		if ($buyer_no != ''){
			$buyerno = "and A.buyer_id = '$buyer_no'";
		} else {
			$buyerno = "";
		}

		if ($style_no != ''){
			$styleno = "and A.buyer_style_no = '$style_no'";
		} else {
			$styleno = "";
		}

		if ($cmt_no != ''){
			$cmtno = "and A.wo_no = '$cmt_no'";
		} else {
			$cmtno = "";
		}

		if ($color_no != ''){
			$colorno = "and UPPER(A.garment_colors) ilike UPPER('$color_no')";
		} else {
			$colorno = "";
		}

		if ($tglship1 == 'A' && $tglship2 == 'A'){
			$tgls = "";
		} else if ($tglship1 == '' && $tglship2 == ''){
			$tgls = "";
		} else {
			$tgls = "AND DATE(A.ex_fty_date) BETWEEN '$tglship1' AND '$tglship2'";
			//and DATE(a.ex_fty_date) BETWEEN '$tglship1' AND '$tglship2'";
		}

		if ($_GET['statusseq'] == 2) {
			$notin = "IN";

		} else {
			$notin = "NOT IN";
		}

	$countwo = 1;
	$cektableqrcode = $con->selectcount("laundry_data_wo A
										 JOIN qrcode_ticketing_master B on A.wo_no=B.wo_no",
										 "A.wo_no",
										 "A.wo_no = '".$cmt_no."' GROUP BY A.wo_no");
	// echo "select A.wo_no from laundry_data_wo A JOIN qrcode_ticketing_master B on A.wo_no=B.wo_no where wo_no = '".$cmt_no."'";

	if ($cektableqrcode > '0'){
		include "tampilwipqueryQRCODE.php";
		//echo "1";
	} else {
		include "tampilwipqueryEDI.php";
		//echo "2";
	}
}	
?>


<script type="text/javascript">$('#loader-off').click();</script>