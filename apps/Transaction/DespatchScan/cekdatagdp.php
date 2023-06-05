<?php 
	$tiketmaster = $con->selectcount("qrcode_ticketing_master","qrcode_key","qrcode_key = '$_POST[scanner]'");
	if ($tiketmaster > 0){
				$scanqc = $con->selectcount("laundry_scan_qrcode","scan_qrcode","scan_qrcode = '$_POST[scanner]' and scan_status_garment = 1 and scan_type = 3");

				$scandespatch = $con->selectcount("laundry_scan_qrcode","scan_qrcode","scan_qrcode = '$_POST[scanner]' and scan_status = 0 and scan_type = 4");

				//jika sudah di QC status good
				if($scanqc > 0) {

					//jika di scan keranjang sudah ada nomor yang sama
					if ($scannow > 0) {
						echo "3|".$_POST['scanner'];
						
					} else {
	
						$idscan = $con->idurut("laundry_scan_qrcode","scan_id");
						$datascan = array(
									 'scan_id' => $idscan,
									 'scan_qrcode' => $_POST['scanner'],
									 'buyer_id' => $buyer_id,
									 'wo_no' => $cmt,
									 'garment_colors' => $colors,
									 'garment_inseams' => $inseams,
									 'garment_sizes' => $sizes,
									 'sequence_no' => $seq_cutting,
									 'shade' => $shade,
									 'scan_createdby' => $_SESSION['ID_LOGIN'],
									 'scan_createdate' => $date,
									 'scan_type' => 4,
									 'scan_status' => 0,
									 'scan_qty' => 1,
									 'lot_no' => $_POST['lot_no'],
									 'scan_status_garment' => 1,
						);
						$execscan= $con->insert("laundry_scan_qrcode", $datascan);
						echo "1|".$_POST['scanner'];
					
					}

				} else {
					echo "2|".$_POST['scanner']."|".$databefore['workcenter_desc'];
				}
		
	} else {
		echo "0|".$_POST['scanner'];
	}

?>
