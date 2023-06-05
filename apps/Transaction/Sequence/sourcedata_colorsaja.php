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
	$denimno = "and F.wash_category_id between 5 and 8";
} else if ($denim_no == '2'){
	$denimno = "and F.wash_category_id > 8";
}

if ($_GET['sbuyer'] != '') {
	$buyer = "and A.buyer_id = '$sbuyer'";
} else {
	$buyer = "";
}

if ($_GET['sstyle'] != '') {
	$style = "and A.buyer_style_no = '$sstyle'";
} else {
	$style = "";
}

if ($_GET['scolor'] != '') {
	$color = "and garment_colors = '$scolor'";
} else {
	$color = "";
}

if ($_GET['scmt'] != '') {
	$cmt = "and A.wo_no = '$scmt'";
} else {
	$cmt = "";
}

if ($_GET['sstatus'] == 1){
	$statcolor = "and CONCAT(A.wo_no,'_',B.garment_colors) NOT IN (select CONCAT(wo_no,'_',garment_colors) from laundry_wo_master_dtl_proc where wo_no = '$scmt' and wo_master_dtl_proc_status = 1)";
} else if ($_GET['sstatus'] == 2){
	$statcolor = "and CONCAT(A.wo_no,'_',B.garment_colors) IN (select CONCAT(wo_no,'_',garment_colors) from laundry_wo_master_dtl_proc where wo_no = '$scmt' and wo_master_dtl_proc_status = 1)";
}

if ($_GET['d'] == 1) {
	$term = trim(strip_tags($_GET['term'])); 
	$dat=$con->select("work_orders A 
					   JOIN order_instructions E ON A.order_no = E.order_no
					   JOIN quotation_header F ON E.quote_no = F.quote_no",
					   "A.buyer_id as buyer_id,A.buyer_name as buyer_name",
					   "A.buyer_name ilike '%$term%' $denimno $cmt $style GROUP BY A.buyer_id,A.buyer_name");
	//echo "select A.buyer_id as buyer_id,A.buyer_name as buyer_name from work_orders A JOIN order_instructions E ON A.order_no = E.order_no JOIN quotation_header F ON E.quote_no = F.quote_no where A.buyer_id ilike '%$term%' $denimno $cmt $style GROUP BY A.buyer_id,A.buyer_name";
	foreach($dat as $row)
	{
		$row['value']=htmlentities(stripslashes($row['buyer_name']));
	    $row['id']=htmlentities(stripslashes($row['buyer_id']));;
	//buat array yang nantinya akan di konversi ke json
	    $row_set[] = $row;
	}
} else if ($_GET['d'] == 2){
	$term = trim(strip_tags($_GET['term'])); 
	$dat=$con->select("work_orders A 
					   JOIN order_instructions E ON A.order_no = E.order_no
					   JOIN quotation_header F ON E.quote_no = F.quote_no",
					   "A.wo_no as wo_no",
					   "A.wo_no ilike '%$term%' $denimno $buyer $style GROUP BY A.wo_no");
	// echo "select A.wo_no as wo_no from work_orders A 
	// 				   JOIN order_instructions E ON A.order_no = E.order_no
	// 				   JOIN quotation_header F ON E.quote_no = F.quote_no 
	// 				   where A.wo_no ilike '%$term%' $denimno $buyer $style GROUP BY A.wo_no";
	foreach($dat as $row)
	{
		$row['value']=htmlentities(stripslashes($row['wo_no']));
	//buat array yang nantinya akan di konversi ke json
	    $row_set[] = $row;
	}
}
else if ($_GET['d'] == 3){
	$term = trim(strip_tags($_GET['term'])); 
	$dat=$con->select("work_orders A 
					   JOIN order_instructions E ON A.order_no = E.order_no
					   JOIN quotation_header F ON E.quote_no = F.quote_no",
					   "A.buyer_style_no",
					   "A.buyer_style_no ilike '%$term%' $denimno $buyer $cmt GROUP BY A.buyer_style_no");
	// echo "select A.buyer_style_no from work_orders A 
	// 				   JOIN order_instructions E ON A.order_no = E.order_no
	// 				   JOIN quotation_header F ON E.quote_no = F.quote_no 
	// 				   where A.buyer_style_no ilike '%$term%' $denimno $buyer $cmt GROUP BY A.buyer_style_no";
	foreach($dat as $row)
	{
		$row['value']=htmlentities(stripslashes($row['buyer_style_no']));
	//buat array yang nantinya akan di konversi ke json
	    $row_set[] = $row;
	}
}
else if ($_GET['d'] == 4){
	$term = trim(strip_tags($_GET['term'])); 
	$dat=$con->select("work_orders A 
					   JOIN order_instructions E ON A.order_no = E.order_no
					   JOIN quotation_header F ON E.quote_no = F.quote_no
					   JOIN wo_sb B on A.wo_no=B.wo_no",
					   "B.garment_colors","B.garment_colors ilike '%$term%' $buyer $cmt $style $statcolor 
					   and 	concat(a.wo_no,'_',b.garment_colors) NOT IN (select concat(wo_no,'_',garment_colors) from laundry_wo_master_keranjang)
					   GROUP BY garment_colors");
	
	foreach($dat as $row)
	{
		$row['value']=htmlentities(stripslashes($row['garment_colors']));
	//buat array yang nantinya akan di konversi ke json
	    $row_set[] = $row;
	}
}
//data hasil query yang dikirim kembali dalam format json
echo json_encode($row_set);
?>