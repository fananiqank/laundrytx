
<?php 
session_start();
include '../../../funlibs.php';
$con = new Database;

$cmt_no = $con->searchseqcmt($_GET['id']);
$no=1;

if (strtoupper(strtoupper(strtoupper($_GET['typelot']))) == 'A'){
	$role1lot = strtoupper($_GET['lot']);
		foreach ($con->select(" laundry_wo_master_dtl_proc a 
		                        JOIN laundry_receive b on a.wo_master_dtl_proc_id = b.wo_master_dtl_proc_id
		                        JOIN laundry_data_wo c on a.wo_no=c.wo_no
		                        JOIN laundry_role_wo d on a.role_wo_master_id=d.role_wo_master_id
		                        LEFT JOIN laundry_role_dtl_wo e ON d.role_wo_id = e.role_wo_id
		                        ",
		                        "a.wo_master_dtl_proc_id,
		                         a.wo_master_dtl_proc_status,
		                         a.wo_no,
		                         a.buyer_id,
		                         a.garment_colors,
		                         a.role_wo_master_id,
		                         a.color_wash,
		                         CASE WHEN COALESCE(b.rec_qty_good_upd,0) != 0 THEN b.rec_qty_good_upd 
		                         ELSE b.rec_qty END as lotqty,
		                         b.rec_no as lotno,
		                         b.rec_createdby as createdby,
		                         c.buyer_id,
		                         b.rec_id as id,
		                         b.rec_break_status as lot_status,
		                         a.status_hold,
		                         to_char(a.ex_fty_date,'DD-MM-YYYY') as ex_fty_date,
		                         a.ex_fty_date as ex_fty_date_asli
		                        ",
		                        "rec_no = '".strtoupper($_GET['lot'])."' limit 1") as $cmt) {}

if($cmt['role_wo_master_id'] <> '') {
	$rolewomasterid = $cmt['role_wo_master_id'];
} else {
	$rolewomasterid = 0;
}

		$tablerole = "  laundry_role_child a 
						LEFT join laundry_role_wo b on a.role_wo_id=b.role_wo_id
						left join laundry_role_dtl_wo c on a.role_dtl_wo_id=c.role_dtl_wo_id
						left join laundry_master_process d on c.master_process_id=d.master_process_id
						left join (
								SELECT
								    max(process_type) as process_type,
									role_wo_id 
								FROM
									laundry_process 
								WHERE
									role_wo_master_id = '".$rolewomasterid."'  
									AND master_type_process_id NOT BETWEEN 4 
									AND 5 
									AND lot_no = '".strtoupper($_GET['lot'])."'
								GROUP BY role_wo_id
						) AS e ON e.role_wo_id = a.role_wo_id
						left join (
								SELECT u.process_type,v.role_dtl_wo_id,u.change_process
		 						FROM laundry_process u 
		 							 JOIN (
											SELECT
												max(process_id) as process_id,
												role_dtl_wo_id
											FROM
												laundry_process 
											WHERE
												role_wo_master_id = '".$rolewomasterid."' 
												AND master_type_process_id BETWEEN 4 
												AND 5 
												AND lot_no = '".strtoupper($_GET['lot'])."'
											GROUP BY
											  	role_dtl_wo_id
									 ) as v ON u.process_id=v.process_id
						) AS f ON f.role_dtl_wo_id = a.role_dtl_wo_id 
						";

		$fieldrole = "a.role_wo_id,
					  a.role_dtl_wo_id,
					  a.role_wo_seq,
					  a.role_dtl_wo_seq,
					  b.role_wo_name,
					  b.master_type_process_id,
					  d.master_process_name,
					  e.process_type as role_process,
					  f.process_type as dtl_process,
					  f.change_process";
			
		$whererole = "a.lot_no = '".strtoupper($_GET['lot'])."'";

} else {
	//pengecekan data.
	include "cekdata.php";
	//======
	
	//jika menggunakan Lot receive maka lot akan menggunakan $_GET[lot]
	if($parlot == ''){
		$role1lot = strtoupper($_GET['lot']);
		$wherecmt = "";
	} else if (strtoupper(strtoupper($_GET['typelot'])) == 'W'){
		$role1lot = strtoupper($_GET['lot']);
		$wherecmt = "and wo_master_dtl_proc_status = 2 ORDER BY rework_seq DESC";
	}
	else {
		$role1lot = $parlot;
		$wherecmt = "";
	}

		foreach($con->select("laundry_wo_master_dtl_proc a 
		                        join laundry_lot_number b on a.wo_master_dtl_proc_id = b.wo_master_dtl_proc_id
		                        JOIN laundry_data_wo c on a.wo_no=c.wo_no
		                        JOIN laundry_role_wo_master g on a.role_wo_master_id=g.role_wo_master_id
		                        JOIN laundry_role_wo d on a.role_wo_master_id=d.role_wo_master_id
		                        LEFT JOIN laundry_role_dtl_wo e ON d.role_wo_id = e.role_wo_id
		                        LEFT JOIN (select event_type,lot_id from laundry_lot_event where lot_no = '".strtoupper($_GET['lot'])."' order by event_id DESC Limit 1) f on b.lot_id=f.lot_id 
		                        ",
		                        "a.wo_master_dtl_proc_id,
		                         a.wo_master_dtl_proc_status,
		                         a.wo_no,
		                         a.buyer_id,
		                         a.garment_colors,
		                         a.role_wo_master_id,
		                         a.status_hold,
		                         a.color_wash,
		                         b.lot_qty as lotqty,
		                         b.lot_no as lotno,
		                         b.lot_createdby as createdby,
		                         b.lot_status,
		                         b.lot_id as id,
		                         b.lot_status,
		                         b.combine_hold,
		                         b.last_lot_from_combine,
		                         c.buyer_id,
		                         f.event_type,
		                         g.type_receive,
		                         to_char(a.ex_fty_date,'DD-MM-YYYY') as ex_fty_date,
		                         a.ex_fty_date as ex_fty_date_asli
		                        ",
		                        "lot_no = '".strtoupper($_GET['lot'])."' $wherecmt limit 1") as $cmt) {}
if($cmt['role_wo_master_id'] <> '') {
	$rolewomasterid = $cmt['role_wo_master_id'];
} else {
	$rolewomasterid = 0;
}
		// foreach($con->select("laundry_wo_master_dtl_proc a 
		//                         join (select max(lot_id) as lot_id,wo_master_dtl_proc_id,lot_no,lot_qty,create_type,role_wo_name_seq,lot_createdby,lot_status,combine_hold,last_lot_from_combine,wo_no,garment_colors,lot_type from laundry_lot_number group by wo_master_dtl_proc_id,lot_no,lot_qty,create_type,role_wo_name_seq,lot_createdby,lot_status,combine_hold,last_lot_from_combine,wo_no,garment_colors,lot_type) b on a.wo_master_dtl_proc_id = b.wo_master_dtl_proc_id
		//                         JOIN laundry_data_wo c on a.wo_no=c.wo_no
		//                         JOIN laundry_role_wo_master g on a.role_wo_master_id=g.role_wo_master_id
		//                         JOIN laundry_role_wo d on a.role_wo_master_id=d.role_wo_master_id
		//                         LEFT JOIN laundry_role_dtl_wo e ON d.role_wo_id = e.role_wo_id
		//                         LEFT JOIN (select event_type,lot_id from laundry_lot_event where lot_no = '".strtoupper($_GET['lot'])."' order by event_id DESC Limit 1) f on b.lot_id=f.lot_id 
		//                         ",
		//                         "a.wo_master_dtl_proc_id,
		//                          a.wo_master_dtl_proc_status,
		//                          a.wo_no,
		//                          a.buyer_id,
		//                          a.garment_colors,
		//                          a.role_wo_master_id,
		//                          a.status_hold,
		//                          a.color_wash,
		//                          b.lot_qty as lotqty,
		//                          b.lot_no as lotno,
		//                          b.lot_createdby as createdby,
		//                          b.lot_status,
		//                          b.lot_id as id,
		//                          b.combine_hold,
		//                          b.last_lot_from_combine,
		//                          c.buyer_id,
		//                          f.event_type,
		//                          g.type_receive,
		//                          to_char(a.ex_fty_date,'DD-MM-YYYY') as ex_fty_date,
		//                          a.ex_fty_date as ex_fty_date_asli
		//                         ",
		//                         "lot_no = '".strtoupper($_GET['lot'])."' $wherecmt") as $cmt) {}
		$tablerole = "  laundry_role_child a 
						join laundry_role_wo b on a.role_wo_id=b.role_wo_id
						left join laundry_role_dtl_wo c on a.role_dtl_wo_id=c.role_dtl_wo_id
						left join laundry_master_process d on c.master_process_id=d.master_process_id
						left join (
								SELECT
									max(process_type) as process_type,
									role_wo_id
								FROM
									laundry_process 
								WHERE
									role_wo_master_id = '".$rolewomasterid."'  
									AND master_type_process_id NOT BETWEEN 4 
									AND 5 
									AND lot_no = '".$role1lot."'
								GROUP BY role_wo_id
						) AS e ON e.role_wo_id = a.role_wo_id
						left join (
								SELECT u.process_type,v.role_dtl_wo_id,u.change_process
		 						FROM laundry_process u 
		 							 JOIN (
											SELECT
												max(process_id) as process_id,
												role_dtl_wo_id
											FROM
												laundry_process 
											WHERE
												role_wo_master_id = '".$rolewomasterid."' 
												AND master_type_process_id BETWEEN 4 
												AND 5 
												AND lot_no = '".$role1lot."'
											GROUP BY
											  	role_dtl_wo_id
									 ) as v ON u.process_id=v.process_id
						) AS f ON f.role_dtl_wo_id = a.role_dtl_wo_id 
						";

		$fieldrole = "a.role_wo_id,
					  a.role_dtl_wo_id,
					  a.role_wo_seq,
					  a.role_dtl_wo_seq,
					  b.role_wo_name,
					  b.master_type_process_id,
					  d.master_process_name,
					  e.process_type as role_process,
					  f.process_type as dtl_process,
					  f.change_process";
			
		$whererole = "a.lot_no = '".$role1lot."'";

//cek jika berada di keranjang combine lot 
	if($cmt['id'] <> ''){
		$cekoncombinekeranjang = $con->selectcount("laundry_lot_number_keranjang","lot_id","lot_id = '".$cmt['id']."'");
	} else {
		$cekoncombinekeranjang = 0;
	}
// =======================================

}

