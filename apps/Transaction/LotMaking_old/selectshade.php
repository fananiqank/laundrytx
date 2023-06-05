<?php 
//dipakai nonaktif 2023-03-06 ============
	// session_start();
	// include '../../../funlibs.php';
	// $con = new Database;

	// $cmt_no = $con->searchseqcmt($_GET['cmt']);
	// $color_no2 = $con->searchseq($_GET['col']);
	// $colors2 = explode('Y|',$color_no2);
	// $colors3 = implode('&',$colors2);
	// $color_no = trim($colors3);
	
	// foreach($con->select("laundry_receive A JOIN laundry_process b ON A.rec_no = b.lot_no ","lot_no","a.rec_status = 2 and master_type_process_id = 2 and lot_type = 1 and process_type = 4") as $lot_no){}
// =======================================

	// $selshade = $con->select("(select cpdshade from qrcode_cutplan_dtlhead GROUP BY cpdshade) as shade","cpdshade","","cpdshade");

//dipakai nonaktif 2023-03-06 ============
	// $selshade = $con->select("laundry_scan_qrcode","shade","wo_no = '$cmt_no' and garment_colors = '$color_no' GROUP BY shade","shade");
//========================================

?>

<!-- <select data-plugin-selectTwo class="form-control populate"  placeholder="Shade" name="shade" id="shade">
	<option value=""></option>
	<?php 
		foreach ($selshade as $shade) {
			echo "<option value='$shade[shade]'>$shade[shade]</option>";	
		}	
		if($_GET['lastrec'] == '2'){ 
			if($lot_no['lot_no']){
	
				echo "<option value='MS'>Mix Shades</option>";
			} 
		}
	?>
</select> -->
<input type="text" class="form-control" placeholder="Shade" name="shade" id="shade">