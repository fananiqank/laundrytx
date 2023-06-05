<?php 
 // session_start();
 // include "../../../funlibs.php";
 // $con = new Database;

//select role pada data master role wo dan role dtl wo
$selseqwo = $con->select("laundry_role_wo A LEFT JOIN laundry_role_dtl_wo B ON A.role_wo_id = b.role_wo_id",
	     "A.role_wo_id,
		  A.role_wo_master_id,
		  B.role_dtl_wo_id,
		  A.role_wo_seq,
		  B.role_dtl_wo_seq,
		  A.master_type_process_id",
  		"role_wo_master_id='$_POST[role_wo_m_id]'","role_wo_seq");
	
foreach ($selseqwo as $sqw) {
	//pengecekan apakah ada role tersebut di child
		$cekrolewoid=$con->selectcount("laundry_role_child","role_child_id","role_wo_id = '$sqw[role_wo_id]'");
		$cekroledtlwoid=$con->selectcount("laundry_role_child","role_child_id","role_wo_id = '$sqw[role_wo_id]' and role_dtl_wo_id = '$sqw[role_dtl_wo_id]'");
		echo $cekroledtlwoid;

		if($sqw['master_type_process_id'] == 4 || $sqw['master_type_process_id'] == 5) {
			if($cekroledtlwoid == 0) {
				$idchild = $con->idurut("laundry_role_child","role_child_id");
				$datainchild = array(
										'role_child_id' => $idchild,
										'role_wo_master_id'  => $_POST['role_wo_m_id'],
										'role_wo_id'  => $sqw['role_wo_id'],
										'role_dtl_wo_id'  => $sqw['role_dtl_wo_id'],
										'lot_type'  => 4,
										'role_child_status'  => 0,
										'role_child_createdate'  => $date,
										'role_child_createdby'  => $_SESSION['ID_LOGIN'],
										'lot_no'  => $lotchild['lot_no'],
										'lot_id'  => $lotchild['lot_id'],
										'role_wo_seq' => $sqw['role_wo_seq'],
										'role_dtl_wo_seq' => $sqw['role_dtl_wo_seq'],
									);
				$execinchild = $con->insert("laundry_role_child",$datainchild);			
			} 
		} else {
			if($cekrolewoid == 0) {
				$idchild = $con->idurut("laundry_role_child","role_child_id");
				$datainchild = array(
										'role_child_id' => $idchild,
										'role_wo_master_id'  => $_POST['role_wo_m_id'],
										'role_wo_id'  => $sqw['role_wo_id'],
										'role_dtl_wo_id'  => $sqw['role_dtl_wo_id'],
										'lot_type'  => 4,
										'role_child_status'  => 0,
										'role_child_createdate'  => $date,
										'role_child_createdby'  => $_SESSION['ID_LOGIN'],
										'lot_no'  => $lotchild['lot_no'],
										'lot_id'  => $lotchild['lot_id'],
										'role_wo_seq' => $sqw['role_wo_seq'],
										'role_dtl_wo_seq' => $sqw['role_dtl_wo_seq'],
									);
				$execinchild = $con->insert("laundry_role_child",$datainchild);			
			} 
		}
		
}
?>