// mendapatkan role yang saat ini / akan di process ======================================================
$tablerole1 = " laundry_role_child a 
				JOIN laundry_role_wo b ON a.role_wo_id=b.role_wo_id 
   			    LEFT JOIN laundry_role_dtl_wo c ON a.role_dtl_wo_id = c.role_dtl_wo_id
				LEFT JOIN laundry_master_process d ON c.master_process_id = d.master_process_id
				LEFT JOIN (
						SELECT u.process_type,v.role_dtl_wo_id,u.change_process,u.machine_id
 						FROM laundry_process u 
 							 JOIN (
									SELECT
										max(process_id) as process_id,
										role_dtl_wo_id
									FROM
										laundry_process 
									WHERE
										role_wo_master_id = '".$rolewomasterid."' 
										AND master_type_process_id BETWEEN 4 
										AND 5 
										AND lot_no = '".$role1lot."'
									GROUP BY
									  	role_dtl_wo_id
							 ) as v ON u.process_id=v.process_id
				) AS e ON e.role_dtl_wo_id = c.role_dtl_wo_id";
$fieldrole1 = " a.role_wo_id,
				a.role_dtl_wo_id,
			    a.role_wo_seq,
			    a.role_dtl_wo_seq,
			    b.role_wo_name,
			    b.role_wo_name_seq,
			    b.role_wo_master_id,
			    d.master_process_name,
			  	e.process_type as dtl_process,
				a.lot_no,
				b.role_wo_time,
				c.role_dtl_wo_time,
				b.master_type_process_id,
				d.master_process_usemachine,
				d.master_process_usemultimachine,
				d.master_process_split_lot,
				d.master_process_combine_lot,
				d.master_process_id,
				COALESCE(e.change_process,0) as change_process,
				e.machine_id
			  ";

