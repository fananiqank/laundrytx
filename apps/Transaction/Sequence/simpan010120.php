<?php 

session_start();
include "../../../funlibs.php";
$con = new Database;
//error_reporting(0);
$date = date("Y-m-d H:i:s");
$date2 = date("Y-m-d");
$tabel = "laundry_role_grup";


//input role proses
if ($_POST['codeproc'] == 'input'){
	//echo "sese";
	if ($_POST['rolemasterid'] == ''){
		$idrolemaster = $con->idurut("laundry_role_grup_master","role_grup_master_id");
		$datarolemaster = array(
					 'role_grup_master_id' => $idrolemaster,
					 'role_grup_master_name' => "Laundry".$date2.$idrolemaster, 
					 'role_grup_master_status' => 1, 
					 'role_grup_master_createdate' => $date,
					 'role_grup_master_user_id' => $_SESSION['ID_LOGIN'],
		); 
		$execrolemaster= $con->insert("laundry_role_grup_master", $datarolemaster);
		
	} else {
		$idrolemaster = $_POST['rolemasterid'];
	}

	$exptype = explode('_',$_POST['type']);
	//include "phpmailer/pushemailadmin.php";
	$selcount = $con->select("laundry_role_grup","COUNT(role_grup_id) as jmlrole","role_grup_user_id = '$_SESSION[ID_LOGIN]' and role_grup_master_id = '$idrolemaster'");
	// echo "select COUNT(role_grup_id) as jmlrole from laundry_role_grup where role_grup_user_id = '$_SESSION[ID_LOGIN]' and role_grup_master_id = '$idrolemaster'";
	// die();
	foreach($selcount as $count) {}
	$selnama = $con->select("laundry_role_grup","COUNT(role_grup_id) as jmlrole","role_grup_user_id = '$_SESSION[ID_LOGIN]' and role_grup_jenis = '$exptype[0]' and role_grup_master_id = '$idrolemaster'");
	foreach($selnama as $nam) {}

	$urutan = $count['jmlrole'] + 1;
	$namaurut = $nam['jmlrole'] + 1;
	$type = $exptype[1]." ".$namaurut;
	$idroker = $con->idurut($tabel,"role_grup_id");
	
	$data = array (
	'role_grup_id' => $idroker,
	'role_grup_name' => $type,
	'role_grup_dateupdate' => $date,
	'role_grup_user_id' => $_SESSION['ID_LOGIN'],
	'role_grup_jenis' => $exptype[0],
	'role_grup_seq' => $urutan,
	'role_grup_master_id' => $idrolemaster,
	);
	$exec = $con->insert($tabel,$data);
	
	foreach ($_POST['process'] as $key => $value){
		$expval = explode("_",$value);
		$mpval = $expval[0];
		$urval = $expval[1];
					$idrodtl = $con->idurut("laundry_role_dtl_grup","role_dtl_grup_id");
					$data2 = array( 
								'role_dtl_grup_id' => $idrodtl,
								 'role_grup_id' => $idroker,
								 'master_process_id' => $mpval,
								 'role_dtl_grup_dateupdate' => $date,
								 'role_dtl_grup_seq' => $urval,
							); 
					$exec2= $con->insert("laundry_role_dtl_grup", $data2);
				}
	echo "<script>window.location='content.php?p=$_POST[getp]'</script>";
} 
//update role process
else if ($_POST['codeproc'] == 'edit'){
	//echo "susu";
	foreach ($_POST['process2'] as $key => $value){
		$expval = explode("_",$value);
		$mpval = $expval[0];
		$urval = $expval[1];
		$id = $expval[2];
					$data2 = array( 
								 /*'nama_berita' => $_POST[nama],*/
								 'role_grup_id' => $id,
								 'master_process_id' => $mpval,
								 'role_dtl_grup_dateupdate' => $date,
								 'role_dtl_grup_seq' => $urval,
							); 
					$exec2= $con->insert("laundry_role_dtl_grup", $data2);
			
	}
	if ($_POST['getp'] != ''){
		echo "<script>window.location='content.php?p=$_POST[getp]&d=$_POST[getd]'</script>";
	}
	else {
		echo "<script>window.location='content.php?p=$_POST[getp]'</script>";
	}
}
// ubah sequence proses
else if ($_POST['idnya'] != ''){
	//echo "sasa";
	$naik = $_POST['naik']-'1';
	$turun = $_POST['turun']+'1';
	if ($_POST['sub'] != ''){
	
		if ($_POST['naik'] != '' && $_POST['turun'] == ''){
			$selseq = $con->select("laundry_role_dtl_grup","*","role_dtl_grup_id = '$_POST[idnya]'");
			foreach ($selseq as $seq) {}
			$dataseq = array(
				'role_dtl_grup_seq' => $naik,
			);
			$execseq = $con->update("laundry_role_dtl_grup",$dataseq,"role_dtl_grup_id = '$_POST[idnya]'");
			$selsequ = $con->select("laundry_role_dtl_grup","*","role_dtl_grup_seq = '$naik' and role_dtl_grup_id != '$_POST[idnya]' and role_grup_id = '$_POST[sub]'");
			foreach ($selsequ as $sequ) {}
				$seqdb = $sequ['role_dtl_grup_seq']+'1';
				$datasequ = array(
				'role_dtl_grup_seq' => $seqdb,
				);
				$execsequ = $con->update("laundry_role_dtl_grup",$datasequ,"role_dtl_grup_id = '$sequ[role_dtl_grup_id]'");
		}
		else if ($_POST['turun'] != '' && $_POST['naik'] == ''){

			$selseq = $con->select("laundry_role_dtl_grup","*","role_dtl_grup_id = '$_POST[idnya]'");
			foreach ($selseq as $seq) {}
			$dataseq = array(
				'role_dtl_grup_seq' => $turun,
			);
			$execseq = $con->update("laundry_role_dtl_grup",$dataseq,"role_dtl_grup_id = '$_POST[idnya]'");
			$selsequ = $con->select("laundry_role_dtl_grup","*","role_dtl_grup_seq = '$turun' and role_dtl_grup_id != '$_POST[idnya]' and role_grup_id = '$_POST[sub]'");
			foreach ($selsequ as $sequ) {}
				$seqdb = $sequ['role_dtl_grup_seq']-'1';
				$datasequ = array(
				'role_dtl_grup_seq' => $seqdb,
				);
				$execsequ = $con->update("laundry_role_dtl_grup",$datasequ,"role_dtl_grup_id = '$sequ[role_dtl_grup_id]'");
		}
	} else {
		if ($_POST['naik'] != '' && $_POST['turun'] == ''){
			$selseq = $con->select("laundry_role_grup","*","role_grup_id = '$_POST[idnya]'");
			foreach ($selseq as $seq) {}
			$dataseq = array(
				'role_grup_seq' => $naik,
			);
			$execseq = $con->update("laundry_role_grup",$dataseq,"role_grup_id = '$_POST[idnya]'");
			$selsequ = $con->select("laundry_role_grup","*","role_grup_seq = '$naik' and role_grup_id != '$_POST[idnya]'");
			foreach ($selsequ as $sequ) {}
				$seqdb = $sequ['role_grup_seq']+'1';
				$datasequ = array(
				'role_grup_seq' => $seqdb,
				);
				$execsequ = $con->update("laundry_role_grup",$datasequ,"role_grup_id = '$sequ[role_grup_id]'");
		}
		else if ($_POST['turun'] != '' && $_POST['naik'] == ''){

			$selseq = $con->select("laundry_role_grup","*","role_grup_id = '$_POST[idnya]'");
			foreach ($selseq as $seq) {}
			$dataseq = array(
				'role_grup_seq' => $turun,
			);

			$execseq = $con->update("laundry_role_grup",$dataseq,"role_grup_id = '$_POST[idnya]'");
			$selsequ = $con->select("laundry_role_grup","*","role_grup_seq = '$turun' and role_grup_id != '$_POST[idnya]'");
			foreach ($selsequ as $sequ) {}
				$seqdb = $sequ['role_grup_seq']-'1';
				$datasequ = array(
				'role_grup_seq' => $seqdb,
				);
				$execsequ = $con->update("laundry_role_grup",$datasequ,"role_grup_id = '$sequ[role_grup_id]'");
		}
	}
} 

