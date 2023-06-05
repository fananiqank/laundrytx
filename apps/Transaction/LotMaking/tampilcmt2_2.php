
<?php 
session_start();
include '../../../funlibs.php';
$con = new Database;

$cmt_no = $con->searchseqcmt($_GET['id']);
$no=1;


if ($_GET['typelot'] == 'A'){
	$role1lot = $_GET['lot'];
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
		                         b.rec_qty as lotqty,
		                         b.rec_no as lotno,
		                         b.rec_createdby as createdby,
		                         b.rec_id as id,
		                         b.rec_break_status as lot_status,
		                         a.status_hold,
		                         to_char(a.ex_fty_date,'DD-MM-YYYY') as ex_fty_date
		                        ",
		                        "rec_no = '".$_GET['lot']."'
		                         ") as $cmt) {}
	
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
									role_wo_master_id = '".$cmt['role_wo_master_id']."'  
									AND master_type_process_id NOT BETWEEN 4 
									AND 5 
									AND lot_no = '".$_GET['lot']."'
								GROUP BY role_wo_id
						) AS e ON e.role_wo_id = a.role_wo_id
						left join (
								SELECT
									max(process_type) as process_type,
									role_dtl_wo_id
								FROM
									laundry_process 
								WHERE
									role_wo_master_id = '".$cmt['role_wo_master_id']."' 
									AND master_type_process_id BETWEEN 4 
									AND 5 
									AND lot_no = '".$_GET['lot']."'
								GROUP BY
								  role_dtl_wo_id
						) AS f ON f.role_dtl_wo_id = a.role_dtl_wo_id 
						";

		$fieldrole = "a.role_wo_id,
					  a.role_dtl_wo_id,
					  a.role_wo_seq,
					  a.role_dtl_wo_seq,
					  b.role_wo_name,
					  d.master_process_name,
					  e.process_type as role_process,
					  f.process_type as dtl_process";
			
		$whererole = "a.lot_no = '".$_GET['lot']."'";

		$tablerole1 = " laundry_role_child a 
				JOIN laundry_receive f ON a.lot_id=f.rec_id
				JOIN laundry_role_wo b ON a.role_wo_id=b.role_wo_id 
   			    LEFT JOIN laundry_role_dtl_wo c ON a.role_dtl_wo_id = c.role_dtl_wo_id
				LEFT JOIN laundry_master_process d ON c.master_process_id = d.master_process_id
				LEFT JOIN (
						SELECT
							max(process_type) as process_type,
							role_dtl_wo_id
						FROM
							laundry_process 
						WHERE
							role_wo_master_id = '".$cmt['role_wo_master_id']."' 
							AND master_type_process_id BETWEEN 4 
							AND 5 
							AND lot_no = '".$role1lot."'
						GROUP BY
						  	role_dtl_wo_id
				) AS e ON e.role_dtl_wo_id = c.role_dtl_wo_id";

		$fieldrole1 = " a.role_wo_id,
				a.role_dtl_wo_id,
			    a.role_wo_seq,
			    a.role_dtl_wo_seq,
			    a.lot_no,
			    a.role_child_id,
			    b.role_wo_name,
			    b.role_wo_name_seq,
			    b.role_wo_time,
			    b.master_type_process_id,
				c.role_dtl_wo_time,
			    d.master_process_name,
				d.master_process_usemachine,
				d.master_process_usemultimachine,
				d.master_process_split_lot,
				d.master_process_combine_lot,
				e.process_type as dtl_process,
				f.rec_break_status as lot_status
			  ";

} else {
	//pengecekan data.
	include "cekdata.php";
	//======
	
	//jika menggunakan Lot receive maka lot akan menggunakan $_GET[lot]
	if($parlot == ''){
		$role1lot = $_GET['lot'];
		$wherecmt = "";
	} else if ($_GET['typelot'] == 'W'){
		$role1lot = $_GET['lot'];
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
		                        LEFT JOIN (select event_type,lot_id from laundry_lot_event where lot_no = '".$_GET['lot']."' order by event_id DESC Limit 1) f on b.lot_id=f.lot_id 
		                        ",
		                        "a.wo_master_dtl_proc_id,
		                         a.wo_master_dtl_proc_status,
		                         a.wo_no,
		                         a.buyer_id,
		                         a.garment_colors,
		                         a.role_wo_master_id,
		                         a.status_hold,
		                         b.lot_qty as lotqty,
		                         b.lot_no as lotno,
		                         b.lot_createdby as createdby,
		                         b.lot_status,
		                         b.lot_id as id,
		                         b.lot_status,
		                         b.combine_hold,
		                         b.last_lot_from_combine,
		                         f.event_type,
		                         g.type_receive,
		                         to_char(a.ex_fty_date,'DD-MM-YYYY') as ex_fty_date
		                        ",
		                        "lot_no = '".$_GET['lot']."' $wherecmt") as $cmt) {}
		
	
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
									role_wo_master_id = '".$cmt['role_wo_master_id']."'  
									AND master_type_process_id NOT BETWEEN 4 
									AND 5 
									AND lot_no = '".$role1lot."'
								GROUP BY role_wo_id
						) AS e ON e.role_wo_id = a.role_wo_id
						left join (
								SELECT
									max(process_type) as process_type,
									role_dtl_wo_id
								FROM
									laundry_process 
								WHERE
									role_wo_master_id = '".$cmt['role_wo_master_id']."' 
									AND master_type_process_id BETWEEN 4 
									AND 5 
									AND lot_no = '".$_GET['lot']."'
								GROUP BY
								  role_dtl_wo_id
						) AS f ON f.role_dtl_wo_id = a.role_dtl_wo_id 
						";

		$fieldrole = "a.role_wo_id,
					  a.role_dtl_wo_id,
					  a.role_wo_seq,
					  a.role_dtl_wo_seq,
					  b.role_wo_name,
					  d.master_process_name,
					  e.process_type as role_process,
					  f.process_type as dtl_process";
			
		$whererole = "a.lot_no = '".$_GET['lot']."'";

		$tablerole1 = " laundry_role_child a 
				JOIN laundry_lot_number f ON a.lot_id=f.lot_id
				JOIN laundry_role_wo b ON a.role_wo_id=b.role_wo_id 
   			    LEFT JOIN laundry_role_dtl_wo c ON a.role_dtl_wo_id = c.role_dtl_wo_id
				LEFT JOIN laundry_master_process d ON c.master_process_id = d.master_process_id
				LEFT JOIN (
						SELECT
							max(process_type) as process_type,
							role_dtl_wo_id
						FROM
							laundry_process 
						WHERE
							role_wo_master_id = '".$cmt['role_wo_master_id']."' 
							AND master_type_process_id BETWEEN 4 
							AND 5 
							AND lot_no = '".$role1lot."'
						GROUP BY
						  	role_dtl_wo_id
				) AS e ON e.role_dtl_wo_id = c.role_dtl_wo_id";

		$fieldrole1 = " a.role_wo_id,
				a.role_dtl_wo_id,
			    a.role_wo_seq,
			    a.role_dtl_wo_seq,
			    a.lot_no,
			    a.role_child_id,
			    b.role_wo_name,
			    b.role_wo_name_seq,
			    b.master_type_process_id,
			    b.role_wo_time,
				c.role_dtl_wo_time,
			    d.master_process_name,
			    d.master_process_usemachine,
				d.master_process_usemultimachine,
				d.master_process_split_lot,
				d.master_process_combine_lot,
			  	e.process_type as dtl_process,	
				f.lot_status as lot_status
			  ";
