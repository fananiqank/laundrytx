<?php 

session_start();
include "../../../funlibs.php";
$con = new Database;
//error_reporting(0);
$date = date("Y-m-d H:i:s");
$date2 = date("Y-m-d");
$tabel = "laundry_role_grup";

//input role proses ================================================================================
if ($_POST['codeproc'] == 'input'){
//echo "1";
	$exptype = explode('_',$_POST['type']);

	//input new sequence pada edit sequence
	if($_POST['getidm'] != ''){
			$selcount = $con->select("laundry_role_wo","COUNT(role_wo_id) as jmlrole","role_wo_master_id = '$_POST[getd]' and role_wo_status != 2");
			
			foreach($selcount as $count) {}
			$selnama = $con->select("laundry_role_wo","COUNT(role_wo_id) as jmlrole","master_type_process_id = '$exptype[0]' and role_wo_master_id = '$_POST[getd]' and role_wo_status != 2");
			foreach($selnama as $nam) {}

			$urutan = $count['jmlrole'] + 1;
			$namaurut = $nam['jmlrole'] + 1;
			$type = $exptype[1]." ".$namaurut;
			$idroker = $con->idurut("laundry_role_wo","role_wo_id");
			if ($exptype[0] <= 3 || $exptype[0] == 6){
				$statrole = 1;
			}  else if ($exptype[0] > 3){
				$statrole = 0;
			} 

			$data = array (
			'role_wo_id' => $idroker,
			'role_wo_name' => $type,
			'role_wo_modifydate' => $date,
			'role_wo_createdby' => $_SESSION['ID_LOGIN'],
			'master_type_process_id' => $exptype[0],
			'role_wo_seq' => $urutan,
			'role_wo_master_id' => $_POST['getd'],
			'role_wo_status' => $statrole,
			'role_wo_rev' => 0,
			'role_wo_name_seq' => $namaurut,
			);
			$exec = $con->insert("laundry_role_wo",$data);
			//var_dump($exec);
			//die();
			foreach ($_POST['process'] as $key => $value){
				$expval = explode("_",$value);
				$mpval = $expval[0];
				$urval = $expval[1];
							$idrodtl = $con->idurut("laundry_role_dtl_wo","role_dtl_wo_id");
							$data2 = array( 
										'role_dtl_wo_id' => $idrodtl,
										 'role_wo_id' => $idroker,
										 'master_process_id' => $mpval,
										 'role_dtl_wo_modifydate' => $date,
										 'role_dtl_wo_seq' => $urval,
										 'role_dtl_wo_rev' => 0,
							); 
							$exec2= $con->insert("laundry_role_dtl_wo", $data2);
			}
			//jika create type new bukan rework
			if($_POST['createtype'] == 1){
				//jika role type adalah Dry
				//jika role type adalah Dry maka akan di tambahkan scan garment pada detail di akhir sequence
				/*if ($exptype[0] == 4){
					$countseq = $con->selectcount("laundry_role_dtl_wo","role_dtl_wo_id","role_wo_id = '$idroker'");
					$seqscan = $countseq+1;
					$idrodtl = $con->idurut("laundry_role_dtl_wo","role_dtl_wo_id");
					$data2 = array( 
								'role_dtl_wo_id' => $idrodtl,
								'role_wo_id' => $idroker,
								'master_process_id' => 42,
								'role_dtl_wo_modifydate' => $date,
								'role_dtl_wo_seq' => $seqscan,
								'role_dtl_wo_time' => 0,
							); 
					$exec2= $con->insert("laundry_role_dtl_wo", $data2);
				}*/
			}
			echo "<script>window.location='content.php?$_POST[getp]&d=$_POST[getd]&idm=$_POST[getidm]&l=v'</script>";
	
	//input new sequence pada pembuatan new sequence
	} else {
			//jika role master tidak ada
			if ($_POST['rolemasterid'] == ''){
				if($_POST['type_receive'] == ''){
					$typerec = $_POST['typerec'];
				} else {
					$typerec = $_POST['type_receive'];
				}
				//input role master
				$idrolemaster = $con->idurut("laundry_role_grup_master","role_grup_master_id");
				$datarolemaster = array(
							 'role_grup_master_id' => $idrolemaster,
							 'role_grup_master_name' => "Laundry".$date2.$idrolemaster, 
							 'role_grup_master_status' => 1, 
							 'role_grup_master_createdate' => $date,
							 'role_grup_master_createdby' => $_SESSION['ID_LOGIN'],
							 'type_receive' => $typerec,
							 'type_repeat_order' => 0,
							 'role_grup_master_remark' => $exptype[2],
				); 
				$execrolemaster= $con->insert("laundry_role_grup_master", $datarolemaster);
			
			//jika role master sudah ada
			} else {
				$idrolemaster = $_POST['rolemasterid'];
			}

			//include "phpmailer/pushemailadmin.php";
			$selcount = $con->select("laundry_role_grup","COUNT(role_grup_id) as jmlrole","role_grup_master_id = '$idrolemaster'");
			
			foreach($selcount as $count) {}
			$selnama = $con->select("laundry_role_grup","COUNT(role_grup_id) as jmlrole","master_type_process_id = '$exptype[0]' and role_grup_master_id = '$idrolemaster'");
			foreach($selnama as $nam) {}

			$urutan = $count['jmlrole'] + 1;
			$namaurut = $nam['jmlrole'] + 1;
			$type = $exptype[1]." ".$namaurut;
			
			//cek master type process
			$cekmastertypeprocess = $con->selectcount("laundry_master_type_process","master_type_process_id","master_type_process_id = $exptype[0]");
			//end cek master type process
			if($cekmastertypeprocess > 0) { //normal add
					// input role 
					$idroker = $con->idurut($tabel,"role_grup_id");
					$data = array (
					'role_grup_id' => $idroker,
					'role_grup_name' => $type,
					'role_grup_modifydate' => $date,
					'role_grup_createdby' => $_SESSION['ID_LOGIN'],
					'master_type_process_id' => $exptype[0],
					'role_grup_seq' => $urutan,
					'role_grup_master_id' => $idrolemaster,
					'role_grup_name_seq' => $namaurut,
					);
					$exec = $con->insert($tabel,$data);
					
					//input role detail
					foreach ($_POST['process'] as $key => $value){
						$expval = explode("_",$value);
						$mpval = $expval[0];
						$urval = $expval[1];
									$idrodtl = $con->idurut("laundry_role_dtl_grup","role_dtl_grup_id");
									$data2 = array( 
												'role_dtl_grup_id' => $idrodtl,
												 'role_grup_id' => $idroker,
												 'master_process_id' => $mpval,
												 'role_dtl_grup_modifydate' => $date,
												 'role_dtl_grup_seq' => $urval,
											); 
									$exec2= $con->insert("laundry_role_dtl_grup", $data2);
					}
			} else { //khusus ppspray rework/ozon rework
				$selinputprocess = $con->select("laundry_master_role_rework","master_type_process_id,role_wo_seq,master_type_process_name","master_role_rework_code = '$exptype[0]' GROUP BY master_type_process_id,role_wo_seq,master_type_process_name","role_wo_seq");
				foreach($selinputprocess as $headsip){

				$idroker = $con->idurut($tabel,"role_grup_id");
				$data = array (
					'role_grup_id' => $idroker,
					'role_grup_name' =>$headsip['master_type_process_name'],
					'role_grup_modifydate' => $date,
					'role_grup_createdby' => $_SESSION['ID_LOGIN'],
					'master_type_process_id' => $headsip['master_type_process_id'],
					'role_grup_seq' => $headsip['role_wo_seq'],
					'role_grup_master_id' => $idrolemaster,
					'role_grup_name_seq' => $namaurut,
					);
				$exec = $con->insert($tabel,$data);

					if($headsip['master_type_process_id'] == 4 || $headsip['master_type_process_id'] == 5){
						$selinputprocess2 = $con->select("laundry_master_role_rework","master_process_id,role_dtl_wo_seq","master_role_rework_code = '$exptype[0]' and master_type_process_id = '$headsip[master_type_process_id]'","role_wo_seq,role_dtl_wo_seq");
						foreach($selinputprocess2 as $sip){
							$idrodtl = $con->idurut("laundry_role_dtl_grup","role_dtl_grup_id");
							$data2 = array( 
									'role_dtl_grup_id' => $idrodtl,
									'role_grup_id' => $idroker,
									'master_process_id' => $sip['master_process_id'],
									'role_dtl_grup_modifydate' => $date,
									'role_dtl_grup_seq' => $sip['role_dtl_wo_seq'],
								); 
							$exec2= $con->insert("laundry_role_dtl_grup", $data2);
						}
					}
				}
			}
		//jika create type new bukan rework
		if($_POST['createtype'] == 1){
			//jika role type adalah Dry maka akan di tambahkan scan garment pada detail di akhir sequence
			/*if ($exptype[0] == 4 && $_POST['typerec'] == 2){
				$countseq = $con->selectcount("laundry_role_dtl_grup","role_dtl_grup_id","role_grup_id = '$idroker'");
				$seqscan = $countseq+1;
				$idrodtl = $con->idurut("laundry_role_dtl_grup","role_dtl_grup_id");
				$data2 = array( 
							'role_dtl_grup_id' => $idrodtl,
							'role_grup_id' => $idroker,
							'master_process_id' => 42,
							'role_dtl_grup_modifydate' => $date,
							'role_dtl_grup_seq' => $seqscan,
							'role_dtl_grup_time' => 0,
						); 
				$exec2= $con->insert("laundry_role_dtl_grup", $data2);
				//var_dump($exec2);
			}*/
		}

		echo "<script>window.location='content.php?$_POST[getp]'</script>";
	}
}
// end input role process ============================================================================== 

