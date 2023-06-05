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

if ($_GET['exdate'] != '') {
	$exdate = "and to_char(ex_fty_date,'DD-MM-YYYY') = '$_GET[exdate]'";
} else {
	$exdate = "";
}


if ($_GET['d'] == 1) {
	$term = trim(strip_tags($_GET['term'])); 
	$dat=$con->select("laundry_wo_master_dtl_proc","wo_no","wo_no ilike '%$term%' GROUP BY wo_no");
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
	$dat=$con->select("laundry_wo_master_dtl_proc","garment_colors","garment_colors ilike '%$term%' $cmt GROUP BY garment_colors");
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
	$dat=$con->select("laundry_wo_master_dtl","garment_inseams","garment_inseams ilike '%$term%' $cmt $color $sizes $exdate GROUP BY garment_inseams");
	//echo "select garment_colors,wo_master_dtl_id from laundry_wo_master_dtl where garment_colors like '%$term%' $cmt $color $sizes $exdate";
	foreach($dat as $row)
	{
		$row['value']=htmlentities(stripslashes($row['garment_inseams']));
	    
	//buat array yang nantinya akan di konversi ke json
	    $row_set[] = $row;
	}
}
else if ($_GET['d'] == 4){
	$term = trim(strip_tags($_GET['term'])); 
	$dat=$con->select("laundry_wo_master_dtl","garment_sizes","garment_sizes ilike '%$term%' $cmt $color $inseams $exdate GROUP BY garment_sizes");
	//echo "select garment_colors,wo_master_dtl_id from laundry_wo_master_dtl where garment_colors like '%$term%' $cmt $color $inseams";
	foreach($dat as $row)
	{
		$row['value']=htmlentities(stripslashes($row['garment_sizes']));
	    
	//buat array yang nantinya akan di konversi ke json
	    $row_set[] = $row;
	}
}
else if ($_GET['d'] == 5){
	$term = trim(strip_tags($_GET['term'])); 
	$dat=$con->select("laundry_wo_master_dtl_proc","ex_fty_date,to_char(ex_fty_date,'DD-MM-YYYY') as ex_fty_date_show","ex_fty_date::text ilike '%$term%' $cmt $color $inseams GROUP BY ex_fty_date");
	//echo "select to_char(ex_fty_date,'DD-MM-YYYY') as ex_fty_date from laundry_wo_master_dtl_proc where ex_fty_date::text ilike '%$term%' $cmt $color $inseams GROUP BY ex_fty_date";
	foreach($dat as $row)
	{
		$row['value']=htmlentities(stripslashes($row['ex_fty_date_show']));
		$row['exftydateasli']=htmlentities(stripslashes($row['ex_fty_date']));
	    
	//buat array yang nantinya akan di konversi ke json
	    $row_set[] = $row;
	}
}
//data hasil query yang dikirim kembali dalam format json
echo json_encode($row_set);
?>