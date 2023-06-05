<?php 
if ($_GET['reload'] == 1){
	session_start();
	include "../../../funlibs.php";
	$con = new Database;
	$typepro = $_GET['typepro'];
}


	//$exptype = explode('_',$_GET['id']);
	$seljml = $con->selectcount("laundry_master_process","master_process_id","master_process_status = '1' and master_type_process = '$typepro'");
	
?>
<table width="100%" cellpadding="0" cellspacing="0" class="table-wrapper-scroll-y my-custom-scrollbar">
	<tr>
		
	</tr>
	<?php 
	
		$selcatsub = $con->select("laundry_master_process","master_process_id,master_process_name","master_process_status = '1' and master_type_process = '$typepro' and master_process_id NOT IN (select master_process_id from laundry_master_machine_dtl where machine_id = '$_GET[id]' and machine_dtl_status = 1)");
	
		foreach ($selcatsub as $subcat) { 
	?>
	<tr>
		<td width="80%">
			<input type="checkbox" class="control-primary" name="process[]" id="process_<?=$subcat[master_process_id]?>" value="<?=$subcat['master_process_id']?>" onchange="onc(<?=$subcat['master_process_id']?>)">&ensp;<?=$subcat['master_process_name']?><br>
		</td>
		
	</tr>
	
<?php 

	} 
?>
	<input type="hidden" name="counting" id="counting" value="0">
</table>