//update role process ==================================================================================
else if ($_POST['codeproc'] == 'edit'){
// echo "2";
	//input new role detail pada edit sequence
	if($_POST['getidm'] != ''){
			foreach ($_POST['process2'] as $key => $value){
				$expval = explode("_",$value);
				$mpval = $expval[0];
				$urval = $expval[1];
				$id = $expval[2];
							$idrodtl = $con->idurut("laundry_role_dtl_wo","role_dtl_wo_id");
							$data2 = array( 
										 'role_dtl_wo_id' => $idrodtl,
										 'role_wo_id' => $id,
										 'master_process_id' => $mpval,
										 'role_dtl_wo_modifydate' => $date2,
										 'role_dtl_wo_seq' => $urval,
									); 
							$exec2= $con->insert("laundry_role_dtl_wo", $data2);
			}
			
			//role detil scan garment harus tetap berada di sequence terakhir.
			/*$seqdtlscan = $con->selectcount("laundry_role_dtl_wo","role_dtl_wo_id","role_wo_id = '$id'");
			$datascan = array( 
						 'role_dtl_wo_seq' => $seqdtlscan,
					); 
			$execscan= $con->update("laundry_role_dtl_wo", $datascan,"role_wo_id = '$id' and master_process_id = '42'");
			*/

			$datamodify = array(  
			 'role_wo_master_modifyby' => $_SESSION['ID_LOGIN'],
			 'role_wo_master_modifydate' => $date,
			); 
			$execmodify = $con->update("laundry_role_wo_master",$datamodify,"role_wo_master_id = '$_POST[role_wo_m_id]'");
			
		
	
	//edit seqence sebelum di confirm	
	} else { 
			foreach ($_POST['process2'] as $key => $value){
				$expval = explode("_",$value);
				$mpval = $expval[0];
				$urval = $expval[1];
				$id = $expval[2];
							$idrodtl = $con->idurut("laundry_role_dtl_grup","role_dtl_grup_id");
							$data2 = array( 
										 'role_dtl_grup_id' => $idrodtl,
										 'role_grup_id' => $id,
										 'master_process_id' => $mpval,
										 'role_dtl_grup_modifydate' => $date2,
										 'role_dtl_grup_seq' => $urval,
									); 
							//var_dump($data2);
							$exec2= $con->insert("laundry_role_dtl_grup", $data2);
			}

			//role detil scan garment harus tetap berada di sequence terakhir.
			/*$seqdtlscan = $con->selectcount("laundry_role_dtl_grup","role_dtl_grup_id","role_grup_id = '$id'");
			$datascan = array( 
						 'role_dtl_grup_seq' => $seqdtlscan,
					); 
			$execscan= $con->update("laundry_role_dtl_grup", $datascan,"role_grup_id = '$id' and master_process_id = '42'");
			*/
			
			$datamodify = array(  
			 'role_grup_master_modifyby' => $_SESSION['ID_LOGIN'],
			 'role_grup_master_modifydate' => $date,
			); 
			$execmodify = $con->update("laundry_role_grup_master",$datamodify,"role_grup_master_id = '$_POST[role_grup_m_id]'");
			
		echo "<script>window.location='content.php?$_POST[getp]'</script>";
			
	}
} 
// end update role process ==========================================================================

// ubah sequence proses =============================================================================
else if ($_POST['idnya'] != ''){
// echo "3";
	$naik = $_POST['naik']-'1';
	
		if ($_POST['naik'] != '' && $_POST['turun'] == ''){
			$selseq = $con->select("laundry_role_grup","*","role_grup_id = '$_POST[idnya]'");
			foreach ($selseq as $seq) {}
			$dataseq = array(
				'role_grup_seq' => $naik,
			);
			$execseq = $con->update("laundry_role_grup",$dataseq,"role_grup_id = '$_POST[idnya]'");
			$selsequ = $con->select("laundry_role_grup","*","role_grup_seq = '$naik' and role_grup_id != '$_POST[idnya]' and role_grup_master_id = '$seq[role_grup_master_id]'");
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
			$selsequ = $con->select("laundry_role_grup","*","role_grup_seq = '$turun' and role_grup_id != '$_POST[idnya]' and role_grup_master_id = '$seq[role_grup_master_id]'");
			foreach ($selsequ as $sequ) {}
				$seqdb = $sequ['role_grup_seq']-'1';
				$datasequ = array(
				'role_grup_seq' => $seqdb,
				);
				$execsequ = $con->update("laundry_role_grup",$datasequ,"role_grup_id = '$sequ[role_grup_id]'");
		}
	
} 
// end ubah sequence ==================================================================================

