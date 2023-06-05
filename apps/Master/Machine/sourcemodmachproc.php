<?php 
if ($_GET['reload'] == 1){
	session_start();
	include "../../../funlibs.php";
	$con = new Database;
	$typepro = $_GET['typepro'];
}

?>
<div>&nbsp;</div>
<div>&nbsp;</div>
<table width="100%" class="table table-bordered table-striped pre-scrollable" id="datatable-ajax">
	
	<tr>
		<td width="5%"><b>No</b></td>
		<td><b>Process Name</b></td>
	</tr>
	
</table>
<table width="100%" class="table table-bordered table-striped pre-scrollable table-wrapper-scroll-y my-custom-scrollbar" id="datatable-ajax">
	<tbody>
		<?php 
			$no=1;
			$selmachproc = $con->select("laundry_master_machine_dtl a join laundry_master_process b on a.master_process_id=b.master_process_id","a.machine_dtl_id,b.master_process_name","machine_id = '$_GET[id]' and machine_dtl_status = 1","machine_dtl_id");
			foreach ($selmachproc as $machproc) { 
				
		?>
		<tr>
			<td width="5%"><?=$no;?></td>
			<td style="border-radius: 10px; padding: 5px; margin-bottom: 20px;" >
				<b style=""><?=$machproc['master_process_name']?></b>
			</td>
			<td width="5%"><a href="javascript:void(0)" class="btn btn-default" onclick="deleteprocess('<?=$machproc[machine_dtl_id]?>','<?=$_GET[id]?>','<?=$typepro?>')"><i class="fa fa-trash"></i></a></td>
		</tr>
		
		<?php $no++; }	?>
	</tbody>
</table>
