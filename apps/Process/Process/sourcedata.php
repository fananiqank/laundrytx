<?php
session_start();
require_once("../../../funlibs.php");
$con=new Database();

	$term = trim(strip_tags($_GET['term'])); 
	$dat=$con->select("laundry_user","user_id,user_name,master_type_process_id","user_name ilike '%$term%'");
	//echo "select wo_master_id,wo_no from laundry_wo_master where wo_no like '%$term%'";
	foreach($dat as $row)
	{
		$row['value']=htmlentities(stripslashes($row['user_name']));
	    $row['id']=(int)$row['user_id'];
	//buat array yang nantinya akan di konversi ke json
	    $row_set[] = $row;
	}

//data hasil query yang dikirim kembali dalam format json
echo json_encode($row_set);
?>