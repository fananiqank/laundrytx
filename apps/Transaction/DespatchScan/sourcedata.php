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
if ($_GET['sins'] != '') {
	$inseams = "and garment_inseams = '$_GET[sins]'";
} else {
	$inseams = "";
}

if ($_GET['ssiz'] != '') {
	$sizes = "and garment_sizes = '$_GET[ssiz]'";
} else {
	$sizes = "";
}


if ($_GET['d'] == 1) {
	$term = trim(strip_tags($_GET['term'])); 
	$dat=$con->select("wip_dtl","wo_no","wo_no ilike '%$term%' GROUP BY wo_no");
	//echo "select wo_master_id,wo_no from laundry_wo_master where wo_no like '%$term%'";
	foreach($dat as $row)
	{
		$row['value']=htmlentities(stripslashes($row['wo_no']));
	    $row['id']=(int)$row['wo_master_id'];
	//buat array yang nantinya akan di konversi ke json
	    $row_set[] = $row;
	}
} else if ($_GET['d'] == 2){
	$term = trim(strip_tags($_GET['term'])); 
	$dat=$con->select("wip_dtl","garment_colors","garment_colors ilike '%$term%' $cmt GROUP BY garment_colors");
	//echo "select garment_colors from wip_dtl where garment_colors like '%$term%' $cmt GROUP BY garment_colors";
	foreach($dat as $row)
	{
		$row['value']=htmlentities(stripslashes($row['garment_colors']));
	    $row['id']=(int)$row['wip_dtl_id'];
	    
	//buat array yang nantinya akan di konversi ke json
	    $row_set[] = $row;
	}
}
else if ($_GET['d'] == 3){
	$term = trim(strip_tags($_GET['term'])); 
	$dat=$con->select("wip_dtl","garment_inseams","garment_inseams ilike '%$term%' $cmt $color $sizes GROUP BY garment_inseams");
	//echo "select garment_colors,wo_master_dtl_id from laundry_wo_master_dtl where garment_colors like '%$term%'";
	foreach($dat as $row)
	{
		$row['value']=htmlentities(stripslashes($row['garment_inseams']));
	    
	//buat array yang nantinya akan di konversi ke json
	    $row_set[] = $row;
	}
}
else if ($_GET['d'] == 4){
	$term = trim(strip_tags($_GET['term'])); 
	$dat=$con->select("wip_dtl","garment_sizes","garment_sizes ilike '%$term%' $cmt $color $inseams GROUP BY garment_sizes");
	//echo "select garment_colors,wo_master_dtl_id from laundry_wo_master_dtl where garment_colors like '%$term%' $cmt $color $inseams";
	foreach($dat as $row)
	{
		$row['value']=htmlentities(stripslashes($row['garment_sizes']));
	    
	//buat array yang nantinya akan di konversi ke json
	    $row_set[] = $row;
	}
}
//data hasil query yang dikirim kembali dalam format json
echo json_encode($row_set);
?>