//cek jika berada di keranjang combine lot 
	$cekoncombinekeranjang = $con->selectcount("laundry_lot_number_keranjang","lot_id","lot_id = '".$cmt['id']."'");

// =======================================

}

// mendapatkan role yang saat ini / akan di process ======================================================

if ($_GET['typelot'] == 'A'){
	$whererole1 = "a.lot_no = '".$_GET['lot']."' AND 
				   a.role_wo_master_id = '".$cmt['role_wo_master_id']."' AND 
				   concat(a.lot_no,'_',a.role_wo_id,'_',c.master_process_id) NOT IN 
				   	(select concat(lot_no,'_',role_wo_id,'_',master_process_id) from laundry_process where process_type = 4)";
} else {
	$whererole1 =  "a.role_wo_master_id = '".$cmt['role_wo_master_id']."' AND a.lot_no = '".$_GET['lot']."' AND
					concat(a.lot_no,'_',a.role_wo_id,'_',c.master_process_id) NOT IN 
					(select concat(lot_no,'_',role_wo_id,'_',master_process_id) from laundry_process where process_type = 4) 
					AND 
					CONCAT(c.master_process_id,'_',a.role_wo_id,'_',a.lot_no) NOT IN  
						(
							select CONCAT(COALESCE(master_process_id,0),'_',role_wo_id,'_',lot_no) as master_process_id
							from laundry_process 
							where role_wo_master_id = '".$cmt['role_wo_master_id']."' and process_type = 4
							GROUP BY master_process_id,role_wo_id,lot_no
						) 
					AND 
					CONCAT(b.master_type_process_id,'_',a.role_wo_id,'_',a.lot_no) NOT IN  
						(
							select CONCAT(COALESCE(master_type_process_id,0),'_',role_wo_id,'_',lot_no) as master_type_process_id
							from laundry_process 
							where role_wo_master_id = '".$cmt['role_wo_master_id']."' and master_type_process_id NOT between 4 and 5 and process_type = 4
						)
					";
}

