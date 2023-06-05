	<?php 
	session_start();
	include "../../../funlibs.php";
	$con = new Database;
	//error_reporting(0);
	$date = date("Y-m-d H:i:s");
	$date2 = date("Y-m-d_G-i-s");
	$datetok = date('dmy');

//shift 
foreach($con->select("laundry_master_shift","shift_id,shift_time_start,shift_time_end,shift_status","TO_CHAR(now(), 'HH24:MI') between TO_CHAR(shift_time_start, 'HH24:MI') and TO_CHAR(shift_time_end, 'HH24:MI') and shift_status = 1") as $shift){}
//end shift

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

		$execrolemaster= $con->update("wip_dtl", $datarolemaster,"wip_dtl_id = '".$_POST['kode']."'");
		//echo "<script>window.location='content.php?p=$_POST[getp]'</script>";
	} 
	else if ($_POST['codeproc'] == 'save'){
		$datarolemaster = array(
			'wip_dtl_status' => 1,
			'wip_dtl_remark' => $_POST['remarkwip'],
		); 

		$execrolemaster= $con->update("wip_dtl", $datarolemaster,"wip_dtl_id = '".$_POST['kode']."'");

		$selwipdtl = $con->select("wip_dtl","*","wip_dtl_id = '".$_POST['kode']."'");
		foreach ($selwipdtl as $wipdtl) {}

		$selcolqty = $con->select("wip_dtl","sum(quantity) as jumqty","wo_no = '".$wipdtl['wo_no']."' and garment_colors = '".$wipdtl['garment_colors']."'");
		foreach ($selcolqty as $colqty) {}

		$selwod = $con->select("laundry_wo_master","COUNT(wo_no) as jmlwo,wo_master_id","wo_no = '".$wipdtl['wo_no']."' GROUP BY wo_master_id");
		foreach ($selwod as $wod) {}
			if ($wod['jmlwo'] == 0) {
			//input laundry_wo_master
				$idwo = $con->idurut("laundry_wo_master","wo_master_id");
				$selwork = $con->select("laundry_data_wo","buyer_style_no","wo_no = '".$wipdtl['wo_no']."'");
				foreach ($selwork as $work) {}
					$datawo = array(
								'wo_master_id' => $idwo,
								'buyer_style_no' => $work['buyer_style_no'],
								'wo_master_createdate' => $date,
								'wo_master_createdby' => $_SESSION['ID_LOGIN'],
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
		
		//echo "<script>window.location='content.php?p=$_POST[getp]'</script>";
	}
	else if ($_POST['conf'] == '9'){
		//echo "c";
		$usewo = "wo_".$_GET['id'];
		$usecolor = "color_".$_GET['id'];
		$usein = "qtyin_".$_GET['id'];
		$usesend = "qtysend_".$_GET['id'];
		$wo = $_POST[$usewo];
		$color = $_POST[$usecolor];
		$in = $_POST[$usein];
		$send = $_POST[$usesend];

		$return = $send-$in;
		//echo $return;
			$idwipdtl = $con->idurut("wip_dtl","wip_dtl_id");
			$datarolemaster = array(
						 'wip_dtl_id' => $idwipdtl,
						 'wo_no' => $_POST['nocmt'],
						 'garment_colors' => $_POST['nocolor'],
						 'garment_inseams' => $_POST['noinseams'],
						 'garment_sizes' => $_POST['nosizes'],
						 'quantity' => $_POST['qty_in'],
						 'wip_dtl_receive_qty' => $_POST['qty_in'],
						 'wip_dtl_good_qty' => $_POST['qty_in'],
						 'wip_dtl_return_total_qty' => $_POST['qty_in'], 
						 'wip_dtl_status' => 9,
						 'wip_dtl_createdate' => $date,
						 'wip_dtl_createdby' => $_SESSION['ID_LOGIN'],
						 'ex_fty_date' => $_POST['exftydateasli'],
			);
			//var_dump($datarolemaster); 
			$execrolemaster= $con->insert("wip_dtl", $datarolemaster);
			//var_dump($execrolemaster);
			//die;
		//echo "<script>window.location='content.php?p=$_POST[getp]'</script>";
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
				
			$exec = $con->update("wip_dtl", $data,"wip_dtl_id = '".$_GET['id']."'");
		
	} 
	else if ($_POST['confmodcart'] == 'simpan'){
		//echo "e";
			//echo $_POST['confmodcart'];
		foreach ($_POST['cutqty'] as $key => $value) {
			$wono = $_POST['wono'][$key];
			$color = $_POST['color'][$key];
			$exftydate = $_POST['exftydate'][$key]; 

			if ($_POST['lastrec'][$key] == '1'){
				$statusrec = 2;
			} else {
				$statusrec = 1;
			}
			$daterec = date('ym');
			$wonorec = substr($wono,8);
			$idreceive = $con->idurut("laundry_receive","rec_id");
			//L191234A1A001
			$selwodtlproc = $con->select("laundry_wo_master_dtl_proc","wo_master_dtl_proc_id,role_wo_master_id","wo_no = '".$wono."' and garment_colors = '".$color."' and ex_fty_date = '".$exftydate."'");
			foreach ($selwodtlproc as $wodtlproc) {}

//create no receive
			//urut bagian awal
			$selecturutcmt = $con->select("laundry_receive","COUNT(rec_id) as max","wo_no = '".$wono."'");
			foreach ($selecturutcmt as $urutcmt) {}
			$cmtseq = $urutcmt['max']+1;
			$expwo = explode('/',$wono);

			//urut bagian belakang
			$selecturutcmtcolor = $con->select("laundry_receive","COUNT(rec_id) as max","wo_no = '".$wono."' and garment_colors = '".$color."' and ex_fty_date = '".$exftydate."'");
			
			foreach ($selecturutcmtcolor as $urutcmtcolor) {}
			$cmtcolseq = $urutcmtcolor['max']+1;

			$noreceive ='L'.$expwo[0].$expwo[4].$expwo[5].trim($expwo[6]).'A'.sprintf('%03s', $cmtseq).'-'.$cmtcolseq;
			//$noreceive = 'R'.$daterec.'/'.$wonorec.'-'.$color;
//cend create noreceive
			$datarec = array(
						'rec_id' => $idreceive,
						'rec_no' => $noreceive,
						'rec_createdate' => $date,
						'rec_createdby' => $_SESSION['ID_LOGIN'],
						'rec_status' => $statusrec,
						'rec_remark' => $_POST['note'][$key],
						'wo_no' => $wono,
						'garment_colors' => $color,
						'rec_qty' => $_POST['totgood'],
						'wo_master_dtl_proc_id' => $wodtlproc['wo_master_dtl_proc_id'],
						'rec_type' => 1,
						'shift_id' => $shift['shift_id'],
						'user_code' => $_POST['username'][$key],
						//'ex_fty_date' => $_POST['']
			); 
			//var_dump($datarec);
			$execrec = $con->insert("laundry_receive", $datarec);
			//var_dump($execrec);
			//die();
			
//input event Lot
				$idevent = $con->idurut("laundry_lot_event","event_id");
				$dataevent = array(
								 'event_id' => $idevent,
								 'lot_id' => $idreceive,
								 'lot_no' => $noreceive,
								 'event_type' => 1,
								 'event_status' => 1,
								 'event_createdby' => $_SESSION['ID_LOGIN'],
								 'event_createdate' => $date,
								 'master_type_process_id' => 1,
								 'master_type_lot_id' => 0,
								 'shift_id' => $shift['shift_id'], 
								 'lot_type' => 1,
								 'user_code' => $_POST['username'][$key],
				); 
				$execevent= $con->insert("laundry_lot_event", $dataevent);
//end event lot

// cek di wo_master
			$jmlwo = $con->selectcount("laundry_wo_master","wo_no","wo_no = '".$wono."'");
			$selwod = $con->select("laundry_wo_master","COUNT(wo_no) as jmlwo,wo_master_id,total_rec_qty","wo_no = '".$wono."' GROUP BY wo_master_id,total_rec_qty");
			  
			  foreach ($selwod as $wod) {}
//end cek
			  	
			  if($jmlwo == '0'){
				$idwo = $con->idurut("laundry_wo_master","wo_master_id");
				$selwork = $con->select("qrcode_workorders a join laundry_data_wo b on a.wo_no =b.wo_no","b.buyer_style_no,a.wo_qty","a.wo_no = '".$wono."'");
				
				foreach ($selwork as $work) {}
					$datawo = array(
								'wo_master_id' => $idwo,
								'buyer_style_no' => $work['buyer_style_no'],
								'wo_master_createdate' => $date,
								'wo_master_createdby' => $_SESSION['ID_LOGIN'],
								'wo_no' => $wono,
								'wo_qty' => $work['wo_qty'],
								'total_rec_qty' => $_POST['jumlahgood'][$key],			
					);
					$execwo= $con->insert("laundry_wo_master", $datawo);
				
			  } else {
			  		
			  		$qty = $wod['total_rec_qty']+$_POST['jumlahgood'][$key];
			  		$datawo = array(
			  					'wo_master_modifydate' => $date,
			  					'wo_master_modifyby'=> $_SESSION['ID_LOGIN'],
								'total_rec_qty' => $qty,			
					);
					$execwo= $con->update("laundry_wo_master", $datawo,"wo_no = '".$wono."'");
			  }
			 
						$selwipproc = $con->select("wip_dtl","wo_no,garment_colors,DATE(ex_fty_date) as ex_fty_date,sum(quantity) as qtycolor","wip_dtl_status = '9' and wo_no = '".$wono."' and garment_colors = '".$color."' and DATE(ex_fty_date) = '".$exftydate."' GROUP BY wo_no,garment_colors,ex_fty_date");
						foreach ($selwipproc as $wipproc) {
//ambil nilai receive sebelumnya
									$selproc = $con->select("laundry_wo_master_dtl_proc","wo_master_dtl_proc_id,wo_master_dtl_proc_qty_rec","wo_no = '".$wono."' and garment_colors= '".$color."' and DATE(ex_fty_date) = '".$exftydate."'");
									foreach ($selproc as $pro) {}
//jumlahkan nilai sekarang dan sebelumnya
										$jmlqty = $pro['wo_master_dtl_proc_qty_rec']+$_POST['jumlahgood'][$key];
										
										$idproc = $con->idurut("laundry_wo_master_dtl_proc","wo_master_dtl_proc_id");
										$dataproc = array(
												  'wo_master_dtl_proc_qty_rec' => $jmlqty,
												  'cutting_qty' => $value,
												  'wo_master_dtl_proc_modifydate' => $date,
												  'wo_master_dtl_proc_modifyby' => $_SESSION['ID_LOGIN'],
										);
										$execproc= $con->update("laundry_wo_master_dtl_proc", $dataproc,"wo_no = '".$wono."' and garment_colors = '".$color."' and DATE(ex_fty_date) = '".$exftydate."'");
								
						}
					
			$selwip = $con->select("wip_dtl","*","wip_dtl_status = '9' and wo_no = '".$wono."' and garment_colors = '".$color."' and DATE(ex_fty_date) = '".$exftydate."'");
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
				$execdtl = $con->insert("laundry_wo_master_dtl", $datadtl);
			}	
		}
		
// input data role per lot receive ke laundry role child
		$selrolechild = $con->select("laundry_role_wo a left join 
									  laundry_role_dtl_wo b on a.role_wo_id=b.role_wo_id",
									 "a.role_wo_id,
									  role_wo_name,
									  master_type_process_id,
									  role_wo_seq,
									  role_wo_time,
									  role_dtl_wo_id,
									  master_process_id,
									  role_dtl_wo_seq,
									  role_dtl_wo_time",
									 "role_wo_master_id = '".$wodtlproc['role_wo_master_id']."' and 
									  role_wo_seq <= (select role_wo_seq from laundry_role_wo where role_wo_master_id = '".$wodtlproc['role_wo_master_id']."' and master_type_process_id = 2)",
									 "role_wo_seq");
		
		foreach ($selrolechild as $child) {
			$idchild = $con->idurut("laundry_role_child","role_child_id");
			$datachild = array(
						'role_child_id' => $idchild,
						'role_wo_master_id'  => $wodtlproc['role_wo_master_id'],
						'role_wo_id'  => $child['role_wo_id'],
						'role_dtl_wo_id'  => $child['role_dtl_wo_id'],
						'lot_type'  => 1,
						'role_child_status'  => 0,
						'role_child_createdate'  => $date,
						'role_child_createdby'  => $_SESSION['ID_LOGIN'],
						'lot_no'  => $noreceive,
						'lot_id'  => $idreceive,
						'role_wo_seq' => $child['role_wo_seq'],
						'role_dtl_wo_seq' => $child['role_dtl_wo_seq'],
			);
			//var_dump($datachild); 
			$execchild = $con->insert("laundry_role_child", $datachild);
			//var_dump($execchild);
		}
// end input data role per lot receive ke laundry role child

//update data wip_dtl menjadi status 1
		$datawip = array(
						 'wip_dtl_status' => '1',
		); 			
	    $execwip = $con->update("wip_dtl", $datawip,"wip_dtl_status = '9'");
//end update data wip_dtl menjadi status 1

	   	echo $noreceive;
	} 
	?>
