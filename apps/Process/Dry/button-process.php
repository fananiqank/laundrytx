<?php  
	if ($_GET['machine']){
		session_start();
		require_once("../../../funlibs.php");
		$con=new Database();
		$mpid = $_GET['mpid'];
	} else if($_GET['masterprocid'] != ''){
		$mpid = $_GET['masterprocid'];;
	} else if($_GET['mpid'] != ''){
		$mpid = $_GET['mpid'];;
	}
	else {
		$mpid = $cmt['master_process_id'];
	}
	
    $lot = $_GET['lot'];

?>
<table width="100%" class="table datatable-basic table-bordered table-striped table-hover dataTable no-footer">
	<tr>
		<td><b>No</b></td>
		<td><b>Machine</b></td>
		<td align="center"><b>Process</b></td>
		<td align="center"><b>Time Machine</b></td>
		<td><b>Time Process</b></td>
		<td align="center"><b>Act</b></td>
		<td align="center"><b>Remark</b></td>
	</tr>
	<?php 
		$no = 1;
		$selmach = $con->select("
			laundry_process_machine a 
			join laundry_master_machine b on a.machine_id=b.machine_id
			LEFT JOIN (select machine_id,process_type,process_createdate,role_wo_id,master_process_id from laundry_process where lot_no = '$lot') as c on a.machine_id = c.machine_id and a.master_process_id=c.master_process_id",
			"a.process_machine_id,
			 a.process_machine_status,
			 a.process_machine_time,
			 a.machine_id,
			 a.master_process_id,
			 a.process_machine_onprogress,
			 a.process_machine_qty_good,
			 a.process_machine_qty_reject,
			 a.process_machine_qty_total,
			 COALESCE(a.process_machine_remark,'-') as process_machine_remark,
			 b.machine_name,
			 c.machine_id AS process_machine,
			 a.role_wo_id,
			 string_agg ( CAST ( c.process_createdate AS VARCHAR ), ',' ) AS process_createdate,
			 string_agg ( CAST ( c.process_type AS VARCHAR ), ',' ) AS process_type ",
			"a.lot_no = '$lot' and A.process_machine_status != 0 and a.master_process_id = '$mpid'
			GROUP BY A.machine_id,a.process_machine_id,process_machine,b.machine_name,process_machine_sequence,c.role_wo_id",
			"process_machine_sequence");
		// echo "a.process_machine_id,
		// 	 a.process_machine_time,
		// 	 a.machine_id,
		// 	 a.master_process_id,
		// 	 a.process_machine_onprogress,
		// 	 a.process_machine_qty_good,
		// 	 a.process_machine_qty_reject,
		// 	 a.process_machine_qty_total,
		// 	 COALESCE(a.process_machine_remark,'-') as process_machine_remark,
		// 	 b.machine_name,
		// 	 c.machine_id AS process_machine,
		// 	 a.role_wo_id,
		// 	 string_agg ( CAST ( c.process_createdate AS VARCHAR ), ',' ) AS process_createdate,
		// 	 string_agg ( CAST ( c.process_type AS VARCHAR ), ',' ) AS process_type
		// 	 from 
		// 	laundry_process_machine a 
		// 	join laundry_master_machine b on a.machine_id=b.machine_id
		// 	LEFT JOIN (select machine_id,process_type,process_createdate,role_wo_id from laundry_process where lot_no = '$lot') as c on a.machine_id = c.machine_id
		// 	wherea.lot_no = '$lot' and A.process_machine_status = 1 and a.master_process_id = '$mpid'
		// 	GROUP BY A.machine_id,a.process_machine_id,process_machine,b.machine_name,process_machine_sequence,c.role_wo_id";
		foreach ($selmach as $mach) {

			//pengecekan machine yang sedang start berdasarkan lot number
			$seldisable = $con->select("laundry_process_machine","machine_id","lot_no = '$lot' 
			AND concat(machine_id,'_',master_process_id) NOT IN (select concat(machine_id,'_',master_process_id) from laundry_process where lot_no = '$lot' and process_type = 3) and process_machine_status = 1","process_machine_sequence","1");
			
			foreach ($seldisable as $disable) {}
			
			//pengecekan machine yang sedang start saat ini.
			$selmachinestart = $con->select("laundry_process_machine","count(machine_id) as jmlmachinestart,lot_no","machine_id = '$mach[machine_id]' and master_process_id = '$mpid' and process_machine_onprogress = 2 GROUP BY lot_no");
		//echo "select count(machine_id) as jmlmachinestart,lot_no from laundry_process_machine where machine_id = '$mach[machine_id]' and master_process_id = '$mpid' and process_machine_onprogress = 2 GROUP BY lot_no";
			foreach ($selmachinestart as $machinestart) {}
			$expro = explode(',', $mach['process_type']);
			$prostart = $expro[0];
			$proend = $expro[1];

			$exdate = explode(',', $mach['process_createdate']);
			
			$timestart = date("H:i",strtotime($exdate[0]));
			$timeend = date("H:i",strtotime($exdate[1]));

			if($mach['process_machine'] != ''){
				// jika sudah dilakukan start akan tercatat waktunya
				if ($prostart == '2'){
					$showtimestart = "<b><i>".$timestart."</i></b>";
					$gettimestart = $timestart;
				} else {
				   	$showtimestart = "00:00";	
				}

				// jika sudah dilakukan end akan tercatat waktunya
				if ($proend == '3'){
					$showtimeend = "<b><i>".$timeend."</i></b>";
				} else {
				   	$showtimeend = "00:00";	
				}  
			} else {
			  	$showtimestart = "00:00";
			  	$showtimeend = "00:00";
			}

			//jika mesin start / end maka tombol start / end akan disable
			if($mach['machine_id'] == $disable['machine_id']){

				//jika mesin masih start (dipakai oleh lot lain) otomatis tidak bisa di start
				if ($machinestart['jmlmachinestart'] > 0 && $machinestart['lot_no'] != $lot) {
					
					$discancel = "";
					$showprocess = "Waiting..";
					$disproc = "style='background-color:#FF8C00' disabled";
				} else {
					echo "<input type='hidden' id='timestart_$no' value='$gettimestart'>";
					if ($mach['process_machine'] != '' && $prostart == '2') {
						$disstart = "disabled";
						$discancel = "display:none;";
						$colapsein = "in";
					} else {
						$disstart = "";
						$discancel = "";
						$colapsein = "in";
					} 

					if ($mach['process_machine'] != '' && $proend == '3') {
						$disend = "disabled";
						$colapsein = "in";
						//$discancel = "display:none;";
					} else {
						$disend = "";
						//$discancel = "";
					}
					$disproc = "";
					$showprocess = "Process";
				}				
			} else {
				$colapsein = "";
				$disstart = "disabled";
	 			$disend = "disabled";
	 			if ($mach['process_machine'] != '' && $mach['process_machine_status'] == 1) {
					$discancel = "display:none;";
					$showprocess = "Done";
					$disproc = "style='background-color:#006400' disabled";
				} else if ($mach['process_machine'] != '' && $mach['process_machine_status'] == 2) {
					$discancel = "display:none;";
					$showprocess = "Broken";
					$disproc = "style='background-color:#FF0000' disabled";
				} else {
					$discancel = "";
					$showprocess = "Waiting..";
					$disproc = "style='background-color:#FF8C00' disabled";
				} 
			}

			if($mach['process_machine_onprogress'] == '3'){
				$showqty = "Good = ".$mach['process_machine_qty_good']."<br>
							Reject  = ".$mach['process_machine_qty_reject']."<br>
							Total= ".$mach['process_machine_qty_total']."<br>
							Remark= ".$mach['process_machine_remark'];
			} else {
				$showqty = "";
			}
	?>
	<tr style="font-size:20px;" >
		<td><?=$no?></td>
		<td><?=$mach['machine_name']?></td>
		<td align="center">
			<a data-toggle='collapse' data-parent="#accordion" href="#buton_<?=$no?>" class="btn btn-info" <?=$disproc?>><?php echo $showprocess;?></a>
			&nbsp;
		</td>
		<td align="center"><?=$mach['process_machine_time']?></td>
		<td style="font-size: 12px;">
			START :
			<?php 
				echo $showtimestart;
			?>
			<br>
			END &ensp; : 
			<?php 
				echo $showtimeend;
			?>
			<br>
		</td>
		<td align="center">
			<a href="javascript:void(0)" style="padding: 3%;" onclick="hapusmachine('<?=$mach[process_machine_id]?>','<?=$mach[machine_id]?>','<?=$lot?>',1)"><i class="fa fa-close" style="color:#FF0000;<?=$discancel?>"></i></a>
		</td>
		<td id="tampilkanmenit_<?=$mach[machine_id]?>" style="font-size: 10px;">
			<?php echo $showqty; ?>
		</td>
	</tr>
	<tr id="buton_<?=$no?>" class="panel-collapse collapse <?=$colapsein?>">
		<td colspan="7">
			<table width="100%">
				<tr>
					<td align="center">
						<br>
						<a href="javascript:void(0)" class="btn btn-primary" style="padding: 3%;" onclick="process_start(2,'<?=$lot?>','<?=$mach[machine_id]?>','<?=$mach[process_machine_time]?>',1,'<?=$mach[master_process_id]?>')" <?=$disstart?>><b style="font-size:20px;">START</b></a>
						  &emsp;
						<a href="javascript:void(0)" class="btn btn-success" style="padding: 3%;background-color: #800000"  <?=$disend?> data-toggle='modal' data-target='#funModal' id='mod' onclick="model('<?=$mach[machine_id]?>','<?=$lot?>','<?=$mach[machine_time]?>',2,0,'<?=$_GET[q]?>','<?=$mach[master_process_id]?>','<?=$_GET['typelot']?>')"><b style="font-size:20px;">END</b></a>
						<input type="hidden" id="macid[]" name="macid[]" value="<?=$mach[machine_id]?>">
						<br><br>
					</td>
				</tr>
			</table>
		</td> 
	</tr>
	<?php $no++; } ?>
</table>