// ubah sequence proses edit cmt proses ===============================================================
else if ($_POST['idnyaedit'] != ''){

	$naik = $_POST['naik']-'1';
	$turun = $_POST['turun']+'1';

		if ($_GET['ed'] == 1){
			$selseq = $con->select("laundry_role_wo","*","role_wo_id = '$_POST[idnyaedit]'");
			foreach ($selseq as $seq) {}
			$dataseq = array(
				'role_wo_seq' => $naik,
				'role_wo_modifydate' => $date
			);
			$execseq = $con->update("laundry_role_wo",$dataseq,"role_wo_master_id = '$seq[role_wo_master_id]' and role_wo_id = '$_POST[idnyaedit]'");
			$selsequ = $con->select("laundry_role_wo","*","role_wo_seq = '$naik' and role_wo_id != '$_POST[idnyaedit]' and role_wo_master_id = '$seq[role_wo_master_id]' and role_wo_status != 2");
			foreach ($selsequ as $sequ) {}
				$seqdb = $sequ['role_wo_seq']+'1';
				$datasequ = array(
				'role_wo_seq' => $seqdb,
				);
				$execsequ = $con->update("laundry_role_wo",$datasequ,"role_wo_master_id = '$seq[role_wo_master_id]' and role_wo_id = '$sequ[role_wo_id]'");

		}
		else if ($_GET['ed'] == 2){
			$selseq = $con->select("laundry_role_wo","*","role_wo_id = '$_POST[idnyaedit]'");
			foreach ($selseq as $seq) {}
			$dataseq = array(
				'role_wo_seq' => $turun,
				'role_wo_modifydate' => $date
			);

			$execseq = $con->update("laundry_role_wo",$dataseq,"role_wo_master_id = '$seq[role_wo_master_id]' and role_wo_id = '$_POST[idnyaedit]'");
			$selsequ = $con->select("laundry_role_wo","*","role_wo_seq = '$turun' and role_wo_id != '$_POST[idnyaedit]' and role_wo_master_id = '$seq[role_wo_master_id]' and role_wo_status != 2");
			// echo "select * from laundry_role_wo where role_wo_seq = '$turun' and role_wo_id != '$_POST[idnyaedit]' and role_wo_master_id = '$seq[role_wo_master_id]'";
			foreach ($selsequ as $sequ) {}
				$seqdb = $sequ['role_wo_seq']-'1';
				$datasequ = array(
				'role_wo_seq' => $seqdb,
				);
				
				$execsequ = $con->update("laundry_role_wo",$datasequ,"role_wo_master_id = '$seq[role_wo_master_id]' and role_wo_id = '$sequ[role_wo_id]'");
				
		}
		$datamodify = array(  
						 'role_wo_master_modifyby' => $_SESSION['ID_LOGIN'],
						 'role_wo_master_modifydate' => $date,
						); 
		$execmodify = $con->update("laundry_role_wo_master",$datamodify,"role_wo_master_id = '$_POST[role_wo_m_id]'");

} 
// end ubah sequence edit cmt process ================================================================

// input cmt no to on plan ===========================================================================
else if ($_POST['cmtwo'] == '1'){ 
// echo "5";
	if ($_POST['cmtno'] != ''){
		foreach ($_POST['cmtno'] as $key => $value){
		
			$expval = explode('|',$value);

			$wono = $expval[0];
			$color = trim($expval[1]);
			//$qty = $expval[2];
			//$bpo = $expval[4];
			$bid = $expval[2];
			$exdate = $expval[3];
			$newrework = $expval[4];
			$seqcode = $expval[5];
			$code = $expval[6];
			$buyer_style_no = $expval[7];
			$color_wash = $expval[8];

			$woidkeranjang = $con->idurut("laundry_wo_master_keranjang","wo_master_keranjang_id");
			if ($_GET['typesource'] == 3){
				if ($_GET['typesource'] == $code){
					$data2 = array( 
					 'wo_master_keranjang_id' => $woidkeranjang,
					 'wo_no' => $wono,
					 'wo_master_keranjang_createdate' => $date,
					 'wo_master_keranjang_createdby' => $_SESSION['ID_LOGIN'],
					 'garment_colors' => $color,
					 'ex_fty_date' => $exdate,
					 'buyer_id' => $bid,
					 'status_seq' => $newrework,
					 'seq_ex_fty_date' => $seqcode,
					 'type_source' => $code,
					 'buyer_style_no' => $buyer_style_no,
					 'color_wash' => $color_wash,
					); 
					//var_dump($data2);
					$exec2= $con->insert("laundry_wo_master_keranjang", $data2);
					if ($exec2 > 0) {
						echo "1_";
					} else {
						echo "4_";
					}
				} else {
					echo "2_";
				}
			} else {
				$data2 = array( 
						 'wo_master_keranjang_id' => $woidkeranjang,
						 'wo_no' => $wono,
						 'wo_master_keranjang_createdate' => $date,
						 'wo_master_keranjang_createdby' => $_SESSION['ID_LOGIN'],
						 'garment_colors' => $color,
						 'ex_fty_date' => $exdate,
						 'buyer_id' => $bid,
						 'status_seq' => $newrework,
						 'seq_ex_fty_date' => $seqcode,
						 'type_source' => $code,
						 'buyer_style_no' => $buyer_style_no,
						 'color_wash' => $color_wash,
				); 
				// var_dump($data2);
				$exec2= $con->insert("laundry_wo_master_keranjang", $data2);
				if ($exec2 > 0) {
					echo "1_";
				} else {
					echo "4_";
				}
			}
		}
	} else {
			echo "3_";
	}
} 
// end input cmt to plan =========================================================================

//hapus cmt no from on plan ======================================================================
else if ($_POST['cmtwo'] == '2'){ 
// echo "6";
	foreach ($_POST['wono'] as $key => $value){

			$where = array('wo_master_keranjang_id' => $value);
			$con->delete("laundry_wo_master_keranjang",$where);
			$selwoid = $con->select("laundry_wo_master","wo_master_id","wo_no = '$value'");
			foreach ($selwoid as $wd) {}
			$whereproc = array('wo_master_id' => $wd['wo_master_id']);
			$con->delete("laundry_wo_master_dtl_proc",$whereproc);
			
	}

}

// edit Time Process detail ======================================================================
else if ($_POST['cmtwo'] == '3' && $_POST['cmtwo2'] == '4'){ 
// echo "7";	
	foreach ($_POST['seq'] as $key => $value){
			$datatime = array( 
					 'role_dtl_grup_time' => $value, 
					 'role_dtl_grup_modifydate' => $date,
			); 
			$pos = $_POST['seq_id'][$key];
			$exectime = $con->update("laundry_role_dtl_grup",$datatime,"role_dtl_grup_id = '$pos'");

	}
	foreach ($_POST['mainseq'] as $key => $value){
			$datatime2 = array( 
					 'role_grup_time' => $value, 
					 'role_grup_modifydate' => $date,
			); 
			$pos2 = $_POST['mainseq_id'][$key];
			$exectime2= $con->update("laundry_role_grup", $datatime2,"role_grup_id = '$pos2'");
	}

	$datamodify = array(  
			 'role_grup_master_modifyby' => $_SESSION['ID_LOGIN'],
			 'role_grup_master_modifydate' => $date,
	); 
	$execmodify = $con->update("laundry_role_grup_master",$datamodify,"role_grup_master_id = '$_POST[role_grup_m_id]'");
} 

