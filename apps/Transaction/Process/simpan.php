<?php 
session_start();
include "../../../funlibs.php";
$con = new Database;
//error_reporting(0);
$date = date("Y-m-d H:i:s");
$date2 = date("Y-m-d_G-i-s");
$tabel = "laundry_role_grup";


//input role proses
if ($_POST['codeproc'] == 'input'){
	//echo "sese";
	if ($_POST['rolemasterid'] == ''){
		$idrolemaster = $con->idurut("laundry_role_grup_master","role_grup_master_id");
		$datarolemaster = array(
					 'role_grup_master_id' => $idrolemaster,
					 'role_grup_master_name' => 'laundry'.$idrolemaster, 
					 'role_grup_master_status' => 1, 
					 'role_grup_master_createdate' => $date,
		); 
		$execrolemaster= $con->insert("laundry_role_grup_master", $datarolemaster);
		
	} else {
		$idrolemaster = $_POST['rolemasterid'];
	}

	$exptype = explode('_',$_POST[type]);
	//include "phpmailer/pushemailadmin.php";
	$selcount = $con->select("laundry_role_grup","COUNT(role_grup_id) as jmlrole","role_grup_user_id = '$_SESSION[ID_LOGIN]'");
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
	echo "<script>window.location='content.php?p=$_POST[getp]'</script>";
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
	//echo "sisi";
	foreach ($_POST['cmtno'] as $key => $value){
		
			$data2 = array( 
					 'wo_no' => $value,
					 'wo_master_keranjang_dateupdate' => $date,
					 'wo_master_keranjang_user_id' => $_SESSION[ID_LOGIN],
			); 
			$exec2= $con->insert("laundry_wo_master_keranjang", $data2);

			$buyer_no = $con->searchseq($_POST['no_buyer']);
			$style_no = $con->searchseq($_POST['no_style']);
			$cmt_no = $con->searchseq($_POST['no_cmt']);
			$color_no = $con->searchseq($_POST['no_color']);

			if ($_POST['no_color'] != ''){
				$incolor = "and a.garment_colors = '$color_no'";
			} else {
				$incolor = "";
			}

			if ($_POST['no_buyer'] != ''){
				$inbuyer = "and a.buyer_po_number = '$buyer_no'";
			} else {
				$inbuyer = "";
			}

			if ($_POST['no_style'] != ''){
				$instyle = "and b.buyer_style_no = '$style_no'";
			} else {
				$instyle = "";
			}

			if ($_POST['no_cmt'] != ''){
				$incmt = "and b.wo_no = '$cmt_no'";
			} else {
				$incmt = "";
			}

			if ($_POST['tanggal1'] == 'A' && $_POST['tanggal2'] == 'A'){
				$tgls = "";
			} else if ($_POST['tanggal1'] == '' && $_POST['tanggal2'] == ''){
				$tgls = "";
			} else {
				$tgls = "and DATE(b.ex_fty_date) BETWEEN '$_POST[tanggal1]' AND '$_POST[tanggal2]'";
			}

			$selwoid = $con->select("laundry_wo_master","wo_master_id","wo_no = '$value'");
			foreach ($selwoid as $wd) {}
			$seldatadtl = $con->select("laundry_wo_master_dtl a join laundry_wo_master b on a.wo_master_id=b.wo_master_id","a.garment_colors,a.buyer_po_number,sum(wo_master_dtl_qty) as qty","b.wo_master_id = '$wd[wo_master_id]' $incolor $inbuyer $instyle $incmt $tgls GROUP BY a.garment_colors,a.buyer_po_number");
			// echo "select a.garment_colors,a.buyer_po_number,a.wo_master_id,sum(wo_master_dtl_qty) as jml from laundry_wo_master_dtl a join laundry_wo_master b on a.wo_master_id=b.wo_master_id where b.wo_master_id = '$wd[wo_master_id]' $incolor $inbuyer $instyle $incmt $tgls GROUP BY a.garment_colors,a.buyer_po_number,a.wo_master_id";

				foreach ($seldatadtl as $datadtl) {
					$idproc = $con->idurut("laundry_wo_master_dtl_proc","wo_master_dtl_proc_id");
					$dataproc = array( 
						 'wo_master_dtl_proc_id' => $idproc,
						 'garment_colors' => $datadtl['garment_colors'],
						 'buyer_po_number' => $datadtl['buyer_po_number'],
						 'wo_master_dtl_proc_qty' => $datadtl['qty'],
						 'wo_master_dtl_proc_status' => 1,
						 'wo_master_id' => $wd['wo_master_id'],
					); 
					$execproc= $con->insert("laundry_wo_master_dtl_proc", $dataproc);
			}
	}
} 
//hapus cmt no from on plan
else if ($_POST['cmtwo'] == '2'){ 
	//echo "soso";
	foreach ($_POST['wono'] as $key => $value){
	
			$where = array('wo_no' => $value);
			$con->delete("laundry_wo_master_keranjang",$where);
			$selwoid = $con->select("laundry_wo_master","wo_master_id","wo_no = '$value'");
			foreach ($selwoid as $wd) {}
			$whereproc = array('wo_master_id' => $wd['wo_master_id']);
			$con->delete("laundry_wo_master_dtl_proc",$whereproc);
			
	}
}
// edit Time Process
else if ($_POST['cmtwo'] == '3' && $_POST['cmtwo2'] == '4'){ 
	//echo "dede";
	foreach ($_POST['seq'] as $key => $value){
			$datatime = array( 
					 'role_dtl_grup_time' => $value, 
					 'role_dtl_grup_dateupdate' => $date,
			); 
			$pos = $_POST[seq_id][$key];
			$exectime= $con->update("laundry_role_dtl_grup", $datatime,"role_dtl_grup_id = '$pos'");
	}
	foreach ($_POST['mainseq'] as $key => $value){
			$datatime2 = array( 
					 'role_grup_time' => $value, 
					 'role_grup_dateupdate' => $date,
			); 
			$pos2 = $_POST[mainseq_id][$key];
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
else {
	// $datarolemaster = array( 
	// 		 'role_grup_master_status' => 2, 
	// 		 'role_grup_master_createdate' => $date,
	// ); 
	// $execrolemaster= $con->update("laundry_role_grup_master", $datarolemaster,"role_grup_master_id = '$_POST[rolegrupmas2]'");
	$jmlappwo = $_POST['jmlappwo'];
	foreach ($_POST['appwo2'] as $key => $value)	
	{
		$woid = explode("_",$_POST['woid'][$key]);
		$id = $woid[0];
		$color = $woid[1];
		$wo = $woid[2];
			$dataproc = array( 
			 	'role_grup_master_id' => $_POST['rolegrupmas2'], 
			 	'wo_master_dtl_proc_approve' => $value,
			 	'wo_master_dtl_proc_status' => 1,
			);

			//var_dump($datawocmt);
			$execproc = $con->update("laundry_wo_master_dtl_proc",$dataproc,"garment_colors = '$color' and wo_master_id = '$id'");
			$datadtl = array( 
			 	'wo_master_dtl_status' => 1,
			);

			//var_dump($datawocmt);
			$execdtl = $con->update("laundry_wo_master_dtl",$datadtl,"garment_colors = '$color' and wo_master_id = '$id'");
			$where = array('wo_no' => $wo);
			$con->delete("laundry_wo_master_keranjang",$where);
	}

	$datawocmt = array( 
		 	'role_grup_master_status' => 2, 
	);
	$execwocmt = $con->update("laundry_role_grup_master",$datawocmt,"role_grup_master_id = '$_POST[rolegrupmas2]'");
	
	echo "<script>window.location='content.php?p=$_POST[getp]'</script>";
}
?>
