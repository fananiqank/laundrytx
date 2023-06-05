<?php 
session_start();
include "../../../funlibs.php";
$con = new Database();

$position = $_POST['position'];
$date = date("Y-m-d H:i:s");

$i=1;
if ($_GET['edit'] == 1){
		foreach($position as $k=>$v){
			$data = array('role_dtl_wo_seq' => $i );
			$exec = $con->update("laundry_role_dtl_wo",$data,"role_dtl_wo_id = '$v'");
			
			//update role child
			//select lot no pada role child

			$sellotchild = $con->select("laundry_role_child","role_child_id,role_child_status","role_dtl_wo_id = '".$v."' and role_wo_master_id = '$_GET[rolemasterid]' and role_child_status = 0");
		//	echo "select role_child_id,role_child_status from laundry_role_child where role_dtl_wo_id = '".$v."' and role_wo_master_id = '$_GET[rolemasterid]' and role_child_status = 0";
			foreach ($sellotchild as $lotchild) {
						$datachnaik = array(
							'role_dtl_wo_seq' => $i,
						);
						//var_dump($datachnaik);

						$execchnaik = $con->update("laundry_role_child",$datachnaik,"role_child_id = '$lotchild[role_child_id]'");
			}
			$i++;
		}
		$datamodify = array(  
					 'role_wo_master_modifyby' => $_SESSION['ID_LOGIN'],
					 'role_wo_master_modifydate' => $date,
					); 
		$execmodify = $con->update("laundry_role_wo_master",$datamodify,"role_wo_master_id = '$_GET[rolemasterid]'");
		
} else {
		foreach($position as $k=>$v){
			$data = array('role_dtl_grup_seq' => $i );
			$exec = $con->update("laundry_role_dtl_grup",$data,"role_dtl_grup_id = '$v'");
			$i++;
		}
		
}

?>