<?php
session_start();
require_once("../../../funlibs.php");
$con=new Database();
$sbuyer = $con->searchseq($_GET['sbuyer']);
$sstyle = $con->searchseq($_GET['sstyle']);
$scmt = $con->searchseqcmt($_GET['scmt']);
$scolor = $con->searchseq($_GET['scolor']);
$denim_no = $_GET['sdenim'];

if ($denim_no == '1'){
	$denimno = "and wash_category_id between 5 and 8";
} else if ($denim_no == '2'){
	$denimno = "and wash_category_id not between 5 and 8";
}

if ($_GET['sbuyer'] != '') {
	$buyer = "and buyer_id = '$sbuyer'";
} else {
	$buyer = "";
}

if ($_GET['sstyle'] != '') {
	$style = "and buyer_style_no = '$sstyle'";
} else {
	$style = "";
}

if ($_GET['scolor'] != '') {
	$color = "and cpdcolor = '$scolor'";
} else {
	$color = "";
}

if ($_GET['scmt'] != '') {
	$cmt = "and A.wo_no = '$scmt'";
} else {
	$cmt = "";
}



if ($_GET['sstatus'] == 1){
	$joinvip = "";
	$colwash = "";
	$statcolor = "and CONCAT(A.wo_no,'_',trim(cpdcolor)) NOT IN (select CONCAT(wo_no,'_',trim(garment_colors)) from laundry_wo_master_dtl_proc where wo_no = '$scmt' and wo_master_dtl_proc_status = 1)";
	$statcolor2 = "and CONCAT(A.wo_no,'_',trim(A.garment_colors)) NOT IN (select CONCAT(wo_no,'_',trim(garment_colors)) from laundry_wo_master_dtl_proc where wo_no = '$scmt' and wo_master_dtl_proc_status = 1)";
} else if ($_GET['sstatus'] == 2){
	$joinvip = "LEFT JOIN laundry_wo_master_dtl_proc C ON A.wo_no=C.wo_no";
	$colwash = ",color_wash";
	$statcolor = "and CONCAT(A.wo_no,'_',trim(cpdcolor)) IN (select CONCAT(wo_no,'_',trim(garment_colors)) from laundry_wo_master_dtl_proc where wo_no = '$scmt' and wo_master_dtl_proc_status = 1)";
	$statcolor2 = "and CONCAT(A.wo_no,'_',trim(A.garment_colors)) IN (select CONCAT(wo_no,'_',trim(garment_colors)) from laundry_wo_master_dtl_proc where wo_no = '$scmt' and wo_master_dtl_proc_status = 1)";
}

if ($_GET['d'] == 1) {
	$term = trim(strip_tags($_GET['term'])); 
	$dat=$con->select("laundry_data_wo A LEFT JOIN qrcode_cutplan_dtlhead B ON A.wo_no = B.cpdwono and substring(A.wo_no,7,2) <> 'WS'",
					   "buyer_id",
					   "buyer_id ilike '%$term%' $denimno $cmt $style GROUP BY buyer_id");
	// echo "select buyer_id from laundry_data_wo where buyer_id ilike '%$term%' $denimno $cmt $style GROUP BY buyer_id";
	foreach($dat as $row)
	{
		$row['value']=htmlentities(stripslashes($row['buyer_id']));
	    $row['id']=htmlentities(stripslashes($row['buyer_id']));
	//buat array yang nantinya akan di konversi ke json
	    $row_set[] = $row;
	}
} else if ($_GET['d'] == 2){
	$term = trim(strip_tags($_GET['term'])); 
	$dat=$con->select("laundry_data_wo A LEFT JOIN qrcode_cutplan_dtlhead B ON A.wo_no = B.cpdwono AND substring(A.wo_no,7,2) <> 'WS'",
					   "wo_no",
					   "wo_no ilike '%$term%' $denimno $buyer $style GROUP BY wo_no");
	
	foreach($dat as $row)
	{
		$row['value']=htmlentities(stripslashes($row['wo_no']));
	//buat array yang nantinya akan di konversi ke json
	    $row_set[] = $row;
	}
}
else if ($_GET['d'] == 3){
	$term = trim(strip_tags($_GET['term'])); 
	$dat=$con->select("laundry_data_wo A LEFT JOIN qrcode_cutplan_dtlhead B ON A.wo_no = B.cpdwono AND substring(A.wo_no,7,2) <> 'WS'",
					   "buyer_style_no",
					   "buyer_style_no ilike '%$term%' $denimno $buyer $cmt GROUP BY buyer_style_no");
	 // echo "select A.buyer_style_no from laundry_data_wo A JOIN qrcode_cutplan_dtlhead B ON A.wo_no = B.cpdwono
		// 			   where A.buyer_style_no ilike '%$term%' $denimno $buyer $cmt GROUP BY A.buyer_style_no";
	foreach($dat as $row)
	{
		$row['value']=htmlentities(stripslashes($row['buyer_style_no']));
	//buat array yang nantinya akan di konversi ke json
	    $row_set[] = $row;
	}
}
// else if ($_GET['d'] == 4){
// 	$term = trim(strip_tags($_GET['term'])); 
// 	$dat=$con->select("laundry_data_wo",
// 					   "garment_colors","garment_colors ilike '%$term%' $buyer $cmt $style $statcolor 
// 					   and 	concat(wo_no,'_',garment_colors) NOT IN (select concat(wo_no,'_',garment_colors) from laundry_wo_master_keranjang)
// 					   GROUP BY garment_colors");
	