if ($_GET['typelot'] == 'A'){
	$whererole1 = "a.lot_no = '".strtoupper($_GET['lot'])."' AND 
				   a.role_wo_master_id = '".$rolewomasterid."' AND 
				   concat(a.lot_no,'_',a.role_wo_id,'_',c.master_process_id) NOT IN 
				   	(select concat(lot_no,'_',role_wo_id,'_',master_process_id) from laundry_process where process_type = 4)";
} else {
	$whererole1 =  "a.role_wo_master_id = '".$rolewomasterid."' AND a.lot_no = '".strtoupper($_GET['lot'])."' 
					-- AND	concat(a.lot_no,'_',a.role_wo_id,'_',c.master_process_id) NOT IN 
					-- (select concat(lot_no,'_',role_wo_id,'_',master_process_id) from laundry_process where process_type = 4) 
					
					AND 
					CONCAT(c.master_process_id,'_',a.role_wo_id,'_',a.lot_no) NOT IN  
						(
							select CONCAT(COALESCE(master_process_id,0),'_',role_wo_id,'_',lot_no) as master_process_id
							from laundry_process 
							where role_wo_master_id = '".$rolewomasterid."' and process_type = 4
							GROUP BY master_process_id,role_wo_id,lot_no
						) 
					AND 
					CONCAT(b.master_type_process_id,'_',a.role_wo_id,'_',a.lot_no) NOT IN  
						(
							select CONCAT(COALESCE(master_type_process_id,0),'_',role_wo_id,'_',lot_no) as master_type_process_id
							from laundry_process 
							where role_wo_master_id = '".$rolewomasterid."' and master_type_process_id NOT between 4 and 5 and process_type = 4
						)
					";
}