$selrole1 = $con->select($tablerole1,$fieldrole1,$whererole1,"a.role_child_dateprocess,a.role_wo_seq,a.role_dtl_wo_seq","1");
 //echo "select $fieldrole1 from $tablerole1 where $whererole1 ORDER by a.role_wo_seq,a.role_dtl_wo_seq limit 1";
foreach ($selrole1 as $role1) {}
// ==================================================================================================

// mendapatkan qty terakhir dari process sebelumnya =================================================
foreach ($con->select("(
						SELECT DISTINCT
							b.master_process_id,
							e.process_qty_good,
							A.role_wo_seq,
							b.role_dtl_wo_seq
						FROM
							laundry_role_wo	A 
							LEFT JOIN laundry_role_dtl_wo b ON A.role_wo_id = b.role_wo_id
							LEFT JOIN laundry_master_process C ON b.master_process_id = C.master_process_id
							LEFT JOIN ( SELECT role_dtl_wo_id, process_qty_good,lot_no,
										role_wo_id FROM laundry_process WHERE role_wo_master_id = '".$cmt['role_wo_master_id']."' AND lot_no = '".$_GET['lot']."' AND process_type = 4 ORDER BY process_type DESC ) AS e ON e.role_dtl_wo_id = b.role_dtl_wo_id 
						WHERE
							role_wo_master_id = '".$cmt['role_wo_master_id']."' AND 
							concat ( e.lot_no, '_', e.role_wo_id, '_', b.master_process_id ) IN ( SELECT concat ( lot_no, '_', role_wo_id, '_', master_process_id ) FROM laundry_process WHERE process_type = 4 ) 
						ORDER BY
								b.role_dtl_wo_seq DESC
					   ) as fo",
					"*","","role_wo_seq DESC","1") as $qtydesc) {};