// 	foreach($dat as $row)
// 	{
// 		$row['value']=htmlentities(stripslashes($row['garment_colors']));
// 	//buat array yang nantinya akan di konversi ke json
// 	    $row_set[] = $row;
// 	}
// }
else if ($_GET['d'] == 4){
	// if ($_GET['scmt'] != '') {
	// 	$cmtc = "and A.wo_no = '$scmt'";
	// } else {
	// 	$cmtc = "";
	// }
	$term = trim(strip_tags($_GET['term'])); 
	//echo "ada";
	$sscmt2= trim($scmt);
	//echo "cpdwono = '$sscmt2'";
	$cekwoqr = $con->selectcount("qrcode_cutplan_dtlhead","cpdwono","cpdwono = '$sscmt2'");
	//echo "select cpdwono from qrcode_cutplan_dtlhead where cpdwono = '$sscmt2'";
	if($cekwoqr > 0) {
		$dat=$con->select("laundry_data_wo A JOIN qrcode_cutplan_dtlhead B ON A.wo_no = B.cpdwono AND substring(A.wo_no,7,2) <> 'WS' $joinvip",
					   "trim(cpdcolor) as garment_colors $colwash","cpdcolor ilike '%$term%' $buyer $cmt $style $statcolor 
					   and concat(A.wo_no,'_',trim(cpdcolor)) NOT IN (select concat(wo_no,'_',trim(garment_colors)) from laundry_wo_master_keranjang)
					   GROUP BY cpdcolor $colwash");
		// echo "select cpdcolor as garment_colors $colwash from laundry_data_wo A JOIN qrcode_cutplan_dtlhead B ON A.wo_no = B.cpdwono AND substring(A.wo_no,7,2) <> 'WS' $joinvip where cpdcolor ilike '%$term%' $buyer $cmt $style $statcolor 
		// 			   and concat(A.wo_no,'_',trim(cpdcolor)) NOT IN (select concat(wo_no,'_',trim(garment_colors)) from laundry_wo_master_keranjang)
		// 			   GROUP BY cpdcolor $colwash";
	}
	else {
		if($_GET['sstatus'] == 1){
			$dat=$con->select("laundry_data_wo A LEFT JOIN laundry_wo_master_dtl_proc B ON A.wo_no=B.wo_no AND substring(A.wo_no,7,2) <> 'WS'",
					   "trim(A.garment_colors) as garment_colors","A.garment_colors ilike '%$term%' $buyer $cmt $style $statcolor2 
					   and concat(A.wo_no,'_',trim(A.garment_colors)) NOT IN (select concat(wo_no,'_',trim(garment_colors)) from laundry_wo_master_keranjang)
					   GROUP BY A.garment_colors");
			// echo "select A.garment_colors from laundry_data_wo A LEFT JOIN laundry_wo_master_dtl_proc B USING (wo_no) where A.garment_colors ilike '%$term%' $buyer $cmt $style $statcolor2 
			// 		   and concat(A.wo_no,'_',A.garment_colors) NOT IN (select concat(wo_no,'_',garment_colors) from laundry_wo_master_keranjang)
			// 		   GROUP BY A.garment_colors";
		} else {
			$dat=$con->select("laundry_wo_master_dtl_proc A",
					   "trim(A.garment_colors) as garment_colors,A.color_wash","A.garment_colors ilike '%$term%' $buyer $cmt $style $statcolor2 
					   and concat(A.wo_no,'_',trim(A.garment_colors)) NOT IN (select concat(wo_no,'_',trim(garment_colors)) from laundry_wo_master_keranjang)
					   GROUP BY A.garment_colors,A.color_wash");
		  // echo "select A.garment_colors,A.color_wash from laundry_wo_master_dtl_proc A where A.garment_colors ilike '%$term%' $buyer $cmt $style $statcolor2 
				// 	   and concat(A.wo_no,'_',A.garment_colors) NOT IN (select concat(wo_no,'_',garment_colors) from laundry_wo_master_keranjang)
				// 	   GROUP BY A.garment_colors, A.color_wash";
		}
	}
	foreach($dat as $row)
	{
		$row['value']=htmlentities(stripslashes($row['garment_colors']));
		$row['color_wash']=htmlentities(stripslashes($row['color_wash']));
	//buat array yang nantinya akan di konversi ke json
	    $row_set[] = $row;
	}
}
//data hasil query yang dikirim kembali dalam format json
echo json_encode($row_set);
?>