// input cmt no to on plan
else if ($_POST['cmtwo'] == '1'){ 
	foreach ($_POST['cmtno'] as $key => $value){
			$expval = explode('_',$value);
			$wono = $expval[0];
			$color = $expval[1];
			$exdate = $expval[2];
			$qty = $expval[3];
			$bpo = $expval[4];

			$data2 = array( 
					 'wo_no' => $wono,
					 'wo_master_keranjang_dateupdate' => $date,
					 'wo_master_keranjang_user_id' => $_SESSION[ID_LOGIN],
					 'garment_colors' => $color,
					 'ex_fty_date' => $exdate,
					 'quantity' => $qty,
					 'buyer_po_number' => $bpo,
			); 
			$exec2= $con->insert("laundry_wo_master_keranjang", $data2);
	}
} 
//hapus cmt no from on plan
else if ($_POST['cmtwo'] == '2'){ 
	//echo "soso";
	foreach ($_POST['wono'] as $key => $value){

			$where = array('wo_master_keranjang_id' => $value);
			$con->delete("laundry_wo_master_keranjang",$where);
			$selwoid = $con->select("laundry_wo_master","wo_master_id","wo_no = '$value'");
			foreach ($selwoid as $wd) {}
			$whereproc = array('wo_master_id' => $wd['wo_master_id']);
			$con->delete("laundry_wo_master_dtl_proc",$whereproc);
			
	}
}
// edit Time Process
else if ($_POST['cmtwo'] == '3' && $_POST['cmtwo2'] == '4'){ 
	
	foreach ($_POST['seq'] as $key => $value){
			$datatime = array( 
					 'role_dtl_grup_time' => $value, 
					 'role_dtl_grup_dateupdate' => $date,
			); 
			$pos = $_POST['seq_id'][$key];
			$exectime = $con->update("laundry_role_dtl_grup",$datatime,"role_dtl_grup_id = '$pos'");
	}
	foreach ($_POST['mainseq'] as $key => $value){
			$datatime2 = array( 
					 'role_grup_time' => $value, 
					 'role_grup_dateupdate' => $date,
			); 
			$pos2 = $_POST['mainseq_id'][$key];
			$exectime2= $con->update("laundry_role_grup", $datatime2,"role_grup_id = '$pos2'");
	}
} 
// hapus Role process
else if ($_POST['cmtwo2'] == '3'){ 
	//echo "didi";
			$where = array('role_grup_id' => $_GET[d]);
			$con->delete("laundry_role_dtl_grup",$where);
			$con->delete("laundry_role_grup",$where);

			$selsequ = $con->select("laundry_role_grup","*","role_grup_seq > '$_GET[e]' and role_grup_user_id = '$_GET[f]'");
			foreach ($selsequ as $sequ) {
				$seqdb = $sequ['role_grup_seq']-'1';
				$datasequ = array(
				'role_grup_seq' => $seqdb,
				);
				$execsequ = $con->update("laundry_role_grup",$datasequ,"role_grup_id = '$sequ[role_grup_id]'");
			}
} 
// hapus edit proses 
else if ($_POST['codeproc'] == 'hapusdtl'){ 
	//echo "dudu";
			$where = array('role_dtl_grup_id' => $_POST['hpsdtl']);
			$con->delete("laundry_role_dtl_grup",$where);

			$selsequ = $con->select("laundry_role_dtl_grup","*","role_dtl_grup_seq > '$_GET[seq]' and role_grup_id = '$_GET[id]'");
			//echo "select * from laundry_role_dtl_grup where role_dtl_grup_seq > '$_GET[seq]' and role_grup_id = '$_GET[id]'";
			foreach ($selsequ as $sequ) {
				$seqdb = $sequ['role_dtl_grup_seq']-'1';
				$datasequ = array(
				'role_dtl_grup_seq' => $seqdb,
				);
				$execsequ = $con->update("laundry_role_dtl_grup",$datasequ,"role_dtl_grup_id = '$sequ[role_dtl_grup_id]'");
			}

			$selsum = $con->select("laundry_role_dtl_grup","SUM(role_dtl_grup_time) as time","role_grup_id = '$_GET[id]'");
			foreach ($selsum as $sum) {}
				$datasum = array('role_grup_time' => $sum['time']);
				$execsum = $con->update("laundry_role_grup",$datasum,"role_grup_id = $_GET[id]");
			
}
//Use Template
else if ($_POST['temple'] == '9'){ 
	//echo "dudu";
		$selrolesatu = $con->select("laundry_role_grup_master","*","role_grup_master_status = 1");
		foreach ($selrolesatu as $rolesatu) {
			$selroledua = $con->select("laundry_role_grup","role_grup_id","role_grup_master_id = '$rolesatu[role_grup_master_id]'");
			foreach ($selroledua as $roledua) {
				$selroletiga = $con->select("laundry_role_dtl_grup","role_dtl_grup_id","role_grup_id = '$roledua[role_grup_id]'");
				foreach ($selroletiga as $roletiga) {
					$where3 = array('role_dtl_grup_id' => $roletiga['role_dtl_grup_id']);
					$con->delete("laundry_role_dtl_grup",$where3);
				}
				$where2 = array('role_grup_id' => $roledua['role_grup_id']);
				$con->delete("laundry_role_grup",$where2);
			}
		$where = array('role_grup_master_id' => $rolesatu['role_grup_master_id']);
		$con->delete("laundry_role_grup_master",$where);
		}
		
		$selrolesatu2 = $con->select("laundry_role_wo_master","*","role_wo_master_id = '$_POST[temp_name]'");
		foreach ($selrolesatu2 as $rolesatu2) {}
				$idgrupmaster = $con->idurut("laundry_role_grup_master","role_grup_master_id");
				$datagrupmaster = array(
					 'role_grup_master_id' => $idgrupmaster, 
					 'role_grup_master_name' => "Laundry".$idgrupmaster,
					 'role_grup_master_status' => 1, 
					 'role_grup_master_createdate' => $date,
					 'role_grup_master_user_id' => $_SESSION['ID_LOGIN'],
				); 
				$execgrupmaster= $con->insert("laundry_role_grup_master", $datagrupmaster);

			$selroledua2 = $con->select("laundry_role_wo","*","role_wo_master_id = '$_POST[temp_name]'");
			foreach ($selroledua2 as $roledua2) {
						$idgrup = $con->idurut($tabel,"role_grup_id");
						$datagrup = array (
						'role_grup_id' => $idgrup,
						'role_grup_name' => $roledua2['role_wo_name'],
						'role_grup_dateupdate' => $date,
						'role_grup_user_id' => $_SESSION['ID_LOGIN'],
						'role_grup_jenis' => $roledua2['role_wo_jenis'],
						'role_grup_seq' => $roledua2['role_wo_seq'],
						'role_grup_master_id' => $idgrupmaster,
						);
						$execgrup = $con->insert($tabel,$datagrup);

				$selroletiga2 = $con->select("laundry_role_dtl_wo","*","role_wo_id = '$roledua2[role_wo_id]'");
				foreach ($selroletiga2 as $roletiga2) {
								$idgrupdtl = $con->idurut("laundry_role_dtl_grup","role_dtl_grup_id");
								$datagrupdtl = array( 
											'role_dtl_grup_id' => $idgrupdtl,
											 'role_grup_id' => $idgrup,
											 'master_process_id' => $roletiga2['master_process_id'],
											 'role_dtl_grup_dateupdate' => $date,
											 'role_dtl_grup_seq' => $roletiga2['role_dtl_wo_seq'],
										); 
								$execgrupdtl= $con->insert("laundry_role_dtl_grup", $datagrupdtl);
				}
				
			}
			echo "<script>window.location='content.php?p=$_POST[getp]'</script>";
		
}
else {
	$jmlappwo = $_POST['jmlappwo'];
	//pengecekan CMT
	if ($jmlappwo == '' ){
		echo "<script>alert('Data CMT On Plan Not Found!!')</script>";
		echo "<script>window.location='content.php?p=$_POST[getp]'</script>";
	} else if ($_POST['role_grup_m_id'] == ''){
		echo "<script>alert('Sequence Process Not Found!!')</script>";
		echo "<script>window.location='content.php?p=$_POST[getp]'</script>";
	}
	else {

		// $datarolemaster = array( 
		// 		 'role_grup_master_status' => 2, 
		// 		 'role_grup_master_createdate' => $date,
		// ); 
		// $execrolemaster= $con->update("laundry_role_grup_master", $datarolemaster,"role_grup_master_id = '$_POST[rolegrupmas2]'");
		//master role wo
		$selkeranjang = $con->select("laundry_wo_master_keranjang","*","wo_master_keranjang_user_id = '$_SESSION[ID_LOGIN]'");
		// echo "select * from laundry_wo_master_keranjang";
		// die();
		foreach ($selkeranjang as $sker) {
			$idproc = $con->idurut("laundry_wo_master_dtl_proc","wo_master_dtl_proc_id");
			$dataproc = array( 
					 'wo_master_dtl_proc_id' => $idproc,
					 'garment_colors' => $sker['garment_colors'],
					 'buyer_po_number' => $sker['buyer_po_number'],
					 'wo_master_dtl_proc_qty' => $sker['quantity'],
					 'wo_master_dtl_proc_status' => 1,
					 'ex_fty_date' => $sker['ex_fty_date'],
					); 		
			//var_dump($dataproc);

			$execproc= $con->insert("laundry_wo_master_dtl_proc", $dataproc);
			
			//create role wo master
			$idrolewom = $con->idurut("laundry_role_wo_master","role_wo_master_id");
			$datarolewom = array(
							 'role_wo_master_id' => $idrolewom,
							 'role_wo_master_name' => $sker['wo_no'].'-'.$sker['garment_colors'].'-'.$date2, 
							 'role_wo_master_status' => 1, 
							 'role_wo_master_createdate' => $date,
							 'role_wo_master_user_id' => $_SESSION['ID_LOGIN'],
						); 
			$execrolemaster= $con->insert("laundry_role_wo_master", $datarolewom);

			foreach ($_POST['role3'] as $key => $value)	
			{
				$selrolewo = $con->select("laundry_role_grup","*","role_grup_id = '$value'","role_grup_seq ASC");
				foreach ($selrolewo as $rolewo) {
					$idrolewot = $con->idurut("laundry_role_wo","role_wo_id");
						$datawot = array (
							'role_wo_id' => $idrolewot,
							'role_wo_name' => $rolewo['role_grup_name'],
							'role_wo_dateupdate' => $date,
							'role_wo_user_id' => $_SESSION['ID_LOGIN'],
							'role_wo_jenis' => $rolewo['role_grup_jenis'],
							'role_wo_seq' => $rolewo['role_grup_seq'],
							'role_wo_master_id' => $idrolewom,
						);
						$execwot = $con->insert("laundry_role_wo",$datawot);
				} 
			}

			foreach ($_POST['appwo2'] as $key => $value)	
			{
			$woid = explode("_",$_POST['woid'][$key]);
			$color = $woid[0];
			$wo = $woid[1];
			$rolemaster = $woid[2];
			$roleid = $woid[3];
			$exftydate = $woid[4];
			// role wo
			//echo $sker['ex_fty_date'];
			// echo $exftydate;
			// die();
				if ($value == 1 && $sker['wo_no']==$wo && $sker['garment_colors']==$color && $sker['ex_fty_date']==$exftydate){
					$selrolewo = $con->select("laundry_role_grup","*","role_grup_id = '$roleid'","role_grup_seq ASC");
					foreach ($selrolewo as $rolewo) {
						
						$idrolewot = $con->idurut("laundry_role_wo","role_wo_id");
							$datawot = array (
								'role_wo_id' => $idrolewot,
								'role_wo_name' => $rolewo['role_grup_name'],
								'role_wo_dateupdate' => $date,
								'role_wo_user_id' => $_SESSION['ID_LOGIN'],
								'role_wo_jenis' => $rolewo['role_grup_jenis'],
								'role_wo_seq' => $rolewo['role_grup_seq'],
								'role_wo_time' => $rolewo['role_grup_time'],
								'role_wo_master_id' => $idrolewom,
							);
							$execwot = $con->insert("laundry_role_wo",$datawot);
							// role dtl
								$selrolewodtl = $con->select("laundry_role_dtl_grup","*","role_grup_id = '$rolewo[role_grup_id]'");
								//echo "select * from laundry_role_dtl_grup where role_grup_id = '$rolewo[role_grup_id]'";
								//die();
								foreach ($selrolewodtl as $rolewodtl) {
									$idroledtl = $con->idurut("laundry_role_dtl_wo","role_dtl_wo_id");
									$datadtl = array( 
												 'role_dtl_wo_id' => $idroledtl,
												 'role_wo_id' => $idrolewot,
												 'master_process_id' => $rolewodtl['master_process_id'],
												 'role_dtl_wo_dateupdate' => $date,
												 'role_dtl_wo_seq' => $rolewodtl['role_dtl_grup_seq'],
												 'role_dtl_wo_time' => $rolewodtl['role_dtl_grup_time'],
											); 
									$execdtl= $con->insert("laundry_role_dtl_wo", $datadtl);
								}
					
					} 
				}
			}
			$dataproc = array( 
					 'role_grup_master_id' => $_POST['role_grup_m_id'],
					 'role_wo_master_id' => $idrolewom,
					); 		
			$execproc= $con->update("laundry_wo_master_dtl_proc", $dataproc, "wo_master_dtl_proc_id = '$idproc'");
			
			$where = array('wo_no' => $sker['wo_no']);
			$con->delete("laundry_wo_master_keranjang",$where);
		}
		
		$datawocmt = array( 
			 	'role_grup_master_status' => 2,
		);
		$execwocmt = $con->update("laundry_role_grup_master",$datawocmt,"role_grup_master_id = '$_POST[rolegrupmas2]'");
		
		echo "<script>window.location='content.php?p=$_POST[getp]'</script>";
	}
}
?>
