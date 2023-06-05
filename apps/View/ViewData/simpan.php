<?php 
session_start();
include "../../../funlibs.php";
$con = new Database;
//error_reporting(0);
$date = date("Y-m-d H:i:s");
$date2 = date("Y-m-d_G-i-s");
$datetok = date('dmy');


//input role proses
if ($_POST['codeproc'] == 'return'){
	//echo "a";
	$datarolemaster = array(
					 'wip_dtl_good_qty' => $_POST['good'],
					 'wip_dtl_reject_qty' => $_POST['reject'], 
					 'wip_dtl_return_total_qty' => $_POST['return'], 
					 'wip_dtl_status' => 2,
					 'wip_dtl_remark' => $_POST['remarkwip'],
	); 

	$execrolemaster= $con->update("wip_dtl", $datarolemaster,"wip_dtl_id = '$_POST[kode]'");
	echo "<script>window.location='content.php?p=$_POST[getp]'</script>";
} 
else if ($_POST['codeproc'] == 'save'){
	//echo "b";
	$datarolemaster = array(
					 // 'wip_dtl_good_qty' => $_POST['good'],
					 // 'wip_dtl_reject_qty' => $_POST['reject'], 
					 // 'wip_dtl_return_total_qty' => $_POST['return'], 
					 'wip_dtl_status' => 1,
					 'wip_dtl_remark' => $_POST['remarkwip'],
	); 

	$execrolemaster= $con->update("wip_dtl", $datarolemaster,"wip_dtl_id = '$_POST[kode]'");

	$selwipdtl = $con->select("wip_dtl","*","wip_dtl_id = '$_POST[kode]'");
	foreach ($selwipdtl as $wipdtl) {}

	$selcolqty = $con->select("wip_dtl","sum(quantity) as jumqty","wo_no = '$wipdtl[wo_no]' and garment_colors = '$wipdtl[garment_colors]'");
	foreach ($selcolqty as $colqty) {}

	$selwod = $con->select("laundry_wo_master","COUNT(wo_no) as jmlwo,wo_master_id","wo_no = '$wipdtl[wo_no]' GROUP BY wo_master_id");
	foreach ($selwod as $wod) {}
		if ($wod['jmlwo'] == 0) {
		//input laundry_wo_master
			$idwo = $con->idurut("laundry_wo_master","wo_master_id");
			$selwork = $con->select("work_orders","buyer_style_no","wo_no = '$wipdtl[wo_no]'");
			foreach ($selwork as $work) {}
				$datawo = array(
							'wo_master_id' => $idwo,
							'buyer_style_no' => $work['buyer_style_no'],
							'wo_master_created_date' => $date,
							// 'wip_qty_global' => $wipdtl['garment_sizes'],
							'cutting_qty' => $_POST['cutting'],
							'wo_no' => $wipdtl['wo_no'],
							'wo_qty' => $work['wo_qty'],
							
				);
			$execwo= $con->insert("laundry_wo_master", $datawo);
		} else {
			$idwo = $wod['wo_master_id'];
		}

		//input laundry_wo_master_Dtl
		$idwodtl = $con->idurut("laundry_wo_master_dtl","wo_master_dtl_id");
		$datadetil = array(
					'wo_master_dtl_id' => $idwodtl,
					'garment_colors' => $wipdtl['garment_colors'],
					'garment_inseams' => $wipdtl['garment_inseams'],
					'garment_sizes' => $wipdtl['garment_sizes'],
					'buyer_po_number' => $wipdtl['buyer_po_number'],
					// 'wo_master_dtl_qty_md' => 
					'wo_master_dtl_status' => 1, 
					'wo_master_id' => $idwo,
					// 'ex_fty_date' => 
					'wo_master_dtl_receive_date' => $date,
					'wo_master_dtl_qty_rec' => $_POST['good'],
					'wo_no' => $wipdtl['wo_no'],
					'qty_color' => $colqty['jumqty'],
					'last_receive' => $_POST['lastrec'],
		); 

		$execdetil= $con->insert("laundry_wo_master_dtl", $datadetil);
	
	echo "<script>window.location='content.php?p=$_POST[getp]'</script>";
}
else if ($_POST['conf'] == '9'){
	//echo "c";
	$usein = "qtyin_".$_GET['id'];
	$usesend = "qtysend_".$_GET['id'];
	$in = $_POST[$usein];
	$send = $_POST[$usesend];
	
	$return = $send-$in;
	//echo $return;
		$datarolemaster = array(
					 'wip_dtl_receive_qty' => $send,
					 'wip_dtl_good_qty' => $in,
					 'wip_dtl_reject_qty' => $return, 
					 'wip_dtl_return_total_qty' => $return, 
					 'wip_dtl_status' => 9,
					 //'wip_dtl_remark' => $_POST['remarkwip'],
		); 
		var_dump($datarolemaster);
		$execrolemaster= $con->update("wip_dtl", $datarolemaster,"wip_dtl_id = '$_GET[id]'");
		//var_dump($execrolemaster);
	echo "<script>window.location='content.php?p=$_POST[getp]'</script>";
} 
else if ($_POST['hpsmodcart'] == 'hpscart'){
	//echo "d";
		$data = array(
					 'wip_dtl_good_qty' => '0',
					 'wip_dtl_reject_qty' => '0', 
					 'wip_dtl_return_total_qty' => '0', 
					 'wip_dtl_status' => '0',
					 'wip_dtl_remark' => '',
		); 
			
		$exec = $con->update("wip_dtl", $data,"wip_dtl_id = '$_GET[id]'");
		var_dump($exec);
	
} 
else if ($_POST['confmodcart'] == 'simpan'){
	//echo "e";
		//echo $_POST['confmodcart'];
	foreach ($_POST['cutqty'] as $key => $value) {
		$wono = $_POST['wono'][$key];
		$color = $_POST['color'][$key];

		if ($_POST['lastrec'][$key] == '1'){
			$statusrec = 2;
		} else {
			$statusrec = 1;
		}
		$daterec = date('ym');
		$wonorec = substr($wono,8);
		$idreceive = $con->idurut("laundry_receive_master","rec_id");
		$noreceive = 'R'.$daterec.'/'.$wonorec.'-'.$color;
		
		$datarec = array(
					'rec_id' => $idreceive,
					'rec_no' => $noreceive,
					'rec_createdate' => $date,
					'rec_status' => $statusrec,
					'rec_remark' => $_POST['note'][$key],
		); 
		$execrec = $con->insert("laundry_receive_master", $datarec);
		
		  // cek di wo_master
		  $selwod = $con->select("laundry_wo_master","COUNT(wo_no) as jmlwo,wo_master_id,rec_qty","wo_no = '$wono' GROUP BY wo_master_id");
		  foreach ($selwod as $wod) {}
		  //end cek

		  if($wod['jmlwo'] == 0){
			$idwo = $con->idurut("laundry_wo_master","wo_master_id");
			$selwork = $con->select("work_orders","buyer_style_no,quantity","wo_no = '$wono'");
			foreach ($selwork as $work) {}
				$datawo = array(
							'wo_master_id' => $idwo,
							'buyer_style_no' => $work['buyer_style_no'],
							'wo_master_created_date' => $date,
							// 'wip_qty_global' => $wipdtl['garment_sizes'],
							'cutting_qty' => $value,
							'wo_no' => $wono,
							'wo_qty' => $work['quantity'],
							'rec_qty' => $_POST['jumlahqty'][$key],			
				);
				$execwo= $con->insert("laundry_wo_master", $datawo);
		  } else {
		  		//echo "rs";
		  		$qty = $wod['rec_qty']+$_POST['jumlahgood'][$key];
		  		$datawo = array(
		  					'wo_master_dateupdate' => $date,
							'rec_qty' => $qty,			
				);
				$execwo= $con->update("laundry_wo_master", $datawo,"wo_no = '$wono'");
		  }
		 
					$selwipproc = $con->select("wip_dtl","wo_no,garment_colors,sum(quantity) as qtycolor","wip_dtl_status = '9' and wo_no = '$wono' and garment_colors = '$color' GROUP BY wo_no,garment_colors");
					foreach ($selwipproc as $wipproc) {
								//ambil nilai receive sebelumnya
								$selproc = $con->select("laundry_wo_master_dtl_proc","wo_master_dtl_proc_id,wo_master_dtl_proc_qty_rec","wo_no = '$wono' and garment_colors= '$color'");
								foreach ($selproc as $pro) {}
									//jumlahkan nilai sekarang dan sebelumnya
									$jmlqty = $pro['wo_master_dtl_proc_qty_rec']+$_POST['jumlahgood'][$key];
									
									$idproc = $con->idurut("laundry_wo_master_dtl_proc","wo_master_dtl_proc_id");
									$dataproc = array(
											  'wo_master_dtl_proc_qty_rec' => $jmlqty,
											  'cutting_qty' => $value,
											  'rec_id' => $idreceive,
									);
									//var_dump($dataproc);
									$execproc= $con->update("laundry_wo_master_dtl_proc", $dataproc,"wo_no = '$wono' and garment_colors = '$color'");
								//var_dump($execproc);
							
					}
				
		$selwip = $con->select("wip_dtl","*","wip_dtl_status = '9' and wo_no = '$wono' and garment_colors = '$color'");
		//echo "select * from wip_dtl where wip_dtl_status = '9' and wo_no = '$wono'";
		foreach ($selwip as $wip) {
			$iddtl = $con->idurut("laundry_wo_master_dtl","wo_master_dtl_id");
			$datadtl = array(
					'wo_master_dtl_id' => $iddtl,
					'garment_colors' => $wip['garment_colors'],
					'garment_inseams' => $wip['garment_inseams'], 
					'garment_sizes' => $wip['garment_sizes'],
					'buyer_po_number' => $wip['buyer_po_number'],
					'wo_master_dtl_status' => 1,
					'ex_fty_date' => $wip['ex_fty_date'],
					'wo_master_dtl_receive_date' => $date,
					'wo_master_dtl_qty_rec' => $wip['quantity'],
					'wo_no' => $wip['wo_no'],
					'rec_id' => $idreceive,
			); 
			//var_dump($datadtl);
			$execdtl = $con->insert("laundry_wo_master_dtl", $datadtl);
			//var_dump($execdtl);
		}
		
	}
	$datawip = array(
					 'wip_dtl_status' => '1',
	); 			
    $execwip = $con->update("wip_dtl", $datawip,"wip_dtl_status = '9'");
} 
?>
