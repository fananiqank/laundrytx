<?php
session_start();
//echo $_SESSION['ID_PEG'];
include "../../../funlibs.php";
$con = new Database();

$seldetail = $con->select("laundry_lot_number d
	JOIN laundry_wo_master_dtl_proc A ON d.wo_master_dtl_proc_id = A.wo_master_dtl_proc_id
	JOIN laundry_role_wo_master b ON A.role_wo_master_id = b.role_wo_master_id
	JOIN laundry_role_wo C ON b.role_wo_master_id = C.role_wo_master_id ","c.*","d.lot_id = $_GET[id]","role_wo_seq");
	echo "select c.* from laundry_lot_number d
	JOIN laundry_wo_master_dtl_proc A ON d.wo_master_dtl_proc_id = A.wo_master_dtl_proc_id
	JOIN laundry_role_wo_master b ON A.role_wo_master_id = b.role_wo_master_id
	JOIN laundry_role_wo C ON b.role_wo_master_id = C.role_wo_master_id where d.lot_id = $_GET[id]";
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
                		<div class="col-sm-2 col-md-2 col-lg-2">
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
                		<div class="col-sm-2 col-md-2 col-lg-2">
                			<b><?=$detail['role_wo_time']?></b>
                		</div>
                		<!-- <div class="col-sm-2 col-md-2 col-lg-2">
                			&nbsp;
                		</div> -->
                	</div>
            	</div>
            		<?php 
            			$no = 1;
            			$seldetdtl = $con->select("laundry_role_dtl_wo a join laundry_master_process b on a.master_process_id = b.master_process_id","master_process_name,role_dtl_wo_time","role_wo_id = '$detail[role_wo_id]'");
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