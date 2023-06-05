<?php
session_start();
require_once("../../../funlibs.php");
$con=new Database();

//echo $_GET['scmt'];
$scmt = $con->searchseqcmt($_GET['scmt']);
$scolor = $con->searchseq($_GET['scolor']);


if ($_GET['scolor'] != '') {
	$color = "and garment_colors = '$scolor'";
} else {
	$color = "";
}

if ($_GET['scmt'] != '') {
	$cmt = "and wo_no = '$scmt'";
} else {
	
	$cmt = "";
}



if ($_GET['d'] == 1) {
	$term = trim(strip_tags($_GET['term'])); 
	$dat=$con->select("laundry_wo_master_dtl_proc","wo_no","wo_no ilike '%$term%' GROUP BY wo_no");
	//echo "select wo_master_id,wo_no from laundry_wo_master where wo_no like '%$term%'";
	foreach($dat as $row)
	{
		$row['value']=htmlentities(stripslashes($row['wo_no']));
	//buat array yang nantinya akan di konversi ke json
	    $row_set[] = $row;
	}
} else if ($_GET['d'] == 2){
	$term = trim(strip_tags($_GET['term'])); 
	$dat=$con->select("laundry_wo_master_dtl_proc","garment_colors","garment_colors ilike '%$term%' $cmt GROUP BY garment_colors");
	//echo "select garment_colors from wip_dtl where garment_colors like '%$term%' $cmt GROUP BY garment_colors";
	foreach($dat as $row)
	{
		$row['value']=stripslashes($row['garment_colors']);
	//buat array yang nantinya akan di konversi ke json
	    $row_set[] = $row;
	}
}

//data hasil query yang dikirim kembali dalam format json
echo json_encode($row_set);
?>