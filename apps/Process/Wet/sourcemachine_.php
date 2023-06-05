<?php 

// include "../../../funlibs.php";
// $con = new Database;

$exptype = explode('_',$_GET['id']);
$seljml = $con->select("laundry_process_machine a join laundry_master_machine b on a.machine_id=b.machine_id","SUM(a.process_machine_time) as counttime,COUNT(process_machine_id) as countmachine","a.master_process_id = '$_GET[id]' and a.lot_no = '$_GET[lot]' and a.process_machine_status=1");	
 echo "select SUM(a.process_machine_time) as counttime,COUNT(process_machine_id) as countmachine from laundry_process_machine a join laundry_master_machine b on a.machine_id=b.machine_id where a.master_process_id = '$_GET[id]' and a.lot_no = '$_GET[lot]' and a.process_machine_status=1";
	foreach($seljml as $jml){}
if ($jml['countmachine'] >= 4){
	echo "<b style='font-size:14px;'><i>Max Machine 4</i></b>";
} 
else {

?>
	<table width="100%" cellpadding="0" cellspacing="0">
		<tr >
			<td><b>Name</b></td>
			<td align="center"><b>Time</b></td>
		</tr>
		<?php 
			$selcatsub = $con->select("laundry_master_machine a join laundry_master_machine_dtl b on a.machine_id=b.machine_id","a.machine_id,a.machine_name","machine_status = '1' and master_process_id = '$_GET[id]' and a.machine_id NOT IN (select machine_id from laundry_process_machine where lot_no = '$_GET[lot]' and process_machine_status = 1)");
			foreach ($selcatsub as $subcat) { 
		?>
		<tr>
			<td width="80%">
				<input type="checkbox" class="control-primary" name="machine[]" id="machine_<?=$subcat['machine_id']?>" value="<?=$subcat['machine_id']?>" onclick="cekmachine('<?=$_GET[lot]?>','<?=$jml[countmachine]?>','<?=$jml[counttime]?>','<?=$subcat[machine_id]?>')">&ensp;<?=$subcat['machine_name'].' - '.$subcat['machine_id'].' Minute'?><br>
			</td>
			<td width="20%">
				<input type="text" class="form-control" name="timemachine_<?=$subcat[machine_id]?>" id="timemachine[]" placeholder="minutes">
			</td>
		</tr>
		<?php 
			} 
		?>
	</table>
	<br><br>
	<div class="form-group">
		<div class="col-md-12 col-lg-12">
			<a href="javascript:void(0)" style="padding: 2%;" class="btn btn-primary" onclick="machineinprocess('<?=$_GET[id]?>','<?=$_GET[lot]?>')" data-dismiss="modal">Submit</a>
		</div>
	</div>
<?php } ?>