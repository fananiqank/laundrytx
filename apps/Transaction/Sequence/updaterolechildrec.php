<?php 
 // session_start();
 // include "../../../funlibs.php";
 // $con = new Database;
if ($lotchild['lot_type'] == 1){
	//echo "A";
	$lottipe = 1;
	$wonameseq = "and role_wo_seq <= (select role_wo_seq from laundry_role_wo where role_wo_master_id = '".$_POST['role_wo_m_id']."' and master_type_process_id = 2 and role_wo_name_seq = 1)";
	//echo $lotchild['lot_no'].$lotchild[lot_type];
}
else if ($lotchild['lot_type'] == 2){
	$lottipe = 2;
	$wonameseq = "and role_wo_seq between (select (role_wo_seq+1) as role_wo_seq from laundry_role_wo where role_wo_master_id = '".$_POST['role_wo_m_id']."' and master_type_process_id = 2 and role_wo_name_seq = 1) and (select role_wo_seq from laundry_role_wo where role_wo_master_id = '".$_POST['role_wo_m_id']."' and master_type_process_id = 2 and role_wo_name_seq = '$lotchild[role_wo_name_seq]')";
}
else if ($lotchild['lot_type'] == 3){
	$lottipe = 2;
	$wonameseq = "and role_wo_seq between (select (role_wo_seq+1) as role_wo_seq from laundry_role_wo where role_wo_master_id = '".$_POST['role_wo_m_id']."' and master_type_process_id = 2 and role_wo_name_seq = 1) and (select role_wo_seq from laundry_role_wo where role_wo_master_id = '".$_POST['role_wo_m_id']."' and master_type_process_id = 2 and role_wo_name_seq = '$lotchild[role_wo_name_seq]')";
}
else if ($lotchild['lot_type'] == 5){
	$lottipe = 2;
	$wonameseq = "and role_wo_seq > (select role_wo_seq from laundry_role_wo where role_wo_master_id = '".$_POST['role_wo_m_id']."' and master_type_process_id = 2 and role_wo_name_seq = '".$maxrolenameseq[role_wo_name_seq]."')";
}
else if ($lotchild['lot_type'] == 4){
	$lottipe = 4;
	$wonameseq = "";
}


//select role pada data master role wo dan role dtl wo
$selseqwo = $con->select("laundry_role_wo A LEFT JOIN laundry_role_dtl_wo B ON A.role_wo_id = B.role_wo_id and B.role_dtl_wo_status = 1",
	     "A.role_wo_id,
		  A.role_wo_master_id,
		  B.role_dtl_wo_id,
		  A.role_wo_seq,
		  B.role_dtl_wo_seq",
  		"role_wo_master_id='".$_POST['role_wo_m_id']."' $wonameseq","role_wo_seq");
		// echo "select A.role_wo_id,
		// 	  A.role_wo_master_id,
		// 	  B.role_dtl_wo_id,
		// 	  A.role_wo_seq,
		// 	  B.role_dtl_wo_seq from laundry_role_wo A LEFT JOIN laundry_role_dtl_wo B ON A.role_wo_id = b.role_wo_id
		// 	  where role_wo_master_id='".$_POST['role_wo_m_id']."' and $wonameseq";
		// 	  die();
	
