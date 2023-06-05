<?php
session_start();
//echo $_SESSION['ID_PEG'];
include "../../../funlibs.php";
$con = new Database();

$seldetail = $con->select("laundry_role_child a
     JOIN laundry_role_wo b ON a.role_wo_id = b.role_wo_id 
     JOIN laundry_lot_number c ON a.lot_id=c.lot_id
     JOIN laundry_wo_master_dtl_proc d ON d.wo_master_dtl_proc_id = c.wo_master_dtl_proc_id",
    "a.role_wo_id,
     b.role_wo_name,
     b.role_wo_time,
     d.type_source,
     b.master_type_process_id
     ",
    "c.lot_id = $_GET[id] and a.lot_type != 1
     GROUP BY 
     a.role_wo_id,
     b.role_wo_name,
     b.role_wo_time,
     d.type_source,
     b.master_type_process_id,
     a.role_wo_seq",
    "a.role_wo_seq");
	// echo "select a.role_wo_id,
 //     b.role_wo_name,
 //     b.role_wo_time,
 //     d.type_source,
 //     role_child_status,
 //     b.master_type_process_id from laundry_role_child a
 //     JOIN laundry_role_wo b ON a.role_wo_id = b.role_wo_id 
 //     JOIN laundry_lot_number c ON a.lot_id=c.lot_id
 //     JOIN laundry_wo_master_dtl_proc d ON d.wo_master_dtl_proc_id = c.wo_master_dtl_proc_id where c.lot_id = $_GET[id] and a.lot_type != 1
 //     GROUP BY 
 //     a.role_wo_id,
 //     b.role_wo_name,
 //     b.role_wo_time,
 //     d.type_source,
 //     b.master_type_process_id,
 //     a.role_wo_seq";
foreach ($seldetail as $headdetail ){}
if($headdetail['type_source'] == '1' || $headdetail['type_source'] == '2'){
		$typereceive = "Manual";
} else {
		$typereceive = "Scan QRCode";
}


?>

<!-- Theme CSS -->
		<!-- Vendor CSS -->
		
		<span class="separator"></span>
				
				
                <div class="row" style="padding-bottom: 3%;">
                	<div class="col-sm-12 col-md-12 col-lg-12" style="font-size: 14px;">
                		<div class="col-sm-2 col-md-2 col-lg-2">
                			<b>&nbsp;</b>
                		</div>
                		<div class="col-sm-3 col-md-3 col-lg-3">
                			<b>Type Receive :</b>
                		</div>
                		<div class="col-sm-3 col-md-3 col-lg-3">
                			<b><?php echo $typereceive; ?></b>
                		</div>
                	</div>
            	</div>
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
                		
                	</div>
            	</div>
            	<?php 
            	$hu = 'A';
            	foreach ($seldetail as $detail ) { 
                   foreach($con->select("laundry_role_child","role_child_status","role_wo_id = $detail[role_wo_id] and lot_id = '$_GET[id]'") as $cekstatuswoid)
            		if ($cekstatuswoid['role_child_status'] == 1) {
            			if($detail['master_type_process_id'] != 4 && $detail['master_type_process_id'] != 5){
					    	$masterprocesstypename = "<b style='color:#8B0000'>".$detail['role_wo_name']."</b>&ensp;<i style='color:#00FF00' class='fa fa-check'></i>";
						} else {
							$masterprocesstypename = $detail['role_wo_name'];
						}
					} else if ($cekstatuswoid['role_child_status'] == 3){
						if($detail['master_type_process_id'] != 4 && $detail['master_type_process_id'] != 5){
					   	 	$masterprocesstypename = "<b style='color:#1E90FF'>".$detail['role_wo_name']."</b>&ensp;<i style='color:#00FF00' class='fa fa-check'></i>";
					   	} else {
					   		$masterprocesstypename = $detail['role_wo_name'];
					   	}
					} else {
					    $masterprocesstypename = $detail['role_wo_name'];
					}
            	?>
            	<div class="row">
                	<div class="col-sm-12 col-md-12 col-lg-12">
                		<div class="col-sm-2 col-md-2 col-lg-2">
                			&nbsp;
                		</div>
                		<div class="col-sm-4 col-md-4 col-lg-4">
                			<b><?=$hu.". ".$masterprocesstypename?></b>
                		</div>
                		<div class="col-sm-2 col-md-2 col-lg-2">
                			<b><?=$detail['role_wo_time']?></b>
                		</div>
                		<div class="col-sm-2 col-md-2 col-lg-2">
                			&nbsp;
                		</div>
                	</div>
            	</div>
            		<?php 
            			$no = 1;
            			
                        $seldetdtl = $con->select("laundry_role_child a 
                            JOIN laundry_role_dtl_wo b ON a.role_dtl_wo_id=b.role_dtl_wo_id
                            JOIN laundry_master_process c on b.master_process_id = c.master_process_id
                            LEFT JOIN (
                                        SELECT
                                            change_process,
                                            lot_no,
                                            role_dtl_wo_id,
                                            master_process_name AS change_name 
                                        FROM
                                            laundry_process
                                            M JOIN laundry_master_process n ON M.change_process = n.master_process_id 
                                        WHERE
                                            process_type = 1 
                                        ) AS d 
                            ON A.role_dtl_wo_id = d.role_dtl_wo_id AND A.lot_no = d.lot_no 
                            ",
                            "CASE WHEN d.change_name != '' THEN d.change_name ELSE c.master_process_name END as master_process_name,b.role_dtl_wo_time,a.role_child_status",
                            "a.lot_id = $_GET[id] and a.role_wo_id = '$detail[role_wo_id]'",
                            "a.role_child_dateprocess,a.role_dtl_wo_seq");
                     
            			foreach ($seldetdtl as $detdtl ) {  
                            
                            if ($detdtl['role_child_status'] == 1) {
                                $masterprocessname = "<b style='color:#8B0000'>".$detdtl['master_process_name']."</b>&ensp;<i style='color:#00FF00' class='fa fa-check'></i>";
                            } else if ($detdtl['role_child_status'] == 3){
                                $masterprocessname = "<b style='color:#1E90FF'>".$detdtl['master_process_name']."</b>&ensp;<i style='color:#00FF00' class='fa fa-check'></i>";
                            } else {
                                $masterprocessname = $detdtl['master_process_name'];
                            }
            		?>
		            	<div class="row">
		                	<div class="col-sm-12 col-md-12 col-lg-12">
		                		<div class="col-sm-2 col-md-2 col-lg-2">
		                			&nbsp;
		                		</div>
		                		<div class="col-sm-4 col-md-4 col-lg-4">
		                			&emsp;<?=$no.". ".$masterprocessname?>
		                			
		                		</div>
		                		<div class="col-sm-2 col-md-2 col-lg-2">
		                			&emsp;<?=$detdtl['role_dtl_wo_time']?>
		                		</div>
		                		
		                	</div>
		            	</div>
		<?php 
		            $no++; }
		            $hu++;
		            echo "<br>";
		 	}