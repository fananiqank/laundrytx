<?php	
session_start();
include "../../../funlibs.php";
$con = new Database;
//error_reporting(0);
$date = date("Y-m-d H:i:s");
$date2 = date("Y-m-d");
$datelabel = date("ymdHi");

$kodeunik = $con->selectcount("laundry_label_container","wo_no","wo_no = '$_POST[nocmt]' and garment_colors = '$_POST[nocolor]'");
//if ($_POST['jenis'] == 'edit'){
		$hwo = explode('/', $_POST['nocmt']);
		$labelcode = $hwo[4].$hwo[5].$datelabel.$kodeunik;
		$dataprocess = array( 
								 'wo_no' => $_POST['nocmt'],
								 'garment_colors' => $_POST['nocolor'],
								 'label_status' => 1,
								 'label_createdate' =>  $date,
								 'label_qty' => $_POST['label_qty'],
								 'usercode' => $_POST['usercode'],
								 'label_no' => $labelcode, 
						);
		
		$execprocess = $con->insert("laundry_label_container",$dataprocess); 
		echo $labelcode.'_'.base64_encode($labelcode);
		//var_dump($execprocess);
// } else {
// 		$idprocess = $con->idurut("laundry_master_process","master_process_id");
// 		$dataprocess = array( 
// 								 'master_process_id' => $idprocess,
// 								 'master_process_name' => $_POST['name'],
// 								 'master_type_process' => $_POST['typeprocess'],
// 								 'master_process_usemachine' => $usemachine,
// 								 'master_process_usemultimachine' => $usemultimachine,
// 								 'master_process_split_lot' => $usesplit,
// 								 'master_process_combine_lot' => $usecombine,
// 								 'master_process_status' => 1,
// 								 'master_process_createdate' => $date,
// 								 'master_process_createdby' => $_SESSION['ID_LOGIN'],
// 						);
// 		//var_dump($dataprocess);
// 		$execprocess = $con->insert("laundry_master_process",$dataprocess); 
// 		//var_dump($execprocess);
// }

		


