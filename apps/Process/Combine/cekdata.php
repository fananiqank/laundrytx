<?php 
session_start();
include "../../../funlibs.php";
$con = new Database;

//pengecekan Data sudah ada atau tidak di Keranjang
$cekdata = $con->selectcount("laundry_lot_number a JOIN laundry_lot_number_keranjang b on a.lot_id=b.lot_id","a.lot_id","a.lot_no = '".$_POST['lot_no']."' and b.lot_keranjang_status = 1");

$cekbreak = $con->selectcount("laundry_lot_number","lot_id","lot_no = '".$_POST['lot_no']."' and lot_status = 1");

foreach($con->select("laundry_lot_number a JOIN laundry_wo_master_dtl_proc b on a.wo_master_dtl_proc_id=b.wo_master_dtl_proc_id join laundry_log c on a.lot_no=c.log_lot_tr","a.lot_no,b.wo_no,b.garment_colors,a.master_type_lot_id,b.role_wo_master_id,b.ex_fty_date,a.lot_type,a.role_wo_name_seq,c.lotmaking_type,a.lot_shade","a.lot_no = '".$_POST['lot_no']."'") as $wocolor){}
	//echo "select a.lot_no,b.wo_no,b.garment_colors,a.master_type_lot_id,b.role_wo_master_id,b.ex_fty_date from laundry_lot_number a JOIN laundry_wo_master_dtl_proc b on a.wo_master_dtl_proc_id=b.wo_master_dtl_proc_id where a.lot_no = '".$_POST['lot_no']."'";

//cek lot pada process despatch
$cekdespatch = $con->selectcount("laundry_process","lot_no","lot_no = '".$_POST['lot_no']."' and master_type_process_id = '6' and process_type = '4'");

include "cekrole1.php";
if ($cekdespatch == '0'){
	if ($cekdata == '' && $cekbreak != ''){
		if ($nextstep['master_process_combine_lot'] == 1 && $nextstep['process_type'] == 1){
			if ($_POST['wo_no'] != ''){
				if ($wocolor['wo_no'] == $_POST['wo_no'] && $wocolor['garment_colors'] == $_POST['garment_colors'] && $wocolor['ex_fty_date'] == $_POST['ex_fty_date'] && $wocolor['role_wo_name_seq'] == $_POST['rolewonameseq'] && $wocolor['lotmaking_type'] == $_POST['lotmakingtype'] && $wocolor['lot_shade'] == $_POST['lotshade']){
					//jika type sama
					if ($wocolor['master_type_lot_id'] == $_POST['master_type_lot_id']){
						//jika process id sama
						if ($nextstep['master_process_id'] == $_POST['master_process_id']){
							if ($wocolor['lot_type'] != 'Q' && $wocolor['lot_type'] != 'P' && $wocolor['lot_type'] != 'F')
							//if ($wocolor['lot_type'] != 'Q' && $wocolor['lot_type'] != 'P')
								echo "1";
							else {
								echo "9";
							}
						} else {
							echo "6";
						}
					} else {
						echo "5";
					}
				} else {
					echo "4";
				}	 
			} else {
				if ($wocolor['lot_type'] != 'Q' && $wocolor['lot_type'] != 'P' && $wocolor['lot_type'] != 'F')
				//if ($wocolor['lot_type'] != 'Q' && $wocolor['lot_type'] != 'P')
					echo "1";
				else {
					echo "9";
				}
			}
		} else {
			echo "8";
		}
	} else if ($cekdata == '' && $cekbreak == ''){
		echo "3";
	} 
	else {
		echo "2";
	}
} else {
	echo "7";
}
?>