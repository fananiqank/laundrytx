<?php 
session_start();
include '../../../funlibs.php';
$con = new Database;
$seldetail = $con->select("laundry_role_wo_master b join laundry_role_wo c on b.role_wo_master_id=c.role_wo_master_id","c.*","b.role_wo_master_id = '".$_GET['id']."' and role_wo_status != 2","role_wo_seq ASC");
 // echo "select c.* from laundry_role_wo_master b join laundry_role_wo c on b.role_wo_master_id=c.role_wo_master_id where b.role_wo_master_id = '$_GET[id]'";

?>		

				<div class="row" style="padding-bottom: 3%;" align="left">
                	<div class="col-sm-12 col-md-12 col-lg-12" style="font-size: 14px;">
                		<div class="col-sm-3 col-md-3 col-lg-3">
                			&nbsp;
                		</div>
                		<div class="col-sm-4 col-md-4 col-lg-4">
                			<b>Sequence</b>
                		</div>
                		<div class="col-sm-4 col-md-4 col-lg-4">
                			<b>Time</b>
                		</div>
                		<div class="col-sm-1 col-md-1 col-lg-1">
                			&nbsp;
                		</div>
                	</div>
            	</div>
            	<?php 
            	$hu = 'A';
            	foreach ($seldetail as $detail ) {  
            	?>
            	<div class="row" align="left">
                	<div class="col-sm-12 col-md-12 col-lg-12">
                		<div class="col-sm-3 col-md-3 col-lg-3">
                			&nbsp;
                		</div>
                		<div class="col-sm-4 col-md-4 col-lg-4">
                			<b><?php echo $hu.". ".$detail['role_wo_name']; ?></b>
                		</div>
                		<div class="col-sm-4 col-md-4 col-lg-4">
                			<b><?php echo $detail['role_wo_time']; ?></b>
                		</div>
                		<div class="col-sm-1 col-md-1 col-lg-1">
                			&nbsp;
                		</div>
                	</div>
            	</div>
            		<?php 
            			$no = 1;
            			$seldetdtl = $con->select("laundry_role_dtl_wo a join laundry_master_process b on a.master_process_id = b.master_process_id","master_process_name,role_dtl_wo_time","role_wo_id = '".$detail['role_wo_id']."'  and role_dtl_wo_status = 1");
                        // echo "select master_process_name,role_dtl_wo_time from laundry_role_dtl_wo a join laundry_master_process b on a.master_process_id = b.master_process_id where role_wo_id = '$detail[role_wo_id]'";
            			foreach ($seldetdtl as $detdtl ) {  
            		?>
		            	<div class="row" align="left">
		                	<div class="col-sm-12 col-md-12 col-lg-12">
		                		<div class="col-sm-3 col-md-3 col-lg-3">
		                			&nbsp;
		                		</div>
		                		<div class="col-sm-4 col-md-4 col-lg-4">
		                			&emsp;<?php echo $no.". ".$detdtl['master_process_name']; ?>
		                			
		                		</div>
		                		<div class="col-sm-4 col-md-4 col-lg-4">
		                			&emsp;<?php echo $detdtl['role_dtl_wo_time']; ?>
		                		</div>
		                		<div class="col-sm-1 col-md-1 col-lg-1">
		                			&nbsp;
		                		</div>
		                	</div>
		            	</div>
		<?php 
		            $no++; }
		            $hu++;
		            echo "<br>";
		 	}

?>