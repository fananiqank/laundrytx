<?php
session_start();
//echo $_SESSION['ID_PEG'];
include "../../../funlibs.php";
$con = new Database();

$seldetail = $con->select("laundry_lot_number A
    JOIN laundry_wo_master_dtl_proc B ON A.wo_master_dtl_proc_id = B.wo_master_dtl_proc_id
    JOIN laundry_role_wo D ON B.role_wo_master_id = D.role_wo_master_id",
    "D.role_wo_name,role_wo_time,D.role_wo_id",
    "A.lot_id = $_GET[id]","role_wo_seq");
	// echo "select C.* from laundry_lot_number A
 //    JOIN laundry_wo_master_dtl_proc B ON A.wo_master_dtl_proc_id = B.wo_master_dtl_proc_id
 //    JOIN laundry_role_child C ON A.lot_no = C.lot_no
 //    JOIN laundry_role_wo D ON C.role_wo_id = D.role_wo_id
 //    LEFT JOIN laundry_role_dtl_wo E ON C.role_dtl_wo_id = E.role_dtl_wo_id
 //    LEFT JOIN laundry_master_process F ON E.master_process_id = F.master_process_id 
 //    where A.lot_id = $_GET[id]";
?>

<!-- Theme CSS -->
		<!-- Vendor CSS -->
		
		<span class="separator"></span>
		
				<div class="row" style="padding-bottom: 3%;">
                	<div class="col-sm-12 col-md-12 col-lg-12" style="font-size: 14px;">
                		<div class="col-sm-2 col-md-2 col-lg-2">
                			&nbsp;
                		</div>
                		<div class="col-sm-4 col-md-4 col-lg-4">
                			<b>Sequence</b>
                		</div>
                		<div class="col-sm-4 col-md-4 col-lg-4">
                			<b>Time</b>
                		</div>
                		<!-- <div class="col-sm-2 col-md-2 col-lg-2">
                			<b>Status</b>
                		</div> -->
                	</div>
            	</div>
            	<?php 
            	$hu = 'A';
            	foreach ($seldetail as $detail ) {  
            	?>
            	<div class="row">
                	<div class="col-sm-12 col-md-12 col-lg-12">
                		<div class="col-sm-2 col-md-2 col-lg-2">
                			&nbsp;
                		</div>
                		<div class="col-sm-4 col-md-4 col-lg-4">
                			<b><?=$hu.". ".$detail['role_wo_name']?></b>
                		</div>
                		<div class="col-sm-4 col-md-4 col-lg-4">
                			<b><?=$detail['role_wo_time']?></b>
                		</div>
                		<!-- <div class="col-sm-2 col-md-2 col-lg-2">
                			&nbsp;
                		</div> -->
                	</div>
            	</div>
            		<?php 
            			$no = 1;
            			$seldetdtl = $con->select("laundry_role_dtl_wo a join laundry_master_process c on a.master_process_id = c.master_process_id","master_process_name,role_dtl_wo_time","a.role_wo_id = '$detail[role_wo_id]'");
            			foreach ($seldetdtl as $detdtl ) {  
            		?>
		            	<div class="row">
		                	<div class="col-sm-12 col-md-12 col-lg-12">
		                		<div class="col-sm-2 col-md-2 col-lg-2">
		                			&nbsp;
		                		</div>
		                		<div class="col-sm-4 col-md-4 col-lg-4">
		                			&emsp;<?=$no.". ".$detdtl['master_process_name']?>
		                			
		                		</div>
		                		<div class="col-sm-2 col-md-2 col-lg-2">
		                			&emsp;<?=$detdtl['role_dtl_wo_time']?>
		                		</div>
		                		<div class="col-sm-2 col-md-2 col-lg-2">
		                			&nbsp;
		                		</div>
		                	</div>
		            	</div>
		<?php 
		            $no++; }
		            $hu++;
		            echo "<br>";
		 	}