// edit Time Process main ==========================================================================
else if ($_POST['cmtwo2'] == '5'){ 
// echo "8";
	foreach ($_POST['mainseq'] as $key => $value){
			$datatime2 = array( 
					 'role_grup_time' => $value, 
					 'role_grup_modifydate' => $date,
			); 
			$pos2 = $_POST['mainseq_id'][$key];
			$exectime2= $con->update("laundry_role_grup", $datatime2,"role_grup_id = '$pos2'");
	}
	
	$datamodify = array(  
			 'role_grup_master_modifyby' => $_SESSION['ID_LOGIN'],
			 'role_grup_master_modifydate' => $date,
	); 
	$execmodify = $con->update("laundry_role_grup_master",$datamodify,"role_grup_master_id = '$_POST[role_grup_m_id]'");
} 

// edit Time Process detail after save dan di edit cmt process ========================================
else if ($_POST['cmtwo'] == '6' && $_POST['cmtwo2'] == '7'){ 
// echo "9";
	foreach ($_POST['seq'] as $key => $value){
			$datatime = array( 
					 'role_dtl_wo_time' => $value, 
					 'role_dtl_wo_modifydate' => $date,
			); 
			$pos = $_POST['seq_id'][$key];
			$exectime = $con->update("laundry_role_dtl_wo",$datatime,"role_dtl_wo_id = '$pos'");
	}
	foreach ($_POST['mainseq'] as $key => $value){
			$datatime2 = array( 
					 'role_wo_time' => $value, 
					 'role_wo_modifydate' => $date,
			); 
			$pos2 = $_POST['mainseq_id'][$key];
			$exectime2= $con->update("laundry_role_wo", $datatime2,"role_wo_id = '$pos2'");
	}
	
	$datamodify = array(  
			 'role_wo_master_modifyby' => $_SESSION['ID_LOGIN'],
			 'role_wo_master_modifydate' => $date,
			); 
	$execmodify = $con->update("laundry_role_wo_master",$datamodify,"role_wo_master_id = '$_POST[role_wo_m_id]'");
} 

// edit Time Process main after save dan di edit cmt process ========================================================
else if ($_POST['cmtwo2'] == '8'){ 
// echo "10";
	foreach ($_POST['mainseq'] as $key => $value){
			$datatime2 = array( 
					 'role_wo_time' => $value, 
					 'role_wo_modifydate' => $date,
			); 
			$pos2 = $_POST['mainseq_id'][$key];
			$exectime2= $con->update("laundry_role_wo", $datatime2,"role_wo_id = '$pos2'");
	}
	$datamodify = array(  
			 'role_wo_master_modifyby' => $_SESSION['ID_LOGIN'],
			 'role_wo_master_modifydate' => $date,
			); 
	$execmodify = $con->update("laundry_role_wo_master",$datamodify,"role_wo_master_id = '$_POST[role_wo_m_id]'");
} 

// hapus Role process langsung dari tombol yang belum disimpan / dlm keranjang============
else if ($_POST['cmtwo2'] == '10'){ 
// echo "11";
			$where = array('role_grup_id' => $_GET[d]);
			$con->delete("laundry_role_dtl_grup",$where);
			$con->delete("laundry_role_grup",$where);

			$selsequ = $con->select("laundry_role_grup","*","role_grup_seq > '$_GET[e]'");
			//echo "select * from laundry_role_grup where role_grup_seq > '$_GET[e]' and role_grup_createdby = '$_GET[f]'";
			foreach ($selsequ as $sequ) {
				$seqdb = $sequ['role_grup_seq']-'1';
				$datasequ = array(
					'role_grup_seq' => $seqdb,
				);
				var_dump($datasequ);
				$execsequ = $con->update("laundry_role_grup",$datasequ,"role_grup_id = '$sequ[role_grup_id]'");
				var_dump($execsequ);
			}

		//cek apakah role grup masih ada untuk role master ini
		$cekrolegrup = $con->selectcount("laundry_role_grup","role_grup_id","role_grup_master_id = '$_POST[role_grup_m_id]'");

		//jika role grup masih ada maka ubah pada siapa user yang sudah mengedit
		if($cekrolegrup > 0){
			$datamodify = array(  
				 'role_grup_master_modifyby' => $_SESSION['ID_LOGIN'],
				 'role_grup_master_modifydate' => $date,
			); 
			$execmodify = $con->update("laundry_role_grup_master",$datamodify,"role_grup_master_id = '$_POST[role_grup_m_id]'");
		} 
		//jika tidak ada role grup maka akan dihapus role grup master nya
		else {
			$where = array('role_grup_master_id' => $_POST['role_grup_m_id']);
			$con->delete("laundry_role_grup_master",$where);
		} 
} 

// hapus Role process after save dan di edit cmt process =============================================================
else if ($_POST['cmtwo2'] == '11'){ 
// echo "12";
			// $where = array('role_wo_id' => $_GET[d]);
			// $con->delete("laundry_role_dtl_wo",$where);
			// $con->delete("laundry_role_wo",$where);

	//delete/ubah status jadi nonaktif => ROLE WO
		$datarw = array(  
					 'role_wo_status' => 2,
					 'role_wo_seq' => 0,
					); 
		$execrw = $con->update("laundry_role_wo",$datarw,"role_wo_id = '$_GET[d]'");

		// $whererw = array('role_wo_id' => $_GET[d]);
		// $con->delete("laundry_role_wo",$whererw);
	// end //delete/ubah status jadi nonaktif

	//delete/ubah status jadi nonaktif => DTL ROLE WO
		$datarwd = array(  
					 'role_dtl_wo_status' => 0,
					 'role_dtl_wo_seq' => 0
					); 
		$execrwd = $con->update("laundry_role_dtl_wo",$datarwd,"role_wo_id = '$_GET[d]'");

		// $whererwd = array('role_wo_id' => $_GET[d]);
		// $con->delete("laundry_role_dtl_wo",$whererwd);
	// end //delete/ubah status jadi nonaktif 

		$selsequ = $con->select("laundry_role_wo","*","role_wo_master_id = '$_POST[role_wo_m_id]' and role_wo_seq > '$_GET[e]' and role_wo_status != 2");
		foreach ($selsequ as $sequ) {
				$seqdb = $sequ['role_wo_seq']-'1';
				$datasequ = array(
				'role_wo_seq' => $seqdb,
				);
				$execsequ = $con->update("laundry_role_wo",$datasequ,"role_wo_master_id = '$_POST[role_wo_m_id]' and role_wo_id = '$sequ[role_wo_id]'");
			}
		$datamodify = array(  
					 'role_wo_master_modifyby' => $_SESSION['ID_LOGIN'],
					 'role_wo_master_modifydate' => $date,
					); 
		$execmodify = $con->update("laundry_role_wo_master",$datamodify,"role_wo_master_id = '$_POST[role_wo_m_id]'");
		//pengecekan apakah ada perubahan role process atau tidak
		
} 

