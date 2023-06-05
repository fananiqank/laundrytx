<?php
$tiketmaster = $con->selectcount("qrcode_ticketing_master", "qrcode_key", "qrcode_key = '" . $_POST['scanner'] . "'");
//echo "select qrcode_key from qrcode_ticketing_master where qrcode_key = '".$_POST['scanner']."'";
foreach ($con->select("qrcode_ticketing_master", "color", "qrcode_key = '" . $_POST['scanner'] . "'") as $colortiket) {
}
if ($tiketmaster > 0) {
	if ($_POST['garment_colors_scan'] != '') {
		if ($colortiket['color'] == $_POST['garment_colors_scan']) {
			echo "4|" . $_POST['scanner'];
		} else {

			if ($_POST['scan_type'] == '1') {
				//get sequence sebelum process saat ini.
				foreach ($con->select("qrcode_step_detail", "workcenter_id,workcenter_seq", "step_id_detail = 4") as $mystepdetail) {
				}

				$beforestepdetail = $mystepdetail['workcenter_seq'] - 1;

				//get data id dan seq pada process sebelumnya
				foreach ($con->select("qrcode_step_detail", "workcenter_id,workcenter_seq", "workcenter_seq = $beforestepdetail") as $databefore) {
				}

				//cek apakah data ini sudah di input pada process sebelumnya. berstatus 1 (confirm)
				$gdpbefore = $con->selectcount("qrcode_gdp", "qrcode_key", "qrcode_key = '" . $_POST['scanner'] . "' and workcenter_seq = '$beforestepdetail' and gdp_status = 1");
				$gdpnow = $con->selectcount("qrcode_gdp", "qrcode_key", "qrcode_key = '" . $_POST['scanner'] . "' and workcenter_seq = '" . $mystepdetail['workcenter_seq'] . "' and gdp_status = 1");
				$scannow = $con->selectcount("laundry_scan_qrcode", "scan_qrcode", "scan_qrcode = '" . $_POST['scanner'] . "' and scan_status = 0 and scan_type = 1");
				//echo "select scan_qrcode from laundry_scan_qrcode where scan_qrcode = '".$_POST['scanner']."' and scan_status = 0 and scan_type = 1";
				//echo "select qrcode_key from qrcode_gdp where qrcode_key = '".$_POST['scanner']."' and workcenter_seq = '$mystepdetail[workcenter_seq]' and gdp_status = 1";

				//jika sudah di input maka simpan ke scan ke database scan laundry
				if ($gdpbefore > 0) {

					//jika di scan keranjang sudah ada nomor yang sama
					if ($scannow > 0) {
						echo "3|" . $_POST['scanner'];
					} else {

						//jika sudah di scan di section yang sama
						if ($gdpnow > 0) {
							echo "3|" . $_POST['scanner'];
						} else {
							$idscan = $con->idurut("laundry_scan_qrcode", "scan_id");
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
								'scan_type' => $_POST['scan_type'],
								'scan_status' => 0,
								'scan_qty' => 1,
								'scan_status_garment' => 1,
								'factory_id' => $_POST['factory'],
								'user_code' => $_POST['usercode'],
								'status_qrcode' => 1,
								'ex_fty_date' => $exftydate
							);
							//var_dump($datascan);
							$execscan = $con->insert("laundry_scan_qrcode", $datascan);
							echo "1|" . $_POST['scanner'];
						}
					}
				} else {
					echo "2|" . $_POST['scanner'] . "|" . $databefore['workcenter_id'];
				}
			}
		}
	} else {
		if ($_POST['scan_type'] == '1') {
			//get sequence sebelum process saat ini.
			foreach ($con->select("qrcode_step_detail", "workcenter_id,workcenter_seq", "step_id_detail = 4") as $mystepdetail) {
			}

			$beforestepdetail = $mystepdetail['workcenter_seq'] - 1;

			//get data id dan seq pada process sebelumnya
			foreach ($con->select("qrcode_step_detail", "workcenter_id,workcenter_seq", "workcenter_seq = $beforestepdetail") as $databefore) {
			}

			//cek apakah data ini sudah di input pada process sebelumnya. berstatus 1 (confirm)
			$gdpbefore = $con->selectcount("qrcode_gdp", "qrcode_key", "qrcode_key = '" . $_POST['scanner'] . "' and workcenter_seq = '$beforestepdetail' and gdp_status = 1");
			$gdpnow = $con->selectcount("qrcode_gdp", "qrcode_key", "qrcode_key = '" . $_POST['scanner'] . "' and workcenter_seq = '$mystepdetail[workcenter_seq]' and gdp_status = 1");
			$scannow = $con->selectcount("laundry_scan_qrcode", "scan_qrcode", "scan_qrcode = '" . $_POST['scanner'] . "' and scan_status = 0 and scan_type = 1");
			//echo "select scan_qrcode from laundry_scan_qrcode where scan_qrcode = '".$_POST['scanner']."' and scan_status = 0 and scan_type = 1";
			//echo "select qrcode_key from qrcode_gdp where qrcode_key = '".$_POST['scanner']."' and workcenter_seq = '$mystepdetail[workcenter_seq]' and gdp_status = 1";

			//jika sudah di input maka simpan ke scan ke database scan laundry
			if ($gdpbefore > 0) {

				//jika di scan keranjang sudah ada nomor yang sama
				if ($scannow > 0) {
					echo "3|" . $_POST['scanner'];
				} else {

					//jika sudah di scan di section yang sama
					if ($gdpnow > 0) {
						echo "3|" . $_POST['scanner'];
					} else {
						$idscan = $con->idurut("laundry_scan_qrcode", "scan_id");
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
							'scan_type' => $_POST['scan_type'],
							'scan_status' => 0,
							'scan_qty' => 1,
							'scan_status_garment' => 1,
							'factory_id' => 4,
							'user_code' => $_POST['usercode'],
							'status_qrcode' => 1,
							'ex_fty_date' => $exftydate
						);
						//var_dump($datascan);
						$execscan = $con->insert("laundry_scan_qrcode", $datascan);

						echo "1|" . $_POST['scanner'];
					}
				}
			} else {
				echo "2|" . $_POST['scanner'] . "|" . $databefore['workcenter_id'];
			}
		}
	}
} 