$selrole1 = $con->select($tablerole1,$fieldrole1,$whererole1,"a.role_child_dateprocess,a.role_wo_seq,a.role_dtl_wo_seq","1");
// echo "select $fieldrole1 from $tablerole1 where $whererole1 ORDER by a.role_child_dateprocess,a.role_wo_seq,a.role_dtl_wo_seq limit 1";
foreach ($selrole1 as $role1) {}
// ==================================================================================================

// mendapatkan qty terakhir dari process sebelumnya =================================================
foreach ($con->select("laundry_process","process_id,role_wo_id,role_dtl_wo_id,process_qty_good","lot_no = '".strtoupper($_GET['lot'])."' and role_wo_master_id = '".$rolewomasterid."'","process_id DESC","1") as $qtydesc) {};

//====================================================================================================

//cek status approve dari sequence yg dijalankan
$selstatus = $con->select("(SELECT COALESCE( role_wo_status, 1 ) AS app,b.role_wo_master_id,C.role_wo_id FROM laundry_receive A 
	JOIN laundry_wo_master_dtl_proc b ON A.wo_master_dtl_proc_id = b.wo_master_dtl_proc_id
	JOIN ( SELECT role_wo_master_id, role_wo_status, role_wo_id FROM laundry_role_wo WHERE role_wo_status = 0 GROUP BY role_wo_master_id, role_wo_status, role_wo_id ) AS C ON b.role_wo_master_id = C.role_wo_master_id 
	WHERE rec_no = '".strtoupper($_GET['lot'])."' and role_wo_id = '".$role1['role_wo_id']."'
	UNION
	SELECT COALESCE ( role_wo_status, 1 ) AS app, b.role_wo_master_id, C.role_wo_id FROM laundry_lot_number	A 
	JOIN laundry_wo_master_dtl_proc b ON A.wo_master_dtl_proc_id = b.wo_master_dtl_proc_id
	JOIN ( SELECT role_wo_master_id, role_wo_status, role_wo_id FROM laundry_role_wo WHERE role_wo_status = 0 GROUP BY role_wo_master_id, role_wo_status, role_wo_id ) AS C ON b.role_wo_master_id = C.role_wo_master_id 
	WHERE lot_no = '".strtoupper($_GET['lot'])."' and role_wo_id = '".$role1['role_wo_id']."') as asi","*","");
foreach ($selstatus as $status) {}
	//echo $status['role_wo_id'];
// ==============================================

//cek status hold dari sequence yang dijalankan
foreach($con->select("laundry_process","process_type","lot_no = '".strtoupper($_GET['lot'])."'","process_id DESC","1") as $cekprocess){}
// ============================================

//process name change process
	if($role1[change_process] <> ''){
foreach ($con->select("laundry_master_process","master_process_name","master_process_id = '$role1[change_process]'") as $cpnm) {};
	}
// =============================

	//untuk keterangan swal jika permission error.
	if ($role1['master_process_name'] == ''){
		$namaprocess = $role1['role_wo_name'];
	} else {
		$namaprocess = $role1['master_process_name'];
	}