foreach ($selseqwo as $sqw) {

		//echo $childbylot['role_wo_id']."--".$seqchild['role_wo_id'];
//select berdasarkan role wo id dan lot number di role child
		foreach($con->select("laundry_role_child A JOIN laundry_role_wo C ON A.role_wo_id = C.role_wo_id",
			 "A.role_child_id, 
			  A.role_wo_id, 
			  A.role_wo_master_id, 
			  A.role_wo_seq as role_wo_seq_child, 
			  A.role_child_status,
			  C.role_wo_seq",
			 "A.role_wo_id = '".$sqw['role_wo_id']."' and A.lot_no = '".$lotchild['lot_no']."'") as $seqchild){}
			// echo "select A.role_child_id, 
			//   A.role_wo_id, 
			//   A.role_wo_master_id, 
			//   A.role_wo_seq as role_wo_seq_child, 
			//   A.role_child_status,
			//   C.role_wo_seq from laundry_role_child A JOIN laundry_role_wo C ON A.role_wo_id = C.role_wo_id where A.role_wo_id = '".$sqw['role_wo_id']."' and A.lot_no = '".$lotchild['lot_no']."'";
//jika role wo id tidak sama dan tidak memiliki dtl wo id (lotmaking,qc,despatch)		
				if($seqchild['role_wo_id'] != $sqw['role_wo_id'] && $sqw['role_dtl_wo_id'] == ""){
					//echo "A";
						$idchild = $con->idurut("laundry_role_child","role_child_id");
						$datainchild = array(
							'role_child_id' => $idchild,
							'role_wo_master_id'  => $_POST['role_wo_m_id'],
							'role_wo_id'  => $sqw['role_wo_id'],
							'lot_type'  => $lottipe,
							'role_child_status'  => 0,
							'role_child_createdate'  => $date,
							'role_child_createdby'  => $_SESSION['ID_LOGIN'],
							'lot_no'  => $lotchild['lot_no'],
							'lot_id'  => $lotchild['lot_id'],
							'role_wo_seq' => $sqw['role_wo_seq'],
						);
						$execinchild = $con->insert("laundry_role_child",$datainchild);
				} 
//jika role wo id tidak sama dan role dtl wo tidak sama (tambah wet / dry proses)
				else if ($seqchild['role_wo_id'] != $sqw['role_wo_id'] && $sqw['role_dtl_wo_id'] != ""){
					//echo "B";
						foreach($con->select("laundry_role_child A 
							   JOIN laundry_role_dtl_wo C ON A.role_dtl_wo_id = C.role_dtl_wo_id and C.role_dtl_wo_status = 1",
							 "A.role_child_id, 
							  A.role_wo_id, 
							  A.role_wo_master_id, 
							  A.role_dtl_wo_seq as role_dtl_wo_seq_child, 
							  C.role_dtl_wo_id, 
							  C.role_dtl_wo_seq,
							  A.role_child_status",
							 "A.role_dtl_wo_id = '".$sqw['role_dtl_wo_id']."' and A.lot_no = '".$lotchild['lot_no']."'") as $seqchild){}
						
										$idchild = $con->idurut("laundry_role_child","role_child_id");
										$datainchild = array(
										'role_child_id' => $idchild,
										'role_wo_master_id'  => $_POST['role_wo_m_id'],
										'role_wo_id'  => $seqchild['role_wo_id'],
										'role_dtl_wo_id'  => $sqw['role_dtl_wo_id'],
										'lot_type'  => $lottipe,
										'role_child_status'  => 0,
										'role_child_createdate'  => $date,
										'role_child_createdby'  => $_SESSION['ID_LOGIN'],
										'lot_no'  => $lotchild['lot_no'],
										'lot_id'  => $lotchild['lot_id'],
										'role_wo_seq' => $sqw['role_wo_seq'],
										'role_dtl_wo_seq' => $sqw['role_dtl_wo_seq'],
										);
										// var_dump($datainchild);
										// echo " || ";
										$execinchild = $con->insert("laundry_role_child",$datainchild);
										//var_dump($execinchild); echo " -- ";
						
				} 
//jika role wo id sama tetapi role dtl wo kosong (ubah sequence lotmaking,qc,despatch)
				else if ($seqchild['role_wo_id'] == $sqw['role_wo_id'] && $sqw['role_dtl_wo_id'] == ""){
					//echo "c";
					if ($seqchild['role_wo_seq'] != $seqchild['role_wo_seq_child']){
						if($seqchild['role_child_status'] == 0){
							$datainchild = array(
									'role_wo_seq' => $seqchild['role_wo_seq'],
							);
							$execinchild = $con->update("laundry_role_child",$datainchild,"role_child_id = '".$seqchild['role_child_id']."'");
						}
					}
				} 
//update data
//jika role wo id sama tapi dtl wo tidak sama (ubah sequence dry / wet detail)
				else if ($seqchild['role_wo_id'] == $sqw['role_wo_id'] && $sqw['role_dtl_wo_id'] != ""){
					//echo "D";
					foreach($con->select("laundry_role_child A 
							   JOIN laundry_role_wo B ON A.role_wo_id = B.role_wo_id
							   JOIN laundry_role_dtl_wo C ON A.role_dtl_wo_id = C.role_dtl_wo_id and C.role_dtl_wo_status = 1",
							 "A.role_child_id, 
							  A.role_wo_id, 
							  A.role_wo_master_id, 
							  A.role_dtl_wo_seq as role_dtl_wo_seq_child, 
							  A.role_wo_seq as role_wo_seq_child, 
							  C.role_dtl_wo_id, 
							  C.role_dtl_wo_seq,
							  A.role_child_status,
							  B.role_wo_seq",
							 "A.role_dtl_wo_id = '".$sqw['role_dtl_wo_id']."' and A.lot_no = '".$lotchild['lot_no']."'") as $seqchild){}
						if($seqchild['role_dtl_wo_id'] != $sqw['role_dtl_wo_id']) {
								foreach($con->select("laundry_role_child A 
										   JOIN laundry_role_dtl_wo C ON A.role_dtl_wo_id = C.role_dtl_wo_id and C.role_dtl_wo_status = 1
										   left join (SELECT MAX ( role_dtl_wo_seq ) AS max_dtl_role,role_wo_id FROM	laundry_role_child where role_wo_id  = '".$sqw['role_dtl_wo_id']."' and lot_no = '".$lotchild['lot_no']."' AND  role_child_status = 3 GROUP BY role_wo_id) as D on A.role_wo_id = D.role_wo_id",
										 "A.role_child_id, 
										  A.role_wo_id, 
										  A.role_wo_master_id, 
										  A.role_dtl_wo_seq as role_dtl_wo_seq_child, 
										  C.role_dtl_wo_id, 
										  C.role_dtl_wo_seq,
										  A.role_child_status,
										  COALESCE(D.max_dtl_role,0) as max_dtl_role",
										 "A.role_dtl_wo_id = '".$sqw['role_dtl_wo_id']."' 
										 and A.lot_no = '".$lotchild['lot_no']."'") as $seqchild){}
								$roledtlwoseq = $sqw['role_dtl_wo_seq']+$seqchild['max_dtl_role'];
								$idchild = $con->idurut("laundry_role_child","role_child_id");
								$datainchild = array(
										'role_child_id' => $idchild,
										'role_wo_master_id'  => $_POST['role_wo_m_id'],
										'role_wo_id'  => $seqchild['role_wo_id'],
										'role_dtl_wo_id'  => $sqw['role_dtl_wo_id'],
										'lot_type'  => $lottipe,
										'role_child_status'  => 0,
										'role_child_createdate'  => $date,
										'role_child_createdby'  => $_SESSION['ID_LOGIN'],
										'lot_no'  => $lotchild['lot_no'],
										'lot_id'  => $lotchild['lot_id'],
										'role_wo_seq' => $sqw['role_wo_seq'],
										'role_dtl_wo_seq' => $roledtlwoseq,
										);
								$execinchild = $con->insert("laundry_role_child",$datainchild);
						} else {
							if ($seqchild['role_dtl_wo_seq'] != $seqchild['role_dtl_wo_seq_child']){
									if($seqchild['role_child_status'] == 0){
										$datainchild = array(
											'role_wo_seq' => $sqw['role_wo_seq'],
											'role_dtl_wo_seq' => $seqchild['role_dtl_wo_seq'],
										);
										$execinchild = $con->update("laundry_role_child",$datainchild,"role_child_id = '".$seqchild['role_child_id']."'");
												
									}
							} else if ($seqchild['role_wo_seq'] != $seqchild['role_wo_seq_child']){
									if($seqchild['role_child_status'] == 0){
										$datainchild = array(
											'role_wo_seq' => $sqw['role_wo_seq'],
											
										);
										$execinchild = $con->update("laundry_role_child",$datainchild,"role_child_id = '".$seqchild['role_child_id']."'");
												
									}
							}
						}
						
				}
	
}

//jika ada role pada role child yang tidak ada pada role saat ini active, maka akan di didelete oleh system
$selchildnonactive = $con->select("laundry_role_child",
	     "role_wo_id,role_child_id,role_child_status",
  		 "role_wo_master_id = '".$_POST['role_wo_m_id']."' and  
  		  role_wo_id NOT IN (SELECT A.role_wo_id
							FROM laundry_role_wo A 
							LEFT JOIN laundry_role_dtl_wo B ON A.role_wo_id = b.role_wo_id and role_dtl_wo_status = 1
							WNERE role_wo_master_id='".$_POST['role_wo_m_id']."' AND 
							      role_wo_seq $panah (SELECT role_wo_seq 
							                      FROM laundry_role_wo 
							                      WHERE role_wo_master_id = '".$_POST['role_wo_m_id']."' 
							                      and master_type_process_id = 2 $wonameseq))");
		
foreach ($selchildnonactive as $childnonactive){
	if ($childnonactive['role_child_status'] != 1 || $childnonactive['role_child_status'] != 3){
		$where = array('role_child_id' => $childnonactive['role_child_id']);
		$con->delete("laundry_role_child",$where);
	}
}

?>