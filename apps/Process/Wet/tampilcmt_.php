
<?php 
session_start();
include '../../../funlibs.php';
$con = new Database;

$cmt_no = $con->searchseqcmt($_GET['id']);
$no=1;

$selstatus = $con->select("laundry_receive a 
						   join laundry_wo_master_dtl_proc b
						   on a.wo_master_dtl_proc_id = b.wo_master_dtl_proc_id
						   left join 
						   (select role_wo_master_id,role_wo_status from laundry_role_wo where role_wo_status = 0 
						    GROUP BY role_wo_master_id,role_wo_status) as c
						    on b.role_wo_master_id = c.role_wo_master_id","ISNULL(role_wo_status,1) as app,b.role_wo_master_id","rec_no = '$_GET[lot]' and user_id = '$_GET[user]'");
foreach ($selstatus as $status) {}
$selcmt = $con->select("laundry_wo_master_dtl_proc a 
                        join laundry_receive b
                        on a.wo_master_dtl_proc_id = b.wo_master_dtl_proc_id
                        JOIN work_orders c on a.wo_no=c.wo_no
                        JOIN  laundry_role_wo d on a.role_wo_master_id=d.role_wo_master_id
                        LEFT JOIN laundry_role_dtl_wo e ON d.role_wo_id = e.role_wo_id
                        ",
                        "a.wo_master_dtl_proc_id,
                         a.wo_no,
                         a.buyer_id,
                         a.garment_colors,
                         a.role_wo_master_id,
                         b.rec_qty,
                         b.rec_no as lotno,
                         b.user_id,
                         c.buyer_name
                        ",
                        "rec_no = '$_GET[lot]'
                         and user_id = '$_GET[user]'");
// echo "select a.wo_master_dtl_proc_id,
//                          a.wo_no,
//                          a.buyer_id,
//                          a.garment_colors,
//                          a.role_wo_master_id,
//                          b.rec_qty,
//                          b.rec_no as lotno,
//                          b.user_id,
//                          c.buyer_name
//                          from laundry_wo_master_dtl_proc a 
//                         join laundry_receive b
//                         on a.wo_no = b.wo_no and a.garment_colors=b.garment_colors
//                         JOIN work_orders c on a.wo_no=c.wo_no
//                         JOIN  laundry_role_wo d on a.role_wo_master_id=d.role_wo_master_id and d.role_wo_status = 1
//                         LEFT JOIN laundry_role_dtl_wo e ON d.role_wo_id = e.role_wo_id
//                         where rec_no = '$_GET[lot]'
//                          and user_id = '$_GET[user]'";
foreach ($selcmt as $cmt) {}

$selrole1 = $con->select("laundry_role_wo
						A LEFT JOIN laundry_role_dtl_wo b ON A.role_wo_id = b.role_wo_id
						LEFT JOIN laundry_master_process C ON b.master_process_id = C.master_process_id
						LEFT JOIN (
						SELECT
							role_wo_id,
							master_process_id,
							role_dtl_wo_id,
							process_type,
							lot_no 
						FROM
							laundry_process 
						WHERE
							role_wo_master_id = '$cmt[role_wo_master_id]'  
							and lot_no = '$_GET[lot]'
							and user_id = '$_GET[user]'
							AND master_type_process_id > 3 
						GROUP BY
							role_dtl_wo_id,
							role_wo_id,
							master_process_id,
							process_type,
							lot_no 
						ORDER BY
							process_type DESC 
						) AS e ON e.role_dtl_wo_id = b.role_dtl_wo_id",
						"DISTINCT b.master_process_id,
						A.role_wo_name,
						master_process_name,
						A.role_wo_id,
						b.role_dtl_wo_id,
						e.lot_no AS dtl_lot_number,
						b.role_dtl_wo_time,
						A.role_wo_seq,
						b.role_dtl_wo_seq,
						a.master_type_process_id,
						c.master_process_usemachine,
						b.role_dtl_wo_time,
						a.role_wo_time",
						"role_wo_master_id = '$cmt[role_wo_master_id]' and 
						 concat(e.lot_no,'_',e.role_wo_id,'_',b.master_process_id) NOT IN (select concat(lot_no,'_',role_wo_id,'_',master_process_id) from laundry_process where process_type = 4)",
						"a.role_wo_seq,b.role_dtl_wo_seq",
						"1"); 
					// echo "SELECT
					//   	DISTINCT b.master_process_id,
					// 	A.role_wo_name,
					// 	master_process_name,
					// 	b.role_wo_id,
					// 	b.role_dtl_wo_id,
					// 	e.lot_no AS dtl_lot_number,
					// 	b.role_dtl_wo_time,
					// 	A.role_wo_seq,
					// 	b.role_dtl_wo_seq,
					// 	a.master_type_process_id
					// FROM
					// 	laundry_role_wo
					// 	A LEFT JOIN laundry_role_dtl_wo b ON A.role_wo_id = b.role_wo_id
					// 	LEFT JOIN laundry_master_process C ON b.master_process_id = C.master_process_id
					// 	LEFT JOIN (
					// 	SELECT
					// 		role_wo_id,
					// 		master_process_id,
					// 		role_dtl_wo_id,
					// 		process_type,
					// 		lot_no 
					// 	FROM
					// 		laundry_process 
					// 	WHERE
					// 		role_wo_master_id = '$cmt[role_wo_master_id]'  
					// 		and lot_no = '$_GET[lot]'
					// 		and user_id = '$_GET[user]'
					// 		AND master_type_process_id > 3 
					// 	GROUP BY
					// 		role_dtl_wo_id,
					// 		role_wo_id,
					// 		master_process_id,
					// 		process_type,
					// 		lot_no 
					// 	ORDER BY
					// 		process_type DESC 
					// 	) AS e ON e.role_dtl_wo_id = b.role_dtl_wo_id 
					// WHERE
					// 	role_wo_master_id = '$cmt[role_wo_master_id]' 
					// ORDER BY
					// 	A.role_wo_seq,
					// 	b.role_dtl_wo_seq ";

foreach ($selrole1 as $role1) {}
if (count($cmt['wo_no']) == 0){
	echo "<script>
	swal({
		 icon: 'info',
		 title: 'Data Not Found',
		 text: 'Check Your Lot Number and User Id',
		 footer: '<a href>Why do I have this issue?</a>'
		});
	</script>";
} 
else if ($status['app'] == 0) {
	echo "<script>
	swal({
		 icon: 'error',
		 title: 'Planning Must be Approve',
		 text: 'Please Approve your Planning',
		 footer: '<a href>Why do I have this issue?</a>'
		});
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
	</script>";
} 
else {
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
			</div>
	</div>
	<div class="form-group"  style="font-size: 12px;">
	    <label class="col-md-2 control-label" for="profileLastName"><b>CMT No :</b></label>
			<div class="col-md-4">
				<?php echo $cmt['wo_no'];?>
			</div>
		<label class="col-md-2 control-label" for="profileLastName"><b>Colors :</b></label>
			<div class="col-md-4">
				<?php echo $cmt['garment_colors'];?>
			</div>
	</div>
	<div class="form-group"  style="font-size: 12px;">
	    <label class="col-md-2 control-label" for="profileLastName"><b>Buyer :</b></label>
			<div class="col-md-4">
				<?php echo $cmt['buyer_name'];?>
			</div>
		<label class="col-md-2 control-label" for="profileLastName"><b>Qty :</b></label>
			<div class="col-md-4">
				<?php echo $cmt['rec_qty'];?>
			</div>
	</div>
	<div class="form-group"  style="font-size: 12px;">
	    <label class="col-md-2 control-label" for="profileLastName"><b>Employee :</b></label>
			<div class="col-md-4">
				<?php echo $cmt['user_id'];?>
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
					$selrole = $con->select("laundry_wo_master_dtl_proc f 
                    JOIN laundry_receive g on f.wo_master_dtl_proc_id = g.wo_master_dtl_proc_id
					JOIN laundry_role_wo A on f.role_wo_master_id=A.role_wo_master_id
					LEFT JOIN laundry_role_dtl_wo b ON A.role_wo_id = b.role_wo_id
					LEFT JOIN laundry_master_process C ON b.master_process_id = C.master_process_id 
					LEFT JOIN (select role_wo_id,master_process_id,role_dtl_wo_id,process_type,lot_no 
							   from laundry_process 
							   where role_wo_master_id = '$cmt[role_wo_master_id]' and 
							   master_type_process_id <= 3 and
							   lot_no = '$_GET[lot]' and
							   user_id = '$_GET[user]'
							  ) as d  
							  on d.role_wo_id=a.role_wo_id 
					LEFT JOIN (select role_wo_id,master_process_id,role_dtl_wo_id,process_type,lot_no 
						  	   from laundry_process 
						  	   where role_wo_master_id = '$cmt[role_wo_master_id]' and 
						  	   master_type_process_id > 3 and 
						  	   lot_no = '$_GET[lot]' and 
						  	   user_id = '$_GET[user]'
							   GROUP BY role_dtl_wo_id,role_wo_id,master_process_id,process_type,lot_no
							   ORDER BY process_type DESC
							  ) as e 
							  on e.role_dtl_wo_id=b.role_dtl_wo_id",
					"DISTINCT e.master_process_id,role_wo_name,master_process_name,e.role_wo_id,e.role_dtl_wo_id,d.lot_no as lot_number,e.lot_no as dtl_lot_number,b.master_process_id as master_process_id_before,a.role_wo_seq,b.role_dtl_wo_seq","A.role_wo_master_id = '$cmt[role_wo_master_id]' and g.rec_no = '$_GET[lot]' and g.user_id = '$_GET[user]'","a.role_wo_seq,b.role_dtl_wo_seq"); 
					// echo "select role_wo_name,master_process_name,e.role_wo_id,e.master_process_id,e.role_dtl_wo_id,e.process_type as dtl_process_type,d.process_type,d.lot_no as lot_number,e.lot_no as dtl_lot_number,b.master_process_id as master_process_id_before 
					// from laundry_wo_master_dtl_proc f 
     //                JOIN laundry_receive g on f.wo_master_dtl_proc_id = g.wo_master_dtl_proc_id
					// JOIN laundry_role_wo A on f.role_wo_master_id=A.role_wo_master_id
					// LEFT JOIN laundry_role_dtl_wo b ON A.role_wo_id = b.role_wo_id
					// LEFT JOIN laundry_master_process C ON b.master_process_id = C.master_process_id 
					// LEFT JOIN (select role_wo_id,master_process_id,role_dtl_wo_id,process_type,lot_no 
					// 		   from laundry_process 
					// 		   where role_wo_master_id = '$cmt[role_wo_master_id]' and 
					// 		   master_type_process_id <= 3 and
					// 		   lot_no = '$_GET[lot]' and
					// 		   user_id = '$_GET[user]'
					// 		  ) as d  
					// 		  on d.role_wo_id=a.role_wo_id 
					// LEFT JOIN (select role_wo_id,master_process_id,role_dtl_wo_id,process_type,lot_no 
					// 	  	   from laundry_process 
					// 	  	   where role_wo_master_id = '$cmt[role_wo_master_id]' and 
					// 	  	   master_type_process_id > 3 and 
					// 	  	   lot_no = '$_GET[lot]' and 
					// 	  	   user_id = '$_GET[user]'
					// 		   GROUP BY role_dtl_wo_id,role_wo_id,master_process_id,process_type,lot_no
					// 		   ORDER BY process_type DESC
					// 		   limit 1
					// 		  ) as e 
					// 		  on e.role_dtl_wo_id=b.role_dtl_wo_id 
					// 		  where A.role_wo_master_id = '$cmt[role_wo_master_id]' and g.rec_no = '$_GET[lot]' and g.user_id = '$_GET[user]' order by a.role_wo_seq,b.role_dtl_wo_seq";
					 foreach ($selrole as $role) {
					 	
					 	//untuk separator
					 	if ($role['master_process_name'] == ''){
									$separator = "";
						} else {
									$separator = " - ";
						}

					 	// jika list process sudah masuk ke database laundry process (artinya sudah di process)
					 	if($role['master_process_id'] != '') {
						 	if ($role['lot_number'] == ''){
						 		$lotnumber = $role['dtl_lot_number'];
						 	} else {
						 		$lotnumber = $role['lot_number'];
						 	}
							 
							 //select process type dari hasil query list process di atas	
							 	$selprocerole = $con->select("laundry_process","process_type","role_wo_id = '$role[role_wo_id]' and master_process_id = '$role[master_process_id]' and lot_no = '$lotnumber'","process_id DESC","1");
							 	foreach ($selprocerole as $procrole) {}
							 		

								if ($procrole['process_type'] == '1'){
									echo "<b style='color:#FFD700'>".$role['role_wo_name'].$separator.$role['master_process_name']."</b>&ensp;IN<br>";
								} else if ($procrole['process_type'] == '2'){
									echo "<b style='color:#1E90FF'>".$role['role_wo_name'].$separator.$role['master_process_name']."</b>&ensp;On Progress<br>";
								} else if ($procrole['process_type'] == '3'){
									echo "<b style='color:#1E90FF'>".$role['role_wo_name'].$separator.$role['master_process_name']."</b>&ensp;On Progress<br>";
								} else if ($procrole['process_type'] == '4'){
									echo "<b style='color:#8B0000'>".$role['role_wo_name'].$separator.$role['master_process_name']."</b>&ensp;<i style='color:#00FF00' class='fa fa-check'></i><br>";
								} else {
									echo $role['role_wo_name'].$separator.$role['master_process_name']."<br>";
								}
					 	} 
					 	// jika list process belum di proses sama sekali
					 	else {
					 		echo $role['role_wo_name'].$separator.$role['master_process_name']."<br>";
					 	}

								
					}
				?>
			</div>
		<label class="col-md-2 control-label" for="profileLastName"><b>Machine:</b></label>
			<div class="col-md-4" id="tampilmachine" >
				<?php include "tampilmachine.php"; ?>
			</div>
			<br><br><br>
			
	</div>
	<div class="form-group"  style="font-size: 12px;" align="center">
	   <a href="javascript:void(0)" class="btn btn-danger" onclick="cancel()">Cancel</a>
	   <a href="javascript:void(0)" class="btn btn-success" id="nextprocess" onclick="correct('<?=$_GET[lot]?>','<?=$_GET[user]?>','<?=$cmt[role_wo_master_id]?>','<?=$_GET[typelot]?>','<?=$role1[master_type_process_id]?>')">Correct</a>
		
	</div>
	<input class="form-control" name="machine-input" id="machine-input" value="" type="hidden" />
	<input type="hidden" class="form-control" id="machine_id" name="machine_id">
<?php } ?>