if (count($cmt['wo_no']) == 0){
	echo "<script>
	swal({
		 icon: 'info',
		 title: 'Data Not Found',
		 text: 'Check Your Lot Number',
		 timer: 2000,
		});
		$('#lot_no').val('');
		$('#lot_no').focus();
	</script>";
} 
else if ($status['role_wo_id'] != '') {
	echo "<script>
	swal({
		 icon: 'error',
		 title: 'Planning Must be Approve',
		 text: 'Please Approve your Planning',
		 footer: '<a href>Why do I have this issue?</a>'
		});
		$('#lot_no').val('');
		$('#lot_no').focus();
	</script>";
}
else if (count($cmt['wo_no']) > 0 && $role1['master_type_process_id'] == '' ){
	echo "<script>
	swal({
		 icon: 'info',
		 title: 'Sequence Done',
		 text: 'Please Check Lot No Sequence Process',
		 footer: '<a href>Why do I have this issue?</a>'
		});
		$('#lot_no').val('');
		$('#lot_no').focus();
	</script>";
}
//jika time pada sequence process kosong, harus di isi dari edit sequence process
else if (count($cmt['wo_no']) > 0 && $role1['role_wo_time'] == '' && $role1['master_type_process_id'] != '6' ){
	echo "<script>
	swal({
		 icon: 'error',
		 title: 'Time is Empty',
		 text: 'Please Check Time on Sequence Process',
		 timer: 3000
		});
		$('#lot_no').val('');
		$('#lot_no').focus();
	</script>";
} 
// jika lot status sudah break
else if ($cmt['lot_status'] == 0){
	echo "<script>
	swal({
		 icon: 'warning',
		 title: 'Lot Number is Break',
		 text: 'Check on View Data',
		 timer : 3000
		});
		$('#lot_no').val('');
		$('#lot_no').focus();
	</script>";
} 
// jika lot status belum keluar dari lot making
else if ($cmt['lot_status'] == 3){
	echo "<script>
	swal({
		 icon: 'error',
		 title: 'Please End Process Lot Making',
		 text: 'Back to Lot making End Process',
		 timer : 6000
		});
		$('#lot_no').val('');
		$('#lot_no').focus();
	</script>";
} 
//jika wo sedang di hold dari sequence master
else if ($cmt['status_hold'] == 1 && ($cekprocess['process_type'] == '1' || $cekprocess['process_type'] == '4' || $cekprocess['process_type'] == '')){
	echo "
	<script>
		swal({
			icon: 'warning',
			title: 'Sequence WO On Hold',
			text: 'Check on Sequence WO Colors Ex fty date Status',
			timer: 3000
		});
		$('#lot_no').val('');
		$('#lot_no').focus();
	</script>";
}
//jika sedang di hold combine
else if ($cmt['combine_hold'] == 1){
	echo "<script>
	swal({
		 icon: 'warning',
		 title: 'Lot Number on Hold Combine',
		 text: 'Check on Combine',
		 footer: '<a href>Why do I have this issue?</a>'
		});
	</script>";
}
//jika sedang berada di process combine, atau sudah di pilih untuk siap di combine
else if ($cekoncombinekeranjang > 0){
	echo "<script>
	swal({
		 icon: 'warning',
		 title: 'Lot Number on Combine Process',
		 text: 'Please Delete From Combine Process',
		 footer: '<a href>Why do I have this issue?</a>'
		});
	</script>";
}
else {
	$data = $rolewomasterid.'_'.
			$role1['role_wo_id'].'_'.
			$cmt['wo_master_dtl_proc_id'].'_'.
			$role1['master_process_id'].'_'.
			$role1['master_process_usemachine'];

?>

		<div class="form-group"  style="font-size: 12px;">
		    <label class="col-md-2 control-label" for="profileLastName"><b>Date :</b></label>
				<div class="col-md-4">
					<?php echo date('d-m-Y'); ?>
					<input id="datedetail" name="datedetail" value="<?php echo date('Y-m-d'); ?>" type="hidden">
				</div>
			<label class="col-md-2 control-label" for="profileLastName"><b>Lot Number :</b></label>
				<div class="col-md-4">
					<?php echo $cmt['lotno'];?>
					<input type="hidden" id="lot_no_process" name="lot_no_process" value="<?php echo $cmt[lotno]; ?>">
				</div>
		</div>
		<div class="form-group"  style="font-size: 12px;">
		    <label class="col-md-2 control-label" for="profileLastName"><b>WO No :</b></label>
				<div class="col-md-4">
					<?php echo $cmt['wo_no'];?>
					<input type="hidden" id="wo_no_process" name="wo_no_process" value="<?php echo $cmt[wo_no]; ?>">
				</div>
			<label class="col-md-2 control-label" for="profileLastName"><b>Color QR :</b></label>
				<div class="col-md-4">
					<?php echo $cmt['garment_colors'];?>
					<input type="hidden" id="garment_colors_process" name="garment_colors_process" value="<?php echo $cmt[garment_colors]; ?>">
				</div>
		</div>
		<div class="form-group"  style="font-size: 12px;">
		    <label class="col-md-2 control-label" for="profileLastName"><b>Ex Fty Date :</b></label>
				<div class="col-md-4">
					<?php echo $cmt['ex_fty_date'];?>
				</div>
			<label class="col-md-2 control-label" for="profileLastName"><b>Color Wash :</b></label>
				<div class="col-md-4">
					<?php echo $cmt['color_wash'];?>
				</div>
		</div>
		<div class="form-group"  style="font-size: 12px;">
		    <label class="col-md-2 control-label" for="profileLastName"><b>Buyer :</b></label>
				<div class="col-md-4">
					<?php echo $cmt['buyer_id'];?>
				</div>
				<label class="col-md-2 control-label" for="profileLastName"><b>Qty :</b></label>
				<div class="col-md-4">
					<?php if ($qtydesc['process_qty_good'] != ''){ 
							echo $qtydesc['process_qty_good']; 
							$qtyprocess = $qtydesc['process_qty_good'];
						}
						  else { echo $cmt['lotqty']; 
						  	$qtyprocess = $cmt['lotqty'];
						}
					?>
					<input type="hidden" id="qty_process" name="qty_process" value="<?php echo $qtyprocess; ?>">
				</div>
		</div>
		<div class="form-group"  style="font-size: 12px;">
			<label class="col-md-2 control-label" for="profileLastName">&nbsp;</label>
				<div class="col-md-4">
					&nbsp;
				</div>
			<label class="col-md-2 control-label" for="profileLastName"><b>Time Planning:</b></label>
				<div class="col-md-4">
					 <?php
					 	if ($role1['role_dtl_wo_time'] != ''){
	                   		echo "<b>".$role1['role_dtl_wo_time']."</b>";
	                    	echo "<input id='time' name='time' value='".$role1['role_dtl_wo_time']."' type='hidden'>";
	                    	$timeexecute = $role1['role_dtl_wo_time'];
		              	} else if ($role1['role_dtl_wo_time'] == '' && $role1['role_dtl_wo_id'] != '') {
		              		echo "Time Not Set";
		              		echo "<input id='time' name='time' value='0' type='hidden'>";
		              		$timeexecute = 0;
		              	}else {
		              		echo "<b>".$role1['role_wo_time']."</b>";
	                    	echo "<input id='time' name='time' value='".$role1['role_wo_time']."' type='hidden'>";
	                    	$timeexecute = $role1['role_wo_time'];
		              	}
		              ?>
		              Minutes
				</div>
		</div>
		<div class="form-group"  style="font-size: 12px;">
		    <label class="col-md-2 control-label" for="profileLastName"><b>Process:</b></label>
				<div class="col-md-4">
					<?php 
						//select Role
						$selrole = $con->select($tablerole,$fieldrole,$whererole,"a.role_child_dateprocess,a.role_wo_seq,a.role_dtl_wo_seq"); 
						//echo "select $fieldrole from $tablerole where $whererole order by role_child_dateprocess,role_wo_seq,role_dtl_wo_seq";
						foreach ($selrole as $role) {
						
						 	//untuk separator
						 	if ($role['master_process_name'] == ''){
										$separator = "";
							} else {
										$separator = " - ";
							}

						 	// jika list process sudah masuk ke database laundry process (artinya sudah di process)
						 	if($role['role_dtl_wo_id'] != '') {
						
								 		
								 	if ($role['dtl_process'] == '1'){
										if($role1['change_process'] != ''){
											echo "<b style='color:#FFD700'>".$role['role_wo_name'].$separator.$role['master_process_name']."</b>&ensp;<i class='fa fa-ban'></i><br>";
											echo "<input type='hidden' id='cp' name='cp' value='$role1[change_process]'><span><b>Changed to : $cpnm[master_process_name]</b><i>&ensp;IN</i></span><br>";
										} else {
											echo "<b style='color:#FFD700'>".$role['role_wo_name'].$separator.$role['master_process_name']."</b><i>&ensp;IN</i><br>";
										}
									} else if ($role['dtl_process'] == '2'){
										if($role1['change_process'] != ''){
											echo "<b style='color:#1E90FF'>".$role['role_wo_name'].$separator.$role['master_process_name']."</b>&ensp;<i class='fa fa-ban'></i><br>";
											echo "<input type='hidden' id='cp' name='cp' value='$role1[change_process]'><span><b>Changed to : $cpnm[master_process_name]</b><i>&ensp;On Progress</i></span><br>";
										} else {
											echo "<b style='color:#1E90FF'>".$role['role_wo_name'].$separator.$role['master_process_name']."</b><i>&ensp;On Progress</i><br>";
										}
									} else if ($role['dtl_process'] == '3'){
										if($role1['change_process'] != ''){
											echo "<b style='color:#4B0082'>".$role['role_wo_name'].$separator.$role['master_process_name']."</b>&ensp;<i class='fa fa-ban'></i><br>";
											echo "<input type='hidden' id='cp' name='cp' value='$role1[change_process]'><span><b>Changed to : $cpnm[master_process_name]</b><i>&ensp;Broken Machine</i></span><br>";
										} else {
											echo "<b style='color:#4B0082'>".$role['role_wo_name'].$separator.$role['master_process_name']."</b><i>&ensp;Broken Machine</i><br>";
										}
									} else if ($role['dtl_process'] == '4'){
										if($role['change_process'] != ''){
											foreach ($con->select("laundry_master_process","master_process_name","master_process_id = '$role[change_process]'") as $cpkm) {};
											echo "<b style='color:#8B0000'>".$role['role_wo_name'].$separator.$role['master_process_name']."</b>&ensp;<i class='fa fa-ban'></i><br>";
											echo "<input type='hidden' id='cp' name='cp' value='$role1[change_process]'><span><b>Changed to : $cpkm[master_process_name]</b>&ensp;<i style='color:#00FF00' class='fa fa-check'></i></span><br>";
										} else {
											echo "<b style='color:#8B0000'>".$role['role_wo_name'].$separator.$role['master_process_name']."</b>&ensp;<i style='color:#00FF00' class='fa fa-check'></i><br>";
										}
									} else {
										if ($role1['role_dtl_wo_id'] == $role['role_dtl_wo_id']) {
											echo $role['role_wo_name'].$separator.$role['master_process_name']."&ensp;<i style='color:#FF4500'>Ready</i><a href='javascript:void(0)' data-toggle='modal' data-target='#funModal' id='mod' onClick=modelchangeprocess('".$cmt[lotno]."',$role[master_type_process_id]) class='label label-primary' style='float:right'>change</a><br>";
											echo "<input type='hidden' id='cp' name='cp' value='$role1[change_process]'><span id='cpr'></span>";
										} else {
											echo $role['role_wo_name'].$separator.$role['master_process_name']."<br>";
										}
									}

						 	} 
						 	// jika list process belum di proses sama sekali
						 	else {
						 		//untuk process selain dry process dan wet jika sudah dilakukan process maka akan tercentang.
						 		if ($role['role_wo_id'] != ''){
									
						 			if($role['role_process'] == '4') {
										echo "<b style='color:#8B0000'>".$role['role_wo_name'].$separator.$role['master_process_name']."</b>&ensp;<i style='color:#00FF00' class='fa fa-check'></i><br>";
									} else if($role['role_process'] == '1') {
										echo "<b style='color:#FFD700'>".$role['role_wo_name'].$separator.$role['master_process_name']."</b>&ensp;<i>&ensp;IN</i><br>";
									}
									else {
										echo $role['role_wo_name'].$separator.$role['master_process_name']."<br>";
									}
								}
								else {
						 			echo $role['role_wo_name'].$separator.$role['master_process_name']."<br>";
						 		}
						 	}									
						}
					?>
				</div>
				<div class="col-md-6" id="tampilmachine" >
					<?php 
						if ($role1['master_type_process_id'] == 4 || $role1['master_type_process_id'] == 5) {	
							include "tampilmachine.php";	
						}
					?>
				</div>
				<br><br><br>
				
		</div>
		<div id="buttonsubmit">
			<?php include "buttonsubmit.php"; ?>
		</div>
		
		<input type="hidden" name="machine-input" id="machine-input" value=""/>
		<input type="hidden" id="machine_id" name="machine_id">
		<input type="hidden" id="master_type_process" name="master_type_process">
		<input type="hidden" id="type_lot" name="type_lot" value="<?php echo $_GET[typelot]; ?>">
		<input type="hidden" id="data_process" name="data_process" value="<?php echo $data; ?>">
		<input type="hidden" id="processtype" name="processtype" value="<?=$role1[dtl_process]?>">
		<input type="hidden" name="process-status" id="process-status" value="">
		<input type="hidden" name="wo-master-dtl-proc-id" id="wo-master-dtl-proc-id" value="<?=$cmt[wo_master_dtl_proc_id]?>">
		<input id='rolewoid' name='rolewoid' value='<?=$role1[role_wo_id]?>' type='hidden'>
	    <input id='roledtlwoid' name='roledtlwoid' value='<?=$role1[role_dtl_wo_id]?>' type='hidden'>
     	<input id='masterprocessid' name='masterprocessid' value='<?=$role1[master_process_id]?>' type='hidden'>
      	<input id='mastertypeprocessid' name='mastertypeprocessid' value='<?=$role1[master_type_process_id]?>' type='hidden'>
        <input id='rolewonameseq' name='rolewonameseq' value='<?=$role1[role_wo_name_seq]?>' type='hidden'>
        <input id='role-wo-master-id' name='role-wo-master-id' value='<?=$role1[role_wo_master_id]?>' type='hidden'>
	            
<?php 
} 

