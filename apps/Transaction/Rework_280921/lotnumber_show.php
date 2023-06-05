<?php 
	session_start();
	include '../../../funlibs.php';
	$con = new Database;

	$cmt_no = $con->searchseqcmt($_GET['cmt']);
	$color_no = $con->searchseq($_GET['color']);
	
	//tampilkan data lot 
	$table = "laundry_lot_number a JOIN laundry_master_type_lot c on a.master_type_lot_id=c.master_type_lot_id JOIN laundry_wo_master_dtl_proc d on a.wo_master_dtl_proc_id=d.wo_master_dtl_proc_id";
	$field = "a.*,c.master_type_lot_name,d.ex_fty_date,to_char(d.ex_fty_date,'DD-MM-YYYY') as ex_fty_date_show";
	$where = "d.wo_no = '$cmt_no' and d.garment_colors = '$color_no' and d.ex_fty_date = '$_GET[exdate]'";
	
	$seltable = $con->select($table,$field,$where);
	//echo "select $field from $table where $where"; 
	foreach ($seltable as $set) {}

	$datasave= $set['wo_master_dtl_proc_id'].'_'.$set['master_type_lot_id'].'_'.$set['role_wo_master_id'].'_'.$set['wo_no'].'_'.$lotnum['garment_colors'];
	
	$sequencecount = $con->selectcount("laundry_lot_number","lot_id","wo_master_dtl_proc_id = '$set[wo_master_dtl_proc_id]'");
		$sequence = $sequencecount+1;
		
		$urutcount = $con->selectcount("laundry_lot_number","lot_id","wo_no = '$set[wo_no]'");
		$urut = $urutcount+1;

		$expmt = explode('/',$set['wo_no']);
		$trimexp6 = trim($expmt[6]);

		$nolb ='L'.$expmt[0].$expmt[4].$expmt[5].$trimexp6."M".sprintf('%03s', $urut);
		echo "<b>Lot No : ".$nolb."</b>";
		echo "<input type='hidden' id='lot_number' name='lot_number' value='".$nolb."'>";
?>