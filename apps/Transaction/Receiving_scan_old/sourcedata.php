<?php
session_start();
require_once("../../../funlibs.php");
$con=new Database();

//echo $_GET['scmt'];
$scmt = $con->searchseqcmt($_GET['scmt']);
$scolor = $con->searchseq($_GET['scolor']);


if ($_GET['scolor'] != '') {
	$color = "and trim(color) = trim('$scolor')";
} else {
	$color = "";
}

if ($_GET['scmt'] != '') {
	$cmt = "and trim(wo_no) = trim('$scmt')";
} else {
	
	$cmt = "";
}
if ($_GET['xty'] != '') {
	$exdate = "and DATE(ex_fty_date) = '$_GET[xty]'";
} else {
	$exdate = "";
}



if ($_GET['d'] == 1) {
	$term = trim(strip_tags($_GET['term'])); 
	$dat=$con->select("laundry_data_wo A JOIN qrcode_cutplan_dtlhead B ON trim(A.wo_no) = trim(B.cpdwono)",
					   "wo_no",
					   "wo_no ilike '%$term%' GROUP BY wo_no");
	foreach($dat as $row)
	{
		$row['value']=htmlentities(stripslashes($row['wo_no']));
	//buat array yang nantinya akan di konversi ke json
	    $row_set[] = $row;
	}
} else if ($_GET['d'] == 2){
	$term = trim(strip_tags($_GET['term'])); 
	$dat=$con->select("laundry_data_wo A JOIN qrcode_cutplan_dtlhead B ON A.wo_no = B.cpdwono","cpdcolor as garment_colors","garment_colors ilike '%$term%' $cmt GROUP BY cpdcolor");
	  //echo "select cpdcolor as garment_colors from laundry_data_wo A JOIN qrcode_cutplan_dtlhead B ON A.wo_no = B.cpdwono where garment_colors ilike '%$term%' $cmt GROUP BY cpdcolor";
	foreach($dat as $row)
	{
		$row['value']=stripslashes($row['garment_colors']);
	//buat array yang nantinya akan di konversi ke json
	    $row_set[] = $row;
	}
}
else if ($_GET['d'] == 3){
	$term = trim(strip_tags($_GET['term'])); 
	$dat=$con->select("qrcode_ticketing_master","DATE(ex_fty_date) as ex_fty_date","DATE(ex_fty_date)::text ilike '%$term%' $cmt $color GROUP BY ex_fty_date");
	 // echo "select DATE(ex_fty_date) as ex_fty_date from qrcode_ticketing_master where DATE(ex_fty_date)::text ilike '%$term%' $cmt $color GROUP BY ex_fty_date";
	foreach($dat as $row)
	{
		$row['value']=htmlentities(stripslashes($row['ex_fty_date']));
	//buat array yang nantinya akan di konversi ke json
	    $row_set[] = $row;
	}
}

//data hasil query yang dikirim kembali dalam format json
echo json_encode($row_set);
?>