?>
<script type="text/javascript">
	if($('#processtype').val() == ''){
		$('#sender').focus();
		
		$("#sender").keyup(function(event){
	    if(event.keyCode == 13){// => Karakter enter dikenali sebagai angka 13
	    	if($('#sender').val() == $('#lot_no').val()){
	    		swall("check your name sender!!",$('#sender').val(),"error","2000");
	    		$('#sender').val('');
	    		$('#sender').focus();
	    	} else { 
	       		$("#receiver").focus();
	    	}
	    } });

	    $("#receiver").keyup(function(event){
	    if(event.keyCode == 13){// => Karakter enter dikenali sebagai angka 13
	       if($('#receiver').val() == $('#lot_no').val()){
	    		swall("check your name receiver!!",$('#receiver').val(),"error","1000");
	    		$('#receiver').val('');
	    		$('#receiver').focus();
	    	} else { 
	       		$("#inbutton").focus();
	    	}
	    } });
	} else {
		$('#foreman').focus();
		
		$("#foreman").keyup(function(event){
	    if(event.keyCode == 13){// => Karakter enter dikenali sebagai angka 13
	    	if($('#foreman').val() == $('#lot_no').val()){
	    		swall("check your name foreman!!",$('#foreman').val(),"error","2000");
	    		$('#foreman').val('');
	    		$('#foreman').focus();
	    	} else { 
	       		$("#operator").focus();
	    	}
	       
	    } });

		$("#operator").keyup(function(event){
	    if(event.keyCode == 13){// => Karakter enter dikenali sebagai angka 13
	    	if($('#operator').val() == $('#lot_no').val()){
	    		swall("check your name operator!!",$('#operator').val(),"error","2000");
	    		$('#operator').val('');
	    		$('#operator').focus();
	    	} else { 
	       		$("#machinecode").focus();
	    	}
	       
	    } });

	    $("#machinecode").keyup(function(event){
		    if(event.keyCode == 13){// => Karakter enter dikenali sebagai angka 13
		    	//alert($('#machinecode').val());
		    	$.ajax({
		    			type: 'GET',
						url:  "apps/Process/Process/cekmachine.php?code="+$('#machinecode').val()+"&dtlprocess="+$('#cekdtlprocess').val()+"&lotno="+$('#lot_no_process').val()+"&mtype="+$('#mastertypeprocessid').val(),
						success: function(mach) {
								expmach=mach.split("_");
								if (expmach[0] == 10000000){
									swal({
										title: "Machine On Process",
										text: "Lot No : "+expmach[1]+"\n Wo No : "+expmach[2]+"\n Colors : "+expmach[3]+" / "+expmach[4],
										icon: "info",
										//timer: 3000
									});
									 $("#machinecode").val('');
									 $("#machinename").val('');
									 $("#machineid").val('');
									  $("#machinecode").focus();
								} else if (expmach[0] == 20000000){
									swal({
										title: "Machine Not Found",
										text: "Please Check machine code",
										icon: "info",
										timer: 3000
									});
									 $("#machinecode").val('');
									 $("#machinename").val('');
									 $("#machineid").val('');
									 $("#machinecode").focus();
								} else if (expmach[0] == 30000000){
									swal({
										title: "Machine Not Match",
										text: "Please Check machine code and Lot Number",
										icon: "info",
										timer: 3000
									});
									 $("#machinecode").val('');
									 $("#machinename").val('');
									 $("#machineid").val('');
									 $("#machinecode").focus();
								} else if(expmach[0] == 40000000){
									swal({
										title: "Machine does Not Match the Process",
										text: "Please Check machine code & Process",
										icon: "info",
										timer: 3000
									});
									 $("#machinecode").val('');
									 $("#machinename").val('');
									 $("#machineid").val('');
									 $("#machinecode").focus();
								} else {
									$('#machineid').val(expmach[1]);
									$('#machinename').val(expmach[0]);
								}
						}
				});      
	    	} 
		});

	}

</script>