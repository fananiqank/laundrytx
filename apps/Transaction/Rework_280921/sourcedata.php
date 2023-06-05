<?php
session_start();
require_once("../../../funlibs.php");
$con=new Database();
$sbuyer = $con->searchseq($_GET['sbuyer']);
$sstyle = $con->searchseq($_GET['sstyle']);
$scmt = $con->searchseqcmt($_GET['scmt']);
$scolor = $con->searchseq($_GET['scolor']);
$denim_no = $_GET['sdenim'];

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

if ($_GET['xty'] != '') {
	$exdate = "and DATE(ex_fty_date) = '$_GET[xty]'";
} else {
	$exdate = "";
}

if ($_GET['sstatus'] == 1){
	$statcolor = "and CONCAT(A.wo_no,'_',B.garment_colors) NOT IN (select CONCAT(wo_no,'_',garment_colors) from laundry_wo_master_dtl_proc where wo_no = '$scmt' and wo_master_dtl_proc_status = 1)";
} else if ($_GET['sstatus'] == 2){
	$statcolor = "and CONCAT(A.wo_no,'_',B.garment_colors) IN (select CONCAT(wo_no,'_',garment_colors) from laundry_wo_master_dtl_proc where wo_no = '$scmt' and wo_master_dtl_proc_status = 1)";
}

if ($_GET['d'] == 1){
	$term = trim(strip_tags($_GET['term'])); 
	$dat=$con->select("laundry_wo_master_dtl_proc",
					   "wo_no",
					   "wo_no ilike '%$term%' and wo_master_dtl_proc_status = 2 GROUP BY wo_no");
	// echo "select wo_no from laundry_wo_master_dtl_proc
	// 		where wo_no ilike '%$term%' and wo_master_dtl_proc_status = 2 GROUP BY wo_no";
	foreach($dat as $row)
	{
		$row['value']=htmlentities(stripslashes($row['wo_no']));
		$row['woasli']=htmlentities(stripslashes($row['wo_no']));
	//buat array yang nantinya akan di konversi ke json
	    $row_set[] = $row;
	}
}
else if ($_GET['d'] == 4){
	$term = trim(strip_tags($_GET['term'])); 
	$dat=$con->select("laundry_wo_master_dtl_proc",
					   "garment_colors","garment_colors ilike '%$term%' and wo_master_dtl_proc_status = 2 and wo_no = '$scmt' GROUP BY garment_colors");
	
	foreach($dat as $row)
	{
		$row['value']=htmlentities(stripslashes($row['garment_colors']));
		$row['colorasli']=htmlentities(stripslashes($row['garment_colors']));
	//buat array yang nantinya akan di konversi ke json
	    $row_set[] = $row;
	}
}

else if ($_GET['d'] == 5){
	$term = trim(strip_tags($_GET['term'])); 
	$dat=$con->select("laundry_wo_master_dtl_proc","DATE(ex_fty_date) as ex_fty_date,to_char(ex_fty_date,'DD-MM-YYYY') as ex_fty_date_show","DATE(ex_fty_date)::text ilike '%$term%' and wo_master_dtl_proc_status = 2 $cmt $color GROUP BY ex_fty_date");
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