//cek status approve dari sequence yg dijalankan
$selstatus = $con->select("laundry_receive a 
						   JOIN laundry_wo_master_dtl_proc b on a.wo_master_dtl_proc_id = b.wo_master_dtl_proc_id
						   JOIN (select role_wo_master_id,role_wo_status,role_wo_id from laundry_role_wo where role_wo_status = 0 
						   GROUP BY role_wo_master_id,role_wo_status,role_wo_id) as c
						    on b.role_wo_master_id = c.role_wo_master_id","COALESCE(role_wo_status,1) as app,b.role_wo_master_id,c.role_wo_id","rec_no = '".$_GET['lot']."' and role_wo_id = '".$role1['role_wo_id']."");
foreach ($selstatus as $status) {}
// ==============================================

//cek status hold dari sequence yang dijalankan
foreach($con->select("laundry_process","process_type","lot_no = '".$_GET['lot']."'","process_id DESC","1") as $cekprocess){}
// ============================================

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
					 timer:2000,
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
		 timer:2000,
		});
		$('#lot_no').val('');
		$('#lot_no').focus();
	</script>";
}
else if ($role1['lot_status'] == 0){
		echo "<script>
				swal({
					 icon: 'warning',
					 title: 'Lot already Receive',
					 text: 'Just Create Lot',
					 footer: '<a href>Why do I have this issue?</a>'
					});
					$('#lot_no').val('');
					$('#lot_no').focus();
			  </script>";
} 
else if ($role1['master_type_process_id'] != '2'){
	echo "<script>
	swal({
		 icon: 'error',
		 title: 'Not For Lot Making',
		 text: 'Please Check Sequence Process',
		 footer: '<a href>Why do I have this issue?</a>'
		});
		$('#lot_no').val('');
		$('#lot_no').focus();
	</script>";
} 
else if (count($cmt['wo_no']) > 0 && $role1['role_wo_time'] == ''){
	echo "<script>
	swal({
		 icon: 'error',
		 title: 'Time is Empty',
		 text: 'Please Check Time on Sequence Process',
		 footer: '<a href>Why do I have this issue?</a>'
		});
		$('#lot_no').val('');
		$('#lot_no').focus();
	</script>";
} 