// hapus edit proses ===============================================================================
else if ($_POST['codeproc'] == 'hapusdtl'){ 
	//hapus detail process saat belum di save /  masih di keranjang
	if ($_GET['jns'] == ''){
			$where = array('role_dtl_grup_id' => $_POST['hpsdtl']);
			$con->delete("laundry_role_dtl_grup",$where);

			$selsequ = $con->select("laundry_role_dtl_grup","*","role_dtl_grup_seq > '$_GET[seq]' and role_grup_id = '$_GET[id]'");
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

			$datamodify = array(  
			 'role_grup_master_modifyby' => $_SESSION['ID_LOGIN'],
			 'role_grup_master_modifydate' => $date,
			); 
			$execmodify = $con->update("laundry_role_grup_master",$datamodify,"role_grup_master_id = '$_POST[role_grup_m_id]'");

	} 
	//hapus detail process saat after save dan di edit cmt process
	else {
		
			// $where = array('role_dtl_wo_id' => $_POST['hpsdtl']);
			// $con->delete("laundry_role_dtl_wo",$where);
			$datadelete = array('role_dtl_wo_status' => 0 );
			$execdelete = $con->update("laundry_role_dtl_wo",$datadelete,"role_dtl_wo_id = '$_POST[hpsdtl]'");

			$selsequ = $con->select("laundry_role_dtl_wo","*","role_dtl_wo_seq > '$_GET[seq]' and role_wo_id = '$_GET[id]' and role_dtl_wo_status = 1");
			foreach ($selsequ as $sequ) {

				$seqdb = $sequ['role_dtl_wo_seq']-'1';
				$datasequ = array(
				'role_dtl_wo_seq' => $seqdb,
				);
				$execsequ = $con->update("laundry_role_dtl_wo",$datasequ,"role_dtl_wo_id = '$sequ[role_dtl_wo_id]'");
			}

			$selsum = $con->select("laundry_role_dtl_wo","SUM(role_dtl_wo_time) as time","role_wo_id = '$_GET[id]' and role_dtl_wo_status = 1");
			foreach ($selsum as $sum) {}

				$datasum = array('role_wo_time' => $sum['time']);
				$execsum = $con->update("laundry_role_wo",$datasum,"role_wo_master_id = '$_POST[role_wo_m_id]' and role_wo_id = $_GET[id]");

			$datamodify = array(  
						 'role_wo_master_modifyby' => $_SESSION['ID_LOGIN'],
						 'role_wo_master_modifydate' => $date,
						); 
			$execmodify = $con->update("laundry_role_wo_master",$datamodify,"role_wo_master_id = '$_POST[role_wo_m_id]'");
		
	}
}