else {
	$data = $cmt['role_wo_master_id'].'_'.
			$role1['role_wo_id'].'_'.
			$cmt['wo_master_dtl_proc_id'].'_'.
			$role1['master_process_id'].'_'.
			$role1['master_process_usemachine'].'_'.
			$role1['role_wo_name_seq'].'_'.
			$role1['role_child_id'];



	//jika lot receive sudah di terima lot making
		
	?>

			<div class="form-group"  style="font-size: 12px;">
			    <label class="col-md-2 control-label" for="profileLastName"><b>Date :</b></label>
					<div class="col-md-4">
						<?php echo date('d-m-Y'); ?>
						<input id="datedetail" name="datedetail" value="<?=date('Y-m-d');?>" type="hidden">
					</div>
				<label class="col-md-2 control-label" for="profileLastName"><b>Lot Number :</b></label>
					<div class="col-md-4">
						<?php echo $cmt['lotno'];?>
						<input type="hidden" id="lot_no_process" name="lot_no_process" value="<?=$cmt[lotno]?>">
					</div>
			</div>
			<div class="form-group"  style="font-size: 12px;">
			    <label class="col-md-2 control-label" for="profileLastName"><b>WO No :</b></label>
					<div class="col-md-4">
						<?php echo $cmt['wo_no'];?>
						<input type="hidden" id="wo_no_process" name="wo_no_process" value="<?=$cmt[wo_no]?>">
					</div>
				<label class="col-md-2 control-label" for="profileLastName"><b>Colors :</b></label>
					<div class="col-md-4">
						<?php echo $cmt['garment_colors'];?>
						<input type="hidden" id="garment_colors_process" name="garment_colors_process" value="<?=$cmt[garment_colors]?>">
					</div>
			</div>
			<div class="form-group"  style="font-size: 12px;">
			    <label class="col-md-2 control-label" for="profileLastName"><b>Ex Fty Date :</b></label>
					<div class="col-md-4">
						<?php echo $cmt['ex_fty_date'];?>
					</div>
				<label class="col-md-2 control-label" for="profileLastName"><b>Qty :</b></label>
					<div class="col-md-4">
						<?php 
							if ($qtydesc['process_qty_good'] != ''){ 
								echo $qtydesc['process_qty_good']; 
								$qtyprocess = $qtydesc['process_qty_good'];
							}
							  else { echo $cmt['lotqty']; 
							  	$qtyprocess = $cmt['lotqty'];
							}
						?>
						<input type="hidden" id="qty_process" name="qty_process" value="<?=$qtyprocess?>">
					</div>
			</div>
			<div class="form-group"  style="font-size: 12px;">
			    <label class="col-md-2 control-label" for="profileLastName"><b>Buyer :</b></label>
					<div class="col-md-4">
						<?php echo $cmt['buyer_id'];?>
					</div>
				<label class="col-md-2 control-label" for="profileLastName"><b>Time Planning:</b></label>
					<div class="col-md-4">
						 <?php
						 	if ($role1['role_dtl_wo_time'] != ''){
		                   		echo "<b>".$role1['role_dtl_wo_time']."</b>";
		                    	echo "<input id='time' name='time' value='$role1[role_dtl_wo_time]' type='hidden'>";
			              	} else {
			              		echo "<b>".$role1['role_wo_time']."</b>";
		                    	echo "<input id='time' name='time' value='$role1[role_wo_time]' type='hidden'>";
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
						//echo "select $fieldrole from $tablerole where $whererole order by role_wo_seq,role_dtl_wo_seq";
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
										echo "<b style='color:#FFD700'>".$role['role_wo_name'].$separator.$role['master_process_name']."</b>&ensp;IN<br>";
									} else if ($role['dtl_process'] == '2'){
										echo "<b style='color:#1E90FF'>".$role['role_wo_name'].$separator.$role['master_process_name']."</b>&ensp;On Progress<br>";
									} else if ($role['dtl_process'] == '3'){
										echo "<b style='color:#1E90FF'>".$role['role_wo_name'].$separator.$role['master_process_name']."</b>&ensp;On Progress<br>";
									} else if ($role['dtl_process'] == '4'){
										echo "<b style='color:#8B0000'>".$role['role_wo_name'].$separator.$role['master_process_name']."</b>&ensp;<i style='color:#00FF00' class='fa fa-check'></i><br>";
									} else {
										echo $role['role_wo_name'].$separator.$role['master_process_name']."<br>";
									}

						 	} 
						 	// jika list process belum di proses sama sekali
						 	else {
						 		//untuk process selain dry process dan wet jika sudah dilakukan process maka akan tercentang.
						 		if ($role['role_wo_id'] != ''){
									
						 			if($role['role_process'] == '4') {
										echo "<b style='color:#8B0000'>".$role['role_wo_name'].$separator.$role['master_process_name']."</b>&ensp;<i style='color:#00FF00' class='fa fa-check'></i><br>";
									} else {
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
			</div>
			
			<div class="form-group"  style="font-size: 12px;" align="center">
			   <a href="javascript:void(0)" class="btn btn-danger" onclick="cancel()">Cancel</a>
			   <a href="javascript:void(0)" class="btn btn-success" id="nextprocess" onclick="correct('<?=$_GET[lot]?>','<?=$_GET[user]?>','<?=$cmt[role_wo_master_id]?>','<?=$_GET[typelot]?>','<?=$role1[master_type_process_id]?>')">Correct</a>
			</div>
			
			<input class="form-control" name="machine-input" id="machine-input" value="" type="hidden" />
			<input type="hidden" class="form-control" id="machine_id" name="machine_id">
			<input type="hidden" class="form-control" id="master_type_process" name="master_type_process">
			<input type="hidden" class="form-control" id="type_lot" name="type_lot" value="<?=$_GET[typelot]?>">
			<input type="hidden" class="form-control" id="data_process" name="data_process" value="<?=$data?>">

<?php 	
	 
} 

?>