//Use Template ===================================================================================
else if ($_POST['temple'] == '9'){ 
// echo "14";
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
					 'role_grup_master_createdby' => $_SESSION['ID_LOGIN'],
					 'type_receive' => $rolesatu2['type_receive'],
					 'type_repeat_order' => $_POST['repeat_order']
				); 
				//var_dump($datagrupmaster);
				//die()
				$execgrupmaster= $con->insert("laundry_role_grup_master", $datagrupmaster);

			$selroledua2 = $con->select("laundry_role_wo","*","role_wo_master_id = '$_POST[temp_name]' and role_wo_status != 2");
			foreach ($selroledua2 as $roledua2) {
						if( $_POST['repeat_order'] == 1){
							$repeat_order2 = $roledua2['role_wo_time'];
						} else {
							$repeat_order2 = 0;
						}
						$idgrup = $con->idurut($tabel,"role_grup_id");
						$datagrup = array (
						'role_grup_id' => $idgrup,
						'role_grup_name' => $roledua2['role_wo_name'],
						'role_grup_modifydate' => $date,
						'role_grup_createdby' => $_SESSION['ID_LOGIN'],
						'master_type_process_id' => $roledua2['master_type_process_id'],
						'role_grup_seq' => $roledua2['role_wo_seq'],
						'role_grup_master_id' => $idgrupmaster,
						'role_grup_name_seq' => $roledua2['role_wo_name_seq'],
						'role_grup_time' => $repeat_order2
						);
						$execgrup = $con->insert($tabel,$datagrup);

				$selroletiga2 = $con->select("laundry_role_dtl_wo","*","role_wo_id = '$roledua2[role_wo_id]' and role_dtl_wo_status = 1");
				foreach ($selroletiga2 as $roletiga2) {
								if( $_POST['repeat_order'] == 1){
									$repeat_order3 = $roletiga2['role_dtl_wo_time'];
								} else {
									$repeat_order3 = 0;
								}

								$idgrupdtl = $con->idurut("laundry_role_dtl_grup","role_dtl_grup_id");
								$datagrupdtl = array( 
											'role_dtl_grup_id' => $idgrupdtl,
											 'role_grup_id' => $idgrup,
											 'master_process_id' => $roletiga2['master_process_id'],
											 'role_dtl_grup_modifydate' => $date,
											 'role_dtl_grup_seq' => $roletiga2['role_dtl_wo_seq'],
											 'role_dtl_grup_time' => $repeat_order3
										); 
								$execgrupdtl= $con->insert("laundry_role_dtl_grup", $datagrupdtl);
				}
				
			}
			echo "<script>window.location='content.php?$_POST[pager]'</script>";
		
}
else if ($_POST['simpanan'] == 'simpanall'){
// echo "15";
	$jmlappwo = $_POST['jmlappwo'];
	
		//master role wo
		$selkeranjang = $con->select("laundry_wo_master_keranjang","*","");
		foreach ($selkeranjang as $sker) {

		if ($sker['status_seq'] == 2){
			//cek jumlah sequence rework
			$countwoproc = $con->selectcount("laundry_wo_master_dtl_proc","wo_master_dtl_proc_id","wo_no = '".$sker['wo_no']."' and garment_colors = '".$sker['garment_colors']."' and wo_master_dtl_proc_status = '2'");
			$reworkseq = $countwoproc + 1 ;
		} else {
			$reworkseq = 0;
		}
			$idedi = "idedi_".$sker['wo_master_keranjang_id'];
			$totalidedi = "totalidedi_".$sker['wo_master_keranjang_id'];

			//jika scan qrcode maka cutting qty akan ambil dari qrcode ticketing master
			if($sker['type_source'] == 3) {
				$jumlahqtycut = $con->selectcount("qrcode_ticketing_master","qrcode_key","wo_no = '$sker[wo_no]' and color = '$sker[garment_colors]' and DATE(ex_fty_date) = DATE('$sker[ex_fty_date]')  and washtype = 'Wash'");
				$cuttingqty = $jumlahqtycut;
			}
			else {
				$cuttingqty = 0;
			}
			//end jika scan qrcode maka cutting qty akan ambil dari qrcode ticketing master

			$idproc = $con->idurut("laundry_wo_master_dtl_proc","wo_master_dtl_proc_id");
			$dataproc = array( 
					 'wo_master_dtl_proc_id' => $idproc,
					 'wo_no' => $sker['wo_no'],
					 'garment_colors' => $sker['garment_colors'],
					 'buyer_po_number' => $sker['buyer_po_number'],
					 'wo_master_dtl_proc_qty_md' => $sker['quantity'],
					 'wo_master_dtl_proc_status' => $sker['status_seq'],
					 'ex_fty_date' => $sker['ex_fty_date'],
					 'buyer_id' => $sker['buyer_id'],
					 'wo_master_dtl_proc_createdate' => $date,
					 'wo_master_dtl_proc_createdby' => $_SESSION['ID_LOGIN'],
					 'rework_seq' => $reworkseq,
					 'seq_ex_fty_date' => $sker['seq_ex_fty_date'],
					 'type_source' => $sker['type_source'],
					 'id_edi' => $_POST[$idedi],
					 'total_id_edi' => $_POST[$totalidedi],
					 'cutting_qty' => $cuttingqty,
					 'buyer_style_no' => $sker['buyer_style_no'],
					 'color_wash' => $sker['color_wash'],
					); 		
			
			$execproc= $con->insert("laundry_wo_master_dtl_proc", $dataproc);

			//create role wo master
			$idrolewom = $con->idurut("laundry_role_wo_master","role_wo_master_id");
			$datarolewom = array(
							 'role_wo_master_id' => $idrolewom,
							 'role_wo_master_name' => $sker['wo_no'].'-'.$sker['garment_colors'].'-'.$sker['seq_ex_fty_date'], 
							 'role_wo_master_status' => 1, 
							 'role_wo_master_createdate' => $date,
							 'role_wo_master_createdby' => $_SESSION['ID_LOGIN'],
							 'type_receive' => $_POST['type_receive'],
							 'role_wo_master_remark' => $_POST['role_grup_master_remark']
						); 
			$execrolemaster= $con->insert("laundry_role_wo_master", $datarolewom);

			// looping role_wo_id yang ada di input id role3, 
			// yaitu process Receive, Lot making, QA (<3)
			foreach ($_POST['role3'] as $key => $value)	
			{

				$selrolewo = $con->select("laundry_role_grup","*","role_grup_id = '".$value."'","role_grup_seq ASC");
				foreach ($selrolewo as $rolewo) {
					$idrolewot = $con->idurut("laundry_role_wo","role_wo_id");
					$datawot = array (
							'role_wo_id' => $idrolewot,
							'role_wo_name' => $rolewo['role_grup_name'],
							'role_wo_modifydate' => $date,
							'role_wo_createdby' => $_SESSION['ID_LOGIN'],
							'master_type_process_id' => $rolewo['master_type_process_id'],
							'role_wo_seq' => $rolewo['role_grup_seq'],
							'role_wo_master_id' => $idrolewom,
							'role_wo_status' => 1,
							'role_wo_time' => $rolewo['role_grup_time'],
							'role_wo_name_seq' => $rolewo['role_grup_name_seq'],
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

				if ($value == 1){
					$stat_role = 1; 
				} else {
					$stat_role = 0;
				}
					$selrolewo = $con->select("laundry_role_grup","*","role_grup_id = '".$roleid."'","role_grup_seq");
					foreach ($selrolewo as $rolewo) {

						//cek role agar tidak double;
						$selwoaja = $con->select("laundry_role_wo","COUNT(role_wo_id) as jmlwoid","role_wo_master_id = '".$idrolewom."' and role_wo_name = '".$rolewo['role_grup_name']."' and master_type_process_id =  '".$rolewo['master_type_process_id']."' and role_wo_seq = '".$rolewo['role_grup_seq']."'");		
						foreach ($selwoaja as $woaja) {}

							if ($woaja['jmlwoid'] == 0) {
								$idrolewot = $con->idurut("laundry_role_wo","role_wo_id");
								$datawot = array (
									'role_wo_id' => $idrolewot,
									'role_wo_name' => $rolewo['role_grup_name'],
									'role_wo_modifydate' => $date,
									'role_wo_createdby' => $_SESSION['ID_LOGIN'],
									'master_type_process_id' => $rolewo['master_type_process_id'],
									'role_wo_seq' => $rolewo['role_grup_seq'],
									'role_wo_time' => $rolewo['role_grup_time'],
									'role_wo_master_id' => $idrolewom,
									'role_wo_status' => $stat_role,
									'role_wo_name_seq' => $rolewo['role_grup_name_seq'],
								);
								//var_dump($datawot);
								$execwot = $con->insert("laundry_role_wo",$datawot);
								//var_dump($execwot);
								//role dtl
									$selrolewodtl = $con->select("laundry_role_dtl_grup","*","role_grup_id = '".$rolewo['role_grup_id']."'");
									foreach ($selrolewodtl as $rolewodtl) {

										$idroledtl = $con->idurut("laundry_role_dtl_wo","role_dtl_wo_id");
										$datadtl = array( 
													 'role_dtl_wo_id' => $idroledtl,
													 'role_wo_id' => $idrolewot,
													 'master_process_id' => $rolewodtl['master_process_id'],
													 'role_dtl_wo_modifydate' => $date,
													 'role_dtl_wo_seq' => $rolewodtl['role_dtl_grup_seq'],
													 'role_dtl_wo_time' => $rolewodtl['role_dtl_grup_time'],
												); 
										$execdtl= $con->insert("laundry_role_dtl_wo", $datadtl);
									}
							}
					}
			}

			foreach($con->select("laundry_role_grup_master","type_repeat_order","role_grup_master_id = '".$_POST['role_grup_m_id']."'") as $rmro){}
			$dataproc = array( 
					 'role_wo_master_id' => $idrolewom,
					 'wo_master_dtl_proc_modifydate' => $date,
					 'wo_master_dtl_proc_modifyby' => $_SESSION['ID_LOGIN'],
					 'repeat_order' => $rmro['type_repeat_order'],
					); 		
			//var_dump($dataproc);
			$execproc= $con->update("laundry_wo_master_dtl_proc", $dataproc, "wo_master_dtl_proc_id = '".$idproc."'");
			
			$where = array('wo_no' => $sker['wo_no']);
			$con->delete("laundry_wo_master_keranjang",$where);

			//$where3 = array('role_grup_master_id' => $_POST['role_grup_m_id']);
			
		}	
		$where1 = array('role_grup_master_id' => $_POST['role_grup_m_id']);
		$con->delete("laundry_role_grup_master",$where1);

		$selgrup = $con->select("laundry_role_grup","role_grup_id","role_grup_master_id = '".$_POST['role_grup_m_id']."'");
			foreach ($selgrup as $grup) {

			 	$where2 = array('role_grup_id' => $grup['role_grup_id']);
				$con->delete("laundry_role_dtl_grup",$where2);
			} 

			$where3 = array('role_grup_master_id' => $_POST['role_grup_m_id']);
			$con->delete("laundry_role_grup",$where3);

		$datamodify = array(  
			 'role_grup_master_modifyby' => $_SESSION['ID_LOGIN'],
			 'role_grup_master_modifydate' => $date,
			 'role_grup_master_status' => 2,
		); 
		$execmodify = $con->update("laundry_role_grup_master",$datamodify,"role_grup_master_id = '".$_POST['rolegrupmas2']."'");

		echo "<script>window.location='content.php?$_POST[getp]'</script>";
}

//simpan saat posisi after save dan di edit (submit di menu edit Sequence)
else if ($_POST['simpanan'] == 'simpanedit'){
// echo "16";
	//jika ada event approve process
	if ($_POST['editapp'] == 'editapp'){
		foreach ($_POST['appwo2'] as $key => $value)	
		{	
			$woid = explode("_",$_POST['woid'][$key]);
			$color = $woid[0];
			$wo = $woid[1];
			$rolemaster = $woid[2];
			$roleid = $woid[3];

				if ($value == 1){
					$stat_role = 1; 
				} else {
					$stat_role = 0;
				}
					$datawot = array (
						'role_wo_status' => $stat_role,
					);
					$execwot = $con->update("laundry_role_wo",$datawot,"role_wo_master_id = '".$_POST['role_wo_m_id']."' and role_wo_id = '".$roleid."'");
					
					$datamodify = array (
						'role_wo_master_modifydate' => $date,
						'role_wo_master_modifyby' => $_SESSION['ID_LOGIN'],
					);
					$execmodify = $con->update("laundry_role_wo_master",$datamodify,"role_wo_master_id = '".$_POST['role_wo_m_id']."'");
		}
	}

//pengecekan apakah ada perubahan role process atau tidak
	
	//update laundry_role_wo
	foreach ($_POST['urutsequence'] as $key => $value) {
		$expuruts = explode("_",$_POST['urutsequence'][$key]);
		$datauruts = array( 'role_wo_seq' => $expuruts[1]);
		$execuruts = $con->update("laundry_role_wo",$datauruts,"role_wo_master_id = '".$_POST['role_wo_m_id']."' and role_wo_id = $expuruts[0]");
	}
	//======================

	//delete child status 0
	$whererwnd = array(
				'role_wo_master_id' => $_POST['role_wo_m_id'],
				'role_child_status' => 0		
				);
	$exdel = $con->delete("laundry_role_child",$whererwnd);
	
	//die();
	//=====================
	foreach($con->select("laundry_role_child_master","*","role_wo_master_id = '".$_POST['role_wo_m_id']."' and lot_status = 1") as $rwnd){
		if ($rwnd['lot_type'] == 1){
			$lottipe = 1;
			$wonameseq = "and role_wo_seq <= (select role_wo_seq from laundry_role_wo where role_wo_master_id = '".$_POST['role_wo_m_id']."' and master_type_process_id = 2 and role_wo_name_seq = 1 and role_wo_status = 1)";
			//echo $lotchild['lot_no'].$lotchild[lot_type];
		}
		else if ($rwnd['lot_type'] == 2){
			$lottipe = 2;
			$wonameseq = "and role_wo_seq between (select (role_wo_seq+1) as role_wo_seq from laundry_role_wo where role_wo_master_id = '".$_POST['role_wo_m_id']."' and master_type_process_id = 2 and role_wo_name_seq = 1 and role_wo_status = 1) and (select role_wo_seq from laundry_role_wo where role_wo_master_id = '".$_POST['role_wo_m_id']."' and master_type_process_id = 2 and role_wo_name_seq = '".$rwnd['role_wo_name_seq']."' and role_wo_status = 1)";
		}
		else if ($rwnd['lot_type'] == 3){
			$lottipe = 2;
			$wonameseq = "and role_wo_seq between (select (role_wo_seq+1) as role_wo_seq from laundry_role_wo where role_wo_master_id = '".$_POST['role_wo_m_id']."' and master_type_process_id = 2 and role_wo_name_seq = 1 and role_wo_status = 1) and (select role_wo_seq from laundry_role_wo where role_wo_master_id = '".$_POST['role_wo_m_id']."' and master_type_process_id = 2 and role_wo_name_seq = '".$rwnd['role_wo_name_seq']."' and role_wo_status = 1)";
		}
		else if ($rwnd['lot_type'] == 5){
			$lottipe = 2;
			$wonameseq = "and role_wo_seq > (select role_wo_seq from laundry_role_wo where role_wo_master_id = '".$_POST['role_wo_m_id']."' and master_type_process_id = 2 and role_wo_name_seq = '".$rwnd['role_wo_name_seq']."' and role_wo_status = 1)";
		}
		else if ($rwnd['lot_type'] == 4){
			$lottipe = 4;
			$wonameseq = "";
		}

		// echo "select a.role_wo_id,b.role_dtl_wo_id,a.role_wo_seq,b.role_dtl_wo_seq from laundry_role_wo a LEFT JOIN laundry_role_dtl_wo b ON a.role_wo_id=b.role_wo_id and role_wo_status = 1 and role_dtl_wo_status = 1 where a.role_wo_master_id = '".$_POST['role_wo_m_id']."' 
		// 	and CONCAT(a.role_wo_id,'_',role_dtl_wo_id) 
		// 	NOT IN (select CONCAT(role_wo_id,'_',role_dtl_wo_id) from laundry_role_child where lot_no = '".$rwnd['lot_no']."')
		// 	$wonameseq";
		foreach ($con->select("laundry_role_wo a LEFT JOIN laundry_role_dtl_wo b ON a.role_wo_id=b.role_wo_id and role_dtl_wo_status = 1","a.role_wo_id,b.role_dtl_wo_id,a.role_wo_seq,b.role_dtl_wo_seq","a.role_wo_master_id = '".$_POST['role_wo_m_id']."' and role_wo_status = 1
			and CONCAT(a.role_wo_id,'_',role_dtl_wo_id) 
			NOT IN (select CONCAT(role_wo_id,'_',role_dtl_wo_id) from laundry_role_child where lot_no = '".$rwnd['lot_no']."')
			$wonameseq",
			"role_wo_seq,role_dtl_wo_seq") as $isirwnd) {

			$idrwnd = $con->idurut("laundry_role_child","role_child_id");
			$datarwnd = array(
				'role_child_id' => $idrwnd,
				'role_wo_master_id'  => $_POST['role_wo_m_id'],
				'role_wo_id'  => $isirwnd['role_wo_id'],
				'role_dtl_wo_id'  => $isirwnd['role_dtl_wo_id'],
				'lot_type'  => $rwnd['lot_type'],
				'role_child_status'  => 0,
				'role_child_createdate'  => $date,
				'role_child_createdby'  => $_SESSION['ID_LOGIN'],
				'lot_no'  => $rwnd['lot_no'],
				'lot_id'  => $rwnd['lot_id'],
				'role_wo_seq' => $isirwnd['role_wo_seq'],
				'role_dtl_wo_seq' => $isirwnd['role_dtl_wo_seq'],
			);
			$execrwnd = $con->insert("laundry_role_child", $datarwnd);
		}
		
	}
//end pengecekan apakah ada perubahan role process atau tidak
	
	//jika ada perubahan (editing) akan masuk ke table revisi dan status revisi
	if ($_POST['editseqwo'] == '1'){
		foreach ($con->select("laundry_role_wo_master","role_wo_master_rev","role_wo_master_id = '".$_POST['isid']."'") as $revisike){}
		$revisinew = $revisike['role_wo_master_rev'] + 1;

		//update nomor revisi pada role master
		$datarev = array(  
			'role_wo_master_rev' => $revisinew,
		); 
		$execrev = $con->update("laundry_role_wo_master",$datarev,"role_wo_master_id = '".$_POST['isid']."'");

//====================================================================================================
		//update status 0 pada laundry_role_wo_rev
		$selrlwo = $con->select("laundry_role_wo_rev","*","role_wo_master_id = '".$_POST['isid']."' and role_wo_rev_status = 0");
		
		foreach ($selrlwo as $rlwo) {
		
			$datarlwo = array(
						'role_wo_rev_status' => 1,
			);
			$execdatarlwo = $con->update("laundry_role_wo_rev",$datarlwo,"role_wo_rev_id = '".$rlwo['role_wo_rev_id']."'");
			
			//update status 0 pada laundry_role_dtl_wo_rev
			$seldtlrlwo = $con->select("laundry_role_dtl_wo_rev","*","role_wo_id = '".$rlwo['role_wo_id']."' and role_dtl_wo_rev_status = 0");
			foreach ($seldtlrlwo as $dtlrl) {
				$datadtlwo = array(
						'role_dtl_wo_rev_status' => 1,
				);
				$execdatadtlwo = $con->update("laundry_role_dtl_wo_rev",$datadtlwo,"role_dtl_wo_rev_id = '".$dtlrl['role_dtl_wo_rev_id']."'");
			}
		}
//===================================================================================================		
	} 
	// jika tidak ada perubahan (tidak ada editing) akan masuk ke table revisi dan status tidak revisi. sebagai history
	else {
		$selrlwo = $con->select("laundry_role_wo_rev","*","role_wo_master_id = '$_POST[isid]' and role_wo_rev_status = 0");
		foreach ($selrlwo as $rlwo) {
		
			$where = array('role_wo_rev_id' => $rlwo['role_wo_rev_id']);
//			$con->delete("laundry_role_wo_rev",$where);
			
			//delete status 0 pada laundry_role_dtl_wo_rev
			$seldtlrlwo = $con->select("laundry_role_dtl_wo_rev","*","role_wo_id = '$rwo[role_wo_id]' and role_dtl_wo_rev_status = 0");
			foreach ($seldtlrlwo as $dtlrl) {

				$where = array('role_dtl_wo_rev_id' => $rlwo['role_dtl_wo_rev_id']);
//				$con->delete("laundry_role_dtl_wo_rev",$where);

			}
		}
	}
}

//edit untuk approve sequence
else if ($_POST['editapp'] == 'editapp'){
// echo "17";
	foreach ($_POST['appwo2'] as $key => $value)	
	{	
		$woid = explode("_",$_POST['woid'][$key]);
		$color = $woid[0];
		$wo = $woid[1];
		$rolemaster = $woid[2];
		$roleid = $woid[3];
		$exftydate = $woid[4];
			
			if ($value == 1){
				$stat_role = 1; 
			} else {
				$stat_role = 0;
			}
				$datawot = array (
					'role_wo_status' => $stat_role,
				);
				$execwot = $con->update("laundry_role_wo",$datawot,"role_wo_master_id = '$_POST[role_wo_m_id]' and role_wo_id = '$roleid'");

				$datamodify = array (
					'role_wo_master_modifydate' => $date,
					'role_wo_master_modifyby' => $_SESSION['ID_LOGIN'],
				);
				$execmodify = $con->update("laundry_role_wo_master",$datamodify,"role_wo_master_id = '$_POST[role_wo_m_id]'");
	}
	//echo "<script>window.location='content.php?p=$_POST[getp]_v'</script>";
}

//saat di edit akan menyimpan data role sebelumnya sebagai history
else if ($_POST['modsequenceeditid'] != ''){
// echo "18";
	//cek on laundry role wo rev ==================
	$prb = $con->selectcount("laundry_role_wo_rev","role_wo_rev_id","role_wo_master_id = '$_POST[modsequenceeditid]' and role_wo_rev_status = 0");
	// ============================================

	if ($prb == 0){
	
		//select role wo  =============================
		$selrlwo = $con->select("laundry_role_wo","*","role_wo_master_id = '$_POST[modsequenceeditid]'");
		foreach ($con->select("laundry_role_wo_master","role_wo_master_rev","role_wo_master_id = '$_POST[modsequenceeditid]'") as $rev) {}
		
		$revisi = $rev['role_wo_master_rev'];

		foreach ($selrlwo as $rwo) {

			$idrev = $con->idurut("laundry_role_wo_rev","role_wo_rev_id");
			$datarlwo = array(
					'role_wo_rev_id' => $idrev,
					'role_wo_id' => $rwo['role_wo_id'],
					'role_wo_name' => $rwo['role_wo_name'],
					'role_wo_modifydate' => $date,
					'role_wo_createdby' => $_SESSION['ID_LOGIN'],
					'master_type_process_id' => $rwo['master_type_process_id'],
					'role_wo_seq' => $rwo['role_wo_seq'],
					'role_wo_master_id' => $rwo['role_wo_master_id'],
					'role_wo_status' => $rwo['role_wo_status'],
					'role_wo_rev' => $revisi,
					'role_wo_rev_status' => 0,
					'role_wo_rev_createdby' => $_SESSION['ID_LOGIN'],
					'role_wo_rev_createdate' => $date,
			);
			$execdatarlwo = $con->insert("laundry_role_wo_rev",$datarlwo);

			//select role detail wo ===============================================================
			$seldtlrlwo = $con->select("laundry_role_dtl_wo","*","role_wo_id = '$rwo[role_wo_id]'");
			foreach ($seldtlrlwo as $dtlrl) {
				$idroledtl = $con->idurut("laundry_role_dtl_wo_rev","role_dtl_wo_rev_id");
				$datadtl =  array( 
							'role_dtl_wo_rev_id' => $idroledtl,
							'role_dtl_wo_id' => $dtlrl['role_dtl_wo_id'],
							'role_wo_id' => $dtlrl['role_wo_id'],
							'master_process_id' => $dtlrl['master_process_id'],
							'role_dtl_wo_modifydate' => $date,
							'role_dtl_wo_seq' => $dtlrl['role_dtl_wo_seq'],
							'role_dtl_wo_time' => $dtlrl['role_dtl_wo_time'],
							'role_dtl_wo_rev' => $revisi,
							'role_dtl_wo_rev_status' => 0,
				); 
				$execdtl= $con->insert("laundry_role_dtl_wo_rev", $datadtl);
			}
			// end select role detail wo =========================================================
		}
		// end select role wo =========================
	} 
}

else if ($_POST['simpanhold'] != ''){
// echo "19";
	if ($_POST['setatus'] == NULL) {
		$hold = 0;
	} else {
		$hold = $_POST['setatus'];
	}

	$datahold =  array( 
					'status_hold' => $hold,
				);
	$exehold= $con->update("laundry_wo_master_dtl_proc",$datahold,"wo_master_dtl_proc_id = '$_GET[id